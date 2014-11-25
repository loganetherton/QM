<?php
/**
 * Add flagAccountManagerId field into "reviews" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m131128_180752_alter_reviews_add_flag_account_manager_id_field extends CDbMigration
{
    public $tableName = 'reviews';
    public $columnName = 'flagAccountManagerId';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'INT(11) NULL AFTER `accountManagerId`');

        $updateQuery = "UPDATE ".$this->tableName." r, users u, account_managers a
            SET r.".$this->columnName." = a.id
            WHERE r.businessId = u.id
            AND u.accountManagerId = a.id
            AND r.status = ".Review::STATUS_FLAGGED;
        $this->execute($updateQuery);
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}