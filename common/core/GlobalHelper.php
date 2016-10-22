<?php
namespace app\common\core;

use yii;

class GlobalHelper
{

    /**
     * @分页函数
     *
     * @param unknown $total
     * @param unknown $pageIndex
     * @param number $pageSize
     * @param string $url
     * @param unknown $context
     * @return string
     */
    public static function pagination($total, $pageIndex, $pageSize = 15, $url = '', $context = array('before' => 5, 'after' => 4, 'ajaxcallback' => ''))
    {
        global $_W;
        $pdata = array(
            'tcount' => 0,
            'tpage' => 0,
            'cindex' => 0,
            'findex' => 0,
            'pindex' => 0,
            'nindex' => 0,
            'lindex' => 0,
            'options' => ''
        );
        if ($context['ajaxcallback']) {
            $context['isajax'] = true;
        }

        $pdata['tcount'] = $total;
        $pdata['tpage'] = ceil($total / $pageSize);
        if ($pdata['tpage'] <= 1) {
            return '';
        }
        $cindex = $pageIndex;
        $cindex = min($cindex, $pdata['tpage']);
        $cindex = max($cindex, 1);
        $pdata['cindex'] = $cindex;
        $pdata['findex'] = 1;
        $pdata['pindex'] = $cindex > 1 ? $cindex - 1 : 1;
        $pdata['nindex'] = $cindex < $pdata['tpage'] ? $cindex + 1 : $pdata['tpage'];
        $pdata['lindex'] = $pdata['tpage'];

        if ($context['isajax']) {
            if (!$url) {
                $url = $_W['script_name'] . '?' . http_build_query($_GET);
            }
            $pdata['faa'] = 'href="javascript:;" page="' . $pdata['findex'] . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['findex'] . '\', this);return false;"' : '');
            $pdata['paa'] = 'href="javascript:;" page="' . $pdata['pindex'] . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['pindex'] . '\', this);return false;"' : '');
            $pdata['naa'] = 'href="javascript:;" page="' . $pdata['nindex'] . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['nindex'] . '\', this);return false;"' : '');
            $pdata['laa'] = 'href="javascript:;" page="' . $pdata['lindex'] . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['lindex'] . '\', this);return false;"' : '');
        } else {
            if ($url) {
                $pdata['faa'] = 'href="?' . str_replace('*', $pdata['findex'], $url) . '"';
                $pdata['paa'] = 'href="?' . str_replace('*', $pdata['pindex'], $url) . '"';
                $pdata['naa'] = 'href="?' . str_replace('*', $pdata['nindex'], $url) . '"';
                $pdata['laa'] = 'href="?' . str_replace('*', $pdata['lindex'], $url) . '"';
            } else {
                $_GET['page'] = $pdata['findex'];
                $pdata['faa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['pindex'];
                $pdata['paa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['nindex'];
                $pdata['naa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['lindex'];
                $pdata['laa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
            }
        }

        $html = '<div><ul class="pagination pagination-centered">';
        if ($pdata['cindex'] > 1) {
            $html .= "<li><a {$pdata['faa']} class=\"pager-nav\">首页</a></li>";
            $html .= "<li><a {$pdata['paa']} class=\"pager-nav\">&laquo;上一页</a></li>";
        }
        if (!$context['before'] && $context['before'] != 0) {
            $context['before'] = 5;
        }
        if (!$context['after'] && $context['after'] != 0) {
            $context['after'] = 4;
        }

        if ($context['after'] != 0 && $context['before'] != 0) {
            $range = array();
            $range['start'] = max(1, $pdata['cindex'] - $context['before']);
            $range['end'] = min($pdata['tpage'], $pdata['cindex'] + $context['after']);
            if ($range['end'] - $range['start'] < $context['before'] + $context['after']) {
                $range['end'] = min($pdata['tpage'], $range['start'] + $context['before'] + $context['after']);
                $range['start'] = max(1, $range['end'] - $context['before'] - $context['after']);
            }
            for ($i = $range['start']; $i <= $range['end']; $i++) {
                if ($context['isajax']) {
                    $aa = 'href="javascript:;" page="' . $i . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $i . '\', this);return false;"' : '');
                } else {
                    if ($url) {
                        $aa = 'href="?' . str_replace('*', $i, $url) . '"';
                    } else {
                        $_GET['page'] = $i;
                        $aa = 'href="?' . http_build_query($_GET) . '"';
                    }
                }
                $html .= ($i == $pdata['cindex'] ? '<li class="active"><a href="javascript:;">' . $i . '</a></li>' : "<li><a {$aa}>" . $i . '</a></li>');
            }
        }

        if ($pdata['cindex'] < $pdata['tpage']) {
            $html .= "<li><a {$pdata['naa']} class=\"pager-nav\">下一页&raquo;</a></li>";
            $html .= "<li><a {$pdata['laa']} class=\"pager-nav\">尾页</a></li>";
        }
        $html .= '</ul></div>';
        return $html;
    }

    /**
     * 根据data判断返回ture or false
     *
     * @param unknown $data
     * @return boolean
     */
    public static function is_error($data)
    {
        if (empty($data) || !is_array($data) || !array_key_exists('errno', $data) || (array_key_exists('errno', $data) && $data['errno'] == 0)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 根据目录创建文件夹
     *
     * @param unknown $path
     * @return boolean
     */
    public static function mkdirs($path)
    {
        if (!is_dir($path)) {
            self::mkdirs(dirname($path));
            mkdir($path);
        }
        return is_dir($path);
    }

    /**
     * php版本
     *
     * @param unknown $version1
     * @param unknown $version2
     * @return mixed
     */
    public static function ver_compare($version1, $version2)
    {
        $version1 = str_replace('.', '', $version1);
        $version2 = str_replace('.', '', $version2);
        $oldLength = self::istrlen($version1);
        $newLength = self::istrlen($version2);
        if ($oldLength > $newLength) {
            $version2 .= str_repeat('0', $oldLength - $newLength);
        }
        if ($newLength > $oldLength) {
            $version1 .= str_repeat('0', $newLength - $oldLength);
        }
        $version1 = intval($version1);
        $version2 = intval($version2);
        return version_compare($version1, $version2);
    }

    /**
     * @字符串长度
     *
     * @param unknown $string
     * @param string $charset
     * @return mixed|number
     */
    public static function istrlen($string, $charset = '')
    {
        $charset = 'utf8';
        if (strtolower($charset) == 'gbk') {
            $charset = 'gbk';
        } else {
            $charset = 'utf8';
        }
        if (function_exists('mb_strlen')) {
            return mb_strlen($string, $charset);
        } else {
            $n = $noc = 0;
            $strlen = strlen($string);
            if ($charset == 'utf8') {
                while ($n < $strlen) {
                    $t = ord($string[$n]);
                    if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                        $n++;
                        $noc++;
                    } elseif (194 <= $t && $t <= 223) {
                        $n += 2;
                        $noc++;
                    } elseif (224 <= $t && $t <= 239) {
                        $n += 3;
                        $noc++;
                    } elseif (240 <= $t && $t <= 247) {
                        $n += 4;
                        $noc++;
                    } elseif (248 <= $t && $t <= 251) {
                        $n += 5;
                        $noc++;
                    } elseif ($t == 252 || $t == 253) {
                        $n += 6;
                        $noc++;
                    } else {
                        $n++;
                    }
                }
            } else {
                while ($n < $strlen) {
                    $t = ord($string[$n]);
                    if ($t > 127) {
                        $n += 2;
                        $noc++;
                    } else {
                        $n++;
                        $noc++;
                    }
                }
            }
            return $noc;
        }
    }

    public static function error($errno, $message = '')
    {
        return array(
            'errno' => $errno,
            'message' => $message,
        );
    }

    public static function file_random_name($dir, $ext)
    {
        do {
            $filename = self::random(30) . '.' . $ext;
        } while (file_exists($dir . $filename));

        return $filename;
    }

    public static function random($length, $numeric = FALSE)
    {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        if ($numeric) {
            $hash = '';
        } else {
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }
        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed{mt_rand(0, $max)};
        }
        return $hash;
    }

    public static function strexists($string, $find)
    {
        return !(strpos($string, $find) === FALSE);
    }

    public static function message($msg, $redirect = '', $type = '')
    {
        global $_W, $_GPC;
        if ($redirect == 'refresh') {
            $redirect = $_W['script_name'] . '?' . $_SERVER['QUERY_STRING'];
        }
        if ($redirect == 'referer') {
            $redirect = referer();
        }
        if ($redirect == '') {
            $type = in_array($type, array('success', 'error', 'info', 'warning', 'ajax', 'sql')) ? $type : 'info';
        } else {
            $type = in_array($type, array('success', 'error', 'info', 'warning', 'ajax', 'sql')) ? $type : 'success';
        }
        if ($_W['isajax'] || !empty($_GET['isajax']) || $type == 'ajax') {
            if ($type != 'ajax' && !empty($_GPC['target'])) {
                exit("
                    <script type=\"text/javascript\">
                    parent.require(['jquery', 'util'], function($, util){
                    	var url = " . (!empty($redirect) ? 'parent.location.href' : "''") . ";
                    	var modalobj = util.message('" . $msg . "', '', '" . $type . "');
                    	if (url) {
                    		modalobj.on('hide.bs.modal', function(){\$('.modal').each(function(){if(\$(this).attr('id') != 'modal-message') {\$(this).modal('hide');}});top.location.reload()});
                    	}
                    });
                    </script>");
            } else {
                $vars = array();
                $vars['message'] = $msg;
                $vars['redirect'] = $redirect;
                $vars['type'] = $type;
                exit(json_encode($vars));
            }
        }
        if (empty($msg) && !empty($redirect)) {
            header('location: ' . $redirect);
        }
        $label = $type;
        if ($type == 'error') {
            $label = 'danger';
        }
        if ($type == 'ajax' || $type == 'sql') {
            $label = 'warning';
        }
        exit();
    }

    static function tomedia($src, $local_path = false)
    {
        global $_W;
        if (empty($src)) {
            return '';
        }
        if (strpos($src, './addons') === 0) {
            return yii::$app->params['siteurl'] . str_replace('./', '', $src);
        }
        $t = strtolower($src);
        if (self::strexists($t, 'http://') || self::strexists($t, 'https://')) {
            return $src;
        }
        if ($local_path) {
            $src = yii::$app->params['siteurl'] . $_W['config']['upload']['attachdir'] . '/' . $src;
        } else {
            $src = $_W['attachurl'] . $src;
        }
        return $src;
    }

    /**
     * 文件打包下载方法
     * @param $filename :打包下载的文件
     * @param $fileNameArr 需要打包下载的文件
     */
    static function download($filename, $fileNameArr)
    {
        // 生成文件
        $zip = new \ZipArchive (); // 使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
        if ($zip->open($filename, \ZIPARCHIVE::CREATE) !== TRUE) {
            exit ('无法打开文件，或者文件创建失败');
        }
        //$fileNameArr 就是一个存储文件路径的数组 比如 array('/a/1.jpg,/a/2.jpg....');
        foreach ($fileNameArr as $val) {
            $zip->addFile($val, basename($val)); // 第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
        }
        $zip->close(); // 关闭
        //下面是输出下载;
        if (!file_exists($filename)) {
            exit("无法找到文件"); //即使创建，仍有可能失败。。。。
        }
        header("Cache-Control: max-age=0");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename=' . basename($filename)); // 文件名
        header("Content-Type: application/zip"); // zip格式的
        header("Content-Transfer-Encoding: binary"); // 告诉浏览器，这是二进制文件
        header('Content-Length: ' . filesize($filename)); // 告诉浏览器，文件大小
        @readfile($filename);//输出文件;
        unlink($filename);
    }


    static function format_date_distance_now($time)
    {
        $t = time() - strtotime($time);
        if ($t > 0) {
            $ba = '前';
        } else {
            $ba = '后';
        }
        $f = array(
            '31536000' => '年',
            '2592000' => '个月',
            '604800' => '星期',
            '86400' => '天',
            '3600' => '小时',
            '60' => '分钟',
            '1' => '秒'
        );

        foreach ($f as $k => $v) {
            if (0 != $c = floor(abs($t) / (int)$k)) {
                return $c . $v . $ba;
            }
        }
    }


    static function array_sort($array, $keys, $type = 'asc')
    {
        $keysvalue = $new_array = array();
        foreach ($array as $k => $v) {
            $keysvalue[$k] = $v[$keys];
        }
        if ($type == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
            $new_array[$k] = $array[$k];
        }
        return $new_array;
    }
}

?>