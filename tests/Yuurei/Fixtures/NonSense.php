<?php

/*
 * Dokudokibundle
 */

namespace tests\Yuurei\Fixtures;

use Trismegiste\Yuurei\Persistence\Persistable;
use Trismegiste\Yuurei\Transform\Skippable;

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