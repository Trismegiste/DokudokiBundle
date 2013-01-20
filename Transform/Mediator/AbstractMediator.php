<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * Design Pattern : Mediator
 * Component : Mediator (abstract)
 * 
 * Responsible for maintaining a list of Colleague
 * 
 * @author flo
 */
abstract class AbstractMediator implements RecursiveMapper, TypeRegistry
{

    protected $mappingColleague = array();

    /**
     * {@inheritDoc}
     */
    public function registerType($name, Mapping $colleague)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('The key type is not a string');
        }

        $this->mappingColleague[$name] = $colleague;
    }

}
