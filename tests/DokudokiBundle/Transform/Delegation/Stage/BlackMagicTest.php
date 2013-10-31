<?php

/*
 * DokudokiBundle
 */

namespace tests\DokudokiBundle\Transform\Delegation\Stage;

use Trismegiste\DokudokiBundle\Transform\Delegation\Stage\BlackMagic;
use Trismegiste\DokudokiBundle\Magic\Document;
use tests\DokudokiBundle\Fixtures\MagicFixture;
use tests\Yuurei\Transform\Delegation\Stage\AbstractStageTest;

/**
 * test for Mediator created by BlackMagic builder
 *
 * @author flo
 */
class BlackMagicTest extends AbstractStageTest
{

    protected function createBuilder()
    {
        return new BlackMagic();
    }

    protected function getSampleTree()
    {
        $couple = array();
        $obj = new Document('root');
        $obj->setTrunk(new Document('trunk'));
        $obj->getTrunk()->setBranch(new Document('branch'));
        $obj->getTrunk()->getBranch()->setLeaf('maple');
        $db = array(
            Document::classKey => 'root',
            'trunk' => array(
                Document::classKey => 'trunk',
                'branch' => array(
                    Document::classKey => 'branch',
                    'leaf' => 'maple'
                )
            )
        );
        $provider = new MagicFixture();
        $couple[] = array($provider->getFullTreeObject(), $provider->getFullTreeFlat());
        $couple[] = array($obj, $db);
        return $couple;
    }

    public function getDataToDb()
    {
        $data = parent::getDataToDb();
        return array_merge($data, $this->getSampleTree());
    }

    public function getDataFromDb()
    {
        $data = parent::getDataFromDb();
        return array_merge($data, $this->getSampleTree());
    }

}
