<?php

namespace Scan\Test;

use \Scan\Kss\Section;

class SectionTest extends \PHPUnit_Framework_TestCase
{
    protected static $section;

    public static function setUpBeforeClass()
    {
        $commentText = <<<comment
# Form Button

Your standard form button.

And another line describing the button.

Markup: <div class="\$modifierClass"></div>

:hover - Highlights when hovering.
:disabled - Dims the button when disabled.
.primary - Indicates button is the primary action.
.smaller - A smaller button
.altFormButton @extends .formButton - An extension of .formButton

Styleguide 2.1.1.
comment;

        self::$section = new Section($commentText);
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
    public function getMarkup()
    {
        $expected = '<div class="$modifierClass"></div>';
        $this->assertEquals($expected, self::$section->getMarkup());
    }

    /**
     * @test
     */
    public function getMarkupMultiLine()
    {
        $commentText = <<<comment
# Form Button

Your standard form button.

And another line describing the button.

Markup:
<div class="\$modifierClass">
    <a href="#">test</a>
</div>

:hover - Highlights when hovering.
:disabled - Dims the button when disabled.
.primary - Indicates button is the primary action.
.smaller - A smaller button
.altFormButton @extends .formButton - An extension of .formButton

Styleguide 2.1.1.
comment;

        $expected = <<<comment
<div class="\$modifierClass">
    <a href="#">test</a>
</div>
comment;

        $testSection = new Section($commentText);
        $this->assertEquals($expected, $testSection->getMarkup());
    }

    public function getMarkupNull()
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

        $testSection = new Section($commentText);
        $this->assertEmpty($testSection->getMarkup());
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
        $this->assertEquals('2.1.1', self::$section->getSection());
    }

    /**
     * @test
     */
    public function getDepth()
    {
        $this->assertEquals(2, self::$section->getDepth());
    }

    /**
     * @test
     */
    public function calcDepth()
    {
        $this->assertEquals(0, Section::calcDepth('1'));
        $this->assertEquals(0, Section::calcDepth('1.0.0'));
        $this->assertEquals(1, Section::calcDepth('1.1'));
        $this->assertEquals(1, Section::calcDepth('1.1.0'));
        $this->assertEquals(2, Section::calcDepth('1.1.1'));
        $this->assertEquals(3, Section::calcDepth('1.1.1.1'));
        $this->assertEquals(3, Section::calcDepth('1.1.0.1'));
    }

    /**
     * @test
     */
    public function getDepthScore()
    {
        $this->assertEquals(2.11, self::$section->getDepthScore());
    }

    /**
     * @test
     */
    public function calcDepthScore()
    {
        $this->assertEquals(1, Section::calcDepthScore('1'));
        $this->assertEquals(1, Section::calcDepthScore('1.0.0'));
        $this->assertEquals(1.1, Section::calcDepthScore('1.1'));
        $this->assertEquals(1.1, Section::calcDepthScore('1.1.0'));
        $this->assertEquals(1.11, Section::calcDepthScore('1.1.1'));
        $this->assertEquals(1.111, Section::calcDepthScore('1.1.1.1'));
        $this->assertEquals(1.101, Section::calcDepthScore('1.1.0.1'));
    }

    /**
     * @test
     */
    public function depthSort()
    {
        $sections = array(
            '2' => new Section('// Styleguide 2'),
            '3.2.1' => new Section('// Styleguide 3.2.1'),
            '3.1' => new Section('// Styleguide 3.1'),
            '1.2' => new Section('// Styleguide 1.2'),
            '1' => new Section('// Styleguide 1'),
            '3.0.0' => new Section('// Styleguide 3.0.0'),
            '2.1.2' => new Section('// Styleguide 2.1.2'),
        );

        uasort($sections, '\Scan\Kss\Section::depthSort');

        $keys = array_keys($sections);
        $expectedKeys = array(
            '1',
            '2',
            '3.0.0',
            '1.2',
            '3.1',
            '2.1.2',
            '3.2.1'
        );
        $this->assertEquals($expectedKeys, $keys);
    }

    /**
     * @test
     */
    public function depthScoreSort()
    {
        $sections = array(
            '2' => new Section('// Styleguide 2'),
            '3.2.1' => new Section('// Styleguide 3.2.1'),
            '3.1' => new Section('// Styleguide 3.1'),
            '1.2' => new Section('// Styleguide 1.2'),
            '1' => new Section('// Styleguide 1'),
            '3.0.0' => new Section('// Styleguide 3.0.0'),
            '2.1.2' => new Section('// Styleguide 2.1.2'),
        );

        uasort($sections, '\Scan\Kss\Section::depthScoreSort');

        $keys = array_keys($sections);
        $expectedKeys = array(
            '1',
            '1.2',
            '2',
            '2.1.2',
            '3.0.0',
            '3.1',
            '3.2.1'
        );
        $this->assertEquals($expectedKeys, $keys);
    }
}
