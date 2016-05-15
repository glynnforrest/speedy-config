<?php

namespace SpeedyConfig\Loader;

use SpeedyConfig\ResourceException;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * YamlLoader
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class YamlLoader implements LoaderInterface
{
    public function load($resource)
    {
        if (!is_file($resource)) {
            throw new ResourceException(sprintf('"%s" not found.', $resource));
        }

        try {
            $values = Yaml::parse(file_get_contents($resource), true);
        } catch (ParseException $e) {
            throw new ResourceException(sprintf('"%s" contains invalid YAML.', $resource), null, $e);
        }

        return is_array($values) ? $values : [];
    }

    public function supports($resource)
    {
        return substr($resource, -4) === '.yml';
    }
}
