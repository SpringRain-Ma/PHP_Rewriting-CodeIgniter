<?php
/**
 * Created by PhpStorm.
 * User: rain
 * Date: 2018/1/5
 * Time: 09:43
 */


/**
 * detect_uri方法的思路
 *
 * 这个方法的目的是为了得到路径中指向了 哪个模块下的控制器的方法, 这样才能执行此方法.
 *
 * 举例:
 *     假设访问路径为: http://localhost:8070/01_URL/index.php/admin/login/index?name=Tom
 *
 *     通过路径, 我们知道要访问的是admin模块下的login控制器中的index方法, 并且传了个name参数
 *
 *     此时, 我们需要获取到 "admin/login/index" 这段字符串.
 *
 * 实现思路:
 *     1. 首先, 可以通过
 *          $_SERVER['REQUEST_URI'] 获取到字符串 "/01_URL/index.php/admin/login/index?name=Tom"
 *          $_SERVER['SCRIPT_NAME'] 获取到字符串 "/01_URL/index.php" (只有访问的文件路径部分)
 *
 *        注意: 这两个全局变量都必须有值, 否则无法获取到想要的字符串.
 *
 *     2. 判断 $_SERVER['REQUEST_URI'] 字符串的开头 是否和 $_SERVER['SCRIPT_NAME'] 的字符串 一致. 如果一致, 那么 从 $_SERVER['REQUEST_URI'] 字符串中将 $_SERVER['SCRIPT_NAME'] 的字符串去掉, 只留下 /模块/控制器/方法?参数=值  的部分
 *
 *     3. 然后通过 parse_url()函数 将字符串中的参数部分去掉, 得到 /模块/控制/方法 字符串.
 *
 *     4. 最后, 去掉字符串开头结尾的 '/', 并且替换掉字符串中的 '//' 和 '../'. TODO:???
 */


/**
 * 得到访问路径中  模块/控制器/方法 的部分的字符串
 * @return string
 */
function detect_uri() {


    // 访问的URL: http://localhost:8070/01_URL/index.php/admin/login/index?name=Tom

    // $_SERVER['REQUEST_URI'] : /01_URL/index.php/admin/login/index?name=Tom

    // $_SERVER['SCRIPT_NAME'] : /01_URL/index.php


    // $_SERVER 是一个包含了诸如Header, 路径, 以及脚本位置等等信息的数组. 数组中的元素是由Web服务器提供的, 但是不同的服务器提供的元素有可能不同, 和命令行模式下提供的元素也不同.
    // REQUEST_URI : URI用来指定要访问的页面()
    // SCRIPT_NAME : 包含当前脚本的路径
    // OR 等同于 ||

    // 要求 $_SERVER['REQUEST_RUI'] 和 $_SERVER['SCRIPT_NAME'] 必须同时都有值.
    if (!isset($_SERVER['REQUEST_URI']) OR !isset($_SERVER['SCRIPT_NAME'])) {
        return '';
    }


    $uri = $_SERVER['REQUEST_URI']; // 获取URI: /01_URL/index.php/admin/login/index?name=Tom



    // TODO: 什么情况下, strpos()函数返回false 怎么办????
    // strpos(str1, str2) 在str1中查找str2首次出现的位置, 没有找到返回false, 找到就返回字符串位置, 由于位置从0开始, 所以应该使用 恒等(===)进行条件判断.
    if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) { //  路径中不能出现中文, 否则strpos会返回false

        // 为了去掉访问文件的路径 /01_URL/index.php, 而只得到 /模块/控制器/方法?参数 的字符串.
        $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME'])); // /admin/login/index?name=Tom
    }

    // 如果访问路径是http://localhost/01_URL/index.php, 那么此时的$uri就是空, 所以需要empty()判断.
    if ($uri == '/' || empty($uri)) {
        return '/';
    }

    $uri = str_replace(array('//', '../'), '/', $uri);
//    echo $uri.'<br>';

    // parse_url — 解析 URL，返回其组成部分
    $uri = parse_url($uri, PHP_URL_PATH); // 这一步是为了去掉路径中的query部分

//    echo $uri.'<br>';
    // 开头结尾不能有/, 将路径中的 '//' 或 '../' 等进行清理.
    // TODO: 什么情况下$uri中会有 // 或 ../ 的情况啊???
//    $uri = str_replace(array('//', '../'), '/', $uri);
    return  trim($uri, '/');

    // 最终只得到   模块/控制器/方法 字符串

}

$uri = detect_uri();
//echo $uri;


/* --------------------------------------------------------------------------- */

/**
 * 把 detect_uri() 方法得到的字符串分隔
 * 比如: admin/login/index  分隔, 得到 存储 'admin', 'login', 'index' 的数组.
 * @param $uri
 * @return array
 */

function explode_uri($uri) {

    // 此时$uri 为 'admin/login/index'

    /**
     * preg_replace("|/*(.+?)/*$|", "\\1", $uri) 作用:
     *  使用正则的方式去掉 $uri字符串中 开头和结尾中 的多个 '/',
     *  比如 $uri是 ///admin/login/index// 这样的时候,  希望得到 admin/login/index 字符串.
     */


    foreach (explode('/', preg_replace("|/*(.+?)/*$|", "\\1", $uri)) as $val) {

        $val = trim($val);
        if ($val != '') {
            $segments[] = $val;
        }

    }
    return $segments;
}


//$uri = '///'.$uri.'///';
//echo $uri;
$uri_segments = explode_uri($uri);
//print_r($uri_segments);


/* --------------------------------------------------------------------------- */

// 测试的访问路径:  http://localhost/01_URL/index.php/welcome/hello
// 也就是说我们要运行 Welcome类下的hello方法.

$class = explode_uri($uri)[0]; // 获取类名
$method = explode_uri($uri)[1]; // 获取方法名称

// 如果需要导入文件, 则需要自动加载
//function loadClass($class) {
//    $file = ucfirst($class) . '.class.php';
//    if (is_file($file)) { // 如果$file是一个文件的名称, 则需要导入这个文件.
//        require_once $file;
//    }
//}
//spl_autoload_register('loadClass');


$class = ucfirst($class); // ucfirst()将字符串的首字母大写
$obj = new $class(); // 相当于 new Welcome();

$obj->$method(); // 相当于 $obj->hello();


class Welcome {
    function hello() {
        echo 'My first PHP Framework!';
    }
}




