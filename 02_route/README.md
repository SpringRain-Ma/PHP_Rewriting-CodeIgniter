# 实现简单路由功能

## 路由具体负责做什么的？

　举个例子，上一课中 http://localhost/learn-ci/index.php/welcome/hello， 会执行 Welcome类的 hello 方法，但是用户可能会去想去执行一个叫 welcome 的函数，并传递 'hello' 为参数。

　更实际一点的例子，比如你是一个产品展示网站， 你可能想要以如下 URI 的形式来展示你的产品，那么肯定就需要重新定义这种映射关系了。

example.com/product/1/

example.com/product/2/

example.com/product/3/

example.com/product/4/