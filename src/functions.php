<?php

namespace Memoize;

function memoize(callable $callable, ...$params) {
    $object = $callable[0];
    if (!isset($object->memoizer)) {
        $object->memoizer = new Memoizer(new InMemoryMemoizerCache());
    }
    return $object->memoizer->memoize($callable, $params);
}