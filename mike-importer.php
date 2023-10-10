<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://michael.se
 * @since             1.0.0
 * @package           Mike_Importer
 *
 * @wordpress-plugin
 * Plugin Name:       Mike Importer
 * Plugin URI:        https://michael.se
 * Description:       Plugin supports to import category, brand
 * Version:           1.0.0
 * Author:            Michael
 * Text Domain:       mike-importer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('MIKE_IMPORTER_VERSION', '1.0.0');
define('MIKE_IMPORTER_ABSPATH', __DIR__ . '/');

function load_mike_importer()
{
    require_once WP_PLUGIN_DIR . '/mike-importer/vendor/autoload.php';
}

add_action('plugins_loaded', 'load_mike_importer');