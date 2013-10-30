<?php

/*
 * DokudokiBundle
 */

namespace tests\Persistence;

use Trismegiste\DokudokiBundle\Persistence\Repository;
use tests\Fixtures;

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
        $obj = new \tests\Fixtures\Simple();
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

    public function getComplexObject()
    {
        $obj = new \tests\Fixtures\InvocStress();
        $tmp = new \DateTime('2013-02-14 08:20:08');
        $dump = array(
            '-fqcn' => 'tests\\Fixtures\\InvocStress',
            'floatVar' => 3.14159265,
            'binaryVar' => new \MongoBinData('299792458', 2),
            'dateVar' => new \MongoDate($tmp->getTimestamp(), 0),
            'stringVar' => 'H Psi = E . Psi',
            'intVar' => 73,
            'objVar' => array(
                '-fqcn' => 'tests\\Fixtures\\Simple',
                'id' => NULL,
                'answer' => 'eureka',
            ),
            'answer' => 42,
            'vector' => array(1, 2, 3)
        );
        return array(array($obj, $dump));
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage A root entity cannot be Skippable
     */
    public function testSkippableRoot()
    {
        $obj = new Fixtures\NonSense();
        $this->repo->persist($obj);
    }

}
