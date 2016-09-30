<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class AccountController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function actionDisplay()
    {
        return $this->renderPartial('display');
    }
}
