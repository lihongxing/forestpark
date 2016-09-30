<?php

namespace app\modules\admin\controllers;

use app\common\base\AdminbaseController;
use app\common\core\GlobalHelper;
use app\models\Sqlbackstore;
use Yii;
use app\models\UploadForm;
use yii\data\Pagination;

class BdbbackuprestoreController extends AdminbaseController
{
    public $layout = 'main';//设置默认的布局文件

    public $menu = [];
    public $tables = [];
    public $fp;
    public $file_name;
    public $_path = null;
    public $back_temp_file = 'db_backup_';

    protected function getPath()
    {
        if (isset ($this->module->path)) {
            $this->_path = $this->module->path;
        } else {
            $this->_path = Yii::$app->basePath . '/_backup/';
        }
        if (!file_exists($this->_path)) {
            mkdir($this->_path);
            chmod($this->_path, '777');
        }
        return $this->_path;
    }

    public function execSqlFile($sqlFile)
    {
        if (file_exists($sqlFile)) {
            $sqlArray = file_get_contents($sqlFile);
            $cmd = Yii::$app->db->createCommand($sqlArray);
            try {
                $cmd->execute();
            } catch (CDbException $e) {
                return array(false, $e->getMessage());
            }
        }
        return array(true, 'function execSqlFile success');
    }

    public function actionIndex()
    {
        $tables = $this->getTables();
        $Sqlbackstoremodel = new Sqlbackstore();
        $sqlbackstoresquery = $Sqlbackstoremodel->find();
        $get = yii::$app->request->get();
        if(!empty($get['keyword'])){
            $sqlbackstoresquery = $sqlbackstoresquery->where(['or',
                ['like', 'sql_name', $get['keyword']],
                ['like', 'sql_content', $get['keyword']],
                ['like', 'sql_des', $get['keyword']]
            ]);
        };
        if($get['searchtime'] == 1){
            $sqlbackstoresquery->andWhere([
                'between','sql_addtime', strtotime($get['time']['start']),strtotime($get['time']['end'])
            ]);
        }

        $pages = new Pagination(['totalCount' => $sqlbackstoresquery->count(), 'pageSize' => 10]);
        $sqlbackstores = $sqlbackstoresquery
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();
        return $this->render('index', array(
            'sqlbackstores' => $sqlbackstores,
            'tables' => $tables,
            'pages' => $pages
        ));
    }

    /**
     * @method 获取数据表的表结构
     * @param $tableName
     * @return array
     */
    public function getColumns($tableName)
    {
        try {
            $sql = 'SHOW CREATE TABLE ' . $tableName;
            $cmd = Yii::$app->db->createCommand($sql);
            $table = $cmd->queryOne();
            $create_query = $table['Create Table'] . ';';
            $create_query = preg_replace('/^CREATE TABLE/', 'CREATE TABLE IF NOT EXISTS', $create_query);
            $create_query = preg_replace('/AUTO_INCREMENT\s*=\s*([0-9])+/', '', $create_query);
            if ($this->fp) {
                $this->writeComment('TABLE `' . addslashes($tableName) . '`');
                $final = 'DROP TABLE IF EXISTS `' . addslashes($tableName) . '`;' . PHP_EOL . $create_query . PHP_EOL . PHP_EOL;
                if (!fwrite($this->fp, $final)) {
                    throw new \Exception('function getColumns error');
                }
            } else {
                $this->tables[$tableName]['create'] = $create_query;
            }
        } catch (\Exception $e) {
            return array(false, $e->getMessage());
        }
        return array(true, 'function getColumns success');
    }

    /**
     * @method 根据表的名称获取表中的数据
     * @param $tableName
     * @return array
     */
    public function getData($tableName)
    {
        try {
            $sql = 'SELECT * FROM ' . $tableName;
            $cmd = Yii::$app->db->createCommand($sql);
            $dataReader = $cmd->query();
            $data_string = '';
            foreach ($dataReader as $data) {
                $itemNames = array_keys($data);
                $itemNames = array_map("addslashes", $itemNames);
                $items = join('`,`', $itemNames);
                $itemValues = array_values($data);
                $itemValues = array_map("addslashes", $itemValues);
                $valueString = join("','", $itemValues);
                $valueString = "('" . $valueString . "'),";
                $values = "\n" . $valueString;
                if ($values != "") {
                    $data_string .= "INSERT INTO `$tableName` (`$items`) VALUES" . rtrim($values, ",") . ";" . PHP_EOL;
                }
            }
            if ($data_string == '')
                return array(true, 'function getData success');
            if ($this->fp) {
                $this->writeComment('TABLE DATA ' . $tableName);
                $final = $data_string . PHP_EOL . PHP_EOL . PHP_EOL;
                if (fwrite($this->fp, $final)) {
                    throw new \Exception('function getData error');
                }
            } else {
                $this->tables[$tableName]['data'] = $data_string;
            }
        } catch (\Exception $e) {
            return array(false, $e->getMessage());
        }
    }

    /**
     * @method 获取数据库表的名称
     * @param null $dbName
     * @return array
     */
    public function getTables($dbName = null)
    {
        $sql = 'SHOW TABLES';
        $cmd = Yii::$app->db->createCommand($sql);
        $tables = $cmd->queryColumn();
        return $tables;
    }

    public function StartBackup($addcheck = true)
    {
        $this->file_name = $this->path . $this->back_temp_file . date('Y.m.d_H.i.s') . '.sql';
        $this->fp = fopen($this->file_name, 'w+');
        if ($this->fp == null)
            return false;
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        if ($addcheck) {
            fwrite($this->fp, 'SET AUTOCOMMIT=0;' . PHP_EOL);
            fwrite($this->fp, 'START TRANSACTION;' . PHP_EOL);
            fwrite($this->fp, 'SET SQL_QUOTE_SHOW_CREATE = 1;' . PHP_EOL);
        }
        fwrite($this->fp, 'SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;' . PHP_EOL);
        fwrite($this->fp, 'SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;' . PHP_EOL);
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        $this->writeComment('START BACKUP');
        return true;
    }

    public function EndBackup($addcheck = true)
    {

        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        fwrite($this->fp, 'SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;' . PHP_EOL);
        fwrite($this->fp, 'SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;' . PHP_EOL);
        if ($addcheck) {
            fwrite($this->fp, 'COMMIT;' . PHP_EOL);
        }
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        $this->writeComment('END BACKUP');
        fclose($this->fp);
        $this->fp = null;
    }

    public function writeComment($string)
    {
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        fwrite($this->fp, '-- ' . $string . PHP_EOL);
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
    }

    /**
     * 后台添加数据库备份方法
     */
    public function actionForm()
    {
        if(!yii::$app->request->isPost){
            $tabletmp = $this->getTables();
            $tables = array();
            foreach($tabletmp as $key => $item){
                $tmp = array();
                $sql = "SHOW TABLE STATUS LIKE '$item'";
                $cmd = Yii::$app->db->createCommand($sql);
                $message = $cmd->queryOne();
                $tmp['comment'] = $message['Comment'];
                $tmp['name'] = $item;
                array_push($tables, $tmp);
            }
            return $this->render('form',[
                'tables' => $tables
            ]);
        }else{
            $message = array(
                'status' => true,
                'message' => 'function create success'
            );
            $tables = yii::$app->request->post('tables');
            if (!empty($tables)) {
                $tables = $tables != 'all' ? $tables : $this->getTables();
            } else {
                $message['status'] = false;
                $message['message'] = 'params error';
                Yii::warning(json_encode($message), "warning");
                echo json_encode($message);
                return;
            }

            if (!$this->StartBackup()) {
                $message['status'] = false;
                $message['message'] = 'function StartBackup error';
                echo json_encode($message);
                Yii::error(json_encode($message), "error");
                return;
            }
            foreach ($tables as $tableName) {
                list($res, $info) = $this->getColumns($tableName);
                if (!$res) {
                    $message['status'] = $res;
                    $message['message'] = $info;
                    Yii::error(json_encode($message), "error");
                    echo json_encode($message);
                    return;
                }
            }
            foreach ($tables as $tableName) {
                list($res, $info) = $this->getData($tableName);
            }
            $this->EndBackup();
            //获取备份信息存入书库
            $columns = array();
            $columns['sql_name'] = basename($this->file_name);
            $columns['sql_des'] = yii::$app->request->post('db_name');
            $columns['sql_size'] = sprintf("%.2f", filesize($this->file_name) / 1024);
            $columns['sql_addtime'] = strtotime(date('Y-m-d H:i:s', filectime($this->file_name)));
            $columns['sql_content'] = implode(',', $tables);
            $Sqlbackstoremodel = new Sqlbackstore();
            list($res, $info) = $Sqlbackstoremodel->create($columns);
            if (!$res) {
                unlink($this->file_name);
                $message['status'] = $res;
                $message['message'] = $info;
                echo json_encode($message);
                Yii::error(json_encode($message), "error");
                return;
            }
            Yii::info(json_encode($message), "info");
            echo json_encode($message);
        }

    }

    public function actionClean($redirect = true)
    {
        $ignore = array('tbl_user', 'tbl_user_role', 'tbl_event');
        $tables = $this->getTables();

        if (!$this->StartBackup()) {
            Yii::$app->user->setFlash('success', "Error");
            return $this->render('index');
        }

        $message = '';
        foreach ($tables as $tableName) {
            if (in_array($tableName, $ignore)) continue;
            fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
            fwrite($this->fp, 'DROP TABLE IF EXISTS ' . addslashes($tableName) . ';' . PHP_EOL);
            fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);

            $message .= $tableName . ',';

        }
        $this->EndBackup();
        Yii::$app->user->logout();

        $this->execSqlFile($this->file_name);
        unlink($this->file_name);
        $message .= ' are deleted.';
        Yii::$app->session->setFlash('success', $message);
        return $this->redirect(array('index'));
    }
    /**
     * @method 单个批量删除备份
     * @params Integer
     * @return String
     */
    public function actionDelete()
    {
        $message = array(
            'status' => true,
            'message' => 'function delete success'
        );
        $id = yii::$app->request->post('id');
        if (empty($id)) {
            $message['status'] = false;
            $message['message'] = 'function delete params error';
            Yii::warning(json_encode($message), "warning");
            echo json_encode($message);
            return;
        }
        $list = $this->getFileList();
        $file = $list[$id];
        $sqlbackstoremodel = new Sqlbackstore();
        list($res, $info) = $sqlbackstoremodel->delByWhere(array('id' => $id));
        if (!$res) {
            $message['status'] = $res;
            $message['message'] = $info;
            echo json_encode($message);
            Yii::error(json_encode($message), "error");
            return;
        }
        Yii::info(json_encode($message), "info");
        echo json_encode($message);
    }

    /**
     * @method批量删除备份
     * @params array
     * @return String
     */
    public function actionDeletes()
    {
        $message = array(
            'status' => true,
            'message' => 'function deletes success'
        );
        $ids = yii::$app->request->post('ids');
        if (empty($ids)) {
            $message['status'] = false;
            $message['message'] = 'function deletes params error';
            Yii::warning(json_encode($message), "warning");
            echo json_encode($message);
            return;
        }
        $sqlbackstoremodel = new Sqlbackstore();
        $success = 0;
        $error = 0;
        if ($ids == 'all') {
            $idsarr = $sqlbackstoremodel->find()->select('id')->asArray()->all();
            $tmparr = array();
            foreach ($idsarr as $key => $item) {
                array_push($tmparr, $item['id']);
            }
            $ids = $tmparr;
        }
        foreach ($ids as $key => $id) {
            list($res, $info) = $sqlbackstoremodel->delByWhere(array('id' => $id));
            if (!$res) {
                $message['status'] = $res;
                $message['message'] = $info;
                $error++;
                Yii::error(json_encode($message) . 'params=' . $id, "error");
            } else {
                $success++;
                Yii::info(json_encode($message), "info");
            }
        }
        echo json_encode(array(
            'status' => true,
            'success' => $success,
            'error' => $error
        ));
    }


    public function actionDownload($file = null)
    {
        if (isset($file)) {
            $sqlFile = $this->path . basename($file);
            $fileNameArr = array();
            array_push($fileNameArr,$sqlFile);
            $filename = "./" . date('YmdH') . ".zip"; // 最终生成的文件名（含路径）
            GlobalHelper::download($filename, $fileNameArr);
        }
    }

    protected function getFileList()
    {
        $path = $this->path;
        $dataArray = array();
        $list = array();
        $list_files = glob($path . '*.sql');
        if ($list_files) {
            $list = array_map('basename', $list_files);
            sort($list);
        }
        return $list;
    }

    public function actionRestore()
    {
        $message = array(
            'status' => true,
            'message' => 'function restore success'
        );
        $filename = yii::$app->request->post('filename');
        if (isset($filename)) {
            $sqlFile = $this->path . basename($filename);
        }
        list($res, $info) = $this->execSqlFile($sqlFile);
        if (!$res) {
            $message['status'] = $res;
            $message['message'] = $info;
            echo json_encode($message);
            Yii::error(json_encode($message), "error");
            return;
        }
        Yii::info(json_encode($message), "info");
        echo json_encode($message);
    }

    public function actionUpload()
    {
        $model = new UploadForm();
        if (isset($_POST['UploadForm'])) {
            $model->attributes = $_POST['UploadForm'];
            $model->upload_file = \yii\web\UploadedFile::getInstance($model, 'upload_file');
            if ($model->upload_file->saveAs($this->path . $model->upload_file)) {
                return $this->redirect(array('index'));
            }
        }
        return $this->render('upload', array('model' => $model));
    }
}
