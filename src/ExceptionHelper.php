<?php

namespace Packaged\Helpers;

use Throwable;

class ExceptionHelper
{
  /**
   * Gets the stack trace as a string without truncating arguments
   *
   * @param Throwable $exception
   *
   * @return string
   */
  public static function getTraceAsString(Throwable $exception)
  {
    $lines = [];
    $count = 0;
    foreach($exception->getTrace() as $frame)
    {
      $args = "";
      if(isset($frame['args']))
      {
        $args = [];
        foreach($frame['args'] as $arg)
        {
          if(is_string($arg))
          {
            $args[] = "'" . $arg . "'";
          }
          else if(is_array($arg))
          {
            $args[] = "Array";
          }
          else if(is_null($arg))
          {
            $args[] = 'NULL';
          }
          else if(is_bool($arg))
          {
            $args[] = ($arg) ? "true" : "false";
          }
          else if(is_object($arg))
          {
            $args[] = 'Object(' . get_class($arg) . ')';
          }
          else if(is_resource($arg))
          {
            $args[] = ((string)$arg) . ' (' . get_resource_type($arg) . ')';
          }
          else
          {
            $args[] = $arg;
          }
        }
        $args = join(", ", $args);
      }
      if(isset($frame['file']))
      {
        $lines[] = sprintf(
          "#%s %s(%s): %s%s%s(%s)",
          $count,
          $frame['file'],
          $frame['line'],
          $frame['class'],
          $frame['type'],
          $frame['function'],
          $args
        );
      }
      else
      {
        $lines[] = sprintf(
          "#%s [internal function]: %s%s%s(%s)",
          $count,
          $frame['class'],
          $frame['type'],
          $frame['function'],
          $args
        );
      }
      $count++;
    }
    $lines[] = '#' . $count . ' {main}';
    return implode("\n", $lines);
  }
}
