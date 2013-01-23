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

    protected $mappingColleague = array(TypeRegistry::FROM_DB => array(), TypeRegistry::TO_DB => array());

    /**
     * {@inheritDoc}
     */
    public function registerType($way, $name, Mapping $colleague)
    {
        if (!in_array($way, array(self::FROM_DB, self::TO_DB))) {
            throw new \InvalidArgumentException("The direction is unknown (must be TypeRegistry::FROM_DB or TypeRegistry::TO_DB");
        }

        if (!is_string($name)) {
            throw new \InvalidArgumentException('The key type is not a string');
        }

        $this->mappingColleague[$way][$name] = $colleague;
    }

}
