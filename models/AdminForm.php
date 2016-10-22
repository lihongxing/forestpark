<?php

namespace app\models;

use Yii;
use yii\base\Model;

class AdminForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    private $_user = false;

    /**
     * @return array 验证规则。
     */
    public function rules()
    {
        return [

            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $admin = $this->getUser();
            if (!$admin || !$admin->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        } else {
            print_r($this->getErrors());
            die();
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->admin->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {

        }
        return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Admin::findByUsername($this->username);
        }
        return $this->_user;
    }
}
