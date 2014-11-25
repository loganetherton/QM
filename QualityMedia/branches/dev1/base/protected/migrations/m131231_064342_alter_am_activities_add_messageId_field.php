<?php
/**
 * Alter "am_activities" table by Adding messageId field
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m131231_064342_alter_am_activities_add_messageId_field extends CDbMigration
{
    public $tableName = 'am_activities';
    public $columnName = 'messageId';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'int(11) UNSIGNED NULL AFTER `reviewId`');

        //Reset history
        $this->truncateTable($this->tableName);

        $this->createPastHistory();
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }

    public function createPastHistory()
    {
        //Sql Union
        $sql['publicComments']  = "SELECT pc.businessId, pc.accountManagerId, pc.id, NULL, 'publicComment' AS actionType, pc.approvalStatus, pc.publicCommentDate AS createdAt
            FROM `reviews` pc
            WHERE pc.publicCommentAuthor != '' AND pc.accountManagerId IS NOT NULL";
        $sql['flags']  = "SELECT f.businessId, f.flagAccountManagerId, f.id, NULL, 'flag' AS actionType, f.flagApprovalStatus, f.flaggedAt AS createdAt
            FROM  `reviews` f
            WHERE f.status = ".Review::STATUS_FLAGGED." AND f.flagAccountManagerId IS NOT NULL";
        $sql['messages']  = "SELECT r.businessId, m.accountManagerId, m.reviewId, m.id, 'privateMessage' AS actionType, m.approvalStatus, m.messageDate AS createdAt
            FROM  `messages` m
            LEFT JOIN `reviews` r ON r.id = m.reviewId
            WHERE m.accountManagerId IS NOT NULL";

        $createSql = sprintf(
            'INSERT INTO `%s` (
                `businessId` ,
                `accountManagerId` ,
                `reviewId` ,
                `messageId` ,
                `type` ,
                `status`,
                `createdAt`
            )
            %s',
            $this->tableName,
            implode(' UNION ', array_values($sql))
        );

        $this->execute($createSql);
    }
}