<?php
/**
 * Static page controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PageController extends Controller
{
    public $layout = 'qualitymedia';

    /**
     * Returns a list of external action classes.
     */
    public function actions()
    {
        return array(
            'view'=>array(
                'class'=>'CViewAction',
                'viewParam'=>'id',
                'basePath'=>false,
            ),
        );
    }
}