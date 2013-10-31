<?php

/*
 * Dokudokibundle
 */

namespace tests\Yuurei\Fixtures;

class CartPlus
{

    protected $row;

    public function __construct()
    {
        $this->row = new \SplObjectStorage();
    }

    public function addItem($qt, $pro)
    {
        $this->row[$pro] = $qt;
    }

}