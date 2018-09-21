<?php

namespace Memoize;

trait Memoize {
    private $memoizer;

    public function __call($method, $params) {
        $cachedMethod = $this->getCachedMethod($method);

        if ($cachedMethod === null && is_callable(['parent', $method])) {
            return parent::__call($method, $params);
        } else {
            return $this->memoize($cachedMethod, $params);
        }
    }

    public function memoize($method, $params)
    {
        if (!isset($this->memoizer)) {
            $this->memoizer = new Memoizer(new InMemoryMemoizerCache());
        }
        return $this->memoizer->memoize([$this, $method], $params);
    }

    private function getCachedMethod($method) {
        if (substr($method, 0, 1) === '_') {
            return substr($method, 1);
        } else {
            return null;
        }
    }
}