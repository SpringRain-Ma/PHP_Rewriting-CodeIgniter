<?php
/**
 * Created by PhpStorm.
 * User: rain
 * Date: 2018/1/9
 * Time: 14:16
 */

/**
 * 解析访问路径
 * Class CI_URI
 */

class CI_URI {
    var $segments = array(); // 原始URL的分段信息
    var $uri_string; // detect_uri()函数得到的路径字符串
    var $rsegments; // 经过路由后的分段信息 TODO: 为什么不初始化一个空数组呢?


    /**
     * TODO: ???
     */
    function fetch_uri_string() {
        if ($uri = $this->detect_uri()) {
            $this->set_uri_string($uri);
            return;
        }
    }


    /**
     * TODO: ???
     * @param $str
     */

    function set_uri_string($str) {
        $this->uri_string = ($str == '/') ? '' : $str;
    }

    /**
     * 得到 模块/控制器/方法 字符串
     * @return mixed|string
     */
    function detect_uri() {
        if (!isset($_SERVER['REQUEST_URI']) OR !isset($_SERVER['SCRIPT_NAME'])) {
            return '';
        }

        $uri = $_SERVER['REQUEST_URI'];

        if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
           $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
        }

        if ($uri == '/' || empty($uri)) {
            return '/';
        }

        $uri = parse_url($uri, PHP_URL_PATH);

        return str_replace(array('//', '../'), '/', trim($uri, '/'));

    }

    /**
     * 获取原始路由分段信息
     */
    function explode_uri() {

        foreach (explode('/', preg_replace('|/*(.+?)/*$|', '\\1', $this->uri_string)) as $val) {

            $val = trim($val);
            if ($val != '') {
                $this->segments[] = $val;
            }

        }


    }
}

