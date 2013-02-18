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

    protected $left;
    protected $right;

    public function setRight(Leaf $vertex)
    {
        $this->right = $vertex;
    }

    public function setLeft(Leaf $vertex)
    {
        $this->left = $vertex;
    }

}