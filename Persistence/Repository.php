<?php

namespace Trismegiste\DokudokiBundle\Persistence;

use Trismegiste\DokudokiBundle\Transform\Factory;

/**
 * Repository of Document
 *
 * @author flo
 */
class Repository implements RepositoryInterface
{

    protected $collection;
    protected $factory;

    public function __construct(\MongoCollection $coll, Factory $fac)
    {
        $this->collection = $coll;
        $this->factory = $fac;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($doc)
    {
        $struc = $this->factory->desegregation($doc);
        if (array_key_exists('id', $struc)) {
            $struc['_id'] = $struc['id'];
            unset($struc['id']);
        }
        $struc['_timestamp'] = time();

        $this->collection->save($struc);
        $doc->setId($struc['_id']);
    }

    /**
     * {@inheritDoc}
     */
    public function findByPk($pk)
    {
        $id = new \MongoId($pk);
        $struc = $this->collection->findOne(array('_id' => $id));
        if (is_null($struc)) {
            throw new NotFoundException($pk);
        }
        $struc['id'] = $struc['_id'];
        unset($struc['_id'], $struc['_timestamp']);
        $obj = $this->factory->create($struc);

        return $obj;
    }

}
