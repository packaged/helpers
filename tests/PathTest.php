<?php
namespace Packaged\Tests;

use Packaged\Helpers\Path;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
  public function testCustom()
  {
    static::assertEquals('abc', Path::custom('/', ['abc']));
    static::assertEquals('abc', Path::custom('/', ['', 'abc']));
    static::assertEquals('abc/d', Path::custom('/', ['abc', 'd']));
    static::assertEquals('/abc', Path::custom('/', ['/abc']));
    static::assertEquals('/abc/d', Path::custom('/', ['/abc', '', 'd']));
    static::assertEquals('/abc/d', Path::custom('/', ['/abc', '/d']));
    static::assertEquals('/abc/d/e/', Path::custom('/', ['/abc', '/d', 'e/']));
    static::assertEquals('/abc/d/e/f', Path::custom('/', ['/abc', '/d', 'e/', 'f']));
    static::assertEquals('/abc/d/0/f', Path::custom('/', ['/abc', '/d', 0, 'f']));
    static::assertEquals('/abc/d/f', Path::custom('/', ['/abc', '/d', null, 'f']));
    static::assertEquals('/abc/d/f', Path::custom('/', ['', '/abc', '', '/d', null, 'f']));
    static::assertEquals('/abc/d/f/', Path::custom('/', ['', '', '/abc', null, null, '/d', null, 'f', '', '', '/']));
    static::assertEquals('abc/d//e/f', Path::custom('/', ['abc', '/d//e/', 'f']));
    static::assertEquals('//cdn.xyz.com/images', Path::custom('/', ['//cdn.xyz.com', 'images']));
    static::assertEquals('abc/d/e/f/g', Path::custom('/', ['abc/d', 'e', null, 'f/g']));
    static::assertEquals('abc/d/e/f/g', Path::custom('/', ['abc/d/e', null, 'f/g']));
  }

  public function testGlobRecursive()
  {
    $baseDir = dirname(__DIR__);
    static::assertContains(
      Path::system($baseDir, 'composer.json'),
      Path::globRecursive($baseDir, '*.json')
    );
    static::assertContains(
      Path::system($baseDir, 'phpunit.xml'),
      Path::globRecursive($baseDir, '*.xml')
    );
    static::assertContains(
      Path::system($baseDir, 'src', 'Traits', 'ArrayAccessTrait.php'),
      Path::globRecursive($baseDir, '*.php')
    );
  }

  public function testBuildPath()
  {
    static::assertEquals("a" . DIRECTORY_SEPARATOR . "b", Path::system("a", "b"));
    static::assertEquals("a" . DIRECTORY_SEPARATOR . "b", Path::system("a", "b"));
  }

  public function testBuildWindowsPath()
  {
    static::assertEquals("a\\b", Path::windows("a", "b"));
  }

  public function testBuildUnixPath()
  {
    static::assertEquals("a/b", Path::unix("a", "b"));
  }

  public function testBuildUrlPath()
  {
    static::assertEquals("a/b", Path::url("a", "b"));
  }

  public function testBuildCustomPath()
  {
    static::assertEquals("a|b", Path::custom("|", ["a", "b"]));
    static::assertEquals("a|b|c", Path::custom("|", ["a", "|b|", "c"]));
    static::assertEquals("a|b", Path::custom("|", [0 => "a", 1 => "b"]));
    static::assertEquals("a/~~b", Path::custom("~~", [0 => "a/", 1 => "b"]));
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
    static::assertEquals($expected, Path::baseName($input));
  }

  public function testUrl()
  {
    static::assertEquals('', Path::url(''));
    static::assertEquals('abc', Path::url('abc'));
    static::assertEquals('/', Path::url('/', ''));
    static::assertEquals('/test', Path::url('/', '/test'));
    static::assertEquals('/c/4/ab', Path::url('/', 'c', 4, 'ab'));
    static::assertEquals('/c/0/ab', Path::url('/', 'c', 0, 'ab'));
    static::assertEquals('/c/ab', Path::url('/', 'c', null, '', 'ab'));
    static::assertEquals('/test', Path::url('/', '', '/test', ''));
    static::assertEquals('//cdn.domain.tld/test', Path::url('//cdn.domain.tld', '', '/test', ''));
    static::assertEquals('/test/subdir/test/', Path::url('/test/', '/subdir/test/'));
    static::assertEquals('/test/subdir/test/', Path::url('/test', '/subdir/test', '/'));
    static::assertEquals('test/subdir/test/', Path::url('test', '/subdir/test/'));
    static::assertEquals('test/subdir//test/', Path::url('test', '/subdir//test/'));
  }
}
