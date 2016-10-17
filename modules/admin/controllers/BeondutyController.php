<?php
namespace app\modules\admin\controllers;
/**
 * 值班备勤的增删盖查
 * User: lihongxing
 * Date: 2016/10/14
 * Time: 15:20
 */


use app\common\base\AdminbaseController;
use app\models\Admin;
use app\models\BeondutyTemplate;
use app\models\Calendar;
use app\modules\rbac\models\User;
use yii;

class BeondutyController extends AdminbaseController
{
    public $layout='main-calendar';//设置默认的布局文件
    public function actionBeondutyList(){
        //获取所有用户信息
        $Usermodel = new User();
        $users = $Usermodel->find()
            ->asArray()
            ->all();
        //获取所有值班模板信息
        $BeondutyTemplatemodel =new BeondutyTemplate();
        $beondutytemplates = $BeondutyTemplatemodel->find()
            ->asArray()
            ->all();
        return $this->render('list',[
            'users' => $users,
            'beondutytemplates' => $beondutytemplates
        ]);
    }

    /**
     * 值班备勤模板添加方法
     * @throws \Exception
     */
    public function actionBeondutyTemplateForm()
    {
        $uid = yii::$app->request->post('uid');
        $currColor = yii::$app->request->post('currColor');
        $username = yii::$app->request->post('username');
        $val = yii::$app->request->post('val');
        if(!empty($currColor) && !empty($uid) && !empty($username)){
            $BeondutyTemplatemodel  = new BeondutyTemplate();
            $BeondutyTemplatemodel->setAttribute('tem_currColor', $currColor);
            $BeondutyTemplatemodel->setAttribute('tem_uid', $uid);
            $BeondutyTemplatemodel->setAttribute('tem_username', $username);
            $BeondutyTemplatemodel->setAttribute('tem_val', $val);
            if($BeondutyTemplatemodel->save(false)){
                $message = [
                    'id' => $BeondutyTemplatemodel->primarykey,
                    'status' => 100
                ];
            }else{
                $message = [
                    'status' => 101
                ];
            }
            $this->ajaxReturn(json_encode($message));
        }
    }

    /**
     * 值班备勤模板删除方法
     * @param tem_id：值班备勤的模板id
     * @throws \Exception
     * @return Json status：状态码
     */
    public function actionBeondutyTemplateDelete(){
        $tem_id = yii::$app->request->post('tem_id');
        if(!empty($tem_id)){
            $BeondutyTemplatemodel  = new BeondutyTemplate();
            $BeondutyTemplate = $BeondutyTemplatemodel->find()
                ->where(['tem_id' => $tem_id])
                ->one();
            $count = $BeondutyTemplate->delete();
            if($count>0){
                $message = [
                    'status' => 100
                ];
            }else{
                $message = [
                    'status' => 101
                ];
            }
            $this->ajaxReturn(json_encode($message));
        }
    }

    /**
     * 值班备勤默认列表展示
     * @param start：开始时间 end：结束时间
     * @return Json 值班备勤数据
     */
    public function actionBeondutyListDefault()
    {
        $start = strtotime(yii::$app->request->get('start'));
        $end = strtotime(yii::$app->request->get('end'));
        $query= new yii\db\Query();
        $calendarstmp = $query->select(['calendar.*', 'admin.*'])
            ->from(Calendar::tableName() . 'as calendar')
            ->leftJoin(Admin::tableName() . ' as admin', 'admin.id = calendar.cal_uid')
            ->where(['between', 'cal_starttime', $start, $end])
            ->all();
        $calendars = array();$calendar = array();
        foreach($calendarstmp as $key => $item){
            $calendar['title'] = $item['cal_title']."({$item['username']})";
            $calendar['start'] = date('Y-m-d', $item['cal_starttime']);
            $calendar['end'] = date('Y-m-d', $item['cal_endtime']);
            $calendar['backgroundColor'] = $item['cal_color'];
            $calendar['id'] = $item['cal_id'];
            $calendar['borderColor'] = $item['cal_color'];
            array_push($calendars, $calendar);
        }
        echo json_encode($calendars);
    }

    /**
     * 值班备勤新增方法
     * @throws \Exception
     */
    public function actionBeondutyForm()
    {
        $flag = yii::$app->request->post('flag');
        $Calendarmodel = new Calendar();
        if($flag == 'add'){
            $y = yii::$app->request->post('y');
            $m = yii::$app->request->post('m');
            $d = yii::$app->request->post('d');
            $tem_id = yii::$app->request->post('tem_id');
            if(!empty($tem_id)){
                $BeondutyTemplatemodel  = new BeondutyTemplate();
                $BeondutyTemplate = $BeondutyTemplatemodel->find()
                    ->where(['tem_id' => $tem_id])
                    ->asArray()
                    ->one();
                $Calendarmodel->setAttribute('cal_title', $BeondutyTemplate['tem_val']);
                $Calendarmodel->setAttribute('cal_starttime', strtotime($y.'-'.$m.'-'.$d));
                $Calendarmodel->setAttribute('cal_endtime', strtotime($y.'-'.$m.'-'.($d+1)));
                $Calendarmodel->setAttribute('cal_is_allday', 1);
                $Calendarmodel->setAttribute('cal_color', $BeondutyTemplate['tem_currColor']);
                $Calendarmodel->setAttribute('cal_uid', $BeondutyTemplate['tem_uid']);
                if($Calendarmodel->save()){
                    $message = [
                        'status' => 100
                    ];
                }else{
                    $message = [
                        'status' => 101
                    ];
                }
            }

        }else if($flag == 'update'){
            $daydiff = (int)yii::$app->request->post('daydiff')*24*60*60;
            $minudiff = (int)yii::$app->request->post('minudiff')*60;
            $id = yii::$app->request->post('id');
            $calendar = $Calendarmodel->find()
                ->where(['cal_id' => $id])
                ->one();
            $calendar->cal_starttime += $daydiff;
            $calendar->cal_endtime += $daydiff;
            if($calendar->save()){
                $message = [
                    'status' => 100
                ];
            }else{
                $message = [
                    'status' => 101
                ];
            }
        }

        $this->ajaxReturn(json_encode($message));
    }

    /**
     * 值班备勤删除
     * @param cal_id：值班备勤的id
     * @throws \Exception
     * @return Json status：删除的状态码
     */
    public function actionBeondutyDelete()
    {
        $cal_id = yii::$app->request->post('cal_id');
        if(!empty($cal_id)){
            $Calendarmodel = new Calendar();
            $calendar = $Calendarmodel->find()
                ->where(['cal_id' => $cal_id])
                ->one();
            $count = $calendar->delete();
            if($count > 0){
                $message = [
                    'status' => 100
                ];
            }else{
                $message = [
                    'status' => 101
                ];
            }
        }else{
            $message = [
                'status' => 102
            ];
        }
        $this->ajaxReturn(json_encode($message));
    }
}