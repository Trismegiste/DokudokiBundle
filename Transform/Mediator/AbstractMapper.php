<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * AbstractMapper is an abstract Colleague for the Mediator Pattern
 *
 * @author florent
 */
abstract class AbstractMapper implements Mapping
{

    protected $mediator;

    public function __construct(Mediator $ctx)
    {
        $this->mediator = $ctx;
    }

}