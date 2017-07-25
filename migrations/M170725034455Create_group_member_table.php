<?php

namespace yuncms\im\migrations;

use yii\db\Migration;

class M170725034455Create_group_member_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%im_group_member}}', [
            'id' => $this->primaryKey(),
            'account_id' => $this->integer()->comment('原始IM ID'),
            'user_id' => $this->integer()->comment('原始用户ID'),
            'group_id' => $this->integer()->comment('原始群ID'),
        ], $tableOptions);
        $this->addForeignKey('{{%im_group_member_ibfk_1}}', '{{%im_group_member}}', 'group_id', '{{%im_group}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%im_group_member_ibfk_2}}', '{{%im_group_member}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%im_group_member_ibfk_3}}', '{{%im_group_member}}', 'account_id', '{{%im_account}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('{{%im_group_member}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M170725034455Create_group_member_table cannot be reverted.\n";

        return false;
    }
    */
}
