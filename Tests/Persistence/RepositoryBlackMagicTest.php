<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Persistence;

use Trismegiste\DokudokiBundle\Magic\Document;
use Trismegiste\DokudokiBundle\Tests\Fixtures\MagicFixture;

/**
 * Test repository with Invocation stage
 *
 * @author flo
 */
class RepositoryBlackMagicTest extends RepositoryTestTemplate
{

    protected function createBuilder()
    {
        return new \Trismegiste\DokudokiBundle\Transform\Delegation\Stage\BlackMagic();
    }

    protected function getSimpleObject()
    {
        $obj = new Document('Saruman');
        $obj->setRing('none');
        return $obj;
    }

    protected function assertSimpleInsert(array $struc)
    {
        $this->assertEquals('none', $struc['ring']);
    }

    protected function editSimpleObject($obj)
    {
        $obj->setAddress('Orthanc');
    }

    protected function assertEditedObject($obj)
    {
        $this->assertEquals('Orthanc', $obj->getAddress());
    }

    protected function getComplexObject()
    {
        $provider = new MagicFixture();
        $obj = $provider->getFullTreeObject();
        return $obj;
    }

}
