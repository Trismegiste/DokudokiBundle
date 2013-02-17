<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Fixtures;

/**
 * Branch is a fixture class for migration tests
 */
class Branch extends Leaf
{

    protected $vertices;

    public function append(Leaf $vertex)
    {
        $this->vertices[] = $vertex;
    }

}