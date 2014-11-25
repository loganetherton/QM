<?php
/**
 * Add "yelp_businesses" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130913_063424_add_yelp_businesses_table extends CDbMigration
{
    public $tableName = 'yelp_businesses';

    public $profileFK = 'yelp_businesses_profiles_fk';
    public $userFK = 'yelp_businesses_users_fk';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'        => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'profileId' => 'INT(11) UNSIGNED NOT NULL',
            'userId'    => 'INT(11) UNSIGNED NOT NULL',
            'yelpId'    => 'varchar(100) NOT NULL COLLATE utf8_general_ci',
            'bizId'     => 'VARCHAR(100) NOT NULL COLLATE utf8_general_ci',
            'label'     => 'VARCHAR(100) NOT NULL COLLATE utf8_general_ci',
            'status'    => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT 1',
            'createdAt' => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt' => 'timestamp DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');

        $this->addForeignKey($this->profileFK, $this->tableName, 'profileId', 'profiles', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey($this->userFK, $this->tableName, 'userId', 'users', 'id', 'CASCADE', 'CASCADE');

        $sql = "INSERT INTO {$this->tableName}
                SELECT NULL, p.id, p.userId, p.yelpId, '', bi.companyName, 1, p.createdAt, p.updatedAt
                FROM profiles p
                LEFT JOIN users u ON p.userId = u.id
                LEFT JOIN billing_info bi ON u.id = bi.userId";

        $this->execute($sql);
    }

    public function down()
    {
        $this->dropForeignKey($this->profileFK, $this->tableName);
        $this->dropForeignKey($this->userFK, $this->tableName);

        $this->dropTable($this->tableName);
    }
}