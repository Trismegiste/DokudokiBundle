<?php

/*
 * Dokudoki
 */

namespace Trismegiste\DokudokiBundle\Tests\Fixtures;

class Product
{

    private $title;
    protected $price = 0;

    public function __construct($tit, $pri)
    {
        $this->title = $tit;
        $this->price = $pri;
    }

}
