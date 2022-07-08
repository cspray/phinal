<?php

namespace Weirdan\PsalmPluginSkeleton\Tests;

use SimpleXMLElement;
use Weirdan\PsalmPluginSkeleton\Plugin;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psalm\Plugin\RegistrationInterface;

class PluginTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var ObjectProphecy<RegistrationInterface>
     */
    private $registration;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->registration = $this->prophesize(RegistrationInterface::class);
    }

    /**
     * @test
     * @return void
     */
    public function hasEntryPoint()
    {
        $this->expectNotToPerformAssertions();
        $plugin = new Plugin();
        $plugin($this->registration->reveal(), null);
    }

    /**
     * @test
     * @return void
     */
    public function acceptsConfig()
    {
        $this->expectNotToPerformAssertions();
        $plugin = new Plugin();
        $plugin($this->registration->reveal(), new SimpleXMLElement('<myConfig></myConfig>'));
    }
}
