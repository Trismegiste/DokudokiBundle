<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Persistence;

use Trismegiste\DokudokiBundle\Persistence\Repository;
use Trismegiste\DokudokiBundle\Facade\Provider;

/**
 * Test repository with Invocation stage
 *
 * @author flo
 */
class RepositoryInvocationTest extends RepositoryTestTemplate
{

    protected function createBuilder()
    {
        return new \Trismegiste\DokudokiBundle\Transform\Delegation\Stage\Invocation();
    }

    protected function getSimpleObject()
    {
        $obj = new \Trismegiste\DokudokiBundle\Tests\Fixtures\Simple();
        $obj->answer = 42;
        return $obj;
    }

    protected function assertSimpleInsert(array $struc)
    {
        $this->assertEquals(42, $struc['answer']);
    }

    protected function editSimpleObject($obj)
    {
        $obj->answer = 73;
    }

    protected function assertEditedObject($obj)
    {
        $this->assertEquals(73, $obj->answer);
    }

    protected function getComplexObject()
    {
        return new \Trismegiste\DokudokiBundle\Tests\Fixtures\InvocStress();
    }

    public function testCycle()
    {
        $obj = $this->getComplexObject();
        $this->assertNull($obj->getId());
        $this->repo->persist($obj);
        $found = $this->repo->findByPk($obj->getId());
        $this->assertEquals($obj, $found);
    }

}
