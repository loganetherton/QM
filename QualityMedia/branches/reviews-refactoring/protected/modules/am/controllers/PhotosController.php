<?php
/**
 * Controller for handling various photos action
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class PhotosController extends AmController
{
    /**
     * Action for showing index of photos for a specific business
     *
     * @access public
     * @param int $id
     * @return void
     */
    public function actionIndex($id)
    {
        $photo = new Photo;

        if (!$photo) {
            $this->redirect(array('clients/index'));
        }

        $photo->business($id)->notDeleted();

        $this->render('index', array(
            'model' => $photo,
            'data' => $photo->search(),
            'id' => $id,
        ));
    }

    /**
     * Flags a photo as inappropiate
     *
     * @access public
     * @param int $id
     * @return void
     */
    public function actionFlag($id)
    {
        $photo = Photo::model()->findByPk($id);
        $photo->flagged = Photo::STATUS_FLAGGED;
        $photo->save();

        Yii::app()->getUser()->setFlash('success', 'Photo Flagged Successfully');

        $this->redirect(array('photos/index', 'id' => $photo->businessId));
    }

    /**
     * Updates the caption for this photo
     *
     * @access public
     * @param int $id
     * @return void
     */
    public function actionUpdate($id)
    {
        $photo = Photo::model()->findByPk($id);
        $photo->caption = $_POST['Photo']['caption'];
        $photo->save();

        Yii::app()->getUser()->setFlash('success', 'Photo\'s Caption Updated Successfully');

        $this->redirect(array('photos/index', 'id' => $photo->businessId));
    }

    /**
     * Deletes a photo
     *
     * @access public
     * @param int $id
     * @return void
     */
    public function actionDelete($id)
    {
        $photo = Photo::model()->findByPk($id);
        $photo->delete();

        Yii::app()->getUser()->setFlash('success', 'Photo Deleted Successfully');

        $this->redirect(array('photos/index', 'id' => $photo->businessId));
    }

    /**
     * Upload a new photo
     *
     * @access public
     * @param int $id
     * @return void
     * @throws CException
     */
    public function actionUpload($id)
    {
        $photo = new Photo;
        $photo->caption = $_POST['Photo']['caption'];
        $image = CUploadedFile::getInstanceByName('file');
        $mime = explode('/', CFileHelper::getMimeType($image->getTempName()));

        if ($mime[0] != 'image')
            Yii::app()->getUser()->setFlash('error', 'Uploaded file is not an image');
        else
        {
            $tmpName = substr(sha1(rand()), 0, 15) . '.' . $image->getExtensionName();
            $dest = Yii::app()->basePath . '/uploads/' . $tmpName;
            $image->saveAs($dest);

            $photo->photoUrl = $dest;
            $photo->businessId = $id;
            $photo->photoId = 'tmp';
            $photo->fromOwner = 1;
            $photo->uploaded = 0;
            $photo->actions = array();

            if (!$photo->save())
            {
                @unlink($tmpName);
                Yii::app()->getUser()->setFlash('error', 'Photo couldn\'t be uploaded');
            }
            else
                Yii::app()->getUser()->setFlash('success', 'Photo uploaded successfully, it\'ll be shown as soon as photos are synced from Yelp');
        }

        $this->redirect(array('photos/index', 'id' => $id));
    }
}