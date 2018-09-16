<?php

require __DIR__.'/../lib/Memoizer.php';
require __DIR__.'/../lib/MemoizerCache.php';
require __DIR__.'/../lib/Memoize.php';

use PHPUnit\Framework\TestCase;

class MemoizeTest extends TestCase {
	public function testMemoizeNoArguments() {
		$c = new SimpleCounter();
		$c->callCached();
		$c->callCached();

		$this->assertEquals(1, $c->calls);
	}

	public function testMemoizeWithArguments() {
		$c = new ArgumentCounter();
		$c->callCached('one');
		$c->callCached('two');
		$c->callCached('two');

		$this->assertEquals(1, $c->calls['one']);
		$this->assertEquals(1, $c->calls['two']);
	}

	public function testBadMethod() {
		$this->expectException(TypeError::class);

		$c = new SimpleCounter();
		$c->missingMethodCached();
	}

	public function testMemoizeFactorial() {
		$m = new Math();
		$m->factorialCached(100);
		$m->factorialCached(100);
		$m->factorialCached(100);

		$this->assertEquals(101, $m->calls);
	}

	public function testMemoizeMagic() {
		$m = new Magic();
		$m->dazzleCached();
		$m->dazzleCached();
		$m->dazzleCached();

		$this->assertEquals(1, $m->calls);
	}
}

class SimpleCounter {
	use Memoize;
	public $calls = 0;
	public function call() {
		$this->calls++;
	}
}

class ArgumentCounter {
	use Memoize;
	public $calls = [];
	public function call($arg) {
		if (!array_key_exists($arg, $this->calls)) {
			$this->calls[$arg] = 0;
		}
		$this->calls[$arg]++;
	}
}

class Math {
	use memoize;
	public $calls = 0;
	public function factorial($i) {
		$this->calls++;
		return ($i < 1) ? 1 : $i * $this->factorial($i - 1);
	}
}

class Magic {
	use memoize {
		__call as m;
	}
	public $calls = 0;
	public function dazzle() {
		$this->calls++;
	}

	public function __call($method, $params) {
		return $this->m($method, $params);
	}
}