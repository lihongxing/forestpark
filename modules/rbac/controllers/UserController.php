<?php

namespace app\modules\rbac\controllers;

use app\common\base\AdminbaseController;
use app\models\Department;
use app\modules\rbac\models\Assignment;
use app\modules\rbac\models\form\NodeForm;
use Yii;
use app\modules\rbac\models\form\Login;
use app\modules\rbac\models\form\PasswordResetRequest;
use app\modules\rbac\models\form\ResetPassword;
use app\modules\rbac\models\form\Signup;
use app\modules\rbac\models\form\ChangePassword;
use app\modules\rbac\models\User;
use app\modules\rbac\models\searchs\User as UserSearch;
use yii\base\InvalidParamException;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\base\UserException;
use yii\helpers\Url;

/**
 * User controller
 */
class UserController extends AdminbaseController
{
    private $_oldMailPath;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'logout' => ['post'],
                    'activate' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        if ($this->_oldMailPath !== null) {
            Yii::$app->getMailer()->setViewPath($this->_oldMailPath);
        }
        return parent::afterAction($action, $result);
    }

    /**
     * 列出条件搜索用户模型。
     * @return mixed
     */
    public function actionIndex()
    {
        $GET = yii::$app->request->get();
        $searchModel = new UserSearch();
        $searchModelquery = $searchModel->find();
        if(!empty($GET['name'])){
            $searchModelquery = $searchModelquery
                ->where(['or',
                    ['like', 'username', $GET['name']],
                    ['like', 'mobile', $GET['name']],
                    ['like', 'email', $GET['name']]
                ]);
        }
        if(!empty($GET['followed'])){
            $searchModelquery = $searchModelquery
                ->andWhere(['status' => $GET['followed']]);
        }
        if(!empty($GET['groupid'])){
            $searchModelquery = $searchModelquery
                ->andWhere(['role' => $GET['groupid']]);
        }
        if($GET['searchtime'] == 1){
            $time = $GET['time'];
            if(!empty($time)){
                $searchModelquery = $searchModelquery
                    ->andWhere(['between','createtime', strtotime($time['start']),strtotime($time['end'])]);
            }
        }
        $pages = new Pagination(['totalCount' => $searchModelquery->count(), 'pageSize' => '10']);
        $users = $searchModelquery
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();
        //获取所有用户角色
        $NodeForm = new NodeForm();
        $ids = $NodeForm->getPermissionsids(1);
        return $this->render('index', [
            'users' => $users,
            'pages' => $pages,
            'ids' => $ids,
            'GET' => $GET
        ]);
    }

    /**
     * 显示一个单一的用户模型。
     * @param integer $id 用户id
     * @return mixed 跳转到视图 并输出用户信息
     */
    public function actionView($id)
    {
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'id' => $id
        ]);
        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }
            $user->save();
        }
        $departmentmodel = new Department();
        $departments = $departmentmodel->find()
            ->asArray()
            ->all();
        return $this->render('view', [
            'user' => $this->findModel($id),
            'departments' => $departments
        ]);
    }

    /**
     * 显示一个单一的用户模型。
     * @param integer $id 用户id
     * @return mixed 跳转到视图 并输出用户信息
     */
    public function actionProfile($id)
    {
        $departmentmodel = new Department();
        $departments = $departmentmodel->find()
            ->asArray()
            ->all();
        return $this->render('profile', [
            'user' => $this->findModel($id),
            'departments' => $departments
        ]);
    }

    /**
     * 用户删除方法
     *
     * @param integer $id 被删除的id
     * @return $message json status删除的状态，message 删除的提示信息
     */
    public function actionDelete()
    {
        $message = array();
        $usermodel = new User();
        $id = yii::$app->request->post('id');
        if(!empty($id)){
            $userinfo  = $usermodel->find()
                ->where(['id' => $id])
                ->one();
            if($userinfo->role == '超级管理员'){
                $message['status'] = 103;
                $message['message'] = 'function delete success';
            }else{
                $count  = $this->findModel($id)->delete();
                if($count > 0){
                    $message['status'] = 100;
                    $message['message'] = 'function delete success';
                    Yii::info(json_encode($message), "info");
                }else{
                    $message['status'] = 102;
                    $message['message'] = 'function delete error sql is error';
                    Yii::error(json_encode($message), "error");
                }
            }
        }else{
            $message['status'] = 101;
            $message['message'] = 'function delete error params $id is null';
            Yii::error(json_encode($message), "error");
        }
        return Json::encode($message);
    }

    /**
     * 用户登录方法
     * @return object 用户对象
     */
    public function actionLogin()
    {
        if (!Yii::$app->getUser()->isGuest) {
            return $this->redirect(\yii\helpers\Url::toRoute('/admin/site/welcome'));
        }
        if(yii::$app->request->isPost){
            $model = new Login();
            if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
                return $this->success('登录成功',\yii\helpers\Url::toRoute('/admin/site/welcome'));
            } else {
                $error = $model->getErrors();
                if($error['status'][0] ==101){
                    $this->error('对不起您已经被禁用，不允许登录',\yii\helpers\Url::toRoute('/rbac/user/login'));
                }else{
                    $this->error('用户名密码错误',\yii\helpers\Url::toRoute('/rbac/user/login'));
                }

            }
        }else{
            return $this->render('login');
        }
    }

    /**
     * Logout
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->getUser()->logout();

        return $this->success('退出成功',\yii\helpers\Url::toRoute('/rbac/user/login'));
    }

    /**
     * Signup new user
     * @return string
     */
    public function actionSignup()
    {
        $model = new Signup();
        $post = Yii::$app->getRequest()->post();
        $signup = array(
            'username' => $post['Signup']['username'],
            'password' => $post['Signup']['password'],
            'mobile' => $post['Signup']['mobile'],
            'head_img' => $post['thumb'],
            'email' => $post['Signup']['email'],
            'department' => $post['Signup']['department'],
        );
        if(yii::$app->request->isPost){
            if($user = $model->signup($signup)) {
                return $this->redirect(Url::toRoute('/rbac/user/index'));
            }
        }
        $departmentmodel = new Department();
        $departments = $departmentmodel->find()
            ->asArray()
            ->all();
        return $this->render('signup', [
            'model' => $model,
            'departments' => $departments
        ]);
    }

    /**
     * Request reset password
     * @return string
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequest();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
                'model' => $model,
        ]);
    }

    /**
     * Reset password
     * @return string
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPassword($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate() && $model->resetPassword()) {
            return $this->success('密码修改成功');
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * 重置密码
     * @return string
     */
    public function actionChangePassword()
    {
        $model = new ChangePassword();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->change()) {
            return $this->redirect(\yii\helpers\Url::toRoute('/admin/site/welcome'));
        }
        return $this->render('change-password', [
                'model' => $model,
        ]);
    }

    /**
     * 激活新用户
     * @param integer $id
     * @return type
     * @throws UserException
     * @throws NotFoundHttpException
     */
    public function actionActivate($id)
    {
        /* @var $user User */
        $user = $this->findModel($id);
        if ($user->status == User::STATUS_INACTIVE) {
            $user->status = User::STATUS_ACTIVE;
            if ($user->save()) {
                return $this->goHome();
            } else {
                $errors = $user->firstErrors;
                throw new UserException(reset($errors));
            }
        }
        return $this->goHome();
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 启用禁用用户方法
     *
     * @param integer $id 被启用禁用用户的id
     * @return $message json status启用禁用的状态，message启用禁用的提示信息
     */
    public function actionStatus()
    {
        $id = yii::$app->request->post('id');
        $state = yii::$app->request->post('status');
        $usermodel = new User();
        //判断当前用户是否为超级管理员
        $userinfo = $usermodel->find()
            ->select(['role'])
            ->where(['id' => $id])
            ->asArray()
            ->one();
        if($userinfo['role'] == '超级管理员'){
            $this->ajaxReturn(json_encode(['status' => 104]));
            $message['status'] = 104;
            $message['message'] = 'function Status error user is Super administrator';
            Yii::error(json_encode($message), "error");
        }
        if(!empty($id)){
            if($state != 'false'){
                $attributes = array(
                    'status' => 1
                );
            }else{
                $attributes = array(
                    'status' => 10
                );
            }
            $condition = 'id=:id';
            $params = array(
                ':id' => $id
            );

            $count = $usermodel->updateAll($attributes, $condition, $params);
            if($count > 0){
                if($count > 0){
                    $message['status'] = 100;
                    $message['message'] = 'function Status success';
                    Yii::info(json_encode($message), "info");
                }else{
                    $message['status'] = 102;
                    $message['message'] = 'function Status error sql is error';
                    Yii::error(json_encode($message), "error");
                }
            }
        }else{
            $message['status'] = 101;
            $message['message'] = 'function Status error params $id or $state is null';
            Yii::error(json_encode($message), "error");
        }
    }

    /**
     * 密码修改验证原密码是否正确
     * @param password：原密码
     * @return string ture：原密码输入正确，false：原密码输入错误
     */
    public function actionCheckpassword()
    {

        $post = yii::$app->request->post();
        $password = $post['ChangePassword']['oldPassword'];
        $userid = yii::$app->user->id;
        if(!empty($password)){
            $usermodel = new User();
            $userinfo = $usermodel->find()
                ->where(['id' => $userid])
                ->one();
            if(!empty($userinfo)){
                if(Yii::$app->security->validatePassword($password, $userinfo->password_hash)){
                    $message['status'] = 100;
                    $message['message'] = 'function checkpassword success';
                    Yii::info(json_encode($message), "info");
                    echo 'true';
                }else{
                    $message['status'] = 102;
                    $message['message'] = 'function checkpassword  error';
                    Yii::info(json_encode($message), "error");
                    echo 'false';
                }
            }else{
                $message['status'] = 101;
                $message['message'] = 'function checkpassword error sql is error';
                Yii::error(json_encode($message), "error");
                echo 'false';
            }
        }else{
            $message['status'] = 101;
            $message['message'] = 'function checkpassword error params password is null';
            Yii::error(json_encode($message), "error");
            echo 'false';
        }
    }

    /**
     * 用户基本信息修改方法
     *
     */
    public function actionUpdata()
    {
        $post = yii::$app->request->post();
        if(!empty($post)){
            $attributes = array(
                'email' => $post['email'],
                'mobile' => $post['mobile'],
                'head_img' => $post['thumb'],
                'updated_at' => time()
            );
            $condition = 'username = :username';
            $params = array(
                ':username' => $post['username']
            );
            $usermodel = new User();
            $count = $usermodel->updateAll($attributes, $condition ,$params);
            if($count > 0){
                return $this->success('修改成功', Url::toRoute(['/rbac/user/profile', 'id' => yii::$app->user->id]));
            }else{
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 管理员修改用户信息方法
     */
    public function actionAdministratorsupdata()
    {
        $post = yii::$app->request->post();
        if(!empty($post)){
            $attributes = array(
                'username' => $post['username'],
                'email' => $post['email'],
                'mobile' => $post['mobile'],
                'head_img' => $post['thumb'],
                'department' => $post['department'],
                'updated_at' => time()
            );
            $condition = 'id = :id';
            $params = array(
                ':id' => $post['id']
            );
            $usermodel = new User();
            $count = $usermodel->updateAll($attributes, $condition ,$params);
            if($count > 0){
                return $this->success('修改成功', Url::toRoute(['/rbac/user/view', 'id' => $post['id']]));
            }else{
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 用户批量删除
     *
     */
    public function actionDeleteselect()
    {
        $ids = yii::$app->request->post('ids');
        if(!empty($ids)){
            $usermodel = new User();
            $query = new Query();
            $userids = $query->select(['{{%auth_assignment}}.user_id as id'])
                ->from('{{%auth_assignment}}')
                ->where(['item_name' => '超级管理员'])
                ->all();
            $idstmp = array();
            foreach($userids as $key => $item){
                array_push($idstmp, $item['id']);
            }
            $delids = array();
            $flag = false;
            foreach($ids as $key => $item){
                if(!in_array($item, $idstmp)){
                    array_push($delids, $item);
                }else{
                    $flag = true;
                }
            }
            if(!empty($delids)){
                $count = $usermodel->deleteAll(['in', 'id', $delids]);
                if($count > 0 ){
                    if($flag){
                        $message['status'] = 103;
                    }else{
                        $message['status'] = 100;
                    }
                }else{
                    $message['status'] = 101;
                }
            }else{
                if($flag){
                    $message['status'] = 103;
                }else{
                    $message['status'] = 100;
                }
            }
        }else{
            $message['status'] = 102;
        }
        $this->ajaxReturn(json_encode($message));
    }

    /**
     * 删除除超级管理员之外的所有用户
     * @throws \Exception
     */
    public function actionDeleteall()
    {
        $usermodel = new User();
        $userids = $usermodel->find()
            ->select('id')
            ->asArray()
            ->all();
        $query = new Query();
        $ids = $query->select(['{{%auth_assignment}}.user_id as id'])
            ->from('{{%auth_assignment}}')
            ->where(['item_name' => '超级管理员'])
            ->all();
        $delids =array();
        foreach($userids as $key => $item){
            if(!in_array($item, $ids)){
                array_push($delids, $item);
            }else{
                $flag = true;
            }
        }
        $count = $usermodel->deleteAll(['in', 'id', $delids]);
        if($count > 0 ){
            $message['status'] = 100;
        }else{
            $message['status'] = 101;
        }
        $this->ajaxReturn(json_encode($message));
    }

    /**
     * 验证用户属性是否被占用
     * @throws \Exception
     * @return boolean true：未被占用，false：被占用
     */
    public function actionCheckAttribute()
    {
        $signup = yii::$app->request->post();
        $key = array_keys($signup['Signup'])[0];
        if(!empty($signup)){
            $usermodel = new User();
            $count = $usermodel->find()->where([$key => $signup['Signup'][$key] ])->count();
            if($count > 0){
                $this->ajaxReturn(false);
            }else{
                $this->ajaxReturn(true);
            }
        }else{
            $this->ajaxReturn(false);
        }
    }


    /**
     * 根据用户账号手机号码姓名搜索用户信息
     * @param Strng keyword：搜索的关键词
     * @return HTML 用户信息html
     */
    public function actionSearch(){
        $keyword = yii::$app->request->get('keyword');
        $select = yii::$app->request->get('select');
        if(!empty($keyword)){
            $searchModel = new UserSearch();
            $searchModelquery = $searchModel->find();
            $searchModelquery = $searchModelquery
                ->where(['or',
                    ['like', 'username', $keyword],
                    ['like', 'mobile', $keyword],
                    ['like', 'email', $keyword]
                ]);
            $users = $searchModelquery
                ->asArray()
                ->all();
            if(!empty($users)){
                $html = '
                    <div style=\'max-height:500px;overflow:auto;min-width:850px;\'>
                        <table class="table table-hover" style="min-width:850px;">
                            <tbody>';
                                foreach($users as $key => $item){
                                    $html = $html.'
                                    <tr>
                                        <td><img src='.$item['head_img'].' style=\'width:30px;height:30px;padding1px;border:1px solid #ccc\'/>'.$item['username'].'</td>
                                        <td>'.$item['username'].'</td>
                                        <td>'.$item['mobile'].'</td>
                                        <td style="width:80px;"><a href="javascript:;" onclick=\''.$select.'('.json_encode($item).')\'>选择</a></td>
                                    </tr>
                                    ';
                                }
                     $html = $html.'
                            </tbody>
                        </table>
                    </div>';
                return $this->ajaxReturn($html);
            }else{
                return $this->ajaxReturn('
                    <div style=\'max-height:500px;overflow:auto;min-width:850px;\'>
                        <table class="table table-hover" style="min-width:850px;">
                            <tbody>
                               <tr>
                                    <td colspan=\'4\' align=\'center\'>未找到用户</td>
                               </tr>
                            </tbody>
                        </table>
                    </div>'
                );
            }
        }else{
        }
    }


    public function actionDepartmentForm()
    {
        if(yii::$app->request->isPost){
            $param_title = yii::$app->request->post('param_title');
            $param_id = yii::$app->request->post('param_id');
            $flag = 'error';
            foreach($param_title as $key =>$item){
                $departmentmodel = new Department();
                if(!empty($param_id[$key])){
                    $attributes = ['dep_name' => $item];
                    $condition = 'dep_id = :dep_id';
                    $params = [':dep_id' => $param_id[$key]];
                    $count = $departmentmodel->updateAll($attributes, $condition, $params);
                    if($count > 0){
                        $flag = 'success';
                    }
                }else{
                    //判断当前部门是否已经存在
                    $count = $departmentmodel->find()
                        ->where(['dep_name' => $item])
                        ->count();
                    if($count <= 0){
                        $departmentmodel->setAttribute('dep_name', $item);
                        $count = $departmentmodel->save();
                        if($count > 0){
                            $flag = 'success';
                        }
                    }
                }
            }
            if($flag == 'success'){
                return $this->success('添加成功');
            }else{
                return $this->success('添加失败');
            }
        }else{
            return $this->ajaxReturn('
                <tr>
                    <td>
                        <a href="javascript:;" class="fa fa-move" title="拖动调整此显示顺序" ><i class="fa fa-arrows"></i></a>&nbsp;
                        <a href="javascript:;" onclick="deleteParam(this)" style="margin-top:10px;"  title="删除"><i class=\'fa fa-times\'></i></a>
                    </td>
                    <td>
                        <input name="param_title[]" type="text" class="form-control param_title" value=""/>
                        <input name="param_id[]" type="hidden" class="form-control" value=""/>
                    </td>
                </tr>
            ');
        }
    }
}
