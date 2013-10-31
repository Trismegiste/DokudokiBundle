<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\Yuurei\Facade;

use Trismegiste\Yuurei\Transform\Mediator\RecursiveMapper;

/**
 * Provider is a concrete & highly coupled facade for this bundle
 * It creates Repository 
 *
 * @author flo
 */
class Provider extends AbstractProvider
{

    protected function createDirector()
    {
        return new \Trismegiste\Yuurei\Transform\Delegation\MappingDirector();
    }

    protected function createTransformer(RecursiveMapper $mapper)
    {
        return new \Trismegiste\Yuurei\Transform\Transformer($mapper);
    }

}