<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Persistence;

/**
 * PersistableImplTest tests the trait for Persistable
 */
class PersistableImplTest extends \PHPUnit_Framework_TestCase
{

    protected $rootEntity;

    protected function setUp()
    {
        $this->rootEntity = $this->getObjectForTrait('Trismegiste\DokudokiBundle\Persistence\PersistableImpl');
    }

    public function testGetterSetter()
    {
        $pk = $this->getMock('MongoId');
        $this->rootEntity->setId($pk);
        $this->assertEquals($pk, $this->rootEntity->getId());
    }

    public function testNotPersistable()
    {
        // to be a real root entity and be persistable, an object MUST implements Persistable
        // because I value strong typing
        $this->assertNotInstanceOf('Trismegiste\DokudokiBundle\Persistence\Persistable', $this->rootEntity);
    }

}