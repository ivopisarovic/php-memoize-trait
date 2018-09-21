<?php

namespace Memoize;

require __DIR__ . '/../src/Memoizer.php';
require __DIR__ . '/../src/MemoizerCache.php';
require __DIR__ . '/../src/Memoize.php';

use PHPUnit\Framework\TestCase;

class MemoizeTest extends TestCase {
	public function testMemoizeNoArguments() {
		$c = new SimpleCounter();
		$c->call();
		$c->call();

		$this->assertEquals(1, $c->calls);
	}

	public function testMemoizeWithArguments() {
		$c = new ArgumentCounter();
		$c->call('one');
		$c->call('two');
		$c->call('two');

		$this->assertEquals(1, $c->calls['one']);
		$this->assertEquals(1, $c->calls['two']);
	}

	public function testBadMethod() {
		$this->expectException(\TypeError::class);

		$c = new SimpleCounter();
		$c->missingMethod();
	}

	public function testMemoizeFactorial() {
		$m = new Math();
		$m->factorial(100);
		$m->factorial(100);
		$m->factorial(100);

		$this->assertEquals(101, $m->calls);
	}

	public function testMemoizeMagic() {
		$m = new Magic();
		$m->dazzle();
		$m->dazzle();
		$m->dazzle();

		$this->assertEquals(1, $m->calls);
	}

    public function testMemoizeViaFunction() {
        $c = new SimpleCounterWithoutTrait();
        memoize([$c, 'call']);
        memoize([$c, 'call']);

        $this->assertEquals(1, $c->calls);
    }

    public function testMemoizeViaFunctionWithArguments() {
        $c = new ArgumentCounterWithoutTrait();

        memoize([$c, 'call'], 'one');
        memoize([$c, 'call'], 'two');
        memoize([$c, 'call'], 'two');

        $this->assertEquals(1, $c->calls['one']);
        $this->assertEquals(1, $c->calls['two']);
    }

}

class SimpleCounter {
	use Memoize;
	public $calls = 0;
	public function _call() {
		$this->calls++;
	}
}

class ArgumentCounter {
	use Memoize;
	public $calls = [];
	public function _call($arg) {
		if (!array_key_exists($arg, $this->calls)) {
			$this->calls[$arg] = 0;
		}
		$this->calls[$arg]++;
	}
}

class Math {
	use memoize;
	public $calls = 0;
	public function _factorial($i) {
		$this->calls++;
		return ($i < 1) ? 1 : $i * $this->factorial($i - 1);
	}
}

class Magic {
	use memoize {
		__call as m;
	}
	public $calls = 0;
	public function _dazzle() {
		$this->calls++;
	}

	public function __call($method, $params) {
		return $this->m($method, $params);
	}
}

class SimpleCounterWithoutTrait {
    public $calls = 0;
    public function call() {
        $this->calls++;
    }
}

class ArgumentCounterWithoutTrait {
    public $calls = [];
    public function call($arg) {
        if (!array_key_exists($arg, $this->calls)) {
            $this->calls[$arg] = 0;
        }
        $this->calls[$arg]++;
    }
}