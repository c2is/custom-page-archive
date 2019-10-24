<?php
  /**
   * Plugin Name:       Custom post type archive
   * Description:       Customize post type archive pages
   * Version:           1.0
   * Author:            Acti
   * Author URI:        https://www.acti.fr/
   * Text Domain:       menu-page-archive
   * Domain Path:       /languages
   */

  define('CPA_SRC_PATH', __DIR__ . '/src');

final class CustomPageArchive
{
    public function __construct()
    {
        spl_autoload_register(array($this, 'loadFiles'));

        new CPA\CustomPageArchiveProcess();
    }

    public function loadFiles($className)
    {
        if (strpos($className, 'CPA') !== false) {
            $className = str_replace('CPA\\', '', $className);
            $className = str_replace("\\", "/", $className);
            $file = CPA_SRC_PATH. "/" . (empty($namespace) ? "" : $namespace . "/") . "{$className}.php";
            if (file_exists($file)) {
                include_once($file);
                if (class_exists($className)) {
                    return true;
                }
            }
        }
        return false;
    }
}

new CustomPageArchive();
