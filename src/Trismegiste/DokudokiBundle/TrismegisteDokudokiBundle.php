<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * DokudokiBundle is a simple MongoDb layer.
 * This layer has 4 modes of persistence if you want full of magic or a right
 * balance of automagic mapping and configured mapping.
 *
 * @author florent
 */
class TrismegisteDokudokiBundle extends Bundle
{

    public function getContainerExtension()
    {
        if (is_null($this->extension)) {
            $class = __NAMESPACE__ . '\DependencyInjection\Extension';
            $this->extension = new $class();
        }

        return $this->extension;
    }

}