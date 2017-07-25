<?php

namespace yuncms\im\migrations;

use yii\db\Migration;

class M170725032440Create_group_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%im_group}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->comment('原始用户ID'),
            'account_id' => $this->integer()->comment('群主ID'),
            'identifier' => $this->string(32)->unique()->comment('创建成功之后的群ID，由IM云后台分配。'),
            'name' => $this->string(30)->notNull()->comment('群名称'),
            'type' => $this->string(30)->notNull()->comment('群类型'),
            'introduction' => $this->string(240)->notNull()->comment('群简介'),
            'notification' => $this->string(300)->notNull()->comment('群公告'),
            'FaceUrl' => $this->string(100)->notNull()->comment('群头像URL'),
            'max_member_count' => $this->integer(6)->comment('最大群成员数量，缺省时的默认值：私有群是200，公开群是2000，聊天室是10000，互动直播聊天室和在线成员广播大群无限制。'),
            'apply_join_option' => $this->string(30)->notNull()->comment('申请加群处理方式。包含FreeAccess（自由加入），NeedPermission（需要验证），DisableApply（禁止加群），不填默认为NeedPermission（需要验证）。'),
            'created_at' => $this->integer()->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at' => $this->integer()->notNull()->defaultValue(0)->comment('更新时间'),
        ], $tableOptions);

        $this->addForeignKey('{{%im_group_ibfk_1}}', '{{%im_group}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%im_group_ibfk_2}}', '{{%im_group}}', 'account_id', '{{%im_account}}', 'id', 'CASCADE', 'RESTRICT');

    }

    public function safeDown()
    {
        $this->dropTable('{{%im_group}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M170725032440Create_group_table cannot be reverted.\n";

        return false;
    }
    */
}
