<?php

/*
 * Dokudokibundle
 */

namespace tests\Fixtures;

use Trismegiste\DokudokiBundle\Persistence\Persistable;
use Trismegiste\DokudokiBundle\Persistence\PersistableImpl;

class Simple implements Persistable
{

    use PersistableImpl;

    public $answer;

}