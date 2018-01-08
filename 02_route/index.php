<?php
/**
 * Created by PhpStorm.
 * User: rain
 * Date: 2018/1/8
 * Time: 14:54
 */

/**
 * 1. 导入routes.php文件
 */
require_once 'routes.php'; // routes.php中设置了$routes数组存储默认路由

/* --------------------------------------------------------------------------- */

/**
 * 2. 解析访问路径, 得到 类/方法 字符串
 *
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

$uri = detect_uri();

function explode_uri($uri) {

    foreach (explode('/', preg_replace("|/*(.+?)/*$|", "\\1", $uri)) as $val) {

        $val = trim($val);
        if ($val != '') {
            $segments[] = $val;
        }

    }
    return $segments;
}

$uri_segments = explode_uri($uri);


/* --------------------------------------------------------------------------- */

/**
 * 3. 根据$routes数组中的默认路径和 第2步 中得到的路径进行对比, 看是不是默认路径.
 */

/**
 * 解析路由
 * @return mixed
 */
function parse_routes() {

    global $uri_segments, $routes, $rsegments; // 将变量设置为全局变量, 这样可以在parse_routes方法以外的作用域中使用.

    $uri = implode('/', $uri_segments);


    if (isset($routes[$uri])) { // 判断uri是不是默认的uri

        $rsegments = explode('/', $routes[$uri]);

        // 重新映射方法.
        set_request($rsegments);
        return;
    }

}

function set_request($segments = array()) {
    global $class, $method;

    $class = $segments[0];

    if (isset($segments[1])) {
        $method = $segments[1];
    } else {
        $method = 'index';
    }
}

/* --------------------------------------------------------------------------- */


// 测试访问路径: http://localhost:8070/02_route/index.php/welcome/hello

parse_routes();


$CI = new $class();

// $rsegments数组中三个元素: welcome, saysomething, hello
// 这一步调用了 Welcome 类中的 saysomething方法, 并且把 hello作为参数传入saysomething方法.

// PHP4之前的写法
//call_user_func_array(array(&$CI, $method), array_slice($rsegments,2));

// PHP5开始的写法
call_user_func_array(array($CI, $method), array_slice($rsegments,2));

/**
 * call_user_func_array(回调函数, 回调函数的参数)
 * http://php.net/manual/zh/function.call-user-func-array.php
 * 使用引用作为参数  array(&$CI, $method), 实际上这种做法已经被PHP5废除.
 * http://php.net/manual/zh/function.call-user-func-array.php#41207
 *
 */


class Welcome {
    function hello() {
        echo 'My first PHP Framework!';
    }

    function saysomething($str) {
        echo $str.", I'm the php framework you created!";
    }

}



