<?php
/**
 * Removes duplicates in billing_info and adds unqiue constraint to userId
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */
class m131212_203513_alter_billing_info_unique_userId extends CDbMigration
{
    public function up()
    {
        $this->execute('
            DELETE FROM billing_info
            WHERE id IN (
                SELECT * FROM (
                    SELECT MAX(id) FROM billing_info GROUP BY userId HAVING COUNT(id) > 1
                ) AS t
            )
        ');

        $this->createIndex('userId_unique', 'billing_info', 'userId', true);
    }

    public function down()
    {
        $this->dropIndex('userId_unique', 'billing_info');
    }
}