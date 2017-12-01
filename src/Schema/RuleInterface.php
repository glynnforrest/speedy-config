<?php

namespace SpeedyConfig\Schema;

/**
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
interface RuleInterface
{
    /**
     * Check the supplied value passes this rule.
     * Throw a ViolationException if not.
     *
     * $key has no functional purpose, but can be used for exception
     * messages.
     *
     * @param mixed  $value
     * @param string $key
     *
     * @throws ViolationException
     */
    public function validate($value, $key);
}
