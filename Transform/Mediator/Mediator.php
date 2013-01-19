<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * Description of Mediator
 *
 * @author flo
 */
class Mediator
{

    protected $mappingColleague = array();

    public function registerType($name, Mapping $colleague)
    {
        if (is_string($name)) {
            $name = array($name);
        }
        foreach ($name as $key) {
            $this->mappingColleague[$key] = $colleague;
        }
    }

    public function recursivDesegregate($obj)
    {
        $stratKey = gettype($obj);

        if (array_key_exists($stratKey, $this->mappingColleague)) {
            return $this->mappingColleague[$stratKey]->mapToDb($obj);
        } else {
            throw new \DomainException('Non supported type');
        }
    }

}
