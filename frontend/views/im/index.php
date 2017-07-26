<?php

use yuncms\im\frontend\assets\ImAsset;
use yuncms\system\widgets\JsBlock;

/* @var $this yii\web\View */
$assets = ImAsset::register($this);
//$this->context->layout = false;
?>
<div class="row">
    <div>

    </div>
    <div class="video-page">
        <!--视频部分 start-->
        <div class="video-play">
            <!--<video controls="controls" preload="auto" src="http://bmw2.thefront.com.cn/m2_2016/media/final.mp4" webkit-playsinline></video>-->
            <!--下列图片可删除，在此做视频示意-->
            <!--        <img src="--><?//= $assets->baseUrl ?><!--/img/back-img2.png" width="100%" height="100%">-->
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
</div>

<?php
$imUser = \yuncms\im\models\Account::findByUser(Yii::$app->user->identity);
?>
<?= \yuncms\im\frontend\widgets\ChatRoomWidget::widget([
    'identifier' => $imUser->identifier,
    'identifierNick' => $imUser->nick,
    'userSig' => $imUser->getSign(),
    'headUrl' => $imUser->faceUrl,
    'avLiveRoomId' => '@TGS#a6GQ7R3E5'
]) ?>

<?php JsBlock::begin(['pos' => \yii\web\View::POS_END]) ?>
<script type="text/javascript">
        //sdk登录
    sdkLogin();
</script>
<?php JsBlock::end() ?>