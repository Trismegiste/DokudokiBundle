<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

use Trismegiste\DokudokiBundle\Utils\ReflectionClassBC;

/**
 * Description of Mediator
 *
 * @author flo
 */
class Mediator implements RecursiveMapper
{

    protected $mappingColleague = array();

    public function registerType($name, Mapping $colleague)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('The key type is not a string');
        }

        $this->mappingColleague[$name] = $colleague;
    }

    /**
     * {@inheritDoc}
     */
    public function recursivDesegregate($obj)
    {
        $stratKey = gettype($obj);

        if (array_key_exists($stratKey, $this->mappingColleague)) {
            return $this->mappingColleague[$stratKey]->mapToDb($obj);
        } else {
            throw new \DomainException("Unsupported type $stratKey");
        }
    }

    /**
     * {@inheritDoc}
     */
    public function recursivCreate($param)
    {
        $stratKey = gettype($param);

        if (array_key_exists($stratKey, $this->mappingColleague)) {
            return $this->mappingColleague[$stratKey]->mapFromDb($param);
        } else {
            throw new \DomainException("Unsupported type $stratKey");
        }
    }

}
