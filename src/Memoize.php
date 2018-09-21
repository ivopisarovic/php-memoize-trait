<?php

namespace Memoize;

trait Memoize {
    private $memoizer;

    public function __call($method, $params) {
        return $this->memoize($this->getMethod($method), $params);
    }

    public function memoize($method, $params) {
        if (!isset($this->memoizer)) {
            $this->memoizer = new Memoizer(new InMemoryMemoizerCache());
        }
        return $this->memoizer->memoize([$this, $method], $params);
    }

    private function getMethod($method) {
        if (substr($method, 0, 1) === '_') {
            return substr($method, 1);
        }
        return null;
    }
}