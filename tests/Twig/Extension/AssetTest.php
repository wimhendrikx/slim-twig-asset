<?php

namespace Carey\Tests\Twig\Extension;

use Carey\Twig\Extension;
use Symfony\Component\Asset\Packages;

require dirname(__DIR__) . '/../../vendor/autoload.php';

class TwigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Carey\Twig\Extension\Asset::getName()
     */
    public function testGetName()
    {
        $extension = new \Carey\Twig\Extension\Asset(new Packages());
        $this->assertEquals("asset", $extension->getName());
    }
}
