<?php
// @codingStandardsIgnoreFile

namespace Hubkat\Hooker;

class Hooks
{
    protected $hooks = [];

    public function hook($name, $domain = null)
    {
        if (! $domain) {
            $default = $this->defaultName($name);
            if (! class_exists($default)) {
                throw new \InvalidArgumentException('Must specify domain');
            }
            $domain = $default;
        }

        $this->hooks[$name] = $domain;
    }

    public function has($hook)
    {
        return isset($this->hooks[$hook]);
    }

    public function get($hook)
    {
        if (! $this->has($hook)) {
            throw new \InvalidArgumentException('Must specify domain');
        }
        return $this->hooks[$hook];
    }

    protected function defaultName($name)
    {
        $upper = function($matches) {
            return strtoupper($matches[1]);
        };

        return preg_replace_callback('/(?:^|_)([a-z])/',$upper, $name);
    }
}
