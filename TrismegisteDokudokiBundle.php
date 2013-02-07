<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * TrismegisteDokudokiBundle is a simple persistence layer
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