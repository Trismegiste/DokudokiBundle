<?php

define('FIXTURES_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR);

// see assetic for the bootstrap with composer and travis

spl_autoload_register(function ($class) {
            if (preg_match('#^Trismegiste\\\\DokudokiBundle\\\\(.+)$#', $class, $ret)) {
                $relPath = str_replace('\\', DIRECTORY_SEPARATOR, $ret[1]);
                require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $relPath . '.php';
            }
        });
