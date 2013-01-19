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

    /**
     * Recursion for desegregation (or untyping/casting to array)
     *
     * @param mixed $obj
     * @return mixed
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
     * Recursion for restoration
     *
     * @param mixed $param
     * @return mixed
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
