<?php

/*
 * DokudokiBundle
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
        $stopwatch = \microtime(true);
        $found = parent::findByPk($id);
        $delta = \microtime(true) - $stopwatch;

        $this->logger->log('findByPk', ['_id' => $id], $delta);

        return $found;
    }

    /**
     * {@inheritDoc}
     */
    public function persist(Persistable $doc)
    {
        $type = is_null($doc->getId()) ? 'create' : 'update';

        $stopwatch = \microtime(true);
        parent::persist($doc);
        $delta = \microtime(true) - $stopwatch;

        $this->logger->log($type, [
            'fqcn' => get_class($doc),
            '_id' => $doc->getId()
                ], $delta);
    }

    /**
     * {@inheritDoc}
     */
    public function find(array $query = array())
    {
        $stopwatch = \microtime(true);
        $found = parent::find($query);
        $delta = \microtime(true) - $stopwatch;

        $this->logger->log('find', $query, $delta);

        return $found;
    }

    /**
     * {@inheritDoc}
     */
    public function findOne(array $query = array())
    {
        $stopwatch = \microtime(true);
        $found = parent::findOne($query);
        $delta = \microtime(true) - $stopwatch;

        $this->logger->log('findOne', $query, $delta);

        return $found;
    }

    /**
     * {@inheritDoc}
     */
    public function getCursor(array $query = array(), array $fields = array())
    {
        $stopwatch = \microtime(true);
        $found = parent::getCursor($query, $fields);
        $delta = \microtime(true) - $stopwatch;

        $this->logger->log('find', $query, $delta);

        return $found;
    }

    /**
     * {@inheritDoc}
     */
    public function batchPersist(array $batch)
    {
        $stopwatch = \microtime(true);
        parent::batchPersist($batch);
        $delta = \microtime(true) - $stopwatch;

        $this->logger->log('batch', ['count' => count($batch)], $delta);
    }

}
