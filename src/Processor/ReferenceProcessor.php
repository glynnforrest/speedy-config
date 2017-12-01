<?php

namespace SpeedyConfig\Processor;

use SpeedyConfig\Config;
use SpeedyConfig\KeyException;
use SpeedyConfig\Schema\Schema;

/**
 * ReferenceProcessor resolves references to other configuration keys
 * and detects circular references.
 *
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class ReferenceProcessor implements ProcessorInterface
{
    protected $referenceStack = [];

    public function onPostMerge(Config $config, Schema $schema)
    {
        try {
            foreach ($config as $key => $value) {
                $config->set($key, $this->resolveValue($config, $key));
            }
        } catch (KeyException $e) {
            throw new KeyException(sprintf('Error resolving references in configuration key "%s"', $key), 1, $e);
        }
    }

    /**
     * Resolve a configuration value by replacing any %tokens% with
     * their substituted values.
     *
     * @param Config $config
     * @param string $key
     */
    protected function resolveValue(Config $config, $key)
    {
        $value = $config->getRequired($key);
        if (is_array($value)) {
            throw new KeyException(sprintf('Referenced configuration key "%s" must not be an array.', $key));
        }

        if (in_array($key, $this->referenceStack)) {
            throw new KeyException(sprintf('Circular reference detected resolving configuration key "%s"', $key));
        }
        $this->referenceStack[] = $key;

        preg_match_all('/%([^%]+)%/', $value, $matches);
        if (!$matches) {
            return;
        }

        foreach ($matches[1] as $referenceKey) {
            $replacement = $this->resolveValue($config, $referenceKey);
            $value = str_replace('%'.$referenceKey.'%', $replacement, $value);
        }
        array_pop($this->referenceStack);

        return $value;
    }
}
