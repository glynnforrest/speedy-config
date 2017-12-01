<?php

namespace SpeedyConfig\Schema;

/**
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class ErrorCollection implements \Countable, \IteratorAggregate
{
    protected $errors = [];

    public function add($key, $message)
    {
        if (!isset($this->errors[$key])) {
            $this->errors[$key] = [];
        }

        $this->errors[$key][] = $message;
    }

    public function getAll()
    {
        return array_reduce($this->errors, function ($carry, $keyErrors) {
            return array_merge($carry, $keyErrors);
        }, []);
    }

    /**
     * @return string
     */
    public function getFirst()
    {
        foreach ($this->errors as $keyErrors) {
            return $keyErrors[0];
        }

        return '';
    }

    public function getByKey($key)
    {
        return isset($this->errors[$key]) ? $this->errors[$key] : [];
    }

    public function count()
    {
        return count($this->getAll());
    }

    public function getIterator()
    {
        return new ArrayIterator($this->getAll());
    }
}
