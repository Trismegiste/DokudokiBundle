<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * Design Pattern : Mediator
 * Component : Mediator
 *
 * Mediator is the delegation of Factory to recursively traverse
 * objects and arrays. Responsible for maintaining a list of Colleague (the
 * mappers converting object, array, scalar, MongoDate, etc. )
 *
 * @author flo
 */
class Mediator extends AbstractMediator
{

    /**
     * {@inheritDoc}
     */
    public function recursivDesegregate($obj)
    {
        $strat = null;
        foreach ($this->mappingColleague as $map) {
            if ($map->isResponsibleToDb($obj)) {
                $strat = $map;
            }
        }

        return $strat->mapToDb($obj);
    }

    /**
     * {@inheritDoc}
     */
    public function recursivCreate($param)
    {
        $strat = null;
        foreach ($this->mappingColleague as $map) {
            if ($map->isResponsibleFromDb($param)) {
                $strat = $map;
            }
        }

        return $strat->mapFromDb($param);
    }

}
