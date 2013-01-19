<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * A contract for a mapper
 * 
 * @author florent
 */
interface Mapping
{

    function mapToDb($var);

    function mapFromDb($var);
}
