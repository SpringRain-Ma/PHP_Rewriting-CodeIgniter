# 用面向对象重构代码

到目前为止，程序主要完成的就是 URL 分析，并根据配置文件进行路由转换，也就是说我们这里有两个对象，分别处理 URL 分析和路由转换。

## 1. URL 分析

URL 分析最主要的两个函数是 `detect_uri` 和 `explode_uri` ，考虑到 路由的需要，URL 类包含的成员变量有 

* segments 数组 原始URL的分段信息

* rsegments 数组 经过路由后的分段信息

* uri_string URL的路径信息，也就是 index.php 之后的路径

----

## 2. Router 路由类
   
   路由类起的作用就是一个路由转换 `set_routing` ，并提供给外界获取转换后路由的接口如 `fetch_class`， `fetch_method` 等, 根据前面两篇的代码和思路，很容易写出如下的 `Router`  类。
   
   其中最关键的就是 `set_routing` 函数，它被入口文件 index.php 执行，通过 URI 类分析获得 分段信息，并根据分析后的结果，获得转换后的路由，设置 class 和 method。
   
   
## 3. 主入口文件逻辑
      
   使用面向对象重构后，主入口文件只需要创建它们的实例，通过调用它们的方法，即可以处理最重要的工作，然后通过调用 `call_user_fun_array` 来执行。