<?php

/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Bootstrap
 * @author $Author$
 * @version $Id$
 */

/**
 * Define constants and stuff here.
 */

/**
 * Version number for the calculation and planning process
 * 
 * This number is added to the csb bean. When there is an attempat to recalculate a day with an incompatible
 * version the system may block that attempt.
 */
define('APP_VERSION', '1.0.1');

/**
 * Magic codes.
 */
define('DAMAGE_CODE_A_UNSUITABLE', '06');

/**
 * Liver damage codes.
 */
define('DAMAGE_CODE_B_LIVER_LT5', 'LE1');

/**
 * Liver damage codes.
 */
define('DAMAGE_CODE_B_LIVER_GT5', 'LE2');

/**
 * Unsuitable damage code.
 */
define('DAMAGE_CODE_UNSUITABLE', '05');

/**
 * Preliminary damage code.
 */
define('DAMAGE_CODE_PRELIMINARY', '06');
