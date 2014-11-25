<?php
/**
 * Exec daemon command.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
Yii::import('application.daemons.*');

class ExecDaemonCommand extends CConsoleCommand
{
    /**
     * Index action.
     */
    public function actionIndex($args)
    {
        if(!posix_isatty(STDOUT)) {
            $sid = posix_setsid();

            if($sid <= 0) {
                throw new CException('Failed to create new process session!');
            }
        }

        $daemon = array_shift($args);

        try {
            $daemon = new $daemon($args);
            $daemon->execute();
        }
        catch(Exception $e) {
            fwrite(STDERR, $e->getMessage()."\n");
            exit(255);
        }
    }
}