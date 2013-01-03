<?php

/**
 * Parser
 *
 * Accepts an array of directories and parses them stylesheet files present in
 * them for KSS Comment Blocks
 */

namespace Scan\Bundle\KssBundle\Model;

use Symfony\Component\Finder\Finder;

class Parser
{
    /**
     * An array of the different KSS sections found in the parsed directories
     *
     * @var array
     */
    protected $sections = array();

    /**
     * Parses specified directories for KSS Comments and adds any valid KSS Sections
     * found.
     *
     * @param string|array $paths A string or array of the paths to scan for KSS
     *                            Comments
     */
    public function __construct($paths)
    {
        $finder = new Finder();
        // Only accept css, sass, scss, and less files.
        $finder->files()->name('/\.(css|sass|scss|less)$/')->in($paths);

        foreach ($finder as $fileInfo) {
            $file = new \splFileObject($fileInfo);
            $commentParser = new CommentParser($file);
            foreach ($commentParser->getBlocks() as $commentBlock) {
                if (self::isKssBlock($commentBlock)) {
                    $this->addSection($commentBlock, $file);
                }
            }
        }
    }

    /**
     * Adds a section to the Sections collection
     *
     * @param string $comment
     * @param \splFileObject $file
     */
    protected function addSection($comment, \splFileObject $file)
    {
        $section = new Section($comment, $file);
        $this->sections[$section->getSection()] = $section;
    }

    /**
     * Returns a Section object matching the requested reference. If reference
     * is not found, an empty Section object is returned instead
     *
     * @param string $reference
     *
     * @return Section
     */
    public function getSection($reference)
    {
        if (array_key_exists($reference, $this->sections)) {
            return $this->sections[$reference];
        }
        return new Section();
    }

    /**
     * Checks to see if a comment block is a KSS Comment block
     *
     * @param string $comment
     *
     * @return boolean
     */
    public static function isKssBlock($comment)
    {
        $commentLines = explode("\n\n", $comment);
        $lastLine = end($commentLines);
        return preg_match('/Styleguide \d/i', $lastLine);
    }
}
