<?php

namespace Carey\Twig\Extension;

use Symfony\Component\Asset\Packages;

/**
 * Class Asset
 * @package Carey\Twig\Extension
 *
 * A Twig extension that allows the use of Symfony Asset with Slim Framework Twig View
 */
class Asset extends \Twig_Extension
{
    /**
     * @var Packages
     */
    protected $packages;

    /**
     * @param Packages $packages
     */
    public function __construct(Packages $packages)
    {
        $this->packages = $packages;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'asset';
    }

    /**
     * Returns the public url/path of an asset.
     *
     * If the package used to generate the path is an instance of
     * UrlPackage, you will always get a URL and not a path.
     *
     * @param string $path        A public path
     * @param string $packageName The name of the asset package to use
     *
     * @return string The public path of the asset
     */
    public function getAssetUrl($path, $packageName = null)
    {
        return $this->packages->getUrl($path, $packageName);
    }

    /**
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('asset', array($this, 'getAssetUrl')),
        ];
    }
}
