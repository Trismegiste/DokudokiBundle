<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Strategy;

use Trismegiste\DokudokiBundle\Transform\Factory;

/**
 * AbstractMapper is a ...
 *
 * @author florent
 */
abstract class AbstractMapper implements Mapping
{

    protected $context;

    public function __construct(Factory $ctx)
    {
        $this->context = $ctx;
    }

}