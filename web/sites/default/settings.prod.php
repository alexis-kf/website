<?php

/**
 * @file
 * #ddev-generated: Automatically generated Drupal settings file.
 * ddev manages this file and may delete or overwrite the file unless this
 * comment is removed.  It is recommended that you leave this file alone.
 */

$host = "localhost";
$port = '';
$driver = "mysql";

$databases['default']['default']['database'] = "upunucjvve";
$databases['default']['default']['username'] = "upunucjvve";
$databases['default']['default']['password'] = "Sdjur35Ctz";
$databases['default']['default']['host'] = 'localhost';
$databases['default']['default']['driver'] = $driver;
$databases['default']['default']['port'] = '';

$settings['hash_salt'] = 'TIDhLuAxiwIBPyjUNzdQavYvVvkxSLJXCmRHzeQIKWqCqDGMCAYZuaNowXpXqXTi';

// This will ensure the site can only be accessed through the intended host
// names. Additional host patterns can be added for custom configurations.
$settings['trusted_host_patterns'] = [
  '^kodffe\.com$',
  '^.+\.kodffe\.com$',
  '^.+\.cloudwaysapps\.com$',
];

// Set $settings['config_sync_directory'] if not set in settings.php.
if (empty($settings['config_sync_directory'])) {
  $settings['config_sync_directory'] = 'config/sync';
}
