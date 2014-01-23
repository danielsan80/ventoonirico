<?php

namespace Dan\CommonBundle\Assetic\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\LessphpFilter;

class LessphpNotCachedFilter extends LessphpFilter
{
    public function filterLoad(AssetInterface $asset)
    {
        $root = $asset->getSourceRoot();
        $path = $asset->getSourcePath();

        $filename = realpath($root . '/' . $path);

        if (file_exists($filename)) {
            touch($filename);
        }

        parent::filterLoad($asset);
    }
}