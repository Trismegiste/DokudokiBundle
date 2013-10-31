<?php

/*
 * Dokudokibundle
 */

namespace tests\Yuurei\Fixtures;

use Trismegiste\Yuurei\Persistence\Persistable;

/**
 * Trunk is a fixture class for migration tests
 */
class Trunk extends Branch implements Persistable
{

    use \Trismegiste\Yuurei\Persistence\PersistableImpl;
}