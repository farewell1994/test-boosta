<?php

namespace App\Service;

/**
 * Class ExtensionChain
 * @package App\Service
 */
class ExtensionChain
{
    /** @var array */
    private $drivers = [];

    /**
     * @param ExtensionDriverInterface $driver
     * @param $alias
     */
    public function addDriver(ExtensionDriverInterface $driver, $alias): void
    {
        $this->drivers[$alias] = $driver;
    }

    /**
     * @param $alias
     * @return ExtensionDriverInterface|null
     */
    public function getDriver($alias): ?ExtensionDriverInterface
    {
        if (array_key_exists($alias, $this->drivers)) {
            return $this->drivers[$alias];
        }

        return null;
    }
}
