<?php

/**
 * Parameters
 *
 * Object to represent the Parameter section of a KSS Comment Block
 */

namespace Scan\Kss;

class Parameter
{
    /**
     * Name of the parameter
     *
     * @var string
     */
    protected $name = '';

    /**
     * Description of the parameter
     *
     * @var string
     */
    protected $description = '';

    /**
     * Creates a new parameter by adding a name and a description
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
     * Returns the name of the parameter
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the parameter
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the description of the parameter
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description of the parameter
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}
