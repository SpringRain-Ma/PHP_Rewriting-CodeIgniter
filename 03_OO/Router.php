<?php
/**
 * Created by PhpStorm.
 * User: rain
 * Date: 2018/1/9
 * Time: 15:14
 */


class CI_Router {

    var $uri;
    var $routes;
    var $class;
    var $method;
    var $default_controller;

    function __construct()
    {
        global $URI;

        $this->uri = &$URI; // TODO: 再次使用引用???
    }

    function set_routing() {
        if (is_file('routes.php')) {
            include 'routes.php'; //TODO: 是不是改为include_once更好???
        }

        $this->routes = (!isset($routes) OR !is_array($routes)) ? array() : $routes;

        unset($routes); // TODO: unset()了

        $this->default_controller = (!isset($this->routes['default_controller']) OR $this->routes['default_controller'] == '') ? false : $this->routes['default_controller'];
        //TODO: 把isset()替换成empty不行吗?


        $this->uri->fetch_uri_string();

        if ($this->uri->uri_string == '') {
            return $this->set_default_controller();
        }

        $this->uri->explode_uri();

        $this->parse_routes();

    }

    function set_default_controller() {

    }

    function parse_routes() {

        $uri = implode('/', $this->uri->segments);

        if (isset($this->routes[$uri])) {
            $rsegments = explode('/', $this->routes[$uri]);

            return $this->set_request($rsegments);
        }
    }

    function set_request($segments = array()) {
        if (count($segments) == 0) {
            return $this->set_default_controller();
        }

        $this->set_class($segments[0]);

        if (isset($segments[1])) {
            $this->set_method($segments[1]);
        } else {
            $method = 'index';
        }

        $this->uri->rsegments = $segments;
    }

    function set_class($class) {
        $this->class = str_replace(array('/', '.'), '', $class);
    }

    function set_method($method) {
        $this->method = $method;
    }

    function fetch_class() {
        return $this->class;
    }

    function fetch_method() {
        return $this->method;
    }



}
