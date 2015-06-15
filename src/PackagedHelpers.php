<?php
namespace Packaged\Helpers;

class PackagedHelpers
{
  public static function includeGlobalFunctions()
  {
    include_once('includes/GlobalFunctions.php');
    include_once('includes/Phutil.php');
  }
}
