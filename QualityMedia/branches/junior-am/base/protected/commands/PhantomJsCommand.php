<?php
/**
 * PhantomJS runner.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PhantomJsCommand extends CConsoleCommand
{
    /**
     * Read analytics info
     *
     * @access public
     * @param array $args
     * @return void
     */
    public function actionReadAnalytics($args)
    {
        $yelpBusiness = YelpBusiness::model()->withCredentials()->findByPk($args[0]);

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $yelpBusiness->profile->yelpUsername,
            $yelpBusiness->profile->yelpPassword,
            $yelpBusiness->bizId,
        ));

        $result = CJSON::decode($phantom->execute('yelp_analytics_data.js'));

        if(isset($result['error'])) {
            echo $result['error'];

            return;
        }

        Yii::import('application.modules.api.models.YelpAnalyticsReceiver');

        $model = new YelpAnalyticsReceiver;
        $model->setAttributes($result);

        echo $model->save() ? 'Saved' : 'Not saved';
        echo "\n";
    }

    /**
     * Read reviews with messages.
     */
    public function actionReadReviewsWithMessages($args)
    {
        $yelpBusiness = YelpBusiness::model()->withCredentials()->findByPk($args[0]);

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $yelpBusiness->profile->yelpUsername,
            $yelpBusiness->profile->yelpPassword,
            $yelpBusiness->bizId,
        ));

        $result = CJSON::decode($phantom->execute('yelp_read_reviews_with_messages.js'));

        if(isset($result['error'])) {
            echo $result['error'];

            return;
        }

        Yii::import('application.modules.api.models.YelpTotalReviewReceiver');

        $model = new YelpTotalReviewReceiver;
        $model->setAttributes($result);

        echo $model->save() ? 'Saved' : 'Not saved';
        echo "\n";
    }

    /**
     * Post public comment.
     */
    public function actionPublicComment($args)
    {
        $model   = Review::model()->findByPk($args[0]);
        $profile = $model->yelpBusiness->profile;

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $profile->yelpUsername,
            $profile->yelpPassword,
            $model->yelpBusiness->bizId,
            $model->reviewId,
            'public',
            escapeshellarg($model->publicCommentContent),
        ));

        $result = $phantom->execute('Yelp_reply_to_review.js');
        echo $result;

        $result = CJSON::decode($result);
        if (!empty($result) && isset($result['is_filtered'])) {
            $model->filtered = !empty($result['is_filtered']) ? 1 : 0;
            $model->save();
        }
    }

    /**
     * Post private message.
     */
    public function actionPrivateMessage($args)
    {
        $model   = Message::model()->findByPk($args[0]);
        $profile = $model->review->yelpBusiness->profile;

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $profile->yelpUsername,
            $profile->yelpPassword,
            $model->review->yelpBusiness->bizId,
            $model->review->reviewId,
            'private',
            escapeshellarg($model->messageContent),
        ));

        $result = $phantom->execute('Yelp_reply_to_review.js');
        echo $result;

        $result = CJSON::decode($result);
        if(!empty($result) && isset($result['is_filtered'])) {
            $model->review->filtered = !empty($result['is_filtered']) ? 1 : 0;
            $model->review->save();
        }
    }

    /**
     * Flag Message
     */
    public function actionFlagReview($args)
    {
        $model = Review::model()->findByPk($args[0]);

        if($model === null) {
            echo "Review {$args[0]} does not exist";
            return;
        }

        $profile = $model->yelpBusiness->profile;

        $message = $args[1];
        $flagReasonCategory = $args[2];

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $profile->yelpUsername,
            $profile->yelpPassword,
            $model->yelpBusiness->bizId,
            $model->reviewId,
            'flag',
            escapeshellarg($message),
            $flagReasonCategory
        ));

        echo $phantom->execute('Yelp_reply_to_review.js');
    }

    public function actionReadInfo($args)
    {
        $yelpBusiness = YelpBusiness::model()->withCredentials()->findByPk($args[0]);

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $yelpBusiness->profile->yelpUsername,
            $yelpBusiness->profile->yelpPassword,
            $yelpBusiness->bizId,
        ));

        $result = CJSON::decode($phantom->execute('yelp_info_read.js'));

        if(isset($result['error'])) {
            echo $result['error'];

            return;
        }

        Yii::import('application.modules.api.models.YelpInfoReceiver');

        $model = new YelpInfoReceiver;
        $model->setAttributes($result);

        echo $model->save() ? 'Saved' : 'Not saved';
        echo "\n";
    }

    public function actionUpdateInfo($args)
    {
        $info = YelpInfo::model()->findByPk($args[1]);

        $params = $update_params = array(
            $info->yelpBusiness->profile->yelpUsername,
            $info->yelpBusiness->profile->yelpPassword,
            $info->yelpBusiness->bizId,
        );

        $data = self::processBizInfo($info->info);

        // Do not update any fields that are there as not-set
        if (!empty($data['basic_info']) && !empty($data['basic_info']['additional_info'])) {
            foreach ($data['basic_info']['additional_info'] as $k => $v) {
                if (!is_bool($v) && empty($v)) {
                    unset($data['basic_info']['additional_info'][$k]);
                }
            }
        }

        $update_params[] = implode(',', array_keys($data));
        $update_params[] = escapeshellarg(CJSON::Encode($data));

        $phantom = Yii::app()->getComponent('phantomjs');
        $phantom->setParams($update_params);
        $result = $phantom->execute('yelp_info_edit_merged.js');

        foreach ($info->originalNodes as $node) {
            if (!array_key_exists($node, $info->info)) {
                $this_params = $params;
                $this_params[] = $node;

                $phantom = Yii::app()->getComponent('phantomjs');
                $phantom->setParams($this_params);
                $result = $phantom->execute('yelp_info_remove.js');
            }
        }

        $info->saveAttributes(array('saved' => 1, 'originalNodes' => implode(',', array_keys($info->info))));

        echo $result;
    }

    /**
     * Basically unsets all lockedAttribute nodes
     *
     * @static
     * @access protected
     * @param array $info
     * @return array
     */
    protected static function processBizInfo(array $info)
    {
        foreach ($info as $k => $v) {
            if ($k === 'lockedAttributes') {
                unset($info[$k]);
            }
            elseif (is_array($v)) {
                $info[$k] = self::processBizInfo($info[$k]);
            }
        }

        return $info;
    }

    public function actionReadPhotos($args)
    {
        $yelpBusiness = YelpBusiness::model()->withCredentials()->findByPk($args[0]);

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $yelpBusiness->profile->yelpUsername,
            $yelpBusiness->profile->yelpPassword,
            $yelpBusiness->bizId,
        ));

        $result = CJSON::decode($phantom->execute('yelp_photos_read.js'));

        if(isset($result['error'])) {
            echo $result['error'];

            return;
        }

        Yii::import('application.modules.api.models.YelpPhotoReceiver');

        $model = new YelpPhotoReceiver;
        $model->setAttributes($result);

        echo $model->save() ? 'Saved' : 'Not saved';
        echo "\n";
    }

    public function actionSavePhotos($args)
    {
        $yelpBusiness = YelpBusiness::model()->findByPk($args[1]);
        $phantom = Yii::app()->getComponent('phantomjs');

        Yii::import('application.modules.api.models.YelpPhotoReceiver');

        // Success flag
        $success = true;

        // Uploaded not uploaded photos
        $photos = YelpPhoto::model()->yelpBusinessScope($yelpBusiness->id)->notUploaded()->findAll();

        foreach ($photos as $photo) {
            $phantom->setParams(array(
                $yelpBusiness->profile->yelpUsername,
                $yelpBusiness->profile->yelpPassword,
                $yelpBusiness->bizId,
                escapeshellarg($photo->photoUrl),
                escapeshellarg($photo->caption),
                ''
            ));

            $result = $phantom->execute('yelp_photos_upload.js');

            $result = CJSON::decode($result);

            if(isset($result['error'])) {
                $success = false;
                Yii::log('YelpPhotosUpload::error' . $result['error'], CLogger::LEVEL_ERROR);
            }
            else {
                $photo->delete();

                // Save this new photo
                $receiver = new YelpPhotoReceiver;
                $receiver->setAttributes(array(
                    'business_id'   => $yelpBusiness->bizId,
                    'photos'        => array($result['data']),
                ));

                $success = $success && $receiver->save();
            }
        }

        // Save existing photo's metadata
        $photos = YelpPhoto::model()->yelpBusinessScope($yelpBusiness->id)->uploaded()->notSaved()->findAll();
        foreach($photos as $photo) {
            $params = array(
                $yelpBusiness->profile->yelpUsername,
                $yelpBusiness->profile->yelpPassword,
                $yelpBusiness->bizId,
                $photo->photoId,
            );

            if($photo->canFlag() && $photo->isFlagged()) {
                $params = array_merge($params, array(
                    'flag',
                    'Inappropiate photo',
                    '',
                ));
            }
            elseif($photo->canDelete() && $photo->isDeleted()) {
                $params = array_merge($params, array(
                    'delete',
                ));
            }
            elseif($photo->canEditCaption()) {
                $params = array_merge($params, array(
                    'edit_caption',
                    escapeshellarg($photo->caption),
                    '',
                ));
            }
            else {
                continue;
            }

            $phantom->setParams($params);
            $result = CJSON::decode($phantom->execute('yelp_photos_edit.js'));

            if(isset($result['error'])) {
                $success = false;
                Yii::log('YelpPhotosEdit::error' . $result['error'], CLogger::LEVEL_ERROR);
            }
            else {
                if($photo->isDeleted()) {
                    $success = $success && $photo->delete(true);
                }
                else {
                    $success = $success && $photo->saveAttributes(array('saved' => 1));
                }
            }
        }

        echo $success == true ? CJSON::encode(array('status'=>'success')) : CJSON::encode(array('error'=>'savePhotos failed'));
    }

    /**
     * Fetch available yelp businesses assigned to the account.
     * @param integer $args[0] Profile model id
     */
    public function actionFetchYelpBusinesses($args)
    {
        $profile = Profile::model()->findByPk($args[0]);

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $profile->yelpUsername,
            $profile->yelpPassword,
        ));

        $result = CJSON::decode($phantom->execute('yelp_authenticate_login.js'));

        if(isset($result['error'])) {
            echo $result['error'];

            return;
        }

        Yii::import('application.modules.api.models.YelpBusinessesReceiver');

        $model = new YelpBusinessesReceiver;
        $model->setProfile($profile);
        $model->setAttributes($result);

        $response = $model->save() ? array('status'=>'success') : array('status'=>'error');
        echo CJSON::encode($response);
    }
}