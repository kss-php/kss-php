<?php

namespace Scan\Test;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function parseScss()
    {
        $parser = new \Scan\Kss\Parser('test/fixtures/scss');
        $this->assertEquals('Your standard form button.', $parser->getSection('2.1.1')->getDescription());
        return $parser;
    }

    /**
     * @test
     */
    public function parseScssWords()
    {
        $parser = new \Scan\Kss\Parser('test/fixtures/scss-words');
        $this->assertEquals(
            'Your standard form button.',
            $parser->getSection('Buttons - Standard')->getDescription()
        );
        return $parser;
    }

    /**
     * @test
     */
    public function parseSass()
    {
        $parser = new \Scan\Kss\Parser('test/fixtures/sass');
        $this->assertEquals(
            'A button suitable for giving stars to someone.',
            $parser->getSection('2.2.1')->getDescription()
        );
        return $parser;
    }

    /**
     * @test
     */
    public function parseLess()
    {
        $parser = new \Scan\Kss\Parser('test/fixtures/less');
        $this->assertEquals('Your standard form button.', $parser->getSection('2.1.1')->getDescription());
        return $parser;
    }

    /**
     * @test
     */
    public function parseCss()
    {
        $parser = new \Scan\Kss\Parser('test/fixtures/css');
        $this->assertEquals('Your standard form button.', $parser->getSection('2.1.1')->getDescription());
        return $parser;
    }

    /**
     * @test
     */
    public function parseStyl()
    {
        $parser = new \Scan\Kss\Parser('test/fixtures/styl');
        $this->assertEquals('Your standard form button.', $parser->getSection('2.1.1')->getDescription());
        return $parser;
    }

    /**
     * @test
     */
    public function parseStylus()
    {
        $parser = new \Scan\Kss\Parser('test/fixtures/stylus');
        $this->assertEquals('Your standard form button.', $parser->getSection('2.1.1')->getDescription());
        return $parser;
    }

    /**
     * @test
     * @depends parseSass
     */
    public function parseMultiLineSass($parser)
    {
        $this->assertEquals('Your standard form button.', $parser->getSection('2.1.1')->getDescription());
    }

    /**
     * @test
     * @depends parseScss
     */
    public function parseNestedScss($parser)
    {
        $this->assertEquals('Your standard form element.', $parser->getSection('3.0.0')->getDescription());
        $this->assertEquals('Your standard text input box.', $parser->getSection('3.0.1')->getDescription());
    }

    /**
     * @test
     * @depends parseScssWords
     */
    public function parseNestedScssWords($parser)
    {
        $this->assertEquals(
            'Your standard form element.',
            $parser->getSection('Forms')->getDescription()
        );
        $this->assertEquals(
            'Your standard text input box.',
            $parser->getSection('Forms.Inputs.Text')->getDescription()
        );
    }

    /**
     * @test
     * @depends parseSass
     */
    public function parseNestedSass($parser)
    {
        $this->assertEquals('Your standard form element.', $parser->getSection('3.0.0')->getDescription());
        $this->assertEquals('Your standard text input box.', $parser->getSection('3.0.1')->getDescription());
    }

    /**
     * @test
     * @depends parseLess
     */
    public function parseNestedLess($parser)
    {
        $this->assertEquals('Your standard form element.', $parser->getSection('3.0.0')->getDescription());
        $this->assertEquals('Your standard text input box.', $parser->getSection('3.0.1')->getDescription());
    }

    /**
     * @test
     * @depends parseStyl
     */
    public function parseNestedStyl($parser)
    {
        $this->assertEquals('Your standard form element.', $parser->getSection('3.0.0')->getDescription());
        $this->assertEquals('Your standard text input box.', $parser->getSection('3.0.1')->getDescription());
    }

    /**
     * @test
     * @depends parseStylus
     */
    public function parseNestedStylus($parser)
    {
        $this->assertEquals('Your standard form element.', $parser->getSection('3.0.0')->getDescription());
        $this->assertEquals('Your standard text input box.', $parser->getSection('3.0.1')->getDescription());
    }

    /**
     * @test
     * @depends parseScss
     */
    public function getSection($parser)
    {
        $this->assertEquals('2.1.1', $parser->getSection('2.1.1')->getReference());
    }

    /**
     * @test
     * @depends parseScssWords
     */
    public function getSectionWords($parser)
    {
        $this->assertEquals(
            'Buttons - Standard',
            $parser->getSection('Buttons - Standard')->getReference()
        );
    }

    /**
     * @test
     * @depends parseScssWords
     */
    public function getSectionWordsCaseInsensitive($parser)
    {
        $this->assertEquals(
            'Buttons - Standard',
            $parser->getSection('buttons - standard')->getReference()
        );
    }

    /**
     * @test
     * @depends parseScssWords
     */
    public function getSectionWordsDashToDots($parser)
    {
        $this->assertEquals(
            'Buttons - Standard',
            $parser->getSection('buttons.standard')->getReference()
        );
    }

    /**
     * @test
     * @expectedException Scan\Kss\Exception\UnexpectedValueException
     * @depends parseScss
     */
    public function getSectionNotFound($parser)
    {
        $this->assertEmpty($parser->getSection('200.1.1')->getReference());
    }

    /**
     * @test
     * @expectedException Scan\Kss\Exception\UnexpectedValueException
     * @depends parseScssWords
     */
    public function getSectionWordsNotFound($parser)
    {
        $this->assertEmpty($parser->getSection('Tables - Zebra')->getReference());
    }

    /**
     * @test
     * @depends parseScss
     */
    public function getSections($parser)
    {
        $sections = $parser->getSections();
        $this->assertCount(5, $sections);

        $expectedSort = array(
            '2',
            '2.1.1',
            '2.2.1',
            '3.0.0',
            '3.0.1',
        );
        $actualSort = array();
        foreach ($sections as $section) {
            $actualSort[] = $section->getReference();
        }
        $this->assertEquals($expectedSort, $actualSort);
    }

    /**
     * @test
     * @depends parseScssWords
     */
    public function getSectionsWords($parser)
    {
        $sections = $parser->getSections();
        $this->assertCount(5, $sections);

        $expectedSort = array(
            'Buttons',
            'Buttons - Standard',
            'Buttons - Stars',
            'Forms',
            'Forms.Inputs.Text',
        );
        $actualSort = array();
        foreach ($sections as $section) {
            $actualSort[] = $section->getReference();
        }
        $this->assertEquals($expectedSort, $actualSort);
    }

    /**
     * @test
     * @depends parseScss
     */
    public function getTopLevelSections($parser)
    {
        $expectedSections = array('2', '3.0.0');
        $sections = $parser->getTopLevelSections();
        $this->assertCount(count($expectedSections), $sections);
        $x = 0;
        foreach ($sections as $section) {
            $this->assertEquals($expectedSections[$x], $section->getReference());
            ++$x;
        }
    }

    /**
     * @test
     * @depends parseScssWords
     */
    public function getTopLevelSectionsWords($parser)
    {
        $expectedSections = array('Buttons', 'Forms');
        $sections = $parser->getTopLevelSections();
        $this->assertCount(count($expectedSections), $sections);
        $x = 0;
        foreach ($sections as $section) {
            $this->assertEquals($expectedSections[$x], $section->getReference());
            ++$x;
        }
    }

    /**
     * @test
     * @depends parseScss
     */
    public function getSectionChildren($parser)
    {
        $expectedSections = array('2.1.1', '2.2.1');
        $sections = $parser->getSectionChildren('2');
        $this->assertCount(count($expectedSections), $sections);
        $x = 0;
        foreach ($sections as $section) {
            $this->assertEquals($expectedSections[$x], $section->getReference());
            ++$x;
        }

        $expectedSections = array('3.0.1');
        $sections = $parser->getSectionChildren('3');
        $this->assertCount(count($expectedSections), $sections);
        $x = 0;
        foreach ($sections as $section) {
            $this->assertEquals($expectedSections[$x], $section->getReference());
            ++$x;
        }
    }

    /**
     * @test
     * @depends parseScssWords
     */
    public function getSectionChildrenWithWords($parser)
    {
        $expectedSections = array('Buttons - Standard', 'Buttons - Stars');
        $sections = $parser->getSectionChildren('Buttons');
        $this->assertCount(count($expectedSections), $sections);
        $x = 0;
        foreach ($sections as $section) {
            $this->assertEquals($expectedSections[$x], $section->getReference());
            ++$x;
        }

        $expectedSections = array('Forms.Inputs.Text');
        $sections = $parser->getSectionChildren('Forms');
        $this->assertCount(count($expectedSections), $sections);
        $x = 0;
        foreach ($sections as $section) {
            $this->assertEquals($expectedSections[$x], $section->getReference());
            ++$x;
        }
    }

    /**
     * @test
     * @depends parseScssWords
     */
    public function getSectionChildrenWithWordsCaseInsensitive($parser)
    {
        $expectedSections = array('Buttons - Standard', 'Buttons - Stars');
        $sections = $parser->getSectionChildren('buttons');
        $this->assertCount(count($expectedSections), $sections);
        $x = 0;
        foreach ($sections as $section) {
            $this->assertEquals($expectedSections[$x], $section->getReference());
            ++$x;
        }

        $expectedSections = array('Forms.Inputs.Text');
        $sections = $parser->getSectionChildren('forms');
        $this->assertCount(count($expectedSections), $sections);
        $x = 0;
        foreach ($sections as $section) {
            $this->assertEquals($expectedSections[$x], $section->getReference());
            ++$x;
        }
    }

    /**
     * @test
     * @depends parseScssWords
     */
    public function getSectionChildrenWithWordsDashToDots($parser)
    {
        $expectedSections = array('Forms.Inputs.Text');
        $sections = $parser->getSectionChildren('forms - inputs');
        $this->assertCount(count($expectedSections), $sections);
        $x = 0;
        foreach ($sections as $section) {
            $this->assertEquals($expectedSections[$x], $section->getReference());
            ++$x;
        }
    }

    /**
     * @test
     * @depends parseScss
     */
    public function getSectionChildrenWithDepth($parser)
    {
        $expectedSections = array();
        $sections = $parser->getSectionChildren('3', 0);
        $this->assertCount(count($expectedSections), $sections);
        $x = 0;
        foreach ($sections as $section) {
            $this->assertEquals($expectedSections[$x], $section->getReference());
            ++$x;
        }

        $expectedSections = array('3.0.1');
        $sections = $parser->getSectionChildren('3', 2);
        $this->assertCount(count($expectedSections), $sections);
        $x = 0;
        foreach ($sections as $section) {
            $this->assertEquals($expectedSections[$x], $section->getReference());
            ++$x;
        }
    }

    /**
     * @test
     * @depends parseScssWords
     */
    public function getSectionChildrenWithDepthWords($parser)
    {
        $expectedSections = array();
        $sections = $parser->getSectionChildren('Forms', 0);
        $this->assertCount(count($expectedSections), $sections);
        $x = 0;
        foreach ($sections as $section) {
            $this->assertEquals($expectedSections[$x], $section->getReference());
            ++$x;
        }

        $expectedSections = array('Forms.Inputs.Text');
        $sections = $parser->getSectionChildren('Forms', 2);
        $this->assertCount(count($expectedSections), $sections);
        $x = 0;
        foreach ($sections as $section) {
            $this->assertEquals($expectedSections[$x], $section->getReference());
            ++$x;
        }
    }

    /**
     * @test
     */
    public function isKssBlock()
    {
        $comment = <<<comment
This is a style comment

.modifier1
.modifier2

Styleguide 1.2.3
comment;

        $this->assertTrue(\Scan\Kss\Parser::isKssBlock($comment));
    }

    /**
     * @test
     */
    public function isKssBlockWords()
    {
        $comment = <<<comment
This is a style comment

.modifier1
.modifier2

Styleguide Forms.Buttons
comment;

        $this->assertTrue(\Scan\Kss\Parser::isKssBlock($comment));
    }

    /**
     * @test
     */
    public function isKssBlockWordsDashed()
    {
        $comment = <<<comment
This is a style comment

.modifier1
.modifier2

Styleguide Forms - Buttons - Special Actions;
comment;

        $this->assertTrue(\Scan\Kss\Parser::isKssBlock($comment));
    }

    /**
     * @test
     */
    public function isKssBlockWithNoReference()
    {
        $comment = <<<comment
This is a style comment

.modifier1
.modifier2

No styleguide reference;
comment;

        $this->assertTrue(\Scan\Kss\Parser::isKssBlock($comment));
    }

    /**
     * @test
     */
    public function isNotKssBlock()
    {
        $comment = <<<comment
This is just a normal comment
It has two lines
comment;

        $this->assertFalse(\Scan\Kss\Parser::isKssBlock($comment));
    }
}
