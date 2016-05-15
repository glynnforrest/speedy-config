<?php

namespace SpeedyConfig\Loader;

use SpeedyConfig\ResourceException;

/**
 * PhpLoader
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class PhpLoader implements LoaderInterface
{
    public function load($resource)
    {
        if (!is_file($resource)) {
            throw new ResourceException(sprintf('"%s" not found.', $resource));
        }

        ob_start();
        $values = include $resource;
        ob_end_clean();
        if (!is_array($values)) {
            throw new ResourceException(sprintf('"%s" does not return a php array.', $resource));
        }

        return $values;
    }

    public function supports($resource)
    {
        return substr($resource, -4) === '.php';
    }
}
