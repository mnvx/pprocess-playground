PProcess Playground
===================

Demonstration of [pprocess](https://github.com/mnvx/pprocess) usage.

Installation
------------

```bash
git clone git@github.com:mnvx/pprocess-playground.git
cd pprocess-playground
composer install
mcedit config/database.php # edit database connection
./cli migrations:migrate
```

How to run tests
----------------

```bash
./vendor/bin/phpunit
```
