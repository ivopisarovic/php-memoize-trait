<?php

namespace Memoize;

function memoize($method, ...$params) {
    $object = $method[0];
    if (!isset($object->memoizer)) {
        $object->memoizer = new Memoizer(new InMemoryMemoizerCache());
    }
    return $object->memoizer->memoize([$object, $method], $params);
}