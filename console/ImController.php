<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\im\console;

use Yii;
use yii\console\Controller;
use yuncms\user\models\User;
use yuncms\im\models\Account;

/**
 * Class ImController
 * @package yuncms\im\console
 */
class ImController extends Controller
{
    public function actionImport()
    {
        $users = User::find()->all();
        foreach ($users as $user) {
            $im = Account::findByUser($user);
            print_r($im->toArray());
        }
    }

    public function actionIndex()
    {
        //$res = Yii::$app->im->accountImport('xcr','xcr','https://mc.qcloudimg.com/static/img/1a692e7c41d513399008018673892cb1/IM.png');

        //print_r($res);
        //exit;

        $res = Yii::$app->im->groupCreate([
            'Owner_Account' => 'xcr',// 群主的identifier（选填）
            'Type' => 'AVChatRoom',// 群组类型：Private/Public/ChatRoom/AVChatRoom（必填）
            'Name' => '测试群名称',// 群名称（必填）
            'ApplyJoinOption' => 'FreeAccess',
        ]);
        if ($res['ErrorCode'] == 0) {

            $this->stdout('Processing the message :' . $res['GroupId'] . PHP_EOL);
        } else {
            $this->stderr('Processing the message failure :' . $res['ErrorCode'] . PHP_EOL);
        }

        print_r($res);
        exit;
    }
}