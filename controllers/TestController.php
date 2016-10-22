<?php

namespace app\controllers;

use yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionTest()
    {
        return $this->renderPartial('test');
    }

    public function actionTestswitch()
    {
        return $this->renderPartial('testswitch');
    }

    /**
     * membercache缓存测试
     */
    public function actionTestsmembercache()
    {
        //\yii::$app->membercache->set('111', '1234', 45);

        echo \yii::$app->membercache->get('111');
        die();

        //\yii::$app->membercache->add('111', '122');
    }

    /**
     * 带分页大小的分页测试
     */
    public function actionTestlinkpage()
    {
        $params = yii::$app->request->get();
        $query = (new Query())
            ->select(['id', 'email'])
            ->from('lhxcms_user');
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '1']);
        $pageSize = isset($params['per-page']) ? intval($params['per-page']) : 1; //默认20
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => $pageSize,],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function search($params)
    {
        $query = (new Query())
            ->select(['id', 'email'])
            ->from('lhxcms_user');
        $pageSize = isset($params['per-page']) ? intval($params['per-page']) : 1; //默认20
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => $pageSize,],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
