<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Facade;

use Trismegiste\DokudokiBundle\Transform\Mediator\RecursiveMapper;

/**
 * Provider is a concrete & highly coupled provider for Repository
 *
 * @author flo
 */
class Provider extends AbstractProvider
{

    protected function createDirector()
    {
        return new \Trismegiste\DokudokiBundle\Transform\Delegation\MappingDirector();
    }

    protected function createTransformer(RecursiveMapper $mapper)
    {
        return new \Trismegiste\DokudokiBundle\Transform\Transformer($mapper);
    }

}