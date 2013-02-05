<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Delegation\Stage;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;
use Trismegiste\DokudokiBundle\Transform\Mediator\TypeRegistry;

/**
 * Design Pattern : Builder
 * Component : Builder (concrete) 
 * 
 * A builder to automagically store object in database by using the FQCN 
 * for class keys. Zero configuration needed.
 * Only magic for storing the type of object.
 * Warning : Can be very messy in queries
 * Usefull : when you have a model and don't want to alias each class. Also
 * usefull for other apps, you don't need to repeat the alias map, only the
 * model classes are needed.
 * Fail when a class not exists.
 *
 * @author flo
 */
class Invocation extends AbstractStage
{

    public function createObject(TypeRegistry $algo)
    {
        new Colleague\MapSkippable($algo);
        new Colleague\MapObject($algo);
    }

}