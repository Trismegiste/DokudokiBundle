<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Strategy;

/**
 *
 * @author florent
 */
interface Mapping
{

    function mapToDb($var);

    function mapFromDb($var);
}
