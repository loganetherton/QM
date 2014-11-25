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
        $model = Photo::model()->yelpBusinessScope($id)->notDeleted();

        $this->render('index', array(
            'model'=>$model,
            'data'=>$model->search(),
            'id'=>$id,
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
        $model = $this->loadModel($id, 'Photo');

        if($model->markAsFlagged()) {
            Yii::app()->getUser()->setFlash('success', 'Photo Flagged Successfully');
        }
        else {
            Yii::app()->getUser()->setFlash('error', 'Photo has not been flagged');
        }

        $this->redirect(array('photos/index', 'id' => $model->yelpBusinessId));
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
        $model = $this->loadModel($id, 'Photo');

        if(isset($_POST['Photo'])) {
            $model->setAttributes($_POST['Photo']);

            if($model->updateCaption()) {
                Yii::app()->getUser()->setFlash('success', "Photo's Caption Updated Successfully");
            }
            else {
                Yii::app()->getUser()->setFlash('error', "Photo's caption hasn't been updated");
            }
        }

        $this->redirect(array('photos/index', 'id' => $model->yelpBusinessId));
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
        $model = $this->loadModel($id, 'Photo');

        if($model->delete()) {
            Yii::app()->getUser()->setFlash('success', 'Photo Deleted Successfully');
        }
        else {
            Yii::app()->getUser()->setFlash('error', 'Photo has not been deleted');
        }

        $this->redirect(array('photos/index', 'id' => $model->yelpBusinessId));
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

        if($mime[0] != 'image') {
            Yii::app()->getUser()->setFlash('error', 'Uploaded file is not an image');
        }
        else {
            $yelpBusiness = $this->loadModel($id, 'YelpBusiness');

            $tmpName = substr(sha1(rand()), 0, 15) . '.' . $image->getExtensionName();
            $dest = Yii::app()->basePath . '/uploads/' . $tmpName;
            $image->saveAs($dest);

            $photo->photoUrl        = $dest;
            $photo->businessId      = $yelpBusiness->userId;
            $photo->yelpBusinessId  = $yelpBusiness->id;
            $photo->photoId         = 'tmp';
            $photo->fromOwner       = 1;
            $photo->uploaded        = 0;
            $photo->actions         = array();

            if(!$photo->save()) {
                @unlink($tmpName);
                Yii::app()->getUser()->setFlash('error', 'Photo couldn\'t be uploaded');
            }
            else {
                Yii::app()->getUser()->setFlash('success', 'Photo uploaded successfully, it\'ll be shown as soon as photos are synced from Yelp');
            }
        }

        $this->redirect(array('photos/index', 'id' => $id));
    }
}