<?php
/**
 * ::: DESCRIPTION HERE :::
 *
 * @author
 */
class m131216_194741_alter_reviews_add_precontract extends CDbMigration
{
    public function up()
    {
        $this->addColumn('reviews', 'precontract', 'TINYINT(1) NOT NULL DEFAULT 0 AFTER replyBlocked');
    }

    public function down()
    {
        $this->dropColumn('reviews', 'precontract');
    }
}