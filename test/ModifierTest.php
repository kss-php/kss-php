<?php

namespace Scan\Test;

class ModifierTest extends \PHPUnit_Framework_TestCase
{
    protected static $modifier;
    protected static $extenderModifier;

    public static function setUpBeforeClass()
    {
        $name = '.modifier';
        $description = 'This is a test modifier';
        self::$modifier = new \Scan\Kss\Modifier($name, $description);

        $name = '.extenderModifier @extend .modifier';
        $description = 'This is a test modifier that extends from .modifier';
        self::$extenderModifier = new \Scan\Kss\Modifier($name, $description);
    }

    /**
     * @test
     */
    public function testGetName()
    {
        $this->assertEquals('.modifier', self::$modifier->getName());
    }

    /**
     * @test
     */
    public function getExtenderName()
    {
        $this->assertEquals('.extenderModifier', self::$extenderModifier->getName());
    }

    /**
     * @test
     */
    public function getDescription()
    {
        $this->assertEquals('This is a test modifier', self::$modifier->getDescription());
    }

    /**
     * @test
     */
    public function getExtenderDescription()
    {
        $this->assertEquals(
            'This is a test modifier that extends from .modifier',
            self::$extenderModifier->getDescription()
        );
    }

    /**
     * @test
     */
    public function isNotExtender()
    {
        $this->assertFalse(self::$modifier->isExtender());
    }
    /**
     * @test
     */
    public function isExtender()
    {
        $this->assertTrue(self::$extenderModifier->isExtender());
    }

    /**
     * @test
     */
    public function getClassName()
    {
        $this->assertEquals('modifier', self::$modifier->getClassName());
    }

    /**
     * @test
     */
    public function getExtenderClassName()
    {
        $this->assertEquals('extenderModifier', self::$extenderModifier->getClassName());
    }

    /**
     * @test
     */
    public function getExtendedClassName()
    {
        $this->assertEquals('', self::$modifier->getExtendedClassName());
    }

    /**
     * @test
     */
    public function getExtenderExtendedClassName()
    {
        $this->assertEquals('modifier', self::$extenderModifier->getExtendedClassName());
    }

    /**
     * @test
     */
    public function getExampleHtml()
    {
        $exampleHtml = '<div class="$modifierClass"></div>';
        $html = self::$modifier->getExampleHtml($exampleHtml);
        $expected = '<div class="modifier"></div>';
        $this->assertEquals($expected, $html);
    }

    /**
     * @test
     */
    public function getExtenderExampleHtml()
    {
        $exampleHtml = '<div class="modifier $modifierClass"></div>';
        $html = self::$extenderModifier->getExampleHtml($exampleHtml);
        $expected = '<div class="extenderModifier "></div>';
        $this->assertEquals($expected, $html);
    }
}
