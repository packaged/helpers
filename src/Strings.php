<?php
namespace Packaged\Helpers;

class Strings
{
  /**
   * Split a camel case string to words e.g. firstName becomes first name
   *
   * @param $string
   *
   * @return mixed
   */
  public static function splitOnCamelCase($string)
  {
    return preg_replace(
      "/(([a-z])([A-Z])|([A-Z])([A-Z][a-z]))/",
      "\\2\\4 \\3\\5",
      $string
    );
  }

  /**
   * Split a string on underscores e.g. first_name > first name
   *
   * @param $string
   *
   * @return mixed
   */
  public static function splitOnUnderscores($string)
  {
    return str_replace('_', ' ', $string);
  }

  /**
   * Convert a string to an underscored string
   *
   * @param $string
   *
   * @return mixed|string
   */
  public static function stringToUnderScore($string)
  {
    $string = self::splitOnCamelCase($string);
    $string = str_replace(' ', '_', $string);
    $string = strtolower($string);
    return $string;
  }

  /**
   * Convert a string to camel case
   *
   * @param $string
   *
   * @return mixed|string
   */
  public static function stringToCamelCase($string)
  {
    $string = self::stringToPascalCase($string);
    $string = lcfirst($string);
    return $string;
  }

  /**
   * Convert a string to pascal case
   *
   * @param $string
   *
   * @return mixed|string
   */
  public static function stringToPascalCase($string)
  {
    $string = self::splitOnCamelCase($string);
    $string = self::splitOnUnderscores($string);
    $string = strtolower($string);
    $string = ucwords($string);
    $string = str_replace(' ', '', $string);
    return $string;
  }

  /**
   * Convert a string to human readable, capitalising every word
   *
   * @param      $title
   * @param bool $splitOnCamel
   *
   * @return string
   */
  public static function titleize($title, $splitOnCamel = true)
  {
    return ucwords(static::humanize($title, $splitOnCamel));
  }

  /**
   * Convert a string to a human readable one
   *
   * @param      $string
   * @param bool $splitOnCamel
   *
   * @return string
   */
  public static function humanize($string, $splitOnCamel = true)
  {
    if($splitOnCamel)
    {
      $string = static::stringToUnderScore($string);
    }
    $string       = preg_replace('/_id$/', "", $string);
    $replacements = [
      "-" => ' ',
      "_" => ' ',
    ];
    return ucfirst(strtr($string, $replacements));
  }

  /**
   * Hyphenate a string, converting spaces and underscores to hyphens
   *
   * @param $string
   *
   * @return string
   */
  public static function hyphenate($string)
  {
    $replacements = [
      " " => '-',
      "_" => '-',
    ];
    return strtr($string, $replacements);
  }

  /**
   * Convert a string to a nice url friendly format
   *
   * @param $url
   *
   * @return string
   */
  public static function urlize($url)
  {
    return strtolower(static::hyphenate($url));
  }

  /**
   * Split a string into an array based on expected human input
   *
   * Ranged are allowed with hyphens, e.g. 1-3 will produce 1,2,3
   * Splitting can be done with spaces, commas, semi colons and pipes
   *
   * @param $string
   *
   * @return array
   */
  public static function stringToRange($string)
  {
    $result = [];
    $ranges = preg_split("(,|\s|;|\|)", $string);
    foreach($ranges as $range)
    {
      if(strstr($range, '-'))
      {
        list($start, $end) = explode("-", $range, 2);
        if(is_numeric($start) && is_numeric($end))
        {
          $result = array_merge($result, range($start, $end));
        }
        else
        {
          $prefix = static::commonPrefix($start, $end);
          $range1 = str_replace($prefix, "", $start);
          $range2 = str_replace($prefix, "", $end);
          if(is_numeric($range1) && is_numeric($range2))
          {
            $prefixRange = range($range1, $range2);
            foreach($prefixRange as $r)
            {
              $result[] = $prefix . $r;
            }
          }
          else
          {
            $result[] = $range;
          }
        }
      }
      else
      {
        $result[] = $range;
      }
    }
    return $result;
  }

  /**
   * Find the common start between two strings
   *
   * @param      $str1
   * @param      $str2
   * @param bool $stopOnInt
   *
   * @return string
   */
  public static function commonPrefix($str1, $str2, $stopOnInt = true)
  {
    if($stopOnInt)
    {
      $str1 = strtok($str1, "0123456789");
    }
    $preLen = strlen($str1 ^ $str2) - strlen(ltrim($str1 ^ $str2, chr(0)));
    return substr($str1, 0, $preLen);
  }

  /**
   * Split a string at a specific character position
   *
   * @param $string string String to split
   * @param $offset int character position to split on
   *
   * @return array [(string)Part1,(string)Part2]
   */
  public static function splitAt($string, $offset)
  {
    $parts = str_split($string, $offset);
    $part1 = array_shift($parts);
    $part2 = implode("", $parts);

    return [$part1, $part2];
  }

  public static function randomString($length = 40)
  {
    if(function_exists('mcrypt_create_iv'))
    {
      $randomData = mcrypt_create_iv(100, MCRYPT_DEV_URANDOM);
    }
    else if(function_exists('openssl_random_pseudo_bytes'))
    {
      $randomData = openssl_random_pseudo_bytes(100);
    }
    else if(@file_exists('/dev/urandom'))
    { // Get 100 bytes of random data
      $randomData = file_get_contents('/dev/urandom', false, null, 0, 100)
        . uniqid(mt_rand(), true);
    }
    else
    {
      $randomData = mt_rand() . mt_rand() . mt_rand() . mt_rand()
        . microtime(true) . uniqid(mt_rand(), true);
    }
    $hashed = hash('sha512', $randomData);
    while(strlen($hashed) < $length)
    {
      $hashed = $hashed . hash('sha512', $hashed);
    }
    return substr($hashed, 0, $length);
  }
}
