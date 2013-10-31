<?php

/*
 * Dokudokibundle
 */

namespace tests\Yuurei\Fixtures;

use Trismegiste\Yuurei\Persistence\Persistable;
use Trismegiste\Yuurei\Persistence\PersistableImpl;

class Simple implements Persistable
{

    use PersistableImpl;

    public $answer;

}