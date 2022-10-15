<?php

namespace Test;

use PHPUnit\Framework\TestCase;

class CommentParserTest extends TestCase
{
    protected static $blocks;

    public static function setUpBeforeClass(): void
    {
        $parser = new \Kss\CommentParser(new \SplFileObject('test/fixtures/comments.txt'));
        self::$blocks = $parser->getBlocks();
    }

    /**
     * @test
     */
    public function isSingleLineComment()
    {
        $this->assertTrue(\Kss\CommentParser::isSingleLineComment('// yes'));
    }

    /**
     * @test
     */
    public function isNotSingleLineComment()
    {
        $this->assertFalse(\Kss\CommentParser::isSingleLineComment('no'));
    }

    /**
     * @test
     */
    public function isStartMultiLineComment()
    {
        $this->assertTrue(\Kss\CommentParser::isStartMultiLineComment('/* yes'));
    }

    /**
     * @test
     */
    public function isNotStartMultiLineComment()
    {
        $this->assertFalse(\Kss\CommentParser::isStartMultiLineComment('no'));
    }

    /**
     * @test
     */
    public function isEndMultiLineComment()
    {
        $this->assertTrue(\Kss\CommentParser::isEndMultiLineComment('yes */'));
        $this->assertTrue(\Kss\CommentParser::isEndMultiLineComment('*/'));
    }

    /**
     * @test
     */
    public function isEndStartMultiLineComment()
    {
        $this->assertFalse(\Kss\CommentParser::isEndMultiLineComment('no'));
    }

    /**
     * @test
     */
    public function parseSingleLineComment()
    {
        $this->assertEquals(' yes', \Kss\CommentParser::parseSingleLineComment('// yes'));
    }

    /**
     * @test
     */
    public function parseMultiLineComment()
    {
        $this->assertEquals(' yes', \Kss\CommentParser::parseMultiLineComment('/* yes */'));
    }

    /**
     * @test
     */
    public function findsSingleLineCommentStyles()
    {
        $expected = <<<comment
This comment block has comment identifiers on every line.

Fun fact: this is Kyle's favorite comment syntax!
comment;
        $this->assertTrue(in_array($expected, self::$blocks));
    }

    /**
     * @test
     */
    public function findsBlockStyleCommentStyles()
    {
        $expected = <<<comment
This comment block is a block-style comment syntax.

There's only two identifier across multiple lines.
comment;
        $this->assertTrue(in_array($expected, self::$blocks));
    }

    /**
     * @test
     */
    public function findsBlockStyleCommentStylesStarsEveryLine()
    {
        $expected = <<<comment
This is another common multi-line comment style.

It has stars at the begining of every line.
comment;
        $this->assertTrue(in_array($expected, self::$blocks));
    }

    /**
     * @test
     */
    public function handlesMixedStyles()
    {
        $expected = 'This comment has a /* comment */ identifier inside of it!';
        $this->assertTrue(in_array($expected, self::$blocks));

        $expected = 'Look at my //cool// comment art!';
        $this->assertTrue(in_array($expected, self::$blocks));
    }

    /**
     * @test
     */
    public function handlesIndentComments()
    {
        $expected = 'Indented single-line comment.';
        $this->assertTrue(in_array($expected, self::$blocks));

        $expected = 'Indented block comment.';
        $this->assertTrue(in_array($expected, self::$blocks));
    }
}
