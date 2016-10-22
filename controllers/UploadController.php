<?php
namespace app\controllers;

use app\models\Upload;
use app\models\UploadForm;
use Yii;
use app\common\core\UploadHelper;
use app\common\core\GlobalHelper;
use app\models\Files;
use app\common\base\BaseController;
use app\common\core\CommunicationHelper;

class UploadController extends BaseController
{
    /**
     * 附件文件上传方法
     * @return 1.没有post数据时直接跳转上传界面。2.上传失败返回失败信息。3.上传成功直接返回原页面
     *
     */
    public $enableCsrfValidation = false;

    /**
     * @附件上传
     */
    public function actionUpload()
    {
        //判断php是否支持gd库
        if (!function_exists('imagecreate')) {
            exit('php不支持gd库，请配置后再使用');
        }
        $module = yii::$app->request->get('module');
        if (!empty(Yii::$app->request->post())) {
            $uploadhelper = new UploadHelper();
            $uploadhelper->hashLevel = 4;
            $type = yii::$app->request->get('type');

            if ($module == 'admin') {
                $uniacid = 'admin';
                $path = $type . "/" . $uniacid . "/" . date('Y/m/d/');
            } else {
                $uniacid = 'goods';
                $path = date('/Y/m/d/');
            }

            switch ($module) {
                case 'admin':
                    $ATTACHMENT_ROOT = $_SERVER['DOCUMENT_ROOT'] . '/attachement';
                    $uploadhelper->savePath = './attachement/' . $path;
                    break;
                case 'goods':
                    $ATTACHMENT_ROOT = $_SERVER['DOCUMENT_ROOT'] . '/upload';
                    $uploadhelper->savePath = './upload' . $path;
                    break;
            }
            GlobalHelper::mkdirs($ATTACHMENT_ROOT . '/' . $path);
            if ($uploadhelper->upload()) {
                $info = $uploadhelper->getUploadFileInfo();
                //保存图片信息导数据库
                $info['uniacid'] = $uniacid;
                //判断是后台图片还是商品信息图片
                switch ($module) {
                    case 'admin':
                        $filesmodel = new Files();
                        $filesmodel->saveImage($info);
                        break;
                    case 'goods':
                        $Uploadmodel = new Upload();
                        $Uploadmodel->saveImage($info);
                        break;
                }
                die(json_encode($info));
            } else {
                $result = array(
                    'message' => $uploadhelper->getErrorMsg()
                );
                die(json_encode($result));
            }
        }
    }

    public function actionFetch()
    {
        $module = yii::$app->request->get('module');
        if (!function_exists('imagecreate')) {
            exit('php不支持gd库，请配置后再使用');
        }
        $url = yii::$app->request->get('url');
        $resp = CommunicationHelper::ihttp_get($url);
        if (GlobalHelper::is_error($resp)) {
            $result['message'] = '提取文件失败, 错误信息: ' . $resp['message'];
            die(json_encode($result));
        }
        if (intval($resp['code']) != 200) {
            $result['message'] = '提取文件失败: 未找到该资源文件.';
            die(json_encode($result));
        }
        $ext = '';
        $type = yii::$app->request->get('type');
        if ($type == 'image') {
            switch ($resp['headers']['Content-Type']) {
                case 'application/x-jpg':
                case 'image/jpeg':
                    $ext = 'jpg';
                    break;
                case 'image/png':
                    $ext = 'png';
                    break;
                case 'image/gif':
                    $ext = 'gif';
                    break;
                default:
                    $result['message'] = '提取资源失败, 资源文件类型错误.';
                    die(json_encode($result));
                    break;
            }
        } else {
            $result['message'] = '提取资源失败, 仅支持图片提取.';
            die(json_encode($result));
        }
        if ($module == 'admin') {
            $uniacid = 'admin';
            $ATTACHMENT_ROOT = $_SERVER['DOCUMENT_ROOT'] . '/attachement';
            $path = $type . "/" . $uniacid . "/" . date('Y/m/d/');
            $savePath = './attachement/' . $path;
        } else {
            $ATTACHMENT_ROOT = $_SERVER['DOCUMENT_ROOT'] . '/upload';
            $uniacid = 'goods';
            $path = date('/Y/m/d/');
            $savePath = './upload' . $path;
        }

        GlobalHelper::mkdirs($ATTACHMENT_ROOT . '/' . $path);
        $filename = GlobalHelper::file_random_name($savePath, $ext);
        $originname = pathinfo($url, PATHINFO_BASENAME);
        $fullname = $savePath . $filename;
        if (file_put_contents($fullname, $resp['content']) == false) {
            $result['message'] = '提取失败.';
            die(json_encode($result));
        }
        //保存信息导数据库
        switch ($type) {
            case 'image':
                $type = 1;
                break;
            case 'audio':
                $type = 2;
                break;
            case 'video':
                $type = 3;
                break;
        }
        $info = array(
            'name' => $originname,
            'ext' => $ext,
            'filename' => $filename,
            'attachment' => substr($fullname, 1, strlen($fullname)),
            'url' => yii::$app->params['siteurl'] . substr($fullname, 1, strlen($fullname)),
            'type' => $type,
            'uniacid' => $uniacid,
            'filesize' => filesize($fullname),
        );
        switch ($module) {
            case 'admin':
                $filesmodel = new Files();
                $filesmodel->saveImage($info);
                break;
            case 'goods':
                $Uploadmodel = new Upload();
                $Uploadmodel->saveImage($info);
                break;
        }
        die(json_encode($info));
    }


    /**
     * @获取本地已经上传附件列表
     */
    public function actionLocal()
    {
        $module = yii::$app->request->get('module');
        $page = intval(yii::$app->request->get('page'));
        $type = yii::$app->request->get('type');
        $typeindex = array('image' => 1, 'audio' => 2);
        $page = max(1, $page);
        $year = intval(yii::$app->request->get('year'));
        $month = intval(yii::$app->request->get('month'));
        if ($year > 0 || $month > 0) {
            if ($month > 0 && !$year) {
                $year = date('Y');
                $starttime = strtotime("{$year}-{$month}-01");
                $endtime = strtotime("+1 month", $starttime);
            } elseif ($year > 0 && !$month) {
                $starttime = strtotime("{$year}-01-01");
                $endtime = strtotime("+1 year", $starttime);
            } elseif ($year > 0 && $month > 0) {
                $year = date('Y');
                $starttime = strtotime("{$year}-{$month}-01");
                $endtime = strtotime("+1 month", $starttime);
            }
        }
        switch ($type) {
            case 'image':
                $typetmp = 1;
                break;
            case 'audio':
                $typetmp = 2;
                break;
            case 'video':
                $typetmp = 3;
                break;
        }
        $size = yii::$app->request->get('pagesize') ? intval(yii::$app->request->get('pagesize')) : 32;
        switch ($module) {
            case 'admin':
                $filesmodel = new Files();
                if ($year > 0 || $month > 0) {
                    $fileslist = $filesmodel->find()
                        ->where(array('type' => $typetmp, 'uniacid' => $module))
                        ->andWhere((['between', 'createtime', $starttime, $endtime]))
                        ->orderBy('id DESC')
                        ->offset(($page - 1) * $size)
                        ->limit($size)
                        ->asArray()->all();
                    $total = $filesmodel->find()
                        ->where(array('type' => $typetmp, 'uniacid' => $module))
                        ->andWhere((['between', 'createtime', $starttime, $endtime]))
                        ->count();
                } else {
                    $fileslist = $filesmodel->find()
                        ->where(array('type' => $typetmp, 'uniacid' => $module))
                        ->orderBy('id DESC')
                        ->offset(($page - 1) * $size)
                        ->limit($size)
                        ->asArray()->all();
                    $total = $filesmodel->find()
                        ->where(array('type' => $typetmp, 'uniacid' => $module))
                        ->count();
                }
                $files = array();
                foreach ($fileslist as &$item) {
                    $item['url'] = yii::$app->params['siteurl'] . $item['attachment'];
                    $item['createtime'] = date('Y-m-d', $item['createtime']);
                    $item['filename'] = $item['name'];
                    unset($item['uid']);
                    $files[$item['id']] = $item;
                }
                break;
            case 'goods':
                $filesmodel = new Upload();
                if ($year > 0 || $month > 0) {
                    $fileslist = $filesmodel->find()
                        ->where(array('uniacid' => $module))
                        ->andWhere((['between', 'pic_addtime', $starttime, $endtime]))
                        ->orderBy('pic_id DESC')
                        ->offset(($page - 1) * $size)
                        ->limit($size)
                        ->asArray()->all();
                    $total = $filesmodel->find()
                        ->where(array('uniacid' => $module))
                        ->andWhere((['between', 'pic_addtime', $starttime, $endtime]))
                        ->count();
                } else {
                    $fileslist = $filesmodel->find()
                        ->where(array('uniacid' => $module))
                        ->orderBy('pic_id DESC')
                        ->offset(($page - 1) * $size)
                        ->limit($size)
                        ->asArray()->all();
                    $total = $filesmodel->find()
                        ->where(array('uniacid' => $module))
                        ->count();
                }
                $files = array();
                foreach ($fileslist as &$item) {
                    $item['url'] = yii::$app->params['siteurl'] . "/" . $item['pic_path'];
                    $item['createtime'] = date('Y-m-d', $item['createtime']);
                    $item['attachment'] = $item['pic_path'];
                    $item['id'] = $item['pic_id'];
                    $item['filename'] = $item['name'];
                    unset($item['uid']);
                    $files[$item['id']] = $item;
                }
                break;
        }
        GlobalHelper::message(array('page' => GlobalHelper::pagination($total, $page, $size, '', array('before' => '2', 'after' => '2', 'ajaxcallback' => 'null')), 'items' => $files), '', 'ajax');
    }

    /**
     * 删除上传的文件
     */
    public function actionDelete()
    {
        $id = yii::$app->request->post('id');
        $filesmodel = new Files();
        $media = $filesmodel->find()
            ->where(array('id' => $id))
            ->asArray()
            ->one();
        if (empty($media)) {
            exit('文件不存在或已经删除');
        }
        $file = $_SERVER['DOCUMENT_ROOT'] . $media['attachment'];
        $status = $this->file_delete($file);
        if (GlobalHelper::is_error($status)) {
            exit($status['message']);
        }
        $filesmodel->deleteAll('id=:id', array(':id' => $id));
        exit('success');
    }

    public function file_delete($file)
    {
        if (empty($file)) {
            return FALSE;
        }
        if (file_exists($file)) {
            @unlink($file);
        }
        return TRUE;
    }
}