<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 *
 * @author florent
 */
interface Mapping
{

    function mapToDb($var);

    function mapFromDb($var);
}
