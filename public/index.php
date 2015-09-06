<?php
/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage System
 * @author $Author$
 * @version $Id$
 */
/**
 * RedbeanPHP Version 3.5.
 */
require __DIR__ . '/../lib/redbean/rb.php';
/**
 * Autoloader.
 */
require __DIR__ . '/../vendor/autoload.php';
/**
 * Configuration.
 */
require __DIR__ . '/../app/config/config.php';

/**
 * Routes
 */
require __DIR__ . '/../app/config/routes.php';
/**
 * Up, up and away.
 */
Flight::start();