<?php

/**
 * Modifier
 *
 * Object to represent the modifiers section of a KSS Comment Block
 */

namespace Scan\Bundle\KssBundle\Model;

class Modifier
{
    /**
     * Name of the modifier
     *
     * @var string
     */
    protected $name = '';

    /**
     * Description of the modifier
     *
     * @var string
     */
    protected $description = '';

    /**
     * Flag to show whether a modifier is done through extension
     *
     * @var boolean
     */
    protected $isExtender = false;

    /**
     * Creates a new modifier by adding a name and a description
     *
     * @param string $name
     * @param string $description
     */
    public function __construct($name, $description = '')
    {
        $this->setName($name);
        $this->setDescription($description);
    }

    /**
     * Returns the name of the modifier
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the modifier
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->isExtender = false;
        if (preg_match('/\s*@extender/i', $name)) {
            $name = preg_replace('/\s*@extender/i', '', $name);
            $this->isExtender = true;
        }
        $this->name = $name;
    }

    /**
     * Returns the description of the modifier
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description of the modifier
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Returns whether the modifier is applied by extension
     *
     * @return boolean
     */
    public function isExtender()
    {
        return $this->isExtender;
    }

    /**
     * Returns the class name for the modifier
     *
     * @return string
     */
    public function getClassName()
    {
        $name = str_replace('.', ' ', $this->name);
        $name = str_replace(':', ' pseudo-class-', $name);
        return trim($name);
    }
}
