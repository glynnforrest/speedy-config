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

    public function isRequired()
    {
        $this->rules[] = new RequiredRule();

        return $this;
    }

    public function hasType($type)
    {
        $this->rules[] = new TypeRule($type);

        return $this;
    }

    public function hasCount($count)
    {
        $this->rules[] = new CountRule($count);

        return $this;
    }
}
