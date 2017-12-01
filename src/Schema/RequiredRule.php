<?php

namespace SpeedyConfig\Schema;

/**
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class RequiredRule implements RuleInterface
{
    public function validate($value, $key)
    {
        if ($value === null) {
            throw new ViolationException(sprintf('The key "%s" is required.', $key));
        }
    }
}
