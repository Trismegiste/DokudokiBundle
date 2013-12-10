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

    /**
     * {@inheritDoc}
     */
    public function findByPk($id)
    {
        $this->logger->log('findByPk', ['_id' => $id]);
        return parent::findByPk($id);
    }

    /**
     * {@inheritDoc}
     */
    public function persist(Persistable $doc)
    {
        $type = is_null($doc->getId()) ? 'create' : 'update';

        parent::persist($doc);

        $this->logger->log($type, [
            'fqcn' => get_class($doc),
            '_id' => $doc->getId()
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function find(array $query = array())
    {
        $this->logger->log('find', $query);
        return parent::find($query);
    }

    /**
     * {@inheritDoc}
     */
    public function findOne(array $query = array())
    {
        $this->logger->log('findOne', $query);
        return parent::findOne($query);
    }

    /**
     * {@inheritDoc}
     */
    public function getCursor(array $query = array(), array $fields = array())
    {
        $this->logger->log('find', $query);
        return parent::getCursor($query, $fields);
    }

}