<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Magic;

/**
 * InternalContent is a container for tree structure
 *
 * @author florent
 */
class InternalContent implements DynamicType
{

    private $property;

    /**
     * Construct the tree.
     *  A class type is mandatory because of database indexing
     *
     * @param string $data a class alias
     */
    public function __construct($data)
    {
        if (is_string($data)) {
            $this->property[self::classKey] = $data;
        } else {
            throw new \LogicException("No class type defined for DynamicType");
        }
    }

    /**
     * Set a property for this object
     *
     * @param string $propName  the property's name
     * @param mixed $arg the value of the property in a one-value array
     *
     * @throws \InvalidArgumentException If the value is not unique
     */
    protected function setter($propName, $arg)
    {
        if (count($arg) !== 1) {
            throw new \InvalidArgumentException("Setting $propName accepts only one argument");
        }

        $this->property[$propName] = $arg[0];
    }

    /**
     * Get a property of this object
     *
     * @param string $propName the property's name
     * @param array $arg an empty array (because I want to test the full signature of this method)
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException Bad argument
     * @throws \DomainException Non-existing property
     */
    protected function getter($propName, $arg)
    {
        if (count($arg) !== 0) {
            throw new \InvalidArgumentException("Getting $propName accepts no argument");
        }

        if (!array_key_exists($propName, $this->property)) {
            throw new \DomainException("Property $propName is unknown");
        }

        return $this->property[$propName];
    }

    protected function has($propName)
    {
        return array_key_exists($propName, $this->property);
    }

    /**
     * {@inheritDoc}
     */
    public function getClassName()
    {
        return $this->property[self::classKey];
    }

    /**
     * Gets a recursive iterator on properties
     *
     * @return \RecursiveArrayIterator
     */
    public function getIterator()
    {
        return new \RecursiveArrayIterator($this->property);
    }

}