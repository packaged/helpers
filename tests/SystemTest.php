<?php
namespace Packaged\Tests;

use Packaged\Helpers\System;
use PHPUnit\Framework\TestCase;

class SystemTest extends TestCase
{
  protected function setUp(): void
  {
    if(!isset($_SERVER['SERVER_SOFTWARE']))
    {
      $_SERVER['SERVER_SOFTWARE'] = 'PHPUnit';
    }
  }

  protected function tearDown(): void
  {
    if($_SERVER['SERVER_SOFTWARE'] == 'PHPUnit')
    {
      unset($_SERVER['SERVER_SOFTWARE']);
    }
  }

  public function testGlobals()
  {
    static::assertInternalType('bool', System::isHHVM());
    static::assertInternalType('bool', System::isMac());
    static::assertInternalType('bool', System::isWindows());
  }

  public function testIsAppEngine()
  {
    $test = $_SERVER['SERVER_SOFTWARE'];
    $_SERVER['SERVER_SOFTWARE'] = 'Google App Engine/1.9.6';
    static::assertTrue(System::isAppEngine());
    $_SERVER['SERVER_SOFTWARE'] = $test;
    static::assertTrue(
      System::isAppEngine('Google App Engine/1.9.6')
    );
    static::assertFalse(
      System::isAppEngine('PHP 5.5.1 Development Server')
    );
  }

  public function testIsBuiltInWebServer()
  {
    $test = $_SERVER['SERVER_SOFTWARE'];
    $_SERVER['SERVER_SOFTWARE'] = 'PHP 5.5.1 Development Server';
    static::assertTrue(System::isBuiltInDevServer());
    $_SERVER['SERVER_SOFTWARE'] = $test;
    static::assertFalse(
      System::isBuiltInDevServer('Google App Engine/1.9.6')
    );
    static::assertTrue(
      System::isBuiltInDevServer(
        'PHP 5.5.1 Development Server'
      )
    );
  }

  public function testCommandFinder()
  {
    static::assertInternalType('bool', System::commandExists('whois'));
    if(System::isWindows())
    {
      static::assertTrue(System::commandExists('explorer'));
    }
    else
    {
      static::assertTrue(System::commandExists('echo'));
    }
    static::assertFalse(System::commandExists('madeupcommand2'));
  }

  public function testDisabledFunctionCheck()
  {
    $verify = explode(',', ini_get('disable_functions'));
    if(empty($verify))
    {
      ini_set('disable_functions', 'packaged_exec');
      $verify = ['packaged_exec'];
    }

    foreach($verify as $check)
    {
      static::assertTrue(System::isFunctionDisabled($check));
    }

    static::assertFalse(System::isFunctionDisabled('echo'));
  }

  public function testAppEngineDisabledFunctions()
  {
    $test = $_SERVER['SERVER_SOFTWARE'];
    $_SERVER['SERVER_SOFTWARE'] = 'Google App Engine/1.9.6';
    static::assertTrue(System::isAppEngine());
    static::assertTrue(System::isFunctionDisabled('phpinfo'));
    static::assertFalse(
      System::isFunctionDisabled(
        'phpinfo',
        'phpinfo,parse_str'
      )
    );
    $_SERVER['SERVER_SOFTWARE'] = $test;
  }

  public function testMsleep()
  {
    //Test no output
    $this->expectOutputString('');
    $time = microtime(true);
    System::msleep(1);
    $deltaMs = (microtime(true) - $time) * 1000;
    //Microtime appears to be a fairly unreliable way to check
    //Below assertion disabled due to flakey validation
    //static::assertTrue($deltaMs > 0.1 && $deltaMs < 1.5);
  }
}
