<?php

namespace Test;

use PHPUnit\Framework\TestCase;

class ParametersTest extends TestCase
{
    protected static $parameter;

    public static function setUpBeforeClass(): void
    {
        $name = '$start';
        $description = 'This is a test parameter';
        self::$parameter = new \Kss\Parameter($name, $description);
    }

    /**
     * @test
     */
    public function testGetName()
    {
        $this->assertEquals('$start', self::$parameter->getName());
    }

    /**
     * @test
     */
    public function getDescription()
    {
        $this->assertEquals('This is a test parameter', self::$parameter->getDescription());
    }
}
