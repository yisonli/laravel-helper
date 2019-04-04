> Common functions for PHP developer, base on laravel framwork.

## Install
```
composer require yisonli/laravel-helper:0.2
```

## Directory
```
.
├── README.md
├── composer.json
└── src
    ├── Crypt
    │   ├── AES.php
    │   ├── RSA.php
    │   └── Sign.php
    ├── Curl
    │   ├── Get.php
    │   ├── Post.php
    │   ├── PostJson.php
    │   ├── ResCode.php
    │   └── Response.php
    ├── Log
    │   ├── MonitorLog.php
    │   └── Sensitive.php
    └── Misc
        ├── ArrayHelper.php
        ├── Datas.php
        ├── Identity.php
        ├── Timestamp.php
        └── Verify.php
```

## Config You Need
For debugging and checking, some methods use custom logs, so you need to add the following configuration to `.env` file. The specific log path can be configured on demand.

```
USE_MONITOR_LOG=1
MONITOR_ERROR_LOG=/tmp/${APP_NAME}/logs/error.log
MONITOR_WARN_LOG=/tmp/${APP_NAME}/logs/warn.log
MONITOR_INFO_LOG=/tmp/${APP_NAME}/logs/info.log
```

## Usage
Now let's introduce the use of these functions.

### Crypt Functions 
【AES】

```
$aes = new AES($token);

$encrypted = $aes->encrypt($string);

$decrypted = $aes->decrypt($encrypted);
```

【Sign - The same as WeChat's sign】

```
$params['sign'] = Sign::getSign($params, $token);

$isValid = Sign::checkSign($params);
```

【RSA】

```
$rsa = new RSA($priKey, $pubKey, 'java');

$params['bizContent'] = $rsa->encrypt($bizContent);

$params['sign'] = $rsa->sign($params);
        
$isValid = $rsa->verify($params, $params['sign']);

$decrypted = $rsa->decrypt($result['bizContent'])
```


### Curl Functions 
【Get】

```
$result = Get::run($url, $params, true);
```

【Post】

```
$result = Post::run($url, $params, true);
```

【PostJson】

```
$result = PostJson::run($url, $params, true);
```

【Response】

```
return Response::success($data, $header, $isHump);

return Response::error(array_merge(ResCode::ERR_LACK_PARAM, ['data'=>$errorMsg]));

return Response::fail(ResCode::ERR_THIRD_API_FAIL, 'something wrong.');
```


### Log Functions 
【MonitorLog】

```
MonitorLog::getInstance()->error($msg, $content);

MonitorLog::getInstance()->warn($msg, $content);

MonitorLog::getInstance()->info($msg, $content);
```
> If you want to filter the sensitive words from log message, you can add config `MONITOR_FILTER=true` to `.env` file. 


【Sensitive】

```
$res_data = Sensitive::filter($data);
```


### Misc Functions 
【ArrayHelper】

```
$array = [
    ['id' => '123', 'name' => 'aaa', 'class' => 'x'],
    ['id' => '124', 'name' => 'bbb', 'class' => 'x'],
    ['id' => '345', 'name' => 'ccc', 'class' => 'y'],
];
$result = ArrayHelper::map($array, 'id', 'name');

$result = ArrayHelper::map($array, 'id', 'name', 'class');


$array = [
    ['id' => '123', 'data' => 'abc'],
    ['id' => '345', 'data' => 'def'],
];
$result = ArrayHelper::index($array, 'id');
```

【Datas】

```
$string = Datas::lineToHump($string);

$string = Datas::humpToLine($string);

$result = Datas::arrayToHump($result);

$result = Datas::arrayToLine($result);
```

To be continue ... 


## About Me
name: yison.li  
blog: [http://yyeer.com](http://yyeer.com)  
github: [https://github.com/yisonli](https://github.com/yisonli)

![](http://yyeer.com/assets/img/YisonWechat.png)
