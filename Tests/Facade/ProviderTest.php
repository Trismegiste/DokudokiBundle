<?php

namespace Trismegiste\DokudokiBundle\Tests\Facade;

use Trismegiste\DokudokiBundle\Facade\Provider;

/**
 * Test class for Provider.
 * Generated by PHPUnit on 2013-02-09 at 12:12:38.
 */
class ProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Provider
     */
    protected $object;

    protected function setUp()
    {
        $collection = $this->getMockBuilder('MongoCollection')
                ->disableOriginalConstructor()
                ->getMock();
        $this->object = new Provider($collection);
    }

    protected function tearDown()
    {
        unset($this->object);
    }

    /**
     * Test Repository
     */
    public function testCreateRepository()
    {
        $builder = $this->getMockForAbstractClass('Trismegiste\DokudokiBundle\Transform\Delegation\Stage\AbstractStage');
        $repo = $this->object->createRepository($builder);
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Persistence\Repository', $repo);
    }

}

