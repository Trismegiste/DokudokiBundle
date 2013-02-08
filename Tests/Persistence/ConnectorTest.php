<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Persistence;

use Trismegiste\DokudokiBundle\Persistence\Connector;

/**
 * Description of ConnectorTest
 *
 * @author flo
 */
class ConnectorTest extends \PHPUnit_Framework_TestCase
{

    public function testCollection()
    {
        $param = array(
            'server' => 'mongo-s0.dev.hevea.lan:27017,mongo-s1.dev.hevea.lan:27017/?replicaSet=allopneus-dev',
      //      'server' => 'localhost:27017',
            'database' => 'dokudoki',
            'collection' => 'TestSuite'
        );
        $cnx = new Connector($param);
        $coll = $cnx->getCollection();
        $this->assertInstanceOf('\MongoCollection', $coll);

        return $coll;
    }

}
