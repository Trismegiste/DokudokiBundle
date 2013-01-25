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
class InternalContent implements DynamicType, \IteratorAggregate
{

    private $property;

    /**
     * Construct the tree.
     *  A class type is mandatory because of database indexing
     *
     * @param array|string $data an array of tree structure with a classKey at first level or a classKey
     */
    public function __construct($data)
    {
        if (is_array($data) && array_key_exists(self::classKey, $data)) {
            $this->property = $data;
        } elseif (is_string($data)) {
            $this->property[self::classKey] = $data;
        } else {
            throw new \LogicException("No class type defined for DynamicType");
        }
    }

    /**
     * Set a property for this object
     *
     * @param string $propName  the property's name
     * @param mixed $arg the value of the property in a one parameter array
     *
     * @throws \InvalidArgumentException If the param is not unique
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

    /**
     * Returns the classname for this object
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->property[self::classKey];
    }

    public function getIterator()
    {
        return new \RecursiveArrayIterator($this->property);
    }

    public function getUnTyped()
    {
        $iter = new \RecursiveArrayIterator($this->property);
        return $this->recursiveUnTyping($iter);
    }

    /**
     * Internal recursive untyping
     *
     * @param \RecursiveArrayIterator $iter
     *
     * @return array
     */
    protected function recursiveUnTyping(\RecursiveArrayIterator $iter)
    {
        $flat = array();
        foreach ($iter as $key => $val) {
            if ($val instanceof DynamicType) {
                $flat[$key] = $val->getUnTyped();
            } else {
                if (is_array($val)) {
                    $flat[$key] = $this->recursiveUnTyping($iter->getChildren());
                } else {
                    $flat[$key] = $val;
                }
            }
        }

        return $flat;
    }

}