<?php
/**
 * Create "am_activities" (Account Manager Activities) table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m131216_080842_create_am_activities_table extends CDbMigration
{
    public $tableName = 'am_activities';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'                => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'businessId'        => 'int(11) UNSIGNED NOT NULL',
            'accountManagerId'  => 'int(11) UNSIGNED NOT NULL',
            'type'              => 'varchar(40) NULL COLLATE utf8_general_ci',
            'status'            => 'smallint(1) NOT NULL',
            'comment'           => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'createdAt'         => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'         => 'timestamp DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');

        $this->createPastHistory();
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }

    public function createPastHistory()
    {
        //Sql Union
        $sql['publicComments']  = "SELECT pc.businessId, pc.accountManagerId, 'publicComment' AS actionType, pc.approvalStatus, pc.publicCommentDate AS createdAt
            FROM `reviews` pc
            WHERE pc.publicCommentAuthor != '' AND pc.accountManagerId IS NOT NULL";
        $sql['flags']  = "SELECT f.businessId, f.flagAccountManagerId, 'flag' AS actionType, f.flagApprovalStatus, f.flaggedAt AS createdAt
            FROM  `reviews` f
            WHERE f.status = ".Review::STATUS_FLAGGED." AND f.flagAccountManagerId IS NOT NULL";
        $sql['messages']  = "SELECT r.businessId, m.accountManagerId, 'privateMessage' AS actionType, m.approvalStatus, m.messageDate AS createdAt
            FROM  `messages` m
            LEFT JOIN `reviews` r ON r.id = m.reviewId
            WHERE m.accountManagerId IS NOT NULL";

        $createSql = sprintf(
            'INSERT INTO `%s` (
                `businessId` ,
                `accountManagerId` ,
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