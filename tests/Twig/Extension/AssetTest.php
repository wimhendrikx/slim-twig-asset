<?php
/**
 * Slim Twig Asset Extension
 *
 * @link      https://github.com/careysizer/slim-twig-asset
 * @copyright Copyright (c) 2016 Carey Sizer
 * @license   https://github.com/careysizer/slim-twig-asset/blob/master/LICENSE.md (MIT License)
 */
namespace Carey\Tests\Twig\Extension;

use Carey\Twig\Extension;
use Symfony\Component\Asset\Packages;

require dirname(__DIR__) . '/../../vendor/autoload.php';

class TwigTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \Carey\Twig\Extension\Asset::getName()
     */
    public function testGetName()
    {
        $extension = new \Carey\Twig\Extension\Asset(new Packages());
        $this->assertEquals('asset', $extension->getName());
    }

    /**
     * @covers \Carey\Twig\Extension\Asset::getAssetUrl()
     */
    public function testGetAssetUrl()
    {
        $packages = new \Symfony\Component\Asset\Packages(
            new \Symfony\Component\Asset\UrlPackage(
                'https://www.example.com/scripts/',
                // Choose the version strategy that works for your purposes
                new \Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy('1.0')
            )
        );
        $extension = new \Carey\Twig\Extension\Asset($packages);
        $this->assertEquals(
            'https://www.example.com/scripts/path/to/file?1.0',
            $extension->getAssetUrl('/path/to/file')
        );
    }

    /**
     * @covers \Carey\Twig\Extension\Asset::getFunctions()
     */
    public function testGetFunctions()
    {
        $extension = new \Carey\Twig\Extension\Asset(new Packages());
        $this->assertContainsOnly(\Twig_SimpleFunction::class, $extension->getFunctions());
    }
}
