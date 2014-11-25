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
    public function actionIndex($archived = false)
    {
        $note = new Note;

        if ($archived)
            $note->archived();
        else
            $note->notArchived();

        if (isset($_GET['Note']))
            $note->companyName = $_GET['Note']['companyName'];

        $this->render('index', array(
            'data' => $note->search(),
            'archived' => $archived,
            'model' => $note,
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
        $this->redirect(array($redirect == 'notes' ? 'notes/index' : 'clients/index'));
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
        Note::model()->findByPk($id)->markArchived(false);
        $this->redirect(array($redirect == 'notes' ? 'notes/index' : 'clients/index'));
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
        $this->redirect(array($redirect == 'notes' ? 'notes/index' : 'clients/index'));
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
        $this->redirect(array($redirect == 'notes' ? 'notes/index' : 'clients/index'));
    }

    /**
     * Updates a single note's text
     *
     * @access public
     * @param int $id
     * @param string $redirect
     * @return void
     */
    public function actionUpdate($id, $redirect = 'clients')
    {
        $note = Note::model()->findByPk($id);
        $note->note = $_POST['Note']['note'];
        $note->dueAt = $_POST['Note']['dueAt'];
        $note->important = (int) (bool) $_POST['Note']['important'];
        $note->save();

        $this->redirect(array_merge(array($redirect == 'notes' ? 'notes/index' : 'clients/index'), CJSON::Decode($_POST['params'])));
    }

    /**
     * Action for creating a new note
     *
     * @access public
     * @param int $id The Client for which this note's being created
     * @param string $redirect
     * @return void
     */
    public function actionCreate($id, $redirect = 'clients')
    {
        $note = new Note;
        $note->accountManagerId = Yii::app()->getUser()->id;
        $note->userId = !empty($id) ? $id : null;
        $note->note = $_POST['Note']['note'];
        $note->dueAt = $_POST['Note']['dueAt'];
        $note->important = (int) (bool) $_POST['Note']['important'];

        if ($note->save())
            Yii::app()->getUser()->setFlash('success', 'Note successfully created');
        else
            Yii::app()->getUser()->setFlash('error', 'Note creation failed');

        $this->redirect(array_merge(array($redirect == 'notes' ? 'notes/index' : 'clients/index'), CJSON::Decode($_POST['params'])));
    }
}