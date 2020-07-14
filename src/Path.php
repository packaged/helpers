<?php
namespace Packaged\Helpers;

class Path
{
  /**
   * Concatenate any number of path sections and correctly
   * handle directory separators
   *
   * @return string
   * @deprecated
   */
  public static function build( /* string... */)
  {
    return static::custom(DIRECTORY_SEPARATOR, func_get_args());
  }

  /**
   * Concatenate any number of path sections and correctly
   * handle directory separators
   *
   * @return string
   */
  public static function system( /* string... */)
  {
    return static::custom(DIRECTORY_SEPARATOR, func_get_args());
  }

  /**
   * Concatenate a path with windows style path separators
   *
   * @return string
   * @deprecated
   */
  public static function buildWindows( /* string... */)
  {
    return static::custom('\\', func_get_args());
  }

  /**
   * Concatenate a path with windows style path separators
   *
   * @return string
   */
  public static function windows( /* string... */)
  {
    return static::custom('\\', func_get_args());
  }

  /**
   * Concatenate a path with unix style path separators
   *
   * @return string
   * @deprecated
   */
  public static function buildUnix( /* string... */)
  {
    return static::custom('/', func_get_args());
  }

  /**
   * Concatenate a path with unix style path separators
   *
   * @return string
   */
  public static function unix( /* string... */)
  {
    return static::custom('/', func_get_args());
  }

  /**
   * Concatenate a path with unix style path separators
   *
   * @return string
   */
  public static function url( /* string... */)
  {
    return static::custom('/', func_get_args());
  }

  /**
   * Concatenate a path with a custom separator
   *
   * @param string   $directorySeparator
   * @param string[] $pathComponents
   *
   * @return string
   * @deprecated
   */
  public static function buildCustom($directorySeparator, array $pathComponents)
  {
    return static::custom($directorySeparator, $pathComponents);
  }

  /**
   * Concatenate a path with a custom separator
   *
   * @param string   $separator
   * @param string[] $pathComponents
   *
   * @return string
   */
  public static function custom($separator, array $pathComponents)
  {
    if(!isset($pathComponents[1]))
    {
      return $pathComponents[0];
    }

    $fullPath = [];
    $last = array_pop($pathComponents);
    foreach($pathComponents as $section)
    {
      $section = (string)$section;
      if(isset($section[1]))
      {
        $fullPath[] = empty($fullPath) ? rtrim($section, $separator) : trim($section, $separator);
      }
      else if(isset($section[0]))
      {
        $fullPath[] = $section === $separator ? '' : $section;
      }
    }

    if($last)
    {
      $fullPath[] = ltrim($last, $separator);
    }
    else if(!isset($fullPath[1]) && $fullPath[0] === '')
    {
      return $separator;
    }

    return implode($separator, $fullPath);
  }

  /**
   * Match all files within a directory to a pattern recursive
   *
   * @param     $baseDir
   * @param     $pattern
   * @param int $flags
   *
   * @return array
   */
  public static function globRecursive($baseDir, $pattern = '*', $flags = 0)
  {
    $ds = DIRECTORY_SEPARATOR;
    $files = glob($baseDir . $ds . $pattern, $flags);

    foreach(glob($baseDir . $ds . '*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir)
    {
      $files = array_merge(
        $files,
        static::globRecursive($dir, $pattern, $flags)
      );
    }
    return $files;
  }

  /**
   * Return the last component from a path. Separates on slash and/or backslash
   *
   * @param string $path
   *
   * @return string
   */
  public static function baseName($path)
  {
    if($path == "/")
    {
      return $path;
    }
    return basename(str_replace('\\', '/', $path));
  }
}
