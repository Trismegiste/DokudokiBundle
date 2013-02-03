<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * Design Pattern : Mediator
 * Component : Mediator
 *
 * Mediator is the delegation of Transformer to recursively traverse
 * objects and arrays. Responsible for maintaining a list of Colleague (the
 * mappers converting object, array, scalar, MongoDate, etc. )
 * 
 * Note : Y U NO use a Chain of Responsibilities ?
 * Well, that was my first try. It fails because :
 * 1. you need a Request embedding the RecursiveMapper and the object
 * 2. Some successors need additional configurations
 * 3. I only love CoR when it is written "new Succ1(new Succ2(new Succ3(new ...)))"
 * 4. It is a pain in the ass to debug, particulary with this recursive algorithm
 * 5. Managing exception becomes very tricky
 * 
 * For all this reasons, I distrust this design pattern in this case.
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
