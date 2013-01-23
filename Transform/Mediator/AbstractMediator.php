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

    protected $mappingColleague = array(TypeRegistry::CREATE => array(), TypeRegistry::DESEGREGATE => array());

    /**
     * {@inheritDoc}
     */
    public function registerType($way, $name, Mapping $colleague)
    {
        if (!in_array($way, array(self::CREATE, self::DESEGREGATE))) {
            throw new \InvalidArgumentException("The direction is unknown (must be TypeRegistry::DESEGREGATE or TypeRegistry::CREATE");
        }

        if (!is_string($name)) {
            throw new \InvalidArgumentException('The key type is not a string');
        }

        $this->mappingColleague[$way][$name] = $colleague;
    }

}
