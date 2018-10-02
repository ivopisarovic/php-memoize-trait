<?php

namespace Memoize;

trait Memoize {
    private $memoizer;

    public function __call($method, $params) {
        $cachedMethod = $this->getCachedMethod($method);

        if ($cachedMethod === null && is_callable(['parent', '__call'])) {
            return parent::__call($method, $params);
        } else {
            return $this->memoize($cachedMethod, $params);
        }
    }

    // Support for Larvel Model's getAttribute
    public function getAttribute($key) {
        $method = 'get' . $key . 'Attribute';
        $cachedMethod = $this->getCachedMethod($method);
        if ($cachedMethod === null) {
            return parent::getAttribute($key);
        } else {
            return $this->memoize($cachedMethod, []);
        }
    }

    public function memoize($method, $params)
    {
        preventLooping($method, $params);

        if (!isset($this->memoizer)) {
            $this->memoizer = new Memoizer(new InMemoryMemoizerCache());
        }

        return $this->memoizer->memoize([$this, $method], $params);
    }

    private function getCachedMethod($method) {
        $targetMethod = '_' . $method;
        if (method_exists($this, $targetMethod)) {
            return $targetMethod;
        } else {
            return null;
        }
    }

}