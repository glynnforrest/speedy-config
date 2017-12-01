<?php

namespace SpeedyConfig\Schema;

/**
 * A single key in a schema.
 * It can have many rules defining how it is validated.
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class Node
{
    protected $rules = [];

    public function addRule(RuleInterface $rule)
    {
        $this->rules[] = $rule;

        return $this;
    }

    public function getRules()
    {
        return $this->rules;
    }
}
