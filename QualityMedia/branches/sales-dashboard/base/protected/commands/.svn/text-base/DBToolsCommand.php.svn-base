<?php
/**
 * DB Tools command tool.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class DbToolsCommand extends CConsoleCommand
{
    /**
     * Fixes invalid encoding in reviews content description]
     * @return void
     */
    public function actionFixReviewsEncoding()
    {
        $reviewsSql = 'UPDATE reviews set reviewContent = convert(binary convert(reviewContent using latin1) using utf8)';
        Yii::app()->db
            ->createCommand($reviewsSql)
            ->execute();

        echo "updated\n";
    }

    /**
     * Fixes message content by removing CR char
     * @return void
     */
    public function actionFixMessagesLineBreaks()
    {
        $reviewsSql = 'UPDATE messages set messageCOntent =  REPLACE(messageContent, "\r\n", "\n"), messageHash = SHA1(CONCAT(userId, REPLACE(messageContent, "\r\n", "\n"))) WHERE messageContent REGEXP "\r\n"';
        $rowsAffected = Yii::app()->db
            ->createCommand($reviewsSql)
            ->execute();

        echo sprintf("%s rows fixed\n", $rowsAffected);
    }

    /**
     * Returns the reviews marked as replied, but without any message
     * @return [type] [description]
     */
    public function actionRepliedWithoutMessages()
    {
        //@TODO change custom command to Review model query
        $sql = "SELECT r.*, (SELECT COUNT(*) FROM messages WHERE reviewId = r.id) as messagesCount
                FROM reviews r
                WHERE status = 4
                AND publicCommentContent = ''
                HAVING messagesCount = 0
                ORDER BY reviewDate DESC";

        $results = Yii::app()->db->createCommand($sql)->query();

        Yii::import('application.modules.am.models.AmReview');

        foreach($results as $row) {
            $review = AmReview::model()->findByPk($row['id']);

            echo implode(", ", array($review->user->billingInfo->companyName, $review->userName, date('Y-m-d', strtotime($review->createdAt))))."\n";
        }
    }


    /**
     *
     * @return fixes invalid encoding in reviews content
     */
    public function actionFixMessagesEncoding()
    {

        $messages = Message::model()->findAll();

        foreach($messages as $message) {

            $message->messageContent = iconv('UTF-8', 'utf-8//ignore', trim($message->messageContent));
            $message->messageHash = sha1($message->userId.$message->messageContent);
            $message->save();
        }

        echo "updated\n";
    }

    public function actionClearMessages()
    {
        $sql = "TRUNCATE TABLE messages";
        Yii::app()->db->createCommand($sql)->execute();

        echo "updated\n";
    }

    /**
     * Executes custom mysql statement
     */
    public function actionCustomSql($args)
    {
        $sql = $args[0];

        $command = Yii::app()->db->createCommand($sql);

        if(substr(strtolower($sql), 0, 6) == 'select') {
            $results = $command->query();

            foreach($results as $row) {
                var_dump($row);
            }

            echo "\n";
        } else {
            $rowsAffected = $command->execute();

            echo sprintf("Sql statement has been executed\n%s rows affected\n", $rowsAffected);
        }


    }
}