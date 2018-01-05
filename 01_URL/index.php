<?php


/**
 * 为了得到访问路径中  模块/控制器/方法 的部分的字符串
 * @return string
 */
function detect_uri() {


    // 访问的URL: http://localhost:8070/01_URL/index.php/module/controller/action?name=Tom

    // $_SERVER['REQUEST_URI'] : /01_URL/index.php?name=Tom

    // $_SERVER['SCRIPT_NAME'] : /01_URL/index.php



    // $_SERVER 是一个包含了诸如Header, 路径, 以及脚本位置等等信息的数组. 数组中的元素是由Web服务器提供的, 但是不同的服务器提供的元素有可能不同, 和命令行模式下提供的元素也不同.
    // REQUEST_URI : URI用来指定要访问的页面()
    // SCRIPT_NAME : 包含当前脚本的路径
    // OR 等同于 ||

    // 要求 $_SERVER['REQUEST_RUI'] 和 $_SERVER['SCRIPT_NAME'] 必须同时都有值.
    if (!isset($_SERVER['REQUEST_URI']) OR !isset($_SERVER['SCRIPT_NAME'])) {
        return '';
    }


    $uri = $_SERVER['REQUEST_URI']; // 获取URI: /01_URL/index.php/module/controller/action?name=Tom

    // 我觉得放在strpos()判断前面更好
    if ($uri == '/' || empty($uri)) { //TODO: 没有必要判断empty($uri)吧???
        return '/';
    }


    // strpos(str1, str2) 在str1中查找str2首次出现的位置, 没有找到返回false, 找到就返回字符串位置, 由于位置从0开始, 所以应该使用 恒等(===)进行条件判断.
    if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) { // TODO: 路径中不能出现中文, 否则strpos会返回false

        // 为了去掉访问文件的路径 /01_URL/index.php, 而只得到 /模块/控制器/方法?参数 的字符串.
        $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME'])); // /module/controller/action?name=Tom
    }


    // parse_url — 解析 URL，返回其组成部分
    $uri = parse_url($uri, PHP_URL_PATH); // 这一步是为了去掉路径中的query部分


    // 开头结尾不能有/, 将路径中的 '//' 或 '../' 等进行清理.
    // TODO: 什么情况下$uri中会有 // 或 ../ 的情况啊???
    return str_replace(array('//', '../'), '/', trim($uri, '/'));

    // 最终只得到   模块/控制器/方法 字符串

}

$uri = detect_uri();
echo $uri;
