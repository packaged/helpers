<?php
namespace Packaged\Tests;

use Packaged\Helpers\Path;

class PathTest extends \PHPUnit_Framework_TestCase
{
  public function testGlobRecursive()
  {
    $baseDir = dirname(__DIR__);
    $this->assertContains(
      Path::system($baseDir, 'composer.json'),
      Path::globRecursive($baseDir, '*.json')
    );
    $this->assertContains(
      Path::system($baseDir, 'phpunit.xml'),
      Path::globRecursive($baseDir, '*.xml')
    );
    $this->assertContains(
      Path::system($baseDir, 'src', 'Traits', 'ArrayAccessTrait.php'),
      Path::globRecursive($baseDir, '*.php')
    );
  }

  public function testBuildPath()
  {
    $this->assertEquals("a" . DIRECTORY_SEPARATOR . "b", Path::system("a", "b"));
    $this->assertEquals("a" . DIRECTORY_SEPARATOR . "b", Path::system("a", "b"));
  }

  public function testBuildWindowsPath()
  {
    $this->assertEquals("a\\b", Path::windows("a", "b"));
  }

  public function testBuildUnixPath()
  {
    $this->assertEquals("a/b", Path::unix("a", "b"));
  }

  public function testBuildCustomPath()
  {
    $this->assertEquals("a|b", Path::custom("|", ["a", "b"]));
    $this->assertEquals("a|b", Path::custom("|", [0 => "a", 1 => "b"]));
  }

  public function baseNameProvider()
  {
    return [
      ['/', '/'],
      ['C:\\', 'C:'],
      ['/test/dir/123/file1', 'file1'],
      ['test/dir/123/file2', 'file2'],
      ['/file3', 'file3'],
      ['//test//dir1//file4', 'file4'],
      ['/test/dir2/dir5/', 'dir5'],
      ['C:\\Program Files\\Test Dir\\file6', 'file6'],
      ['C:\\test\\dir2/file7', 'file7'],
    ];
  }

  /**
   * @dataProvider baseNameProvider
   *
   * @param string $input
   * @param string $expected
   */
  public function testBaseName($input, $expected)
  {
    $this->assertEquals($expected, Path::baseName($input));
  }
}
