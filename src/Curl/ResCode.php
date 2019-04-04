<?php

namespace LaravelHelper\Curl;

class ResCode
{
    /*
     * 通用错误码 Code约定
     * 0表示成功[SUCCESS],                        看到0，业务处理成功。
     * 10000 - 19999表示业务警告[WARN_],           这种code不是常规武器，能免则免。
     * 20000 - 29999表示通用错误代码[ERR_],        各个系统通用的错误代码。
     * 30000 - 39999表示业务自定义错误代码[DIY_]
     * 40000 - 49999表示系统错误[SYS_],            系统错误单独拉出来，作为独立区域。理论上这部分也是通用的，不可以自定义。
     */
    const SUCCESS = ['code' => 0, 'msg' => '操作成功'];

    const ERR_LACK_PARAM = ['code' => 20001, 'msg' => '请求参数不正确'];
    const ERR_NO_LOGIN = ['code' => 20002, 'msg' => '用户未登录'];
    const ERR_NO_RIGHT = ['code' => 20003, 'msg' => '没有权限访问该资源'];
    const ERR_NO_SERVICE = ['code' => 20004, 'msg' => '资源不存在'];
    const ERR_WRONG_STATUS = ['code' => 20005, 'msg' => '资源的当前状态不支持该操作'];
    const ERR_LACK_CONFIG = ['code' => 20006, 'msg' => '缺少必要的配置项'];
    const ERR_PROCESS_FAIL = ['code' => 20007, 'msg' => '业务处理失败'];
    const ERR_THIRD_API_FAIL = ['code' => 20008, 'msg' => '调用第三方接口失败'];
    const ERR_IS_DELETED = ['code' => 20009, 'msg' => '资源已删除'];
    const ERR_UPDATE_FAIL = ['code' => 20010, 'msg' => '更新操作失败'];

    const SYS_MAINTENANCE = ['code' => 40001, 'msg' => '系统维护中'];
    const SYS_BUSY = ['code' => 40002, 'msg' => '系统繁忙'];
    const SYS_EXCEPTION = ['code' => 40003, 'msg' => '系统异常'];
}
