<?php

/*
 * Dokudokibundle
 */

namespace tests\Fixtures;

use Trismegiste\DokudokiBundle\Persistence\Persistable;
use Trismegiste\DokudokiBundle\Transform\Skippable;

/**
 * NonSense is ...
 *
 * @author flo
 */
class NonSense implements Persistable, Skippable
{

    public function getId()
    {
        
    }

    public function setId(\MongoId $od)
    {
        
    }

}