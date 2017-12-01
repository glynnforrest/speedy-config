<?php

namespace SpeedyConfig\Schema;

/**
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class InvalidSchemaException extends \Exception
{
    protected $errors;

    public function __construct(ErrorCollection $errors, $msg)
    {
        $this->errors = $errors;

        return parent::__construct($msg);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
