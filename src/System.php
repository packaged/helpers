<?php
namespace Packaged\Helpers;

class System
{
  private static $commandCache = [];

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
   * Detect if the script is running under HHVM
   *
   * @return bool
   */
  public static function isHHVM()
  {
    return defined('HHVM_VERSION')
    || (array_key_exists('HPHP', $_ENV) && $_ENV['HPHP'] === 1);
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
   * Detect if the script is running on the built-in php dev server
   *
   * @param string $server $_SERVER['SERVER_SOFTWARE']
   *
   * @return bool
   */
  public static function isBuiltInDevServer($server = null)
  {
    if($server === null && isset($_SERVER['SERVER_SOFTWARE']))
    {
      $server = $_SERVER['SERVER_SOFTWARE'];
    }

    return preg_match('/^PHP \d\.\d\.\d Development Server/', $server) === 1;
  }

  /**
   * Check to see if a command exists
   *
   * @param $cmd
   *
   * @return bool
   */
  public static function commandExists($cmd)
  {
    return (bool)self::findCommand($cmd);
  }

  /**
   * Retrieve the path of a command
   *
   * @param $cmd
   *
   * @return mixed
   */
  public static function findCommand($cmd)
  {
    if(!isset(self::$commandCache[$cmd]))
    {
      $path = false;
      $retval = -1;
      $searchCmd = System::isWindows() ? 'where "%s" 2>nul' : 'which "%s"';
      exec(sprintf($searchCmd, $cmd), $output, $retval);
      if($retval === 0 && isset($output[0]))
      {
        $path = $output[0];
      }
      self::$commandCache[$cmd] = $path;
    }

    return self::$commandCache[$cmd];
  }

  /**
   * Check to see if a function has been disabled through php.ini
   *
   * @param string $function         function name to verify
   * @param mixed  $enabledFunctions known enabled functions
   *
   * @return bool
   */
  public static function isFunctionDisabled($function, $enabledFunctions = null)
  {
    $disabledFunctions = explode(',', ini_get('disable_functions'));
    if(self::isAppEngine())
    {
      if($enabledFunctions === null)
      {
        $enabledFunctions = ini_get('google_app_engine.enable_functions');
      }
      $aeDisabled = array_diff(
        [
          'gc_collect_cycles',
          'gc_enable',
          'gc_disable',
          'gc_enabled',
          'getmypid',
          'getmyuid',
          'getmygid',
          'getrusage',
          'getmyinode',
          'get_current_user',
          'libxml_disable_entity_loader',
          'parse_str',
          'phpinfo',
          'phpversion',
          'php_uname',
          'php_sapi_name'
        ],
        ValueAs::arr($enabledFunctions)
      );
      $disabledFunctions = array_merge($disabledFunctions, $aeDisabled);
    }
    return in_array($function, $disabledFunctions);
  }

  /**
   * Sleep for X milliseconds
   *
   * @param $milliseconds
   */
  public static function msleep($milliseconds)
  {
    usleep($milliseconds * 1000);
  }
}
