<?php

namespace Trismegiste\Yuurei\Persistence;

use Trismegiste\Yuurei\Transform\TransformerInterface;

/**
 * Repository of mongo document
 *
 * @author flo
 */
class Repository implements RepositoryInterface
{

    protected $collection;
    protected $factory;

    public function __construct(\MongoCollection $coll, TransformerInterface $fac)
    {
        $this->collection = $coll;
        $this->factory = $fac;
    }

    /**
     * {@inheritDoc}
     */
    public function persist(Persistable $doc)
    {
        $struc = $this->factory->desegregate($doc);
        if (array_key_exists('id', $struc)) {
            unset($struc['id']);
        }
        if (!is_null($doc->getId())) {
            $struc['_id'] = $doc->getId();
        }
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

        return $this->createFromDb($struc);
    }

    /**
     * {@inheritDoc}
     */
    public function createFromDb(array $struc)
    {
        if (!isset($struc['_id'])) {
            throw new \InvalidArgumentException("The database entry does not have a primary key");
        }
        $id = $struc['_id'];
        unset($struc['_id']);
        $obj = $this->factory->create($struc);
        if ($obj instanceof Persistable) {
            $obj->setId($id);
        } else {
            throw new \DomainException(get_class($obj) . ' is not Persistable');
        }

        return $obj;
    }

}
