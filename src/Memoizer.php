<?php

namespace Memoize;

class Memoizer {
    private $cache;

    public function __construct(MemoizerCache $cache) {
        $this->cache = $cache;
    }

    public function memoize(callable $callback, $params) {
        $key = $this->getKey($callback, $params);

        return $this->cache->get($key, function() use ($callback, $params) {
            return call_user_func_array($callback, $params);
        });
    }

    private function getKey(callable $callback, $params) {
        list($class, $method) = $callback;

        $className = is_string($class) ? $class : get_class($class);
        $paramsHash = $this->getParamsHash($params);

        return $className . "---" . $method. "---" . $paramsHash;
    }

    private function getParamsHash() {
        return md5(serialize(func_get_args()));
    }
}