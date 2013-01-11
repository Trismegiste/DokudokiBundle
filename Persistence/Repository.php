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
        $obj = $this->factory->create($struc);

        return $obj;
    }

}
