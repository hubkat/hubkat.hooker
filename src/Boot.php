<?php
// @codingStandardsIgnoreFile
/**
 *
 * This file is part of Radar for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */

namespace Hubkat\Hooker;

use Aura\Di\Container;
use Aura\Di\ContainerBuilder;

class Boot
{
    protected $containerCache;

    public function __construct($containerCache = null)
    {
        $this->containerCache = $containerCache;
    }

    /**
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function hooker(array $config = [], $autoResolve = false)
    {
        if ($this->containerCache) {
            $di = $this->cachedContainer($config, $autoResolve);
        } else {
            $di = $this->newContainer($config, $autoResolve);
        }

        return $di->get('hubkat/hooker:hooker');
    }

    /**
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    protected function cachedContainer(array $config, $autoResolve = false)
    {
        if (file_exists($this->containerCache)) {
            return unserialize(file_get_contents($this->containerCache));
        }

        $di = $this->newContainer($config, $autoResolve);
        file_put_contents($this->containerCache, serialize($di));
        return $di;
    }

    protected function newContainer(array $config, $autoResolve = false)
    {
        $config = array_merge(['Hubkat\Hooker\Config'], $config);
        return (new ContainerBuilder())->newConfiguredInstance($config, $autoResolve);
    }
}
