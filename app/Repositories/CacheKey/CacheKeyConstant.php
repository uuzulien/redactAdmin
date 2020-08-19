<?php


namespace App\Repositories\CacheKey;


class CacheKeyConstant
{
    // 微信公众号的所有openid
    const WECHAT_OPENID_LIST = "wechat_openid_list";
    //记录活跃粉丝的key
    const WECHAT_ACTIVE_FANS = "wechat_active_fans";
    // 公众号的基本信息
    const WECHAT_INFO = "wechat_info";


    //调用公众号的access_token 。authorizer_access_token_{授权方的appid}
    const AUTHORIZER_ACCESS_TOKEN = "authorizer_access_token_";
    //存储令牌
    const COMPONENT_TOKEN = "component_token";
    //用来记录队列发送成功的次数(群发模板消息)
    const TEMPLATE_MSG = "template_msg_";
    //用来记录队列发送成功的次数(群发客服消息)
    const SERVICE_MSG = "service_msg_";
}