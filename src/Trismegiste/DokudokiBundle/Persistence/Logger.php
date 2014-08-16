<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Persistence;

/**
 * Stacks queries for DBAL
 */
interface Logger
{

    function log($access, array $detail, $timer);
}
