<?php

/*
 * Dokudokibundle
 */

namespace tests\DokudokiBundle\Persistence;

use Trismegiste\DokudokiBundle\Persistence\MultipleClassDecorator;

/**
 * OneClassDecoratorTest tests the OneClassDecorator
 */
class MultipleClassDecoratorTest extends DecoratorTestTemplate
{

    protected function createRepository()
    {
        return new MultipleClassDecorator($this->mockRepo, '-class', ['stdClass']);
    }

    protected function getFilter()
    {
        return ['-class' => ['$in' => ['stdClass']]];
    }

}