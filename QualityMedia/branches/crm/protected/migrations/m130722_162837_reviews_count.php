<?php

class m130722_162837_reviews_count extends CDbMigration
{
    protected $table = 'profiles';

	public function up()
	{
        $this->addColumn($this->table, 'yelpReviewsCount', 'INT(11) UNSIGNED NOT NULL DEFAULT 0');

        // Should optimise this a bit
        $profiles = Profile::model()->findAll();
        foreach ($profiles as $profile) {
            $profile->saveAttributes(array('yelpReviewsCount' => YelpReview::model()->businessScope($profile->userId)->count()));
        }
	}

	public function down()
	{
		$this->dropColumn($this->table, 'yelpReviewsCount');
	}
}