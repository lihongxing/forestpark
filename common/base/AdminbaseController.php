<?php
namespace app\common\base;

use app\modules\rbac\components\Helper;
use Yii;
use yii\di\Instance;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\User;

class AdminbaseController extends BaseController
{
    //成功信息的跳转时间
    private $_successWait = 2;
    //失败信息的跳转时间
    private $_errorWait = 3;
    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    function error($message = '', $jumpUrl = '', $ajax = false)
    {
        $this->dispatchJump($message, 0, $jumpUrl, $ajax);
    }

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param string $message 提示信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    function success($message = '', $jumpUrl = '', $ajax = false)
    {
        $this->dispatchJump($message, 1, $jumpUrl, $ajax);
    }

    /**
     * 默认跳转操作 支持错误导向和正确跳转
     * 调用模板显示 默认为public目录下面的success页面
     * 提示页面为可配置 支持模板标签
     * @param string $message 提示信息
     * @param Boolean $status 状态
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @access private
     * @return void
     */
    function dispatchJump($message, $status = 1, $jumpUrl = '', $ajax = false)
    {
        if (true === $ajax || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')) {// AJAX提交
            $data = is_array($ajax) ? $ajax : array();
            $data['info'] = $message;
            $data['status'] = $status;
            $data['url'] = $jumpUrl;
            $this->ajaxReturn($data);
        }
        $viewData = array();
        $viewData['waitSecond'] = 0;
        $viewData['message'] = $viewData["error"] = $message;
        if (is_int($ajax))
            $viewData['waitSecond'] = $ajax;
        if (!empty($jumpUrl))
            $viewData['jumpUrl'] = $jumpUrl;
        // 提示标题
        $viewData['msgTitle'] = $status ? "提示信息" : "错误信息";
        $viewData['status'] = $status;
        if ($status) { //发送成功信息
            // 成功操作后默认停留2秒
            $viewData['waitSecond'] = $this->_successWait;
            // 默认操作成功自动返回操作前页面
            if (!isset($viewData['jumpUrl']))
                $viewData["jumpUrl"] = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "javascript:window.close();";
            $this->renderself($viewData); //渲染模板
        } else {
            //发生错误时候默认停留3秒
            $viewData['waitSecond'] = $this->_errorWait;
            // 默认发生错误的话自动返回上页
            if (!isset($viewData['jumpUrl']))
                $viewData['jumpUrl'] = "javascript:history.back(-1);";
            $this->renderself($viewData); //渲染模板
            // 中止执行  避免出错后继续执行
            exit;
        }
    }
    function renderself($data)
    {
        extract($data);
        include  realpath(dirname(__FILE__).'/../') . "/dispatch_jump.php";
    }

    public function beforeAction($action)
    {
        if(parent::beforeAction($action)){
            $actionId = $action->getUniqueId();
            $user = Instance::ensure('user', User::className());
            if(in_array($actionId, [
                'rbac/user/login',
                'rbac/user/logout',
                'rbac/user/search',
                'rbac/user/department-form',
                'admin/site/welcome',
                'admin/site/error',
            ])){
                return true;
            }
            if ($user->getIsGuest()) {
                return $this->redirect(Url::toRoute('/rbac/user/login'));
            }

            if (Helper::checkRoute('/' . $actionId, Yii::$app->getRequest()->get(), $user)) {
                return true;
            }else{
                if(Yii::$app->request->isAjax){
                    $this->ajaxReturn(json_encode(['status' => 403, 'message' => '对不起，您现在还没获此操作的权限']));
                    return false;
                }else{
                    throw new ForbiddenHttpException('对不起，您现在还没获此操作的权限');
                }
            }
        }else{
            return false;
        }

    }
}