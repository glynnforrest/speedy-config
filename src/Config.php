<?php

namespace SpeedyConfig;

use Crutches\DotArray;
use IteratorAggregate;
use ArrayIterator;

/**
 * The result of a processed configuration.
 * Extends Crutches\DotArray with some extra methods.
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 */
class Config extends DotArray implements IteratorAggregate
{
    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        parent::set($key, $value);

        return $this;
    }

    /**
     * Get a configuration value that matches $key in the same way as
     * get(), but a KeyException will be thrown if
     * the key is not found.
     *
     * @param string $key
     *
     * @return mixed
     *
     * @throws KeyException
     */
    public function getRequired($key)
    {
        $value = $this->get($key);
        if (null !== $value) {
            return $value;
        }
        throw new KeyException("Required value not found: $key");
    }

    /**
     * Get the first value from an array of configuration values that
     * matches $key in the same way as getFirst(), but a
     * KeyException will be thrown if the key is not found.
     *
     * @param string $key
     *
     * @return mixed
     *
     * @throws KeyException
     */
    public function getFirstRequired($key)
    {
        $value = $this->getFirst($key);
        if (null !== $value) {
            return $value;
        }
        throw new KeyException("Required first value not found: $key");
    }

    public function getIterator()
    {
        return new ArrayIterator($this->flatten($this->get()));
    }

    private function flatten(array $array, $key_prefix = '')
    {
        $values = [];
        foreach ($array as $key => $value) {
            if (!is_array($value)) {
                $values[$key_prefix.$key] = $value;
                continue;
            }
            $values = array_merge($values, $this->flatten($value, $key_prefix.$key.'.'));
        }

        return $values;
    }
}
