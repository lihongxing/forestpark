<?php
namespace app\common\core;
use Yii;
use yii\base\Object;
use yii\web\Controller;
use yii\helpers\Url;

class MessageHelper extends Object
{
    private $_successWait = 3;
    //成功信息的跳转时间
    private $_errorWait = 3;

    //失败信息的跳转时间
    function init()
    {
    }

    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    static function error($message = '', $jumpUrl = '', $ajax = false)
    {
        $self = new self();
        $self->dispatchJump($message, 0, $jumpUrl, $ajax);

    }

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param string $message 提示信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    static function success($message = '', $jumpUrl = '', $ajax = false)
    {
        $self = new self();
        $self->dispatchJump($message, 1, $jumpUrl, $ajax);
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

    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @return void
     */
    function ajaxReturn($data, $type = 'JSON')
    {
        switch (strtoupper($type)) {
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data));
            case 'XML' :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit($this->xml_encode($data));
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
            default :
                // 其他返回格式抛出异常
                throw new \Exception('该数据格式尚未支持，请修改本函数源码添加对应的头');
        }
    }

    /**
     * XML编码
     * @param mixed $data 数据
     * @param string $root 根节点名
     * @param string $item 数字索引的子节点名
     * @param string $attr 根节点属性
     * @param string $id 数字索引子节点key转换的属性名
     * @param string $encoding 数据编码
     * @return string
     */
    function xml_encode($data, $root = 'root', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8')
    {
        if (is_array($attr)) {
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr = trim($attr);
        $attr = empty($attr) ? '' : " {$attr}";
        $xml = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
        $xml .= "<{$root}{$attr}>";
        $xml .= $this->data_to_xml($data, $item, $id);
        $xml .= "</{$root}>";
        return $xml;
    }

    /**
     * 数据XML编码
     * @param mixed $data 数据
     * @param string $item 数字索引时的节点名称
     * @param string $id 数字索引key转换为的属性名
     * @return string
     */
    function data_to_xml($data, $item = 'item', $id = 'id')
    {
        $xml = $attr = '';
        foreach ($data as $key => $val) {
            if (is_numeric($key)) {
                $id && $attr = " {$id}=\"{$key}\"";
                $key = $item;
            }
            $xml .= "<{$key}{$attr}>";
            $xml .= (is_array($val) || is_object($val)) ? $this->data_to_xml($val, $item, $id) : $val;
            $xml .= "</{$key}>";
        }
        return $xml;
    }

    //渲染模板
    function renderself($data)
    {
        extract($data);
        include realpath(dirname(__FILE__) . '/../') . "/message.php";
    }

    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter))
            return $this->$setter($value);
    }

    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter))
            return $this->$getter();
    }

    function setSuccessWait($value)
    {
        $this->_successWait = (int)$value;
    }

    function setErrorWait($value)
    {
        $this->_errorWait = (int)$value;
    }


}

?>
