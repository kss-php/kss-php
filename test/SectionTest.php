<?php

namespace Scan\Test;

class SectionTest extends \PHPUnit_Framework_TestCase
{
    protected static $section;

    public static function setUpBeforeClass()
    {
        $commentText = <<<comment
# Form Button

Your standard form button.

And another line describing the button.

:hover - Highlights when hovering.
:disabled - Dims the button when disabled.
.primary - Indicates button is the primary action.
.smaller - A smaller button
.altFormButton @extends .formButton - An extension of .formButton

Styleguide 2.1.1.
comment;

        self::$section = new \Scan\Kss\Section($commentText);
    }

    /**
     * @test
     */
    public function getFilename()
    {
        $this->assertEmpty(self::$section->getFilename());
    }

    /**
     * @test
     */
    public function getTitle()
    {
        $expected = 'Form Button';
        $this->assertEquals($expected, self::$section->getTitle());
    }

    /**
     * @test
     */
    public function getDescription()
    {
        $expected = <<<comment
Your standard form button.

And another line describing the button.
comment;
        $this->assertEquals($expected, self::$section->getDescription());
    }

    /**
     * @test
     */
    public function getModifiers()
    {
        $this->assertCount(5, self::$section->getModifiers());
    }

    /**
     * @test
     */
    public function getSection()
    {
        $expected = '2.1.1';
        $this->assertEquals($expected, self::$section->getSection());
    }
}
