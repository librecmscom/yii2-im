<?php

namespace yuncms\im\migrations;

use yii\db\Migration;

class M170725031036Create_account_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%im_account}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->comment('原始用户ID'),
            'identifier' => $this->string(32)->notNull()->unique()->comment('用户名，长度不超过 32 字节'),
            'nick' => $this->string(50)->comment('用户昵称'),
            'head_url' => $this->string()->comment('用户头像URL。'),
            'type' => $this->smallInteger(1)->defaultValue(0)->comment('帐号类型，开发者默认无需填写，值 0 表示普通帐号，1表示机器人帐号。'),
            'state' => $this->smallInteger(1)->defaultValue(0b0)->comment('用户在线状态'),
            'sign' => $this->text()->comment('用户签名'),
            'expires_at' => $this->integer()->defaultValue(null)->comment('签名有效期'),
            'created_at' => $this->integer()->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at' => $this->integer()->notNull()->defaultValue(0)->comment('更新时间'),
        ], $tableOptions);

        $this->addForeignKey('{{%im_account_ibfk_1}}', '{{%im_account}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('{{%im_account}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M170725031036Create_group_table cannot be reverted.\n";

        return false;
    }
    */
}
