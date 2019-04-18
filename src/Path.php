<?php
namespace Packaged\Helpers;

class Path
{
  /**
   * Concatenate any number of path sections and correctly
   * handle directory separators
   *
   * @param array $parts
   *
   * @return string
   */
  public static function system(...$parts)
  {
    return static::custom(DIRECTORY_SEPARATOR, $parts);
  }

  /**
   * Concatenate a path with windows style path separators
   *
   * @param array $parts
   *
   * @return string
   */
  public static function windows(...$parts)
  {
    return static::custom('\\', $parts);
  }

  /**
   * Concatenate a path with unix style path separators
   *
   * @param array $parts
   *
   * @return string
   */
  public static function unix(...$parts)
  {
    return static::custom('/', $parts);
  }

  /**
   * Concatenate a path with unix style path separators
   *
   * @param array $parts
   *
   * @return string
   */
  public static function url(...$parts)
  {
    return static::custom('/', $parts);
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
    $fullPath = [];
    $charList = '/\\' . $separator;
    foreach($pathComponents as $section)
    {
      $section = (string)$section;
      if(isset($section[1]))
      {
        $fullPath[] = empty($fullPath) ? rtrim($section, $charList) : trim($section, $charList);
      }
      else if(isset($section[0]))
      {
        $fullPath[] = $section == $separator ? '' : $section;
      }
    }

    return ($fullPath[0] == '' && count($fullPath) === 1 ? $separator : implode($separator, $fullPath));
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
