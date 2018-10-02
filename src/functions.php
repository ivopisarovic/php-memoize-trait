<?php

namespace Memoize;

use \Illuminate\Support\Facades\Log;

function memoize(callable $callable, ...$params)
{
    $object = $callable[0];
    if (!isset($object->memoizer)) {
        $object->memoizer = new Memoizer(new InMemoryMemoizerCache());
    }
    return $object->memoizer->memoize($callable, $params);
}

function preventLooping($method, $params)
{
    if (!isset($GLOBALS['methodCounter'])) {
        $GLOBALS['methodCounter'] = 0;
    }

    $GLOBALS['methodCounter']++;

    if($GLOBALS['methodCounter'] > 10000) {
        Log::info($method.' : '. join(',', $params));
    }

    if($GLOBALS['methodCounter'] > 11000) {
        Log::info('ERROR! Application was killed by X-MEN to prevent looping.');
        exit;
    }
}