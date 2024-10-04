<?php

/**
 * @file
 * Automatically generated Drupal settings file.
 * ddev manages this file and may delete or overwrite the file unless this
 * comment is removed.  It is recommended that you leave this file alone.
 */

$host = "db";
$port = 3306;
$driver = "mysql";

$databases['default']['default']['database'] = "db";
$databases['default']['default']['username'] = "db";
$databases['default']['default']['password'] = "db";
$databases['default']['default']['host'] = $host;
$databases['default']['default']['port'] = $port;
$databases['default']['default']['driver'] = $driver;

$settings['hash_salt'] = '93b6f1a94b85ada780ef7e3600f8324283995e133cfc6dd5a0f432f4acb49e25';

// This will prevent Drupal from setting read-only permissions on sites/default.
$settings['skip_permissions_hardening'] = TRUE;

// This will ensure the site can only be accessed through the intended host
// names. Additional host patterns can be added for custom configurations.
$settings['trusted_host_patterns'] = ['.*'];

// Don't use Symfony's APCLoader. ddev includes APCu; Composer's APCu loader has
// better performance.
$settings['class_loader_auto_detect'] = FALSE;

// Override drupal/symfony_mailer default config to use Mailpit
$config['symfony_mailer.mailer_transport.sendmail']['plugin'] = 'smtp';
$config['symfony_mailer.mailer_transport.sendmail']['configuration']['user']='';
$config['symfony_mailer.mailer_transport.sendmail']['configuration']['pass']='';
$config['symfony_mailer.mailer_transport.sendmail']['configuration']['host']='localhost';
$config['symfony_mailer.mailer_transport.sendmail']['configuration']['port']='1025';

// Enable verbose logging for errors.
// https://www.drupal.org/forum/support/post-installation/2018-07-18/enable-drupal-8-backend-errorlogdebugging-mode
$config['system.logging']['error_level'] = 'verbose';
