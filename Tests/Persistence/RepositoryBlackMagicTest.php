<?php

/*
 * DokudokiBundle
 */

namespace tests\Persistence;

use Trismegiste\DokudokiBundle\Magic\Document;
use tests\Fixtures\MagicFixture;

/**
 * Test repository with BlackMagic stage
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

    public function getComplexObject()
    {
        $provider = new MagicFixture();
        return array(
            array($provider->getFullTreeObject(), $provider->getFullTreeFlat())
        );
    }

}
