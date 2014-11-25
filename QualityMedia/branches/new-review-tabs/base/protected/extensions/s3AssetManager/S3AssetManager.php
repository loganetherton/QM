<?php
/**
 * Amazon S3 asset manager.
 *
 * @author Jakub Pospiech <jakub.pospiech@gmail.com>
 */
class S3AssetManager extends CAssetManager
{
    /**
     * @var string $bucket S3 bucket name.
     */
    public $bucket;

    /**
     * @var string $path Path to assets.
     */
    public $path;

    /**
     * @var string $host Amazon S3 host.
     */
    public $host;

    /**
     * @var string $s3component
     */
    public $s3Component = 's3';

    /**
     * @var string $cacheComponent Cache component name.
     */
    public $cacheComponent = 'cache';

    /**
     * @var string $_basePath Base web accessible path for storing private files
     */
    private $_basePath;

    /**
     * @var string $_baseUrl Base URL for accessing the publishing directory.
     */
    private $_baseUrl;

    /**
     * @var array $_published published assets.
     */
    private $_published = array();

    /**
     * @return string The root directory storing the published asset files.
     */
    public function getBasePath()
    {
        if($this->_basePath === null) {
            $this->_basePath = $this->path;
        }

        return $this->_basePath;
    }

    /**
     * @return string the base url that the published asset files can be accessed.
     * Note, the ending slashes are stripped off.
     */
    public function getBaseUrl()
    {
        if($this->_baseUrl === null) {
            $this->_baseUrl = 'http://'.$this->host.'/'.$this->path;
        }

        return $this->_baseUrl;
    }

    /**
     * @return object Cache component
     * @throws CException If cache component does not exist
     */
    protected function getCache()
    {
        if(!Yii::app()->hasComponent($this->cacheComponent)) {
            throw new CException('You need to configure a cache storage or set the variable cacheComponent');
        }

        return Yii::app()->getComponent($this->cacheComponent);
    }

    /**
     * @return string Cache key calculated based on server name and path
     */
    protected function getCacheKey($path)
    {
        return $this->hash(Yii::app()->getRequest()->getServerName()).'.'.$path;
    }

    /**
     * @return object S3 component
     * @throws CException If component does not exist
     */
    protected function getS3()
    {
        if(!Yii::app()->hasComponent($this->s3Component)) {
            throw new CException('You need to configure the S3 component or set the variable s3Component properly');
        }

        return Yii::app()->getComponent($this->s3Component);
    }

    /**
     * Publishes a file or a directory to Amazon S3.
     * @param string $path the asset (file or directory) to be published
     * @param boolean $hashByName whether the published directory should be named as the hashed basename.
     * If false, the name will be the hash taken from dirname of the path being published and path mtime.
     * Defaults to false. Set true if the path being published is shared among
     * different extensions.
     * @param integer $level level of recursive copying when the asset is a directory.
     * Level -1 means publishing all subdirectories and files;
     * Level 0 means publishing only the files DIRECTLY under the directory;
     * level N means copying those directories that are within N levels.
     * @param boolean $forceCopy whether we should copy the asset file or directory even if it is already published before.
     * This parameter is set true mainly during development stage when the original
     * assets are being constantly changed. The consequence is that the performance
     * is degraded, which is not a concern during development, however.
     * This parameter has been available since version 1.1.2.
     * @return string an absolute URL to the published asset
     * @throws CException if the asset to be published does not exist.
     */
    public function publish($path, $hashByName = false, $level = -1, $forceCopy = false)
    {
        if(isset($this->_published[$path])) {
            return $this->_published[$path];
        }
        elseif(($src = realpath($path)) !== false) {
            if(is_file($src)) {
                $dir = $this->hash($hashByName ? basename($src) : dirname($src).filemtime($src));
                $fileName = basename($src);
                $dstDir = $this->getBasePath().'/'.$dir;
                $dstFile = $dstDir.'/'.$fileName;

                if($this->getCache()->get($this->getCacheKey($path)) === false) {
                    $contentType = CFileHelper::getMimeTypeByExtension($dstFile);

                    if($this->getS3()->putObjectFile($src, $this->bucket, $dstFile, $acl = S3::ACL_PUBLIC_READ, array(), $contentType)) {
                        $this->getCache()->set($this->getCacheKey($path), true, 0, new CFileCacheDependency($src));
                    }
                    else {
                        throw new CException('Could not send asset do S3');
                    }
                }

                return $this->_published[$path] = $this->getBaseUrl()."/$dir/$fileName";
            }
            elseif(is_dir($src)) {
                $dir = $this->hash($hashByName ? basename($src) : $src.filemtime($src));
                $dstDir = $this->getBasePath().DIRECTORY_SEPARATOR.$dir;

                if($this->getCache()->get($this->getCacheKey($path)) === false) {
                    $files = CFileHelper::findFiles($src, array(
                        'exclude' => $this->excludeFiles,
                        'level' => $level,
                    ));

                    foreach($files as $file) {
                        $dstFile = $this->getBasePath().'/'.$dir.'/'.str_replace($src.DIRECTORY_SEPARATOR, '', $file);
                        $contentType = CFileHelper::getMimeTypeByExtension($dstFile);

                        if(!$this->getS3()->putObjectFile($file, $this->bucket, $dstFile, $acl = S3::ACL_PUBLIC_READ, array(), $contentType)) {
                            throw new CException('Could not send assets do S3');
                        }
                    }

                    $this->getCache()->set($this->getCacheKey($path), true, 0, new CDirectoryCacheDependency($src));
                }

                return $this->_published[$path] = $this->getBaseUrl().'/'.$dir;
            }
        }

        throw new CException(Yii::t('yii', 'The asset "{asset}" to be published does not exist.', array('{asset}' => $path)));
    }
}