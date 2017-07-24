<?php

use yuncms\im\frontend\assets\ImAsset;
use yuncms\system\widgets\JsBlock;

/* @var $this yii\web\View */
$assets = ImAsset::register($this);
?>
<div class="video-page">
    <!--视频部分 start-->
    <div class="video-play">
        <!--<video controls="controls" preload="auto" src="http://bmw2.thefront.com.cn/m2_2016/media/final.mp4" webkit-playsinline></video>-->
        <!--下列图片可删除，在此做视频示意-->
        <img src="<?= $assets->baseUrl ?>/img/back-img2.png" width="100%" height="100%">
    </div>
    <!--视频部分 end-->

    <!--聊天部分 start-->
    <div class="video-pane">
        <div class="video-pane-head">
            <div class="video-pane-info">
                <div class="video-info">
                    <div class="user-img">
                        <img src="<?= $assets->baseUrl ?>/img/user-img.png" width="45">
                    </div>
                    <div class="user-info-text">
                        <div class="user-info-name">美女主播朴信惠</div>
                        <div class="user-info-num">
                            <i class="user-icon-fans"></i><span id="user-icon-fans">0</span>
                            <i class="user-icon-like"></i><span id="user-icon-like">0</span>
                        </div>
                    </div>
                </div>
            </div>
            <a href="javascript:void(0);" class="video-close" onclick="quitBigGroup()"></a>
        </div>
        <div class="video-pane-body">
            <div class="video-discuss">
                <ul class="video-sms-list" id="video_sms_list">
                    <!--
                    <li>
                        <div class="video-sms-pane">
                            <div class="video-sms-text"><span class="user-name-green" >毛利晴</span>有品位</div>
                        </div>
                    </li>
                    <li>
                        <div class="video-sms-pane">
                            <div class="video-sms-text"><span class="user-name-red">近朱者赤进入房间</span></div>
                        </div>
                    </li>
                    <li>
                        <div class="video-sms-pane">
                            <div class="video-sms-text"><span class="user-name-blue">janzhu</span>美女你好</div>
                        </div>
                    </li>
                    <li>
                        <div class="video-sms-pane">
                            <div class="video-sms-text"><span class="user-name-org">cherylma</span>美女你好美女你好美女你好重要的事情说3遍</div>
                        </div>
                    </li>
                    -->

                </ul>
                <div class="video-discuss-pane">
                    <div class="video-discuss-tool" id="video-discuss-tool">
                        <!--<span class="like-icon zoomIn green"></span>-->
                        <a href="javascript:void(0);" class="video-discuss-sms" onclick="smsPicClick()"></a>

                        <a href="javascript:void(0);" class="video-discuss-like" onclick="sendGroupLoveMsg()"></a>
                    </div>
                    <div class="video-discuss-form" id="video-discuss-form" style="display: none">
                        <input type="text" class="video-discuss-input" id="send_msg_text">
                        <a href="javascript:void(0);" class="video-discuss-face" onclick="showEmotionDialog()"></a>
                        <button class="video-discuss-button" onclick="onSendMsg()">发送</button>
                    </div>
                    <div class="video-discuss-emotion" id="video-discuss-emotion" style="display: none">
                        <div class="video-emotion-pane">
                            <ul id="emotionUL">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--聊天部分 end-->
</div>
<script type="text/javascript">
    //帐号模式，0-表示独立模式，1-表示托管模式
    var accountMode = 0;
    //官方 demo appid,需要开发者自己修改（托管模式）
    var sdkAppID = <?=Yii::$app->im->appId?>;
    var accountType = <?=Yii::$app->im->accountType?>;
    var avChatRoomId = '@TGS#a3GZRIZE6'; //默认房间群ID，群类型必须是直播聊天室（AVChatRoom），这个为官方测试ID(托管模式)
    //当前用户身份
    var loginInfo = {
        'sdkAppID': sdkAppID, //用户所属应用id,必填
        'appIDAt3rd': sdkAppID, //用户所属应用id，必填
        'accountType': accountType, //用户所属应用帐号类型，必填
        'identifier': null, //当前用户ID,必须是否字符串类型，选填
        'identifierNick': "null", //当前用户昵称，选填
        'userSig': "<?=Yii::$app->im->genSig('a');?>", //当前用户身份凭证，必须是字符串类型，选填
        'headurl': '<?=$assets->baseUrl?>/img/2016.gif'//当前用户默认头像，选填
    };

    var selType = webim.SESSION_TYPE.GROUP;
    var selToID = avChatRoomId;//当前选中聊天id（当聊天类型为私聊时，该值为好友帐号，否则为群号）
    var selSess = null;//当前聊天会话

    //默认群组头像(选填)
    var selSessHeadUrl = '<?=$assets->baseUrl?>/img/2017.jpg';

    //监听（多终端同步）群系统消息方法，方法都定义在demo_group_notice.js文件中
    //注意每个数字代表的含义，比如，
    //1表示监听申请加群消息，2表示监听申请加群被同意消息，3表示监听申请加群被拒绝消息等
    var onGroupSystemNotifys = {
        //"1": onApplyJoinGroupRequestNotify, //申请加群请求（只有管理员会收到,暂不支持）
        //"2": onApplyJoinGroupAcceptNotify, //申请加群被同意（只有申请人能够收到,暂不支持）
        //"3": onApplyJoinGroupRefuseNotify, //申请加群被拒绝（只有申请人能够收到,暂不支持）
        //"4": onKickedGroupNotify, //被管理员踢出群(只有被踢者接收到,暂不支持)
        "5": onDestoryGroupNotify, //群被解散(全员接收)
        //"6": onCreateGroupNotify, //创建群(创建者接收,暂不支持)
        //"7": onInvitedJoinGroupNotify, //邀请加群(被邀请者接收,暂不支持)
        //"8": onQuitGroupNotify, //主动退群(主动退出者接收,暂不支持)
        //"9": onSetedGroupAdminNotify, //设置管理员(被设置者接收,暂不支持)
        //"10": onCanceledGroupAdminNotify, //取消管理员(被取消者接收,暂不支持)
        "11": onRevokeGroupNotify, //群已被回收(全员接收)
        "255": onCustomGroupNotify//用户自定义通知(默认全员接收)
    };


    //监听事件
    var listeners = {
        "onConnNotify": onConnNotify, //选填
        "jsonpCallback": jsonpCallback, //IE9(含)以下浏览器用到的jsonp回调函数,移动端可不填，pc端必填
        "onBigGroupMsgNotify": onBigGroupMsgNotify, //监听新消息(大群)事件，必填
        "onMsgNotify": onMsgNotify,//监听新消息(私聊(包括普通消息和全员推送消息)，普通群(非直播聊天室)消息)事件，必填
        "onGroupSystemNotifys": onGroupSystemNotifys, //监听（多终端同步）群系统消息事件，必填
        "onGroupInfoChangeNotify": onGroupInfoChangeNotify//监听群资料变化事件，选填
    };

    var isAccessFormalEnv = true;//是否访问正式环境

</script>
<?php JsBlock::begin() ?>
<script type="text/javascript">

    if (webim.Tool.getQueryString("groupid")) {
        avChatRoomId = webim.Tool.getQueryString("groupid");//用户自定义房间群id
    }

    if (webim.Tool.getQueryString("isAccessFormalEnv") == "false") {
        isAccessFormalEnv = false;//访问测试环境
    }

    //监听连接状态回调变化事件
    var onConnNotify = function (resp) {
        switch (resp.ErrorCode) {
            case webim.CONNECTION_STATUS.ON:
                //webim.Log.warn('连接状态正常...');
                break;
            case webim.CONNECTION_STATUS.OFF:
                webim.Log.warn('连接已断开，无法收到新消息，请检查下你的网络是否正常');
                break;
            default:
                webim.Log.error('未知连接状态,status=' + resp.ErrorCode);
                break;
        }
    };

    var isLogOn = true;//是否在浏览器控制台打印sdk日志

    //其他对象，选填
    var options = {
        'isAccessFormalEnv': isAccessFormalEnv,//是否访问正式环境，默认访问正式，选填
        'isLogOn': isLogOn//是否开启控制台打印日志,默认开启，选填
    };

    var curPlayAudio = null;//当前正在播放的audio对象

    var openEmotionFlag = false;//是否打开过表情

    if (accountMode == 1) {//托管模式
        //判断是否已经拿到临时身份凭证
        if (webim.Tool.getQueryString('tmpsig')) {
            if (loginInfo.identifier == null) {
                webim.Log.info('start fetchUserSig');
                //获取正式身份凭证，成功后会回调tlsGetUserSig(res)函数
                TLSHelper.fetchUserSig();
            }
        } else {//未登录,无登录态模式
            //sdk登录
            sdkLogin();
        }
    } else {//独立模式
        //sdk登录
        sdkLogin();
    }
    if (/debug/gi.test(location.hash)) {
        document.write('<script src="http://sdklog.isd.com/js/vconsole.min.js"></scr' + 'ipt>');
    }
</script>
