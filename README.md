PHP Memoize Trait

## Usage

Sufix any method name with `Cached` to memoize calls to that method.

```php
$instance = new Object();
$instance->method();  // normal
$instance->methodCached(); // memoized
```

*Note:* Requires special consideration when your class (or a super class) already implements the magic `__call` method.

## Test

```
% phpunit tests/MemoizeTest.php 
```
