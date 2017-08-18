<?php

namespace yuncms\im\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%im_account}}".
 *
 * @property int $id
 * @property int $user_id 原始用户ID
 * @property string $identifier 用户名，长度不超过 32 字节
 * @property string $nick 用户昵称
 * @property string $head_url 用户头像URL。
 * @property int $type 帐号类型，开发者默认无需填写，值 0 表示普通帐号，1 表示机器人帐号。
 * @property string $sign 签名
 * @property int $expires_at 签名过期时间
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property User $user
 * @property Group[] $Groups
 */
class Account extends ActiveRecord
{
    //普通账户
    const TYPE_GENERAL = 0b0;

    //机器人账户
    const TYPE_ROBOT = 0b1;

    const OLD_EMAIL_CONFIRMED = 0b1;
    const NEW_EMAIL_CONFIRMED = 0b10;
    const OLD_MOBILE_CONFIRMED = 0b11;

    const STATE_OFFLINE = 0b0;//客户端主动退出登录或者客户端自上一次登录起7天之内未登录过。
    const STATE_PUSH_ONLINE = 0b1;//IOS客户端退到后台或进程被杀或因网络问题掉线，进入PushOnline状态，
    //此时仍然可以接收消息离线APNS推送。注意，云通信后台只会保存PushOnline状态7天时间，若从掉线时刻起7天之内未登录过，则进入Offline状态。
    const STATE_ONLINE = 0b11; //客户端登录后和云通信后台有长连接

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%im_account}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
//            'blameable' => [
//                'class' => BlameableBehavior::className(),
//                'attributes' => [
//                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
//                ],
//            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['identifier'], 'unique'],
            [['identifier'], 'string', 'max' => 32],

            [['nick', 'head_url'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],

            // type rules
            ['type', 'default', 'value' => self::TYPE_GENERAL],
            ['type', 'in', 'range' => [
                self::TYPE_GENERAL,
                self::TYPE_ROBOT,
            ]],

            // state rules
            ['state', 'default', 'value' => self::STATE_OFFLINE],
            ['state', 'in', 'range' => [
                self::STATE_OFFLINE,
                self::STATE_PUSH_ONLINE,
                self::STATE_ONLINE,
            ]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('im', 'ID'),
            'user_id' => Yii::t('im', 'User Id'),
            'identifier' => Yii::t('im', 'Identifier'),
            'nick' => Yii::t('im', 'Nick'),
            'head_url' => Yii::t('im', 'Face Url'),
            'type' => Yii::t('im', 'Account Type'),
            'created_at' => Yii::t('im', 'Created At'),
            'updated_at' => Yii::t('im', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * 定义群组关系
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['account_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return AccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AccountQuery(get_called_class());
    }

    /**
     * 获取用户IM资料
     * @param User $user
     * @return null|static
     */
    public static function findByUser(User $user)
    {
        if (($model = static::findOne(['user_id' => $user->id])) != null) {
            return $model;
        } else {
            $model = new static([
                'identifier' => md5($user->id),
                'nick' => $user->username,
                'type' => self::TYPE_GENERAL,
                'user_id' => $user->id,
                'head_url' => $user->getAvatar(User::AVATAR_BIG)
            ]);
            if ($model->save()) {
                return $model;
            } else {
                return null;
            }
        }
    }

    /**
     * 获取签名
     * @return string
     */
    public function getSign()
    {
        if (time() > $this->expires_at) {//已经过期
            $this->resetSign();
        }
        return $this->sign;
    }

    /**
     * 创建签名
     */
    public function generateSign()
    {
        $this->expires_at = time() + 7200;
        $this->sign = Yii::$app->im->genSig($this->identifier, 7200);
    }

    /**
     * 重置签名
     * @return void
     */
    public function resetSign()
    {
        $this->generateSign();
        $this->updateAttributes(['sign' => $this->sign, 'expires_at' => $this->expires_at]);
    }

    /**
     * 保存前生成签名
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->generateSign();
        }
        return parent::beforeSave($insert);
    }

    /**
     * 模型保存后执行
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            //注册
            $res = Yii::$app->im->accountImport($this->identifier, $this->nick, $this->head_url, $this->type);
        } else {
            //修改

        }
    }

    /**
     * 删除后执行
     * im 无此接口
     */
//    public function afterDelete()
//    {
//        parent::afterDelete();
//
//    }
}
