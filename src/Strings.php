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
    $string = preg_replace('/_id$/', "", $string);
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

  const RANDOM_STRING_MCRYPT = 'mcrypt';
  const RANDOM_STRING_OPENSSL = 'openssl';
  const RANDOM_STRING_URANDOM = 'urandom';
  const RANDOM_STRING_CUSTOM = 'custom';

  /**
   * Generate a random string of $length bytes
   *
   * @param int    $length
   * @param string $forceMethod
   *
   * @return string
   */
  public static function randomString($length = 40, $forceMethod = null)
  {
    if(($forceMethod == self::RANDOM_STRING_MCRYPT || $forceMethod == null) &&
      function_exists('mcrypt_create_iv')
    )
    {
      $randomData = mcrypt_create_iv(100, MCRYPT_DEV_URANDOM);
    }
    elseif(($forceMethod == self::RANDOM_STRING_OPENSSL || $forceMethod == null)
      && function_exists('openssl_random_pseudo_bytes')
    )
    {
      $randomData = openssl_random_pseudo_bytes(100);
    }
    elseif(($forceMethod == self::RANDOM_STRING_URANDOM || $forceMethod == null)
      && @file_exists('/dev/urandom')
    )
    {
      $randomData = file_get_contents('/dev/urandom', false, null, 0, 100)
        . uniqid(mt_rand(), true);
    }
    else
    {
      $randomData = mt_rand() . mt_rand() . mt_rand() . mt_rand()
        . microtime(true) . uniqid(mt_rand(), true);
    }

    $hash = preg_replace('/[^a-z0-9]/i', '', $randomData);
    while(strlen($hash) < $length)
    {
      $hash .= static::randomString($length - strlen($hash), $forceMethod);
    }
    return substr($hash, 0, $length);
  }

  public static function pattern($pattern = 'XX00-XX00-00XX-00XX-XXXX')
  {
    $return = '';
    foreach(str_split($pattern) as $character)
    {
      if($character == '!')
      {
        $match = ['X', '0'];
        $character = $match[rand(0, 1)];
      }
      else if($character == '?')
      {
        $match = ['x', '0'];
        $character = $match[rand(0, 1)];
      }
      else if($character == '*')
      {
        $match = ['x', '0', 'X'];
        $character = $match[rand(0, 2)];
      }

      switch($character)
      {
        case 'X':
          $return .= chr(rand(65, 90));
          break;
        case 'x':
          $return .= chr(rand(97, 122));
          break;
        case '0':
          $return .= rand(0, 9);
          break;
        case is_numeric($character):
          $return .= rand(0, $character);
          break;
        default:
          $return .= $character;
          break;
      }
    }
    return $return;
  }

  public static function verifyPattern($template, $pattern)
  {
    $regexPattern = str_replace(
      ['X', 'x', '0', '5', '?', '!', '*'],
      [
        '[A-Z]',
        '[a-z]',
        '[0-9]',
        '[0-5]',
        '[a-z0-9]',
        '[A-Z0-9]',
        '[a-zA-Z0-9]'
      ],
      $template
    );

    return preg_match("/$regexPattern/", $pattern) === 1;
  }

  /**
   * Take a short extract from a string.
   *
   * @param        $string
   * @param        $length
   * @param string $append
   * @param bool   $forceOnSpace
   *
   * @return string
   */
  public static function excerpt(
    $string, $length, $append = ' ...', $forceOnSpace = false
  )
  {
    if(mb_strlen($string) < $length)
    {
      return $string;
    }

    $string = mb_substr($string, 0, $length);
    $pos = mb_strrpos($string, " ");
    //Ensure we do not cut the string too early on
    if(!$forceOnSpace && $length - $pos > 5)
    {
      $pos = false;
    }
    return mb_substr($string, 0, !$pos ? $length : $pos) . $append;
  }

  /**
   * Return substring between other strings (or string)
   *
   * @param string      $string    Haystack
   * @param string|null $start     Left margin
   * @param string|null $end       Right margin
   * @param bool        $inclusive Include start and end items in response
   *
   * @return string|false         Return string if it was found, otherwise false
   */
  public static function between(
    $string, $start = null, $end = null, $inclusive = false
  )
  {
    if($start !== null)
    {
      $left = strpos($string, $start);
      if($left === false)
      {
        return false;
      }
      $left += strlen($start);
    }
    else
    {
      $left = 0;
    }

    if($end !== null)
    {
      $right = strpos($string, $end, $left);
      if($right === false)
      {
        return false;
      }
    }
    else
    {
      $right = strlen($string);
    }

    $final = substr($string, $left, $right - $left);

    if($inclusive)
    {
      return $start . $final . $end;
    }
    return $final;
  }

  /**
   * Concatinate multiple values to a single string
   *
   * @return string
   */
  public static function concat(/* string... */)
  {
    return implode('', func_get_args());
  }

  /**
   * Escape HTML String
   *
   * @param $string
   *
   * @return string
   */
  public static function escape($string)
  {
    return \htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
  }

  /**
   * Assert that passed data can be converted to string.
   *
   * @param  string $parameter Assert that this data is valid.
   *
   * @return void
   *
   * @throws \InvalidArgumentException
   */
  public static function stringable($parameter)
  {
    switch(gettype($parameter))
    {
      case 'string':
      case 'NULL':
      case 'boolean':
      case 'double':
      case 'integer':
        return;
      case 'object':
        if(method_exists($parameter, '__toString'))
        {
          return;
        }
        break;
      case 'array':
      case 'resource':
      case 'unknown type':
      default:
        break;
    }

    throw new \InvalidArgumentException(
      "Argument must be scalar or object which implements __toString()!"
    );
  }

  /**
   * Split a corpus of text into lines. This function splits on "\n", "\r\n",
   * or
   * a mixture of any of them.
   *
   * NOTE: This function does not treat "\r" on its own as a newline because
   * none of SVN, Git or Mercurial do on any OS.
   *
   * @param $corpus        string Block of text to be split into lines.
   * @param $retainEndings bool If true, retain line endings in result strings.
   *
   * @return array List of lines.
   */
  public static function splitLines($corpus, $retainEndings = true)
  {
    if(!strlen($corpus))
    {
      return [''];
    }

    // Split on "\r\n" or "\n".
    if($retainEndings)
    {
      $lines = preg_split('/(?<=\n)/', $corpus);
    }
    else
    {
      $lines = preg_split('/\r?\n/', $corpus);
    }

    // If the text ends with "\n" or similar, we'll end up with an empty string
    // at the end; discard it.
    if(end($lines) == '')
    {
      array_pop($lines);
    }

    return $lines;
  }

  /**
   * Explode a string, filling the remainder with provided defaults.
   *
   * @param string      $delimiter The boundary string
   * @param string      $string    The input string.
   * @param array|mixed $defaults  Array to return, with replacements made,
   *                               or a padding value
   * @param int|null    $limit     Passed through to the initial explode
   *
   * @return array
   *
   */
  public static function explode(
    $delimiter, $string, $defaults = null, $limit = null
  )
  {
    if($limit === null)
    {
      $parts = explode($delimiter, $string);
    }
    else
    {
      $parts = explode($delimiter, $string, $limit);
    }

    if(is_array($defaults))
    {
      return array_replace($defaults, $parts);
    }

    return array_pad($parts, $limit, $defaults);
  }

  /**
   * Retrieve the final part of a string, after the first instance of the
   * needle has been located
   *
   * @param $haystack
   * @param $needle
   *
   * @return string
   */
  public static function offset($haystack, $needle)
  {
    if(stristr($haystack, $needle))
    {
      $haystack = substr(
        $haystack,
        strpos($haystack, $needle) + strlen($needle)
      );
    }
    return $haystack;
  }

  /**
   * Strip off a specific string from the start of another, if an exact match
   * is not found, the original string (haystack) will be returned
   *
   * @param $haystack
   * @param $needle
   *
   * @return string
   */
  public static function ltrim($haystack, $needle)
  {
    if(static::startsWith($haystack, $needle))
    {
      $haystack = substr($haystack, strlen($needle));
    }
    return $haystack;
  }

  /**
   * Check a string contains another string
   *
   * @param       $haystack
   * @param array $needle
   * @param bool  $case
   *
   * @return bool
   */
  public static function contains($haystack, $needle, $case = true)
  {
    if($case)
    {
      return strstr($haystack, $needle) !== false;
    }
    else
    {
      return stristr($haystack, $needle) !== false;
    }
  }

  /**
   * Check a string contains one of the provided needles
   *
   * @param       $haystack
   * @param array $needles
   * @param bool  $case
   *
   * @return bool
   */
  public static function containsAny($haystack, array $needles, $case = true)
  {
    foreach($needles as $needle)
    {
      if(static::contains($haystack, $needle, $case))
      {
        return true;
      }
    }
    return false;
  }

  /**
   * Check a string ends with one of the provided needles
   *
   * @param       $haystack
   * @param array $needles
   * @param bool  $case
   *
   * @return bool
   */
  public static function endsWithAny($haystack, array $needles, $case = true)
  {
    foreach($needles as $needle)
    {
      if(static::endsWith($haystack, $needle, $case))
      {
        return true;
      }
    }
    return false;
  }

  /**
   * Check a string ends with a specific string
   *
   * @param      $haystack
   * @param      $needle
   * @param bool $case
   *
   * @return bool
   */
  public static function endsWith($haystack, $needle, $case = true)
  {
    if(is_array($needle))
    {
      return static::endsWithAny($haystack, $needle, $case);
    }
    return static::startsWith(strrev($haystack), strrev($needle), $case);
  }

  /**
   * Check a string starts with one of the needles provided
   *
   * @param       $haystack
   * @param array $needles
   * @param bool  $case
   *
   * @return bool
   */
  public static function startsWithAny($haystack, array $needles, $case = true)
  {
    foreach($needles as $needle)
    {
      if(static::startsWith($haystack, $needle, $case))
      {
        return true;
      }
    }
    return false;
  }

  /**
   * Check a string starts with a specific string
   *
   * @param      $haystack
   * @param      $needle
   * @param bool $case
   *
   * @return bool
   */
  public static function startsWith($haystack, $needle, $case = true)
  {
    if(is_array($needle))
    {
      return static::startsWithAny($haystack, $needle, $case);
    }

    if(!$case)
    {
      return strncasecmp($haystack, $needle, strlen($needle)) == 0;
    }
    else
    {
      return strncmp($haystack, $needle, strlen($needle)) == 0;
    }
  }

  /**
   * Short cut for json_encode with JSON_PRETTY_PRINT
   *
   * @param $object
   *
   * @return string json encoded string
   */
  public static function jsonPretty($object)
  {
    return json_encode($object, JSON_PRETTY_PRINT);
  }
}
