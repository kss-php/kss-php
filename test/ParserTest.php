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
     */
    public function isKssBlock()
    {
        $comment = '// This is a style comment
//
// .modifier1
// .modifier2
//
// Styleguide 1.2.3
        ';

        $this->assertTrue(\Scan\Kss\Parser::isKssBlock($comment));
    }

    /**
     * @test
     */
    public function isNotKssBlock()
    {
        $comment = '// This is just a normal comment
// It has two lines
        ';

        $this->assertFalse(\Scan\Kss\Parser::isKssBlock($comment));
    }
}
