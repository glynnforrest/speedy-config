<?php

namespace SpeedyConfig\Loader;

/**
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class ArrayLoader implements LoaderInterface
{
    public function load($resource)
    {
        return $resource;
    }

    public function supports($resource)
    {
        return is_array($resource);
    }
}
