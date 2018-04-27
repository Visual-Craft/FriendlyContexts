<?php

namespace Knp\FriendlyContexts\Alice\Fixtures;

use Knp\FriendlyContexts\Alice\ProviderResolver;
use Nelmio\Alice\Loader\NativeLoader;

class Loader extends NativeLoader
{
    private $cache = [];

    public function __construct($locale, ProviderResolver $providers)
    {
        parent::__construct($locale, $providers->all());
    }

    public function getCache()
    {
        return $this->cache;
    }

    public function clearCache()
    {
        $this->cache = [];
    }

    // BC compatibility
    public function load($file)
    {
        return $this->loadFile($file);
    }

    /**
     * {@inheritdoc}
     */
    protected function instantiateFixtures(array $fixtures)
    {
        parent::instantiateFixtures($fixtures);

        foreach ($fixtures as $fixture) {
            $spec = array_map(function ($property) {
                return $property->getValue();
            }, $fixture->getProperties());

            $this->cache[] = [ $spec, $this->objects->get($fixture->getName()) ];
        }
    }
}
