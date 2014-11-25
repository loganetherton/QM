<?php
/**
 * Daemon combined log controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class DaemonCombinedLogController extends AdminController
{
    /**
     * Index action.
     */
    public function actionIndex()
    {
        $model = new DaemonLogEvent('search');
        $model->unsetAttributes();

        if(isset($_GET['DaemonLogEvent'])) {
            $model->setAttributes($_GET['DaemonLogEvent']);
        }

        $model->newestFirst();

        $this->render('index', array(
            'model'=>$model,
        ));
    }

    /**
     * Evaluate CSS class name for the row.
     * @param integer $row Row number (zero-based)
     * @param object $data Data model associated with the row
     * @return string CSS class name
     */
    protected function evaluateRowCssClass($row, $data)
    {
        $classes = array();

        $classes[] = $row % 2 ? 'even' : 'odd';
        $classes[] = 'daemon' . ($data->daemon->id % 8 + 1);

        return implode(' ', $classes);
    }
}