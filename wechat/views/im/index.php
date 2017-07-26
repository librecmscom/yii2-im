<?php

use yuncms\im\wechat\assets\ImAsset;
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


<?php JsBlock::begin(['pos' => \yii\web\View::POS_END]) ?>
<script type="text/javascript">
    //官方 demo appid,需要开发者自己修改（托管模式）
    var sdkAppID = <?=$this->context->module->im->appId?>;
    var accountType = <?=$this->context->module->im->accountType?>;
    var avChatRoomId = '@TGS#a6GQ7R3E5';//群ID
    var selType = webim.SESSION_TYPE.GROUP;//消息类型
    var selToID = avChatRoomId;//当前选中聊天id（当聊天类型为私聊时，该值为好友帐号，否则为群号）
    var selSess = null;//当前聊天会话
    var selSessHeadUrl = '<?=$assets->baseUrl?>/img/2017.jpg';//默认群组头像(选填)
    //当前用户身份
    var loginInfo = {
        'sdkAppID': sdkAppID, //用户所属应用id,必填
        'appIDAt3rd': sdkAppID, //用户所属应用id，必填
        'accountType': accountType, //用户所属应用帐号类型，必填
        'identifier': 'c4ca4238a0b923820dcc509a6f75849b', //当前用户ID,必须是否字符串类型，选填
        'identifierNick': "方圆百里找对手", //当前用户昵称，选填
        'userSig': "eJxtjttOg0AQht*F2xoze4BlvaNVI7XVHggebsiyu*CqUISlhRjfXUrwzrmZ5Pvmzz-fTrTaXwopD21pE9tX2rlyOEPcuRiNUbq0JjO6HrikUlBMfAEpHxYGJaULXHgZc33K0ykjqsqoRNiE1GpIIQoAGGOEJ9*oj2S8*U-qrjK1TkRmx0qGASZz1HVjDuUAMSAXYQLnmaQ1xflz5AJwj1Df-*sy*YDXNy*LcHvdxBvRfkEz55n2yKssFwHuYmaXd6SNwkJVZM83Uc*eujx8C0KcvpezftWwdf1wbD-N7WORPtNlx8JdMJ-Fp9OW5Ka6X-jg-PwCh8Jf5A__", //当前用户身份凭证，必须是字符串类型，选填
        'headurl': '<?=$assets->baseUrl?>/img/2016.gif'//当前用户默认头像，选填
    };
    //监听（多终端同步）群系统消息方法，方法都定义在demo_group_notice.js文件中
    //注意每个数字代表的含义，比如，
    //1表示监听申请加群消息，2表示监听申请加群被同意消息，3表示监听申请加群被拒绝消息等
    var onGroupSystemNotifys = {
        "5": onDestoryGroupNotify, //群被解散(全员接收)
        "11": onRevokeGroupNotify, //群已被回收(全员接收)
        "255": onCustomGroupNotify//用户自定义通知(默认全员接收)
    };

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


    //监听事件
    var listeners = {
        "onConnNotify": onConnNotify, //选填
        "jsonpCallback": jsonpCallback, //IE9(含)以下浏览器用到的jsonp回调函数,移动端可不填，pc端必填
        "onBigGroupMsgNotify": onBigGroupMsgNotify, //监听新消息(大群)事件，必填
        "onMsgNotify": onMsgNotify,//监听新消息(私聊(包括普通消息和全员推送消息)，普通群(非直播聊天室)消息)事件，必填
        "onGroupSystemNotifys": onGroupSystemNotifys, //监听（多终端同步）群系统消息事件，必填
        "onGroupInfoChangeNotify": onGroupInfoChangeNotify//监听群资料变化事件，选填
    };

    //SDK可选参数
    var options = {
        'isAccessFormalEnv': true,//是否访问正式环境，默认访问正式，选填
        'isLogOn': true//是否开启控制台打印日志,默认开启，选填
    };

    //当前正在播放的audio对象
    var curPlayAudio = null;

    //是否打开过表情
    var openEmotionFlag = false;

    //sdk登录
    sdkLogin();
</script>
<?php JsBlock::end() ?>