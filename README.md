# phpunit-entity-tester : Quickly test your entities

You can write very quickly unit tests for the accessors, adders and removers of your entities.

## Requirements

- PHP : 5.3.3 or later
- phpunit/phpunit : ~4.8

## Installation

* With Composer
```
composer install jngermon/phpunit-entity-tester
```

## Quick example
This is an example of unit test file:
```php
<?php

namespace Demo\Tests;

use PhpUnitEntityTester\AccessorTester;
use PhpUnitEntityTester\AccessorCollectionTester;

class MyEntityTest extends \PHPUnit_Framework_TestCase
{
    public function simpleTest()
    {
        $entity = new MyEntity(); // The entity that you want to test

        // Test 'setName' and 'getName'
        new AccessorTester($entity, 'name')->test('John Doe');

        // Test 'addFruit', 'removeFruit' and 'getFruits'
        new AccessorCollectionTester($entity, 'fruits')->test('apple', 'pear');
    }
}
```

## Documentation

### Test the accessors

#### 1. Add 'use' statement
```php
use PhpUnitEntityTester\AccessorTester;
```
#### 2. Create the tester object
```php
$tester = new AccessortTester($entity, 'attribute');
```
Where `$entity` is the entity to test and `'attribute'` the base name of setter and getter methods.

In this case, the tester will test the methods 'setAttribute' and 'getAttribute'.

#### 3. Configure the tester
Your can change the setter and getter methods like this :
```php
$tester->setterMethod('setFoo') //fluent method
    ->getteMethod('getBar'); // fluent method
```
You can remove the fluent constaint for the setter (set to `true` by default)
```php
$tester->fluent(false);
```

#### 4. Run tests
Simply use the `test` method :
```php
$tester->test('the value');
```
In this case the tester :
1. calls the setter with 'the value'
2. tests the fluent constraint 
3. tests that the getter return 'the value'

If the value returned by the getter have to be different to the value used with the setter 
use the second argument of the `test` method like this :
```php
$tester->test('value for setter', 'value that the getter have to return');
```

### Test the adders, removers and collection getters
 
#### 1. Add 'use' statement
```php
use PhpUnitEntityTester\AccessorCollectionTester;
```
#### 2. Create the tester object
```php
$tester = new AccessorCollectionTester($entity, 'items');
```
Where `$entity` is the entity to test and `'items'` the base name for adder, remover and getter methods. For the adder and remover methods, the final 's' of the base name will be removed.

In this case, ther tester will test the methods 'addItem', 'removeItem' and 'getItems'.

#### 3. Configure the tester
You can change the adder, remover and getter methods like this :
```php
$tester->addMethod('addCustomItem') // fluent method
    ->removeMethod('popItem') // fluent method
    ->getMethod('getAllItems'); // fluent method
```
You can remove the fluent constraint for the adder and remover methods (set to `true` by default)
```php
$tester->fluent(false);
```
By default, the tester considere that the collection respect the unicity of its items.
You can force the tester to considere thaht the collection don't respect the unicity like that :
```php
$tester->unique(false);
```

#### 4. Run tests
Simply use the `test` method :
```php
$tester->test('first value', 'second value');
```
This method needs two arguments to work.
In this case the tester :
1. calls and tests the adder method with the first value
2. try to remove the second value (that is not in collection yet)
3. calls and tests the adder method with the second value
4. try to add again the first value (to tetunique constraint)
5. try to remove the first value
6. add again the first value (to obtain a collection with the first and the second value in it)

This method uses the following three methods that you can also use separatly.

##### Test Adder
You can also only test the adder method like that :
```php
$tester->testAdd('value');
```
This method calls the adder method and tests :
1. the fluent constraint
2. if the value is in collection (added)
3. the unicity constraint

##### Test Remover
You can also only test the remover method like that :
```php
$tester->testRemove('value');
```
This method calls the remover method and tests :
1. the fluent constraint
2. if the value is not in colleciton (removed)
3. if the remover removed the good number of items (all that match value but not more)

##### Test Getter
You can also only test the getter method like that :
```php
$tester->testGet();
```
This method tests if the returned value of the getter method is :
1. (if return is an object) implement `Countable` interface
2. (if return is an object) implement `Traversable` interface
3. (if return is not an object) is an `array` or `null`

## Tricks
To test entity quicker, use `dataProvider` with tester.
1. Create the test method
```php
public function testAccessor($attribute, $setValue, $getValue = AccessorTester::USE_SET_DATA)
{
    $entity = new YourEntityClass();
    $tester = new AccessorTester($entity, $attribute);

    $tester->test($setValue, $getValue);
}
```
2. Create the data provider
```php
/**
 * @dataProvider testAccessor
 */
public function providerTestAccessor()
{
    return [
        ['name', 'John Doe'],
        ['surname', 'JD'],
        ['astro', 'lion'],
        ['age', 12],
        ['age', -5, 0],
        ['color', 'red', '#ff0000'],
        ['createdAt', '2015-12-01', new \Datetime('2015-12-01')],
        // ...
        // one line here, generate complete test of your accessor
    ];
}
```

You can also use the process for `AccessorCollectionTester` by customize the test method.
## Credits
Special thanks for gerg0ire who encourage me to do that library

