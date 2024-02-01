<?php
/**
 * Cinnebar.
 *
 * Just to know?
 *
 * @package Cinnebar
 * @subpackage System
 * @author $Author$
 * @version $Id$
 */
/**
 * RedbeanPHP Version 5.5.
 */
require __DIR__ . '/../lib/redbean/rb-mysql.php';
require __DIR__ . '/../lib/redbean/Plugin/Cooker.php';

/**
 * Autoloader.
 */
require __DIR__ . '/../vendor/autoload.php';

/**
 * Bootstrap.
 */
require __DIR__ . '/../app/config/bootstrap.php';

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
