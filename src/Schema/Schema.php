<?php

namespace SpeedyConfig\Schema;

/**
 * Add gradual type-hinting to configuration.
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class Schema
{
    protected $nodes = [];

    /**
     * @return Node
     */
    public function node($key)
    {
        return $this->nodes[$key] = new Node();
    }

    /**
     * @return Node[]
     */
    public function getNodes()
    {
        return $this->nodes;
    }
}
