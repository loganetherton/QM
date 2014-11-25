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
        $user = User::model()->findByPk($args[0]);

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $user->profile->yelpUsername,
            $user->profile->yelpPassword,
            $user->profile->yelpId,
        ));

        $result = $phantom->execute('yelp_analytics_data.js');

        Yii::import('application.modules.api.models.YelpAnalyticsReceiver');

        $model = new YelpAnalyticsReceiver;
        $model->setAttributes(CJSON::Decode($result));

        echo $model->save() ? 'Saved' : 'Not saved';
        echo "\n";
    }

    /**
     * Read reviews with messages.
     */
    public function actionReadReviewsWithMessages($args)
    {
        $user = User::model()->findByPk($args[0]);

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $user->profile->yelpUsername,
            $user->profile->yelpPassword,
            $user->profile->yelpId,
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

    public function actionReadReviews($args)
    {
        $user = User::model()->findByPk($args[0]);

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $user->profile->yelpUsername,
            $user->profile->yelpPassword,
            $user->profile->yelpId,
        ));

        $result = $phantom->execute('yelp_read_reviews.js');

        Yii::import('application.modules.api.models.YelpReviewReceiver');

        $model = new YelpReviewReceiver;

        $model->setAttributes(CJSON::decode($result));

        echo $model->save() ? 'Saved' : 'Not saved';
        echo "\n";
    }

    /**
     * Read private messages
     */
    public function actionReadMessages($args)
    {
        $user = User::model()->findByPk($args[0]);

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $user->profile->yelpUsername,
            $user->profile->yelpPassword,
            $user->profile->yelpId,
            $args[1],
        ));

        $result = $phantom->execute('yelp_read_messages.js');

        echo "Results are imported\n";

        Yii::import('application.modules.api.models.YelpMessageReceiver');

        $model = new YelpMessageReceiver;
        $model->setAttributes(CJSON::decode($result));

        echo $model->save() ? 'Saved' : 'Not saved';
        echo "\n";
    }

    /**
     * Post private message.
     */
    public function actionPostPrivateMessage()
    {
        $model = YelpMessage::model()->find('source = :source AND sent = 0', array(':source'=>YelpMessage::SOURCE_DASHBOARD));

        if($model === null) {
            echo "There are no messages to post\n";
            return;
        }

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $model->review->user->profile->yelpUsername,
            $model->review->user->profile->yelpPassword,
            $model->review->reviewId,
            'private',
            sprintf('"%s"', $model->message),
        ));

        $result = $phantom->execute('Yelp_reply_to_review.js');
        print_r($result);

        $model->sent = 1;

        echo $model->save() ? 'Sent' : 'Not sent';
        echo "\n";
    }

    /**
     * Post public message.
     */
    public function actionPostPublicMessage()
    {
        $model = YelpReview::model()->find('sent = 0');

        if($model === null) {
            echo "There are no messages to post\n";
            return;
        }

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $model->user->profile->yelpUsername,
            $model->user->profile->yelpPassword,
            $model->reviewId,
            'public',
            sprintf('"%s"', $model->publicComment),
        ));

        $result = $phantom->execute('Yelp_reply_to_review.js');
        print_r($result);

        $model->sent = 1;

        echo $model->save() ? 'Sent' : 'Not sent';
        echo "\n";
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

        $message = $args[1];

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $model->user->profile->yelpUsername,
            $model->user->profile->yelpPassword,
            $model->reviewId,
            'flag',
            sprintf('"%s"', $message),
        ));

        $result = $phantom->execute('Yelp_reply_to_review.js');

        echo $result;
    }

    public function actionReadInfo($args)
    {
        $user = User::model()->findByPk($args[0]);

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $user->profile->yelpUsername,
            $user->profile->yelpPassword,
            $user->profile->yelpId,
        ));

        $result = $phantom->execute('yelp_info_read.js');

        Yii::import('application.modules.api.models.YelpInfoReceiver');

        $model = new YelpInfoReceiver;
        $model->setAttributes(CJSON::Decode($result));

        echo $model->save() ? 'Saved' : 'Not saved';
        echo "\n";
    }

    public function actionUpdateInfo($args)
    {
        $user = User::model()->findByPk($args[0]);
        $info = YelpInfo::model()->findByAttributes(array('businessId' => $args[0]));

        $params = $update_params = array(
            $user->profile->yelpUsername,
            $user->profile->yelpPassword,
        );

        $data = self::processBizInfo($info->info);

        $update_params[] = implode(',', array_keys($data));
        $update_params[] = escapeshellarg(CJSON::Encode($data));

        $phantom = Yii::app()->getComponent('phantomjs');
        $phantom->setParams($update_params);
        $result = $phantom->execute('yelp_info_edit_merged.js');

        print_r($result);

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

        echo "\nDone";
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
        $user = User::model()->findByPk($args[0]);

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $user->profile->yelpUsername,
            $user->profile->yelpPassword,
            $user->profile->yelpId,
        ));

        $result = $phantom->execute('yelp_photos_read.js');

        Yii::import('application.modules.api.models.YelpPhotoReceiver');

        $model = new YelpPhotoReceiver;
        $model->setAttributes(CJSON::decode($result));

        echo $model->save() ? 'Saved' : 'Not saved';
        echo "\n";
    }

    public function actionSavePhotos($args)
    {
        $user = User::model()->findByPk($args[0]);

        Yii::import('application.modules.api.models.YelpPhotoReceiver');

        // Uploaded not uploaded photos
        $photos = YelpPhoto::model()->business($user->id)->notUploaded()->findAll();
        foreach ($photos as $photo)
        {
            $phantom = Yii::app()->getComponent('phantomjs');

            $phantom->setParams(array(
                $user->profile->yelpUsername,
                $user->profile->yelpPassword,
                escapeshellarg($photo->photoUrl),
                escapeshellarg($photo->caption),
                ''
            ));

            $result = CJSON::Decode(CJSON::Decode($phantom->execute('yelp_photos_upload.js')));

            if (empty($result['error'])) {
                $photo->delete();

                // Save this new photo
                $receiver = new YelpPhotoReceiver;
                $receiver->setAttributes(array(
                    'business_id' => $user->profile->yelpId,
                    'photos' => array($result['data']),
                ));

                $receiver->save();
            }
        }

        // Save existing photo's metadata
        $photos = YelpPhoto::model()->business($user->id)->uploaded()->notSaved()->findAll();
        foreach ($photos as $photo)
        {
            $phantom = Yii::app()->getComponent('phantomjs');

            $params = array(
                $user->profile->yelpUsername,
                $user->profile->yelpPassword,
                $user->profile->yelpId,
                $photo->photoId,
            );

            if ($photo->canFlag() && $photo->isFlagged())
                $params = array_merge($params, array(
                    'flag',
                    'Inappropiate photo',
                    '',
                ));
            elseif ($photo->canDelete() && $photo->isDeleted())
                $params = array_merge($params, array(
                    'delete',
                ));
            elseif ($photo->canEditCaption())
                $params = array_merge($params, array(
                    'edit_caption',
                    escapeshellarg($photo->caption),
                    '',
                ));
            else
                continue;

            $phantom->setParams($params);
            $result = $phantom->execute('yelp_photos_edit.js');

            print_r($result);

            if (empty($result['error']))
                if ($photo->isDeleted())
                    $photo->delete(true);
                else
                    $photo->saveAttributes(array('saved' => 1));
        }
    }
}