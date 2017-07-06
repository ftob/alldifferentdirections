# All Different Directions
#### https://open.kattis.com/problems/alldifferentdirections
[![Build Status Master](https://secure.travis-ci.org/ftob/alldifferentdirections.svg?branch=master)](http://travis-ci.org/ftob/alldifferentdirections)
[![Build Status Develop](https://secure.travis-ci.org/ftob/alldifferentdirections.svg?branch=develop)](http://travis-ci.org/ftob/alldifferentdirections)
If you walk through a big city and try to find your way around, you might try asking people for directions. However, asking nn people for directions might result in nn different sets of directions. But you believe in the law of averages: if you consider everyone’s advice, then you will have a good idea of where to go by computing the average destination that they all lead to. You would also like to know how far off were the worst directions. You compute this as the maximum straight-line distance between each direction’s destination and the averaged destination.

#### Requirements and depends
* PHP >= 7

## Install

* ``$ composer install``

## Install in docker

1. ``$ docker-compose up -d``
2. ``$ docker exec -i -t test_php bash``
3. ``$ composer install``
4. ``./bin/console filename``

## Tests

1. ``$ docker-compose up -d``
2. ``$ docker exec -i -t test_php bash``
3. ``$ composer install``
4. ``./vendor/bin/phpunit``

## Uses

```php
    $file = new SplFileObject($filePath);
    $dd = new DifferentDirections($file, new Directions());

    echo (string)$dd;
```

``$filePath`` - Path to exist file