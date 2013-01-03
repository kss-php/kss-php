<?php

/**
 * Section
 *
 * A KSS Comment Block that represents a single section containing a description,
 * modifiers, and a section reference.
 */

namespace Scan\Bundle\KssBundle\Model;

class Section
{
    /**
     * The raw KSS Comment Block before it was chopped into pieces
     *
     * @var string
     */
    protected $rawComment = '';

    /**
     * The sections of the KSS Comment Block
     *
     * @var array
     */
    protected $commentSections = null;

    /**
     * The file where the KSS Comment Block came from
     *
     * @var \SplFileObject
     */
    protected $file = null;

    /**
     * The section reference identifier
     *
     * @var string
     */
    protected $section = null;

    /**
     * Creates a section with the KSS Comment Block and source file
     *
     * @param string $comment
     * @param \SplFileObject $file
     */
    public function __construct($comment = '', \SplFileObject $file = null)
    {
        $this->rawComment = $comment;
        $this->file = $file;
    }

    /**
     * Returns the comment block used when creating the section as an array of
     * paragraphs within the comment block
     *
     * @return array
     */
    public function getCommentSections()
    {
        if ($this->commentSections === null && $this->rawComment) {
            $this->commentSections = explode("\n\n", $this->rawComment);
        }

        return $this->commentSections;
    }

    /**
     * Returns the source filename for where the comment block was located
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->file->getFilename();
    }

    /**
     * Returns the reference number for the section
     *
     * @return string
     */
    public function getSection()
    {
        if ($this->section === null) {
            $sectionComment = $this->getSectionComment();
            $sectionComment = preg_replace('/\.$/', '', $sectionComment);

            if (preg_match('/Styleguide (\d\S+)/', $sectionComment, $matches)) {
                $this->section = $matches[1];
            }
        }

        return $this->section;
    }

    /**
     * Returns the description for the section
     *
     * @return string
     */
    public function getDescription()
    {
        $descriptionSections = array();

        foreach ($this->getCommentSections() as $commentSection) {
            // Anything that is not the section comment or modifiers comment
            // must be the description comment
            if ($commentSection != $this->getSectionComment()
                && $commentSection != $this->getModifiersComment()
            ) {
                $descriptionSections[] = $commentSection;
            }
        }

        return implode("\n\n", $descriptionSections);
    }

    /**
     * Returns the modifiers used in the section
     *
     * @return array
     */
    public function getModifiers()
    {
        $lastIndent = null;
        $modifiers = array();

        if ($modiferComment = $this->getModifiersComment()) {
            $commentLines = explode("\n", $modiferComment);
            foreach ($commentLines as $line) {
                if (empty($line)) {
                    continue;
                }

                preg_match('/^\s*/', $line, $matches);
                $indent = strlen($matches[0]);

                if ($lastIndent && $indent > $lastIndent) {
                    $modifier = end($modifiers);
                    $modifier->setDescription($modifier->getDescription() + trim($line));
                } else {
                    $lineParts = explode(' - ', $line);
                    $modifiers[] = new Modifier(trim($lineParts[0]), trim($lineParts[1]));
                }
            }
        }

        return $modifiers;
    }

    /**
     * Gets the part of the KSS Comment Block that contains the section reference
     *
     * @return string
     */
    protected function getSectionComment()
    {
        $sectionComment = null;

        foreach ($this->getCommentSections() as $commentSection) {
            // Identify it by the Styleguide 1.2.3. pattern
            if (preg_match('/Styleguide \d/i', $commentSection)) {
                $sectionComment = $commentSection;
                break;
            }
        }

        return $sectionComment;
    }

    /**
     * Returns the part of the KSS Comment Block that contains the modifiers
     *
     * @return string
     */
    protected function getModifiersComment()
    {
        $modifiersComment = null;

        foreach ($this->getCommentSections() as $commentSection) {
            // Assume that the modifiers section starts with either a class or a
            // pseudo class.
            if (preg_match('/^\s*(?:\.|:)/', $commentSection)) {
                $modifiersComment = $commentSection;
                break;
            }
        }

        return $modifiersComment;
    }
}
