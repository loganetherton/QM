<?php
/**
 * Controller for handling various notes action
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class NotesController extends AmController
{
    /**
     * Shows an index of all the notes
     *
     * @access public
     * @param bool $archived
     * @return void
     */
    public function actionIndex($archived = false, $type = 'client')
    {
        $note = new Note;

        if ($archived)
            $note->archived();
        else
            $note->notArchived();

        if ($type == Note::TYPE_REVIEW) {
            $note->reviewNote();
        }
        else {
            $note->clientNote();
        }

        if (isset($_GET['Note']))
            $note->companyName = $_GET['Note']['companyName'];

        $this->render('index', array(
            'data' => $note->search(),
            'archived' => $archived,
            'model' => $note,
            'type' => $type,
        ));
    }

    /**
     * Wrapper for archived notes
     *
     * @access public
     * @return void
     */
    public function actionArchived()
    {
        $this->actionIndex(true);
    }

    /**
     * Action for archiving a note
     *
     * @access public
     * @param int $id
     * @param string $redirect
     * @return void
     */
    public function actionArchive($id, $redirect = 'clients')
    {
        Note::model()->findByPk($id)->markArchived();

        $this->redirectTo($redirect);
    }

    /**
     * Action for archiving a note
     *
     * @access public
     * @param int $id
     * @param string $redirect
     * @return void
     */
    public function actionUnarchive($id, $redirect = 'clients')
    {
        try {
            Note::model()->findByPk($id)->markArchived(false);
        }
        catch (CException $e) {
            Yii::app()->getUser()->setFlash('error', $e->getMessage());
        }

        $this->redirectTo($redirect);
    }

    /**
     * Action for marking a note as important
     *
     * @access public
     * @param int $id
     * @param string $redirect
     * @return void
     */
    public function actionImportant($id, $redirect = 'clients')
    {
        Note::model()->findByPk($id)->markImportant();

        $this->redirectTo($redirect);
    }

    /**
     * Action for marking a note as not important
     *
     * @access public
     * @param int $id
     * @param string $redirect
     * @return void
     */
    public function actionNotImportant($id, $redirect = 'clients')
    {
        Note::model()->findByPk($id)->markImportant(false);

        $this->redirectTo($redirect);
    }

    /**
     * Updates a single note's text
     *
     * @access public
     * @param int $id
     * @param string $redirect
     * @return void
     */
    public function actionUpdate($id, $redirect = 'clients', $ajax = false)
    {
        try {
            $note = Note::model()->findByPk($id);
            $note->subject = $_POST['Note']['subject'];
            $note->note = $_POST['Note']['note'];
            $note->dueAt = $_POST['Note']['dueAt'];

            if (isset($_POST['Note']['important'])) {
                $note->important = (int) (bool) $_POST['Note']['important'];
            }

            $note->save();
        }
        catch (CException $e) {
            Yii::app()->getUser()->setFlash('error', $e->getMessage());

            if ($ajax) {
                Yii::app()->end();
            }
        }

        if (!$ajax) {
            $this->redirectTo($redirect);
        }
        else {
            echo CJSON::Encode(array(
                'subject' => $_POST['Note']['subject'],
                'note' => $_POST['Note']['note'],
                'dueAt' => Yii::app()->getComponent('format')->formatDate(strtotime($_POST['Note']['dueAt'])),
            ));
            Yii::app()->end();
        }
    }

    /**
     * Action for creating a new note
     *
     * @access public
     * @param int $id The Client for which this note's being created
     * @param string $type
     * @param string $redirect
     * @return void
     */
    public function actionCreate($id, $type = 'client', $redirect = 'clients')
    {
        try {
            $note = new Note;
            $note->accountManagerId = Yii::app()->getUser()->id;

            if ($type == Note::TYPE_CLIENT) {
                $note->userId = !empty($id) ? $id : null;
                $note->type = Note::TYPE_CLIENT;
            }
            else {
                $note->reviewId = !empty($id) ? $id : null;
                $note->type = Note::TYPE_REVIEW;

                if(!empty($id)) {
                    $note->userId = Review::model()->findByPk($id)->businessId;
                }
            }

            $note->subject = $_POST['Note']['subject'];
            $note->note = $_POST['Note']['note'];
            $note->dueAt = $_POST['Note']['dueAt'];
            $note->important = (int) (bool) $_POST['Note']['important'];

            if ($note->save())
                Yii::app()->getUser()->setFlash('success', 'Note successfully created');
            else
                Yii::app()->getUser()->setFlash('error', 'Note creation failed');
        }
        catch (CException $e) {
            Yii::app()->getUser()->setFlash('error', $e->getMessage());
        }

        $this->redirectTo($redirect);
    }

    /**
     * Redirects after a POST action
     *
     * @access public
     * @param string $to
     * @return void
     */
    public function redirectTo($to)
    {
        parent::redirect(array($to == 'notes' ? 'notes/index' : ($to == 'clients' ? 'clients/index' : urldecode($to))));
    }
}