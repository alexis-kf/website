<?php declare(strict_types = 1);

namespace PHPStan\ExtensionInstaller;

/**
 * This class is generated by phpstan/extension-installer.
 * @internal
 */
final class GeneratedConfig
{

	public const EXTENSIONS = array (
  'composer/composer' => 
  array (
    'install_path' => '/var/www/html/vendor/composer/composer',
    'relative_install_path' => '../../../composer/composer',
    'extra' => 
    array (
      'includes' => 
      array (
        0 => 'phpstan/rules.neon',
      ),
    ),
    'version' => '2.5.8',
  ),
  'mglaman/phpstan-drupal' => 
  array (
    'install_path' => '/var/www/html/vendor/mglaman/phpstan-drupal',
    'relative_install_path' => '../../../mglaman/phpstan-drupal',
    'extra' => 
    array (
      'includes' => 
      array (
        0 => 'extension.neon',
        1 => 'rules.neon',
      ),
    ),
    'version' => '1.1.37',
  ),
  'phpstan/phpstan-deprecation-rules' => 
  array (
    'install_path' => '/var/www/html/vendor/phpstan/phpstan-deprecation-rules',
    'relative_install_path' => '../../phpstan-deprecation-rules',
    'extra' => 
    array (
      'includes' => 
      array (
        0 => 'rules.neon',
      ),
    ),
    'version' => '1.1.3',
  ),
  'phpstan/phpstan-phpunit' => 
  array (
    'install_path' => '/var/www/html/vendor/phpstan/phpstan-phpunit',
    'relative_install_path' => '../../phpstan-phpunit',
    'extra' => 
    array (
      'includes' => 
      array (
        0 => 'extension.neon',
        1 => 'rules.neon',
      ),
    ),
    'version' => '1.3.13',
  ),
);

	public const NOT_INSTALLED = array (
);

	private function __construct()
	{
	}

}
