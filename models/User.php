<?php
namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use app\common\core\StringHelper;
use yii\data\Pagination;

class User extends ActiveRecord implements IdentityInterface
{

    public static function tableName()
    {
        return '{{%users}}';
    }

    public function rules()
    {
        return [
            [['u_name', 'u_pass', 'u_phone', 'u_login_num', 'u_login_time', 'u_old_login_time', 'u_login_ip', 'u_old_login_ip'], 'required'],
            [['u_name'], 'string'],
            [['u_pass'], 'string', 'max' => 128],
            [['u_phone'], 'string', 'max' => 32],
        ];

    }

    public function attributeLabels()
    {
        return [
            'u_id' => '用户id',
            'u_name' => '用户名',
            'u_pass' => '用户密码',
            'u_points' => '用户积分',
            'u_realname' => '用户真实姓名',
            'u_phone' => '手机号码',
            'u_phoneauthentication' => '手机号认证状态，默认为0 1 未认证 1已认证',
            'u_tel' => '用户联系电话',
            'u_email' => '用户邮箱',
            'u_address' => '用户地址',
            'u_flag' => '用户标识',
            'u_regtime' => '用户注册时间',
            'u_avatr' => '用户头像',
            'u_sex' => '用户性别,1 男 2 女',
            'u_birthday' => '用户生日',
            'u_qq' => '用户qq',
            'u_login_num' => '登录次数',
            'u_login_time' => '当前登录时间',
            'u_old_login_time' => '上次登录时间',
            'u_login_ip' => '当前登录ip',
            'u_old_login_ip' => '上次登录ip',
            'is_buy' => '会员是否拥有购买权限 1 为开启 0 为关闭',
            'u_state' => '会员的开启状态 1 为开启 0 为关闭',
            'u_ctid' => '城市id',
            'u_proid' => '省份id',
        ];
    }

    /**
     * 根据用户id返回用户信息
     * @param Int $id
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * 根据用户token返回用户信息
     * @param $token
     * @param string $type
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * 根据公司名返回用户信息
     * @param $username
     */
    public static function findByCompanyname($companyname)
    {
        $user = User::find()
            ->where(['companyname' => $companyname])
            ->asArray()
            ->one();
        if ($user) {
            return new static($user);
        }
        return null;
    }

    /**
     * 根据用户名返回用户信息
     * @param $username
     */
    public static function findByUsername($username)
    {
        $user = User::find()
            ->where(['username' => $username])
            ->asArray()
            ->one();
        if ($user) {
            return new static($user);
        }
        return null;
    }


    //根据会员编号查询内容
    public static function findByUsernum($username)
    {
        $user = User::find()
            ->where(['username' => $username])
            ->asArray()
            ->one();
        if ($user) {
            return new static($user);
        }
        return null;
    }

    public static function findByEmail($email)
    {
        $user = User::find()
            ->where(['email' => $email])
            ->asArray()
            ->one();
        if ($user) {
            return new static($user);
        }
        return null;
    }

    /**
     * 获取用户id
     * @see \yii\web\IdentityInterface::getId()
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 获取用户authKey
     * @see \yii\web\IdentityInterface::getAuthKey()
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * 验证用户授权
     * @see \yii\web\IdentityInterface::validateAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * 验证用户密码
     * @param $password
     */
    public function validatePassword($password)
    {
        return \yii::$app->security->validatePassword($password, $this->password);
        //return $this->password === $password;
    }

    /**
     * 用户信息修改
     * @param array $regesterdata
     * @return 100:修改成功，101：修改失败
     */
    public function useredit($regesterdata)
    {
        $products = '';
        foreach ($regesterdata['field_products'] as $product) {
            if (!empty($product) && $product != "") {
                $products = $products . $product . '|';
            }
        }
        $brands = '';
        foreach ($regesterdata['field_brands'] as $brand) {
            if (!empty($brand) && $brand != "") {
                $brands = $brands . $brand . '|';
            }
        }
        $attributes = array(
            'telephone' => $regesterdata['field_telephoness'],
            'country' => $regesterdata['field_country'],
            'speciality' => $regesterdata['field_speciality'],
            'facility' => $regesterdata['field_facility'],
            'products' => substr($products, 0, strlen($products) - 1),
            'brands' => substr($brands, 0, strlen($brands) - 1),
            'password' => Yii::$app->security->generatePasswordHash($regesterdata['field_password']),
        );
        $condition = 'id=:id';

        $params = array(
            ':id' => $regesterdata['uid']
        );
        $count = $this->updateAll($attributes, $condition, $params);
        if ($count > 0) {
            return 100;
        } else {
            return 101;
        }
    }

    /**
     * 密码加密并设置到模型
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * AuthKey加密并设置到模型
     */
    public function generateAuthKey()
    {
        return Yii::$app->security->generateRandomString();
    }

    /**
     * 重置密码
     */
    public function resetPassword($password, $valitekey)
    {
        //判断用户名邮箱是否存在
        $attributes = array(
            'password' => Yii::$app->security->generatePasswordHash($password),
            'validekeystate' => 1
        );
        $condition = 'validekey=:validekey';
        $params = array(
            ':validekey' => $valitekey
        );
        $count = $this->updateAll($attributes, $condition, $params);
        if ($count > 0) {
            return "100";
        } else {
            return "101";
        }
    }

    /**
     * 用户邮箱激活
     * @param 用户id
     * @return 100:表示成功，101：表示失败
     */
    public function active($id)
    {
        $attributes = array(
            'status' => 1
        );
        $condition = 'id=:id';
        $params = array(
            ':id' => $id
        );
        $count = $this->updateAll($attributes, $condition, $params);
        if ($count > 0) {
            return 100;
        } else {
            return 101;
        }
    }

    public function getUserlistAdmin($flag, $content)
    {
        if ($flag == 'all') {
            $query = $this->find()->orderBy("cast(u_id as unsigned) desc");
        } else if ($flag == '1') {
            $query = $this->find()->where(array('email' => $content));
        } else if ($flag == '2') {
            $query = $this->find()->where(array('companyname' => $content));
        } else if ($flag == '3') {
            $query = $this->find()->where(array('username' => $content));
        } else if ($flag == '4') {
            $query = $this->find()->where(array('telephone' => $content));
        } else {
            $query = $this->find()->orderBy("cast(u_id as unsigned) desc");
        }
        $countQuery = clone $query;
        $pages = new Pagination(['defaultPageSize' => 10, 'totalCount' => $countQuery->count()]);
        $users = $query->offset($pages->offset)->limit($pages->limit)->all();
        return array(
            'pages' => $pages,
            'users' => $users
        );
    }

    /**
     * 用户注册
     * @param 用户注册表单提交数据
     * @return 返回插入用户详细信息 ,以及注册状态100：成功,101,102:失败
     */
    public function registeradd($regesterdata)
    {
        $this->setAttribute('id', '');
        $this->setAttribute('countlogin', '0');
        $this->setAttribute('username', $regesterdata['field_first_name']);
        $this->setAttribute('email', $regesterdata['email']);
        $this->setAttribute('telephone', $regesterdata['field_telephone']);
        $this->setAttribute('country', $regesterdata['field_country']);
        $this->setAttribute('status', 1);
        $this->setPassword(strtolower($regesterdata['field_password']));
        $this->setAttribute('authKey', $this->generateAuthKey());
        //生成随机码
        $time = time();
        $randomcode = StringHelper::randString(10);
        $randomcode = md5($randomcode . $time . $regesterdata['field_first_name']);
        $this->setAttribute('randomcode', $randomcode);
        $this->setAttribute('companyname', $regesterdata['field_companyname']);
        $this->setAttribute('committee', $regesterdata['field_committee']);
        $this->setAttribute('associationduties', $regesterdata['field_associationduties']);
        $this->setAttribute('branchduties', $regesterdata['field_branchduties']);
        $this->setAttribute('addtime', $regesterdata['field_addtime']);
        if ($this->validate()) {
            if ($this->save()) {
                return array(
                    'status' => '100'
                );
            } else {
                return array(
                    'status' => '102'
                );
            }
        } else {
            return array(
                'status' => '101'
            );
        }
    }

    public function resetPasswordStep1($emailname, $validekey, $passoword)
    {
        $condition = 'email=:email';
        $params = array(
            ':email' => $emailname
        );
        $attributes = array(
            'validekey' => $validekey,
            'validekeystate' => 2,
            'tmppassword' => $passoword
        );
        $count = $this->updateAll($attributes, $condition, $params);
        if ($count > 0) {
            return 100;
        } else {
            return 101;
        }
    }
}
