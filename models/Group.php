<?php

namespace yuncms\im\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%im_group}}".
 *
 * @property int $id
 * @property int $user_id 原始用户ID
 * @property int $account_id 群主ID
 * @property string $identifier 创建成功之后的群ID，由IM云后台分配。
 * @property string $name 群名称
 * @property string $type 群类型
 * @property string $introduction 群简介
 * @property string $notification 群公告
 * @property string $FaceUrl 群头像URL
 * @property int $max_member_count 最大群成员数量，缺省时的默认值：私有群是200，公开群是2000，聊天室是10000，互动直播聊天室和在线成员广播大群无限制。
 * @property string $apply_join_option 申请加群处理方式。包含FreeAccess（自由加入），NeedPermission（需要验证），DisableApply（禁止加群），不填默认为NeedPermission（需要验证）。
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property User $user
 * @property Account $account
 * @property ImGroupMember[] $imGroupMembers
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%im_group}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'account_id', 'max_member_count', 'created_at', 'updated_at'], 'integer'],
            [['name', 'type', 'introduction', 'notification', 'FaceUrl', 'apply_join_option'], 'required'],
            [['identifier'], 'string', 'max' => 32],
            [['name', 'type', 'apply_join_option'], 'string', 'max' => 30],
            [['introduction'], 'string', 'max' => 240],
            [['notification'], 'string', 'max' => 300],
            [['FaceUrl'], 'string', 'max' => 100],
            [['identifier'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['account_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('im', 'ID'),
            'user_id' => Yii::t('im', '原始用户ID'),
            'account_id' => Yii::t('im', '群主ID'),
            'identifier' => Yii::t('im', '创建成功之后的群ID，由IM云后台分配。'),
            'name' => Yii::t('im', '群名称'),
            'type' => Yii::t('im', '群类型'),
            'introduction' => Yii::t('im', '群简介'),
            'notification' => Yii::t('im', '群公告'),
            'FaceUrl' => Yii::t('im', '群头像URL'),
            'max_member_count' => Yii::t('im', '最大群成员数量，缺省时的默认值：私有群是200，公开群是2000，聊天室是10000，互动直播聊天室和在线成员广播大群无限制。'),
            'apply_join_option' => Yii::t('im', '申请加群处理方式。包含FreeAccess（自由加入），NeedPermission（需要验证），DisableApply（禁止加群），不填默认为NeedPermission（需要验证）。'),
            'created_at' => Yii::t('im', '创建时间'),
            'updated_at' => Yii::t('im', '更新时间'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImGroupMembers()
    {
        return $this->hasMany(ImGroupMember::className(), ['group_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return GroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GroupQuery(get_called_class());
    }
}
