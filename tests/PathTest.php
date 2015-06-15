<?php

use Packaged\Helpers\Path;

class PathTest extends PHPUnit_Framework_TestCase
{
  public function testGlobRecursive()
  {
    $baseDir = dirname(__DIR__);
    $this->assertContains(
      Path::build($baseDir, 'composer.json'),
      Path::globRecursive($baseDir, '*.json')
    );
    $this->assertContains(
      Path::build($baseDir, 'phpunit.xml'),
      Path::globRecursive($baseDir, '*.xml')
    );
    $this->assertContains(
      Path::build($baseDir, 'src', 'Traits', 'ArrayAccessTrait.php'),
      Path::globRecursive($baseDir, '*.php')
    );
  }

  public function testBuildPath()
  {
    $this->assertEquals("a" . DIRECTORY_SEPARATOR . "b", Path::build("a", "b"));
    $this->assertEquals("a" . DIRECTORY_SEPARATOR . "b", Path::build("a", "b"));
  }

  public function testBuildWindowsPath()
  {
    $this->assertEquals("a\\b", Path::buildWindows("a", "b"));
  }

  public function testBuildUnixPath()
  {
    $this->assertEquals("a/b", Path::buildUnix("a", "b"));
  }

  public function testBuildCustomPath()
  {
    $this->assertEquals("a|b", Path::buildCustom("|", ["a", "b"]));
    $this->assertEquals("a|b", Path::buildCustom("|", [0 => "a", 1 => "b"]));
  }
}
