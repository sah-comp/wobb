Cinnebar
========

Layout for web development using PHPUnit, Flight, RedBeanPHP and Boilerplate stuff.


Features
--------

* Multilingual
* Role based access control

Installation
------------

Create a database.

Copy the _config.examle.php_ in app/config and name it config.php.
Open it with a text editor and make changes as you fancy, e.g. enter the login information for the database(s) used.


Notes to self
-------------

The following is more of a note to myself.


Composer
--------

I use [Composer](http://getcomposer.org/).

The following requires you to have composer.phar installed and in your $PATH.
There must also already be a composer.json file in your project directory.

On your command line do this to install your project:

cd /path/to/project

composer install

On your command line do this to update your project:

cd /path/to/project

composer update

RedBeanPHP
----------

I enjoy [RedBeanPHP](http://redbeanphp.com/) as a ORM.

On your command line do this to build a rbnc.php file:

php nocomment.php

This will give you a rbnc.php file which combines all that was in your replica.xml satisfying your flavor.


Tests
-----

Make a copy of _setup.example.php_ in tests/ and name it setup.php. Open that file and edit the login information for a test database. Do _not_ use your production database for testing, because the database will be nuked before testing.

I use [PHPUnit](http://phpunit.de/).

On your command line do this to run a test:

cd /path/to/project/tests

../vendor/bin/phpunit StackTest.php


Website
-------

Feel free to visit [sah-company.com](http://sah-company.com).