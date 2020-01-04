# API RESULT FORMAT


#### 中间件配置

bean配置(file: app/beans.php)
```
'httpDispatcher'   => [
    // Add global http middleware
    'middlewares' => [
        // Notice: Please add to the first place
        \Minms\SwoftContracts\Http\Middleware\ApiResultFormatResult::class,
    ],
],
```

#### 异常处理里使用
```
return bean("apiResultFormat")->format($data, $code, $message);
```