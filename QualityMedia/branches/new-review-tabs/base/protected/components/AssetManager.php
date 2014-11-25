<?php
/**
 * Custom changes in CAssetManager.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class AssetManager extends CAssetManager
{
    /**
     * Disable asset manager from publishing assets.
     * @param string $path the asset (file or directory) to be published
     * @param boolean $hashByName whether the published directory should be named as the hashed basename.
     * @param integer $level level of recursive copying when the asset is a directory.
     * @param boolean $forceCopy whether we should copy the asset file or directory even if it is already
     * published before.
     * @return string an absolute URL to the published asset
     */
    public function publish($path, $hashByName=false, $level=-1, $forceCopy=null)
    {
        return null;
    }
}