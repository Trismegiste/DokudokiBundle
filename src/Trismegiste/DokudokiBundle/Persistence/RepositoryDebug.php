<?php

/*
 * GraphRpg
 */

namespace Trismegiste\DokudokiBundle\Persistence;

use Trismegiste\Yuurei\Persistence\Decorator;
use Trismegiste\Yuurei\Persistence\RepositoryInterface;
use Trismegiste\Yuurei\Persistence\Persistable;

/**
 * RepositoryDebug decorates a repository with logging behavior
 */
class RepositoryDebug extends Decorator
{

    protected $logger;

    public function __construct(RepositoryInterface $repo, Logger $log)
    {
        parent::__construct($repo);
        $this->logger = $log;
    }

    public function findByPk($id)
    {
        $this->logger->log('findOne', ['_id' => $id]);
        return parent::findByPk($id);
    }

    public function persist(Persistable $doc)
    {
        $this->logger->log('save', [
            'fqcn' => get_class($doc),
            'hash' => spl_object_hash($doc),
            '_id' => $doc->getId()
        ]);
        return parent::persist($doc);
    }

    public function find(array $query = array())
    {
        $this->logger->log('find', $query);
        return parent::find($query);
    }

    public function findOne(array $query = array())
    {
        $this->logger->log('findOne', $query);
        return parent::findOne($query);
    }

    public function getCursor(array $query = array(), array $fields = array())
    {
        $this->logger->log('find', $query);
        return parent::getCursor($query, $fields);
    }

}