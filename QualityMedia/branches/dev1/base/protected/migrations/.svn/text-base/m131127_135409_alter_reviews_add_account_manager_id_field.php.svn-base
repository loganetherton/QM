<?php
/**
 * Add accountManagerId field into "reviews" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m131127_135409_alter_reviews_add_account_manager_id_field extends CDbMigration
{
    public $tableName = 'reviews';
    public $columnName = 'accountManagerId';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'INT(11) NULL AFTER `userId`');

        $updateQuery = "UPDATE ".$this->tableName." r, users u, account_managers a
            SET r.".$this->columnName." = a.id
            WHERE r.businessId = u.id
            AND u.accountManagerId = a.id
            AND r.publicCommentContent !=''
            AND (r.publicCommentAuthor = CONCAT(a.lastName, ' ', a.firstName) OR r.publicCommentAuthor = CONCAT(a.firstName, ' ', a.lastName))
            ";
        $this->execute($updateQuery);
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}