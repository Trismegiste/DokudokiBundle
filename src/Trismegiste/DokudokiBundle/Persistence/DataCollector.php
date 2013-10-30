<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Persistence;

use Symfony\Component\HttpKernel\DataCollector\DataCollector as BaseCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * DataCollector is a ...
 *
 * @author florent
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

    public function log($access, $detail)
    {
        $this->data[] = array('access' => $access, 'detail' => $detail);
    }

    public function getQueries()
    {
        return $this->data;
    }

}