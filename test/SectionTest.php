<?php

namespace Scan\Test;

use \Scan\Kss\Section;

class SectionTest extends \PHPUnit_Framework_TestCase
{
    protected static $section;

    public static function setUpBeforeClass()
    {
        $commentText = <<<'comment'
# Form Button

Your standard form button.

And another line describing the button.

Markup: <div class="$modifierClass"></div>

Deprecated: Styling for legacy wikis. We'll drop support for these wikis on July 13, 2007.

Experimental: An alternative signup button styling used in AB Test #195.

:hover - Highlights when hovering.
:disabled - Dims the button when disabled.
.primary - Indicates button is the primary action.
.smaller - A smaller button
.altFormButton @extends .formButton - An extension of .formButton

Compatible in IE6+, Firefox 2+, Safari 4+.

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
    public function getTitleFromReferenceWord()
    {
        $section = new Section('Styleguide Forms.Checkboxes');
        $this->assertEquals('Checkboxes', $section->getTitle());

        $section = new Section('Styleguide Forms - Special Checkboxes');
        $this->assertEquals('Special Checkboxes', $section->getTitle());
    }

    /**
     * @test
     */
    public function getTitleFromReferenceWordWithTitle()
    {
        $commentText = <<<comment
# Form Checkboxes

Your standard form checkboxes.

Styleguide Forms.Checkboxes
comment;
        $section = new Section($commentText);
        $this->assertEquals('Form Checkboxes', $section->getTitle());

        $commentText = <<<comment
# Form Special Checkboxes

Your standard form checkboxes.

Styleguide Forms - Special Checkboxes
comment;
        $section = new Section($commentText);
        $this->assertEquals('Form Special Checkboxes', $section->getTitle());
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
    public function getMarkupNormalEmpty()
    {
        $expected = '<div class=""></div>';
        $this->assertEquals($expected, self::$section->getMarkupNormal());
    }

    /**
     * @test
     */
    public function getMarkupNormalReplacement()
    {
        $expected = '<div class="{class}"></div>';
        $this->assertEquals($expected, self::$section->getMarkupNormal('{class}'));
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

    /**
     * @test
     */
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
        $this->assertNull($testSection->getMarkup());
    }

    /**
     * @test
     */
    public function hasMarkup()
    {
        $this->assertTrue(self::$section->hasMarkup());
    }

    /**
     * @test
     */
    public function hasMarkupFalse()
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
        $this->assertFalse($testSection->hasMarkup());
    }

    /**
     * @test
     */
    public function getCompatibility()
    {
        $expected = "Compatible in IE6+, Firefox 2+, Safari 4+.";
        $this->assertEquals($expected, self::$section->getCompatibility());
    }

    /**
     * @test
     */
    public function getCompatibilityColon()
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

Compatibility: IE6+, Firefox 2+, Safari 4+.

Styleguide 2.1.1.
comment;

        $testSection = new Section($commentText);
        $this->assertEquals('Compatibility: IE6+, Firefox 2+, Safari 4+.', $testSection->getCompatibility());
    }

    /**
     * @test
     */
    public function getCompatibilityUntested()
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

Compatibility untested.

Styleguide 2.1.1.
comment;

        $testSection = new Section($commentText);
        $this->assertEquals('Compatibility untested.', $testSection->getCompatibility());
    }

    /**
     * @test
     */
    public function getCompatibilityNull()
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
        $this->assertNull($testSection->getCompatibility());
    }

    /**
     * @test
     */
    public function getDeprecated()
    {
        $expected = "Styling for legacy wikis. We'll drop support for these wikis on July 13, 2007.";
        $this->assertEquals($expected, self::$section->getDeprecated());
    }

    /**
     * @test
     */
    public function getDeprecatedMultiLine()
    {
        $commentText = <<<comment
# Form Button

Your standard form button.

And another line describing the button.

Deprecated:
Styling for legacy wikis. We'll drop support for these wikis on
July 13, 2007.

:hover - Highlights when hovering.
:disabled - Dims the button when disabled.
.primary - Indicates button is the primary action.
.smaller - A smaller button
.altFormButton @extends .formButton - An extension of .formButton

Styleguide 2.1.1.
comment;

        $expected = <<<comment
Styling for legacy wikis. We'll drop support for these wikis on
July 13, 2007.
comment;

        $testSection = new Section($commentText);
        $this->assertEquals($expected, $testSection->getDeprecated());
    }

    /**
     * @test
     */
    public function getDeprecatedNull()
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
        $this->assertEmpty($testSection->getDeprecated());
    }

    /**
     * @test
     */
    public function getExperimental()
    {
        $expected = 'An alternative signup button styling used in AB Test #195.';
        $this->assertEquals($expected, self::$section->getExperimental());
    }

    /**
     * @test
     */
    public function getExperimentalMultiLine()
    {
        $commentText = <<<comment
# Form Button

Your standard form button.

And another line describing the button.

Experimental:
An alternative signup button styling used in
AB Test #195.

:hover - Highlights when hovering.
:disabled - Dims the button when disabled.
.primary - Indicates button is the primary action.
.smaller - A smaller button
.altFormButton @extends .formButton - An extension of .formButton

Styleguide 2.1.1.
comment;

        $expected = <<<comment
An alternative signup button styling used in
AB Test #195.
comment;

        $testSection = new Section($commentText);
        $this->assertEquals($expected, $testSection->getExperimental());
    }

    /**
     * @test
     */
    public function getExperimentalNull()
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
        $this->assertEmpty($testSection->getExperimental());
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
    public function getModifiersDescriptionContainsDelimiter()
    {
        $commentText = <<<comment
# Form Button

Your standard form button.

.smaller - A smaller button - really small

Styleguide 2.1.1.
comment;

        $testSection = new Section($commentText);
        $modifiers = $testSection->getModifiers();
        $description = $modifiers[0]->getDescription();
        $expected = 'A smaller button - really small';

        $this->assertEquals($expected, $description);
    }

    /**
     * @test
     */
    public function getParameters()
    {
        $commentText = <<<'comment'
Mixin for parameter

$start - First description of the parameter
%middle - Second description of the parameter
@end - Third description of the parameter

Styleguide 2.1.1.
comment;

        $testSection = new Section($commentText);
        $this->assertCount(3, $testSection->getParameters());
    }

    /**
     * @test
     */
    public function getParametersDescriptionContainsDelimiter()
    {
        $commentText = <<<'comment'
Mixin for parameter

$parameter  - Description of the parameter - really fancy

Styleguide 2.1.1.
comment;

        $testSection = new Section($commentText);
        $parameters = $testSection->getParameters();
        $description = $parameters[0]->getDescription();
        $expected = 'Description of the parameter - really fancy';

        $this->assertEquals($expected, $description);
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
    public function getSectionWords()
    {
        $section = new Section('Styleguide Forms.Checkboxes');
        $this->assertEquals('Forms.Checkboxes', $section->getSection());

        $section = new Section('Styleguide Forms - Special Checkboxes');
        $this->assertEquals('Forms - Special Checkboxes', $section->getSection());
    }

    /**
     * @test
     */
    public function getReference()
    {
        $this->assertEquals('2.1.1', self::$section->getReference());

        $section = new Section('Styleguide 3.0.0');
        $this->assertEquals('3.0.0', $section->getReference());
    }

    /**
     * @test
     */
    public function getReferenceWithOtherStyleguideInComment()
    {
        $comment = <<<comment
# Dropdown Menu

The dropdown bootstrap menu class modified with Scan colors

Markup:
<div class="dropdown open">
    <ul class="dropdown-menu \$modifierClass">
        <li><a href="#" title="Link 1">Link 1</a></li>
        <li class="divider"></li>
        <li><a href="#" title="Link 2" class="active">Link 2</a></li>
        <li><a href="#" title="Link 3">Link 3</a></li>
        <li class="divider-header">Divider with Text</li>
        <li><a href="#" title="Link 4">Link 4</a></li>
    </ul>
</div>
<!-- Below div is to help styleguide rendering -->
<div style="height: 220px;"></div>

.dropdown-menu--wider

Styleguide 3.3
comment;
        $section = new Section($comment);
        $this->assertEquals('3.3', $section->getReference());
    }

    /**
     * @test
     */
    public function getReferenceWithNoStyleguideReference()
    {
        $comment = <<<comment
Your standard form button.

:hover    - Highlights when hovering.
:disabled - Dims the button when disabled.
.primary  - Indicates button is the primary action.
.smaller  - A little bit smaller now.

No styleguide reference.
comment;
        $section = new Section($comment);
        $this->assertEquals(null, $section->getReference());
    }

    /**
     * @test
     */
    public function getReferenceWords()
    {
        $section = new Section('Styleguide Forms.Checkboxes');
        $this->assertEquals('Forms.Checkboxes', $section->getReference());

        $section = new Section('Styleguide Forms - Special Checkboxes');
        $this->assertEquals('Forms - Special Checkboxes', $section->getReference());
    }

    /**
     * @test
     */
    public function hasReferenceNumeric()
    {
        $section = new Section('Styleguide 1.2.3');
        $this->assertTrue($section->hasReference());
    }

    /**
     * @test
     */
    public function hasReferenceWords()
    {
        $section = new Section('Styleguide Forms.Checkboxes');
        $this->assertTrue($section->hasReference());
    }

    /**
     * @test
     */
    public function hasReferenceWordsDashed()
    {
        $section = new Section('Styleguide Forms - Special Checkboxes');
        $this->assertTrue($section->hasReference());
    }

    /**
     * @test
     */
    public function hasReferenceNoReference()
    {
        $section = new Section('No styleguide reference');
        $this->assertFalse($section->hasReference());
    }

    /**
     * @test
     */
    public function isReferenceNumeric()
    {
        $this->assertTrue(Section::isReferenceNumeric('1'));
        $this->assertTrue(Section::isReferenceNumeric('1.1'));
        $this->assertTrue(Section::isReferenceNumeric('1.0.0.1'));
        $this->assertTrue(Section::isReferenceNumeric('1.0.0.1.'));
        $this->assertFalse(Section::isReferenceNumeric('Forms'));
        $this->assertFalse(Section::isReferenceNumeric('Forms.Checkboxes'));
        $this->assertFalse(Section::isReferenceNumeric('Forms - Special Checkboxes'));
    }

    /**
     * @test
     */
    public function getReferenceParts()
    {
        $section = new Section('Styleguide 1');
        $this->assertEquals(
            array(1),
            $section->getReferenceParts()
        );

        $section = new Section('Styleguide 1.1');
        $this->assertEquals(
            array(1,1),
            $section->getReferenceParts()
        );

        $section = new Section('Styleguide 1.0.0.1');
        $this->assertEquals(
            array(1,0,0,1),
            $section->getReferenceParts()
        );

        $section = new Section('Styleguide 1.0.0.1.');
        $this->assertEquals(
            array(1,0,0,1),
            $section->getReferenceParts()
        );

        $section = new Section('Styleguide Forms');
        $this->assertEquals(
            array('Forms'),
            $section->getReferenceParts()
        );

        $section = new Section('Styleguide Forms.Checkboxes');
        $this->assertEquals(
            array('Forms', 'Checkboxes'),
            $section->getReferenceParts()
        );

        $section = new Section('Styleguide Forms - Special Checkboxes');
        $this->assertEquals(
            array('Forms', 'Special Checkboxes'),
            $section->getReferenceParts()
        );
    }

    /**
     * @test
     */
    public function getReferenceTrimmed()
    {
        $this->assertEquals('2.1.1', self::$section->getReference(true));

        $section = new Section('Styleguide 3.0.0');
        $this->assertEquals('3', $section->getReference(true));
    }

    /**
     * @test
     */
    public function getReferenceTrimmedWords()
    {
        $section = new Section('Styleguide Forms.Checkboxes.');
        $this->assertEquals('Forms.Checkboxes', $section->getReference(true));

        $section = new Section('Styleguide Forms - Special Checkboxes -');
        $this->assertEquals('Forms - Special Checkboxes', $section->getReference(true));
    }

    /**
     * @test
     */
    public function trimReference()
    {
        $this->assertEquals('1.1.1', Section::trimReference('1.1.1'));
        $this->assertEquals('1.1.1', Section::trimReference('1.1.1.'));
        $this->assertEquals('1.1.1', Section::trimReference('1.1.1.0'));
        $this->assertEquals('1.1.1', Section::trimReference('1.1.1.0.'));
        $this->assertEquals('1.1.1', Section::trimReference('1.1.1.00000000'));
        $this->assertEquals('1.1.1', Section::trimReference('1.1.1.00000000.'));
        $this->assertEquals('1.1.1', Section::trimReference('1.1.1.0.0.0.0'));
        $this->assertEquals('1.1.1', Section::trimReference('1.1.1.0.00.000.0000'));
        $this->assertEquals('1.0.1.1.0.00.1.0.10000.10', Section::trimReference('1.0.1.1.0.00.1.0.10000.10'));
        $this->assertEquals('1.0.1.1.0.00.1.0.10000.10', Section::trimReference('1.0.1.1.0.00.1.0.10000.10.00'));
    }

    /**
     * @test
     */
    public function trimReferenceWords()
    {
        $this->assertEquals('Forms.Checkboxes', Section::trimReference('Forms.Checkboxes'));
        $this->assertEquals('Forms.Checkboxes', Section::trimReference('Forms.Checkboxes.'));
        $this->assertEquals('Forms - Special Checkboxes', Section::trimReference('Forms - Special Checkboxes'));
        $this->assertEquals('Forms - Special Checkboxes', Section::trimReference('Forms - Special Checkboxes -'));
    }

    /**
     * @test
     */
    public function normalizeReference()
    {
        $this->assertEquals('1.0', Section::normalizeReference('1.0'));
        $this->assertEquals('1.0.', Section::normalizeReference('1.0.'));
        $this->assertEquals('1.0', Section::normalizeReference('1-0'));
        $this->assertEquals('1.0', Section::normalizeReference('1 - 0'));
    }

    /**
     * @test
     */
    public function normalizeReferenceWords()
    {
        $this->assertEquals('Forms', Section::normalizeReference('Forms'));
        $this->assertEquals('Forms.Checkboxes', Section::normalizeReference('Forms.Checkboxes'));
        $this->assertEquals('Forms.Checkboxes.', Section::normalizeReference('Forms.Checkboxes -'));
        $this->assertEquals('Forms.Checkboxes', Section::normalizeReference('Forms - Checkboxes'));
        $this->assertEquals('Forms.Checkboxes.Special', Section::normalizeReference('Forms - Checkboxes-Special'));
    }

    /**
     * @test
     */
    public function belongsToReference()
    {
        $this->assertTrue(self::$section->belongsToReference('2'));
        $this->assertTrue(self::$section->belongsToReference('2.1'));
        $this->assertTrue(self::$section->belongsToReference('2.1.1'));
        $this->assertTrue(self::$section->belongsToReference('2.1.1.0'));
        $this->assertTrue(self::$section->belongsToReference('2.1.1.0.0'));
        $this->assertTrue(self::$section->belongsToReference('2.1.1.0.0.'));

        $this->assertFalse(self::$section->belongsToReference('2.1.1.1'));
        $this->assertFalse(self::$section->belongsToReference('2.1.2'));
        $this->assertFalse(self::$section->belongsToReference('2.2.1'));
        $this->assertFalse(self::$section->belongsToReference('3'));
        $this->assertFalse(self::$section->belongsToReference('1.1'));

        $commentText = <<<comment
# Section test

Styleguide 20.
comment;

        $section20 = new Section($commentText);

        $this->assertFalse($section20->belongsToReference('2'));
        $this->assertTrue($section20->belongsToReference('20'));
        $this->assertFalse($section20->belongsToReference('200'));
    }

    /**
     * @test
     */
    public function belongsToReferenceWords()
    {
        $section = new Section('Styleguide Forms.Buttons.Actions.');
        $this->assertTrue($section->belongsToReference('Forms'));
        $this->assertTrue($section->belongsToReference('Forms.Buttons'));
        $this->assertTrue($section->belongsToReference('Forms.Buttons.Actions'));
        $this->assertTrue($section->belongsToReference('Forms.Buttons.Actions.'));

        $this->assertFalse($section->belongsToReference('Tables'));
        $this->assertFalse($section->belongsToReference('Forms.Checkboxes'));
        $this->assertFalse($section->belongsToReference('Forms.Buttons.Links'));
        $this->assertFalse($section->belongsToReference('Forms.Buttons.Actions.Special'));
    }

    /**
     * @test
     */
    public function belongsToReferenceWordsCaseInsensitive()
    {
        $section = new Section('Styleguide Forms.Buttons.Actions.');
        $this->assertTrue($section->belongsToReference('forms'));
        $this->assertTrue($section->belongsToReference('forms.buttons'));
        $this->assertTrue($section->belongsToReference('forms.buttons.actions'));
        $this->assertTrue($section->belongsToReference('forms.buttons.actions.'));

        $this->assertFalse($section->belongsToReference('tables'));
        $this->assertFalse($section->belongsToReference('forms.checkboxes'));
        $this->assertFalse($section->belongsToReference('forms.buttons.links'));
        $this->assertFalse($section->belongsToReference('forms.buttons.actions.special'));
    }

    /**
     * @test
     */
    public function belongsToReferenceWordsDashed()
    {
        $section = new Section('Styleguide Forms - Buttons - Special Actions -');
        $this->assertTrue($section->belongsToReference('Forms'));
        $this->assertTrue($section->belongsToReference('Forms - Buttons'));
        $this->assertTrue($section->belongsToReference('Forms-Buttons'));
        $this->assertTrue($section->belongsToReference('Forms - Buttons - Special Actions'));
        $this->assertTrue($section->belongsToReference('Forms - Buttons - Special Actions -'));
        $this->assertTrue($section->belongsToReference('Forms-Buttons-Special Actions'));
        $this->assertTrue($section->belongsToReference('Forms-Buttons-Special Actions-'));
        $this->assertTrue($section->belongsToReference('Forms- Buttons - Special Actions-'));

        $this->assertFalse($section->belongsToReference('Tables'));
        $this->assertFalse($section->belongsToReference('Forms - Checkboxes'));
        $this->assertFalse($section->belongsToReference('Forms - Buttons - Links'));
        $this->assertFalse($section->belongsToReference('Forms - Buttons - Special Actions - Super Special'));
    }

    /**
     * @test
     */
    public function belongsToReferenceWordsDashedToDots()
    {
        $section = new Section('Styleguide Forms - Buttons - Special Actions -');
        $this->assertTrue($section->belongsToReference('Forms'));
        $this->assertTrue($section->belongsToReference('Forms.Buttons'));
        $this->assertTrue($section->belongsToReference('Forms.Buttons'));
        $this->assertTrue($section->belongsToReference('Forms.Buttons.Special Actions'));
        $this->assertTrue($section->belongsToReference('Forms.Buttons.Special Actions.'));
        $this->assertTrue($section->belongsToReference('Forms.Buttons.Special Actions'));
        $this->assertTrue($section->belongsToReference('Forms.Buttons.Special Actions.'));

        $this->assertFalse($section->belongsToReference('Tables'));
        $this->assertFalse($section->belongsToReference('Forms.Checkboxes'));
        $this->assertFalse($section->belongsToReference('Forms.Buttons.Links'));
        $this->assertFalse($section->belongsToReference('Forms.Buttons.Special Actions.Super Special'));
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
    public function getDepthWords()
    {
        $section = new Section('Styleguide Forms.Buttons.Actions.');
        $this->assertEquals(2, $section->getDepth());

        $section = new Section('Styleguide Forms - Buttons - Actions -');
        $this->assertEquals(2, $section->getDepth());
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
    public function calcDepthWords()
    {
        $this->assertEquals(0, Section::calcDepth('Forms'));
        $this->assertEquals(0, Section::calcDepth('Forms.'));
        $this->assertEquals(0, Section::calcDepth('Forms -'));
        $this->assertEquals(1, Section::calcDepth('Forms.Buttons'));
        $this->assertEquals(1, Section::calcDepth('Forms - Buttons'));
        $this->assertEquals(2, Section::calcDepth('Forms.Buttons.Actions'));
        $this->assertEquals(2, Section::calcDepth('Forms - Buttons - Special Actions'));
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
    public function getDepthScoreWords()
    {
        $section = new Section('//Styleguide Forms.Checkboxes');
        $this->assertEquals(null, $section->getDepthScore());
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
    public function calcDepthScoreWords()
    {
        $this->assertEquals(null, Section::calcDepthScore('1.Forms'));
        $this->assertEquals(null, Section::calcDepthScore('Forms'));
        $this->assertEquals(null, Section::calcDepthScore('Forms.Checkboxes'));
        $this->assertEquals(null, Section::calcDepthScore('Forms - Special Checkboxes'));
    }

    /**
     * @test
     */
    public function depthSort()
    {
        $sections = array(
            '2' => new Section('Styleguide 2'),
            '3.2.1' => new Section('Styleguide 3.2.1'),
            '3.1' => new Section('Styleguide 3.1'),
            '1.2' => new Section('Styleguide 1.2'),
            '1' => new Section('Styleguide 1'),
            '3.0.0' => new Section('Styleguide 3.0.0'),
            '2.1.2' => new Section('Styleguide 2.1.2'),
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
    public function depthSortWords()
    {
        $sections = array(
            'Tables' => new Section('Styleguide Tables'),
            'Forms.Buttons.Actions' => new Section('Styleguide Forms.Buttons.Actions'),
            'Forms.Checkboxes' => new Section('Styleguide Forms.Checkboxes'),
            'Menus.Dropdown' => new Section('Styleguide Menus.Dropdown'),
            'Menus' => new Section('Styleguide Menus'),
            'Forms.' => new Section('Styleguide Forms.'),
            'Forms.Buttons' => new Section('Styleguide Forms.Buttons'),
            'Tables.Body.Alt' => new Section('Styleguide Tables.Body.Alt'),
        );

        uasort($sections, '\Scan\Kss\Section::depthSort');

        $keys = array_keys($sections);
        $expectedKeys = array(
            'Forms.',
            'Menus',
            'Tables',
            'Forms.Buttons',
            'Forms.Checkboxes',
            'Menus.Dropdown',
            'Forms.Buttons.Actions',
            'Tables.Body.Alt'
        );
        $this->assertEquals($expectedKeys, $keys);
    }

    /**
     * @test
     */
    public function depthSortWordsDashed()
    {
        $sections = array(
            'Tables' => new Section('Styleguide Tables'),
            'Forms - Buttons - Actions' => new Section('Styleguide Forms - Buttons -Actions'),
            'Forms - Checkboxes' => new Section('Styleguide Forms - Checkboxes'),
            'Menus - Dropdown' => new Section('Styleguide Menus - Dropdown'),
            'Menus' => new Section('Styleguide Menus'),
            'Forms -' => new Section('Styleguide Forms -'),
            'Forms - Buttons' => new Section('Styleguide Forms - Buttons'),
            'Tables - Body - Alt' => new Section('Styleguide Tables - Body - Alt'),
        );

        uasort($sections, '\Scan\Kss\Section::depthSort');

        $keys = array_keys($sections);
        $expectedKeys = array(
            'Forms -',
            'Menus',
            'Tables',
            'Forms - Buttons',
            'Forms - Checkboxes',
            'Menus - Dropdown',
            'Forms - Buttons - Actions',
            'Tables - Body - Alt'
        );
        $this->assertEquals($expectedKeys, $keys);
    }

    /**
     * @test
     */
    public function depthSortMixed()
    {
        $sections = array(
            '2' => new Section('Styleguide 2'),
            '3.2.1' => new Section('Styleguide 3.2.1'),
            '3.1' => new Section('Styleguide 3.1'),
            '1.2' => new Section('Styleguide 1.2'),
            '1' => new Section('Styleguide 1'),
            '3.0.0' => new Section('Styleguide 3.0.0'),
            '2.1.2' => new Section('Styleguide 2.1.2'),
            'Tables' => new Section('Styleguide Tables'),
            'Forms.Buttons.Actions' => new Section('Styleguide Forms.Buttons.Actions'),
            'Forms - Checkboxes' => new Section('Styleguide Forms - Checkboxes'),
            'Menus.Dropdown' => new Section('Styleguide Menus.Dropdown'),
            'Menus' => new Section('Styleguide Menus'),
            'Forms-' => new Section('Styleguide Forms.'),
            'Forms.Buttons' => new Section('Styleguide Forms.Buttons'),
            'Tables - Body - Alt' => new Section('Styleguide Tables - Body - Alt'),
            'Tables.Body.Main' => new Section('Styleguide Tables.Body.Main'),
        );

        uasort($sections, '\Scan\Kss\Section::depthSort');

        $keys = array_keys($sections);
        $expectedKeys = array(
            '1',
            '2',
            '3.0.0',
            'Forms-',
            'Menus',
            'Tables',
            '1.2',
            '3.1',
            'Forms.Buttons',
            'Forms - Checkboxes',
            'Menus.Dropdown',
            '2.1.2',
            '3.2.1',
            'Forms.Buttons.Actions',
            'Tables - Body - Alt',
            'Tables.Body.Main',
        );
        $this->assertEquals($expectedKeys, $keys);
    }

    /**
     * @test
     */
    public function depthScoreSort()
    {
        $sections = array(
            '2' => new Section('Styleguide 2'),
            '3.2.1' => new Section('Styleguide 3.2.1'),
            '3.1' => new Section('Styleguide 3.1'),
            '1.2' => new Section('Styleguide 1.2'),
            '1' => new Section('Styleguide 1'),
            '3.0.0' => new Section('Styleguide 3.0.0'),
            '2.1.2' => new Section('Styleguide 2.1.2'),
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

    /**
     * @test
     */
    public function alphaDepthScoreSort()
    {
        $sections = array(
            '2' => new Section('Styleguide 2'),
            '3.2.1' => new Section('Styleguide 3.2.1'),
            '3.1' => new Section('Styleguide 3.1'),
            '1.2' => new Section('Styleguide 1.2'),
            '1' => new Section('Styleguide 1'),
            '3.0.0' => new Section('Styleguide 3.0.0'),
            '2.1.2' => new Section('Styleguide 2.1.2'),
            'Tables' => new Section('Styleguide Tables'),
            'Forms.Buttons.Actions' => new Section('Styleguide Forms.Buttons.Actions'),
            'Forms - Checkboxes' => new Section('Styleguide Forms - Checkboxes'),
            'Menus.Dropdown' => new Section('Styleguide Menus.Dropdown'),
            'Menus' => new Section('Styleguide Menus'),
            'Forms-' => new Section('Styleguide Forms.'),
            'Forms.Buttons' => new Section('Styleguide Forms.Buttons'),
            'Tables - Body - Alt' => new Section('Styleguide Tables - Body - Alt'),
            'Tables.Body.Main' => new Section('Styleguide Tables.Body.Main'),
        );

        uasort($sections, '\Scan\Kss\Section::alphaDepthScoreSort');

        $keys = array_keys($sections);
        $expectedKeys = array(
            '1',
            '1.2',
            '2',
            '2.1.2',
            '3.0.0',
            '3.1',
            '3.2.1',
            'Forms-',
            'Forms.Buttons',
            'Forms.Buttons.Actions',
            'Forms - Checkboxes',
            'Menus',
            'Menus.Dropdown',
            'Tables',
            'Tables - Body - Alt',
            'Tables.Body.Main',
        );
        $this->assertEquals($expectedKeys, $keys);
    }
}
