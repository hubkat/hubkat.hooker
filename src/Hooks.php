<?php
// @codingStandardsIgnoreFile

namespace Hubkat\Hooker;

class Hooks
{
    protected $hooks = [];

    public function hook($name, $hook = null)
    {
        if (! $hook) {
            $hook = $this->defaultHook($name);
            if (! class_exists($hook)) {
                throw new \InvalidArgumentException('Must specify hook callable');
            }
        }

        $this->hooks[$name] = $hook;
    }

    public function has($name)
    {
        return isset($this->hooks[$name]);
    }

    public function get($name)
    {
        if (! $this->has($name)) {
            throw new \InvalidArgumentException('Must specify domain');
        }
        return $this->hooks[$name];
    }

    protected function defaultHook($name)
    {
        $upper = function($matches) {
            return strtoupper($matches[1]);
        };

        return preg_replace_callback('/(?:^|_)([a-z])/', $upper, $name);
    }
}
