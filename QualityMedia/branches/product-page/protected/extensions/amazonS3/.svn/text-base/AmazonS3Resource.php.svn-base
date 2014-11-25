<?php
/**
 * Amazon S3 Resource component.
 * This component is reponsible for preparing URLs for resources stored in Amazon S3.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class AmazonS3Resource extends CComponent
{
    /**
     * @var string $accessKey Amazon S3 access key.
     */
    public $accessKey;

    /**
     * @var string $secretKey Amazon S3 secret key.
     */
    public $secretKey;

    /**
     * @var string $bucket Bucket name.
     */
    public $bucket;

    /**
     * Init component.
     */
    public function init()
    {
    }

    /**
     * Returns resource stored in Amazon S3.
     * @param string $resource
     * @return string URL to resource stored in Amazon S3
     */
    public function getResource($resource)
    {
        if(empty($this->bucket)) {
            throw new CException('Amazon S3 bucket cannot be empty');
        }

        return sprintf('http://%s.s3-website-us-west-2.amazonaws.com/%s', $this->bucket, ltrim($resource,'/'));
    }
}