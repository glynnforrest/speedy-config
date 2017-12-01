<?php

namespace SpeedyConfig\Schema;

use SpeedyConfig\Schema\ViolationException;

/**
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class TypeRule implements RuleInterface
{
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function validate($value, $key)
    {
        if (gettype($value) !== $this->type) {
            throw new ViolationException(sprintf('The key "%s" must be of type %s, %s given.', $key, $this->type, gettype($value)));
        }
    }
}
