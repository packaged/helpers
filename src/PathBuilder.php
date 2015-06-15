<?php
namespace Packaged\Helpers;

class PathBuilder
{
  /**
   * Concatenate any number of path sections and correctly
   * handle directory separators
   *
   * @return string
   */
  public static function path( /* string... */)
  {
    return static::custom(DIRECTORY_SEPARATOR, func_get_args());
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
   */
  public static function unix( /* string... */)
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
   */
  public static function custom($directorySeparator, array $pathComponents)
  {
    $fullPath = "";
    foreach($pathComponents as $section)
    {
      if(!empty($section))
      {
        if($fullPath == "")
        {
          $fullPath = $section;
        }
        else
        {
          $fullPath = rtrim($fullPath, '/\\' . $directorySeparator) .
            $directorySeparator . ltrim($section, '/\\' . $directorySeparator);
        }
      }
    }

    return $fullPath;
  }
}
