<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Persistence;

use Symfony\Component\HttpKernel\DataCollector\DataCollector as BaseCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * DataCollector collects data for repository
 */
class DataCollector extends BaseCollector implements Logger
{

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        // $this->data = array('mongodb' => $this->data);
    }

    public function getQueriesCount()
    {
        return count($this->data);
    }

    public function getName()
    {
        return 'mongodb';
    }

    public function log($access, array $detail)
    {
        $this->data[] = array('access' => $access, 'detail' => json_encode($detail));
    }

    public function getQueries()
    {
        return $this->data;
    }

}