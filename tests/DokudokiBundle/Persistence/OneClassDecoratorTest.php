<?php

/*
 * Dokudokibundle
 */

namespace tests\DokudokiBundle\Persistence;

use Trismegiste\DokudokiBundle\Persistence\OneClassDecorator;

/**
 * OneClassDecoratorTest tests the OneClassDecorator
 */
class OneClassDecoratorTest extends DecoratorTestTemplate
{

    protected function createRepository()
    {
        return new OneClassDecorator($this->mockRepo, '-class', 'stdClass');
    }

    protected function getFilter()
    {
        return ['-class' => 'stdClass'];
    }

}