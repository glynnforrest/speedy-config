<?php

namespace SpeedyConfig\Schema;

use SpeedyConfig\Schema\ViolationException;

/**
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class CountRule implements RuleInterface
{
    protected $amount;

    public function __construct($amount)
    {
        $this->amount = $amount;
    }

    public function validate($value, $key)
    {
        if (count($value) !== $this->amount) {
            throw new ViolationException(sprintf('The number of items in "%s" must be %s, %s found.', $key, $this->amount, count($value)));
        }
    }
}
