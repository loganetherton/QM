<?php
/**
 * Amazon S3 component.
 *
 * @author Jakub Pospiech <jakub.pospiech@gmail.com>
 */
Yii::import('ext.s3AssetManager.lib.S3', true);

class S3Component extends CApplicationComponent
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
     * @var string $lastError Amazon API call last error.
     */
    public $lastError = '';

    /**
     * @var object $s3 Amazon S3 api object.
     */
    protected $s3;

    /**
     * Get Amazon S3 API instance.
     * @throws CException If access key or secret key is missing
     */
    protected function getInstance()
    {
        if($this->s3 === null) {
            if($this->accessKey === null || $this->secretKey === null) {
                throw new CException('Amazon S3 keys are missing');
            }

            $this->s3 = new S3($this->accessKey, $this->secretKey);
        }

        return $this->s3;
    }

    /**
     * Pass all method calls to Amazon S3 sdk.
     * @throws CException If method does not exist
     */
    public function __call($name, $arguments)
    {
        //dd($this->getInstance());
        if(method_exists($this->getInstance(), $name)) {
            return call_user_func_array(array($this->getInstance(), $name), $arguments);
        }

        throw new CException("Method '{$name}' does not exist");
    }
}