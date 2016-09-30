<?php
namespace app\modules\rbac\models\form;

use Yii;
use app\modules\rbac\models\User;
use yii\base\Model;

/**
 * Signup form
 */
class Signup extends Model
{
    public $username;
    public $email;
    public $password;
    public $head_img;
    public $mobile;
    public $created_at;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'app\modules\rbac\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'app\modules\rbac\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup($signup)
    {
        $user = new User();
        $user->username = $signup['username'];
        $user->email =$signup['email'];
        $user->head_img = $signup['head_img'];
        $user->mobile = $signup['mobile'];
        $user->setPassword($signup['password']);
        if ($user->save()) {
            return $user;
        }
        return null;

    }
}
