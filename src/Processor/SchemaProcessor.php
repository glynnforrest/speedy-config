<?php

namespace SpeedyConfig\Processor;

use SpeedyConfig\Config;
use SpeedyConfig\Schema\Schema;
use SpeedyConfig\Schema\ViolationException;
use SpeedyConfig\Schema\ErrorCollection;
use SpeedyConfig\Schema\InvalidSchemaException;

/**
 * Check the config matches the given schema.
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class SchemaProcessor implements ProcessorInterface
{
    public function onPostMerge(Config $config, Schema $schema)
    {
        $errors = new ErrorCollection();
        foreach ($schema->getNodes() as $key => $node) {
            $value = $config->get($key);
            foreach ($node->getRules() as $rule) {
                try {
                    $rule->validate($value, $key);
                } catch (ViolationException $e) {
                    $errors->add($key, $e->getMessage());
                }
            }
        }

        if (count($errors) > 0) {
            throw new InvalidSchemaException($errors, 'The resolved configuration is invalid: '.$errors->getFirst());
        }
    }
}
