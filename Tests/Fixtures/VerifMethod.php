<?php

/*
 * Dokudoki
 */

namespace Trismegiste\DokudokiBundle\Tests\Fixtures;

class VerifMethod
{

    protected $vat = 19.6;
    protected $price;

    public function __construct($x)
    {
        $this->price = $x;
    }

    public function getTotal()
    {
        return $this->price * (1 + $this->vat / 100);
    }

}
