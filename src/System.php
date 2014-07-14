<?php
namespace Packaged\Helpers;

class System
{
  /**
   * Detect if the server is running Windows
   *
   * @return bool
   */
  public static function isWindows()
  {
    return strncasecmp(PHP_OS, 'WIN', 3) == 0;
  }

  /**
   * Detect if the server is running on Mac
   * @return bool
   */
  public static function isMac()
  {
    return strncasecmp(PHP_OS, 'Darwin', 6) == 0;
  }

  /**
   * Detect if the script is running under HipHop
   *
   * @return bool
   */
  public static function isHipHop()
  {
    return (array_key_exists('HPHP', $_ENV) && $_ENV['HPHP'] === 1);
  }

  /**
   * Detect if the script is running on App Engine
   *
   * @param string $server $_SERVER['SERVER_SOFTWARE']
   *
   * @return bool
   */
  public static function isAppEngine($server = null)
  {
    if($server === null && isset($_SERVER['SERVER_SOFTWARE']))
    {
      $server = $_SERVER['SERVER_SOFTWARE'];
    }

    return stristr($server, 'Google App Engine') !== false;
  }

  /**
   * Detect if the script is running on the build in php dev server
   *
   * @param string $server $_SERVER['SERVER_SOFTWARE']
   *
   * @return bool
   */
  public static function isBuildInDevServer($server = null)
  {
    if($server === null && isset($_SERVER['SERVER_SOFTWARE']))
    {
      $server = $_SERVER['SERVER_SOFTWARE'];
    }

    return preg_match('/^PHP \d\.\d\.\d Development Server/', $server) === 1;
  }
}
