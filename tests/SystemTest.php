<?php
namespace Packaged\Tests;

use Packaged\Helpers\System;

class SystemTest extends \PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    if(!isset($_SERVER['SERVER_SOFTWARE']))
    {
      $_SERVER['SERVER_SOFTWARE'] = 'PHPUnit';
    }
  }

  protected function tearDown()
  {
    if($_SERVER['SERVER_SOFTWARE'] == 'PHPUnit')
    {
      unset($_SERVER['SERVER_SOFTWARE']);
    }
  }

  public function testGlobals()
  {
    $this->assertInternalType('bool', System::isHHVM());
    $this->assertInternalType('bool', System::isMac());
    $this->assertInternalType('bool', System::isWindows());
  }

  public function testIsAppEngine()
  {
    $test = $_SERVER['SERVER_SOFTWARE'];
    $_SERVER['SERVER_SOFTWARE'] = 'Google App Engine/1.9.6';
    $this->assertTrue(System::isAppEngine());
    $_SERVER['SERVER_SOFTWARE'] = $test;
    $this->assertTrue(
      System::isAppEngine('Google App Engine/1.9.6')
    );
    $this->assertFalse(
      System::isAppEngine('PHP 5.5.1 Development Server')
    );
  }

  public function testIsBuiltInWebServer()
  {
    $test = $_SERVER['SERVER_SOFTWARE'];
    $_SERVER['SERVER_SOFTWARE'] = 'PHP 5.5.1 Development Server';
    $this->assertTrue(System::isBuiltInDevServer());
    $_SERVER['SERVER_SOFTWARE'] = $test;
    $this->assertFalse(
      System::isBuiltInDevServer('Google App Engine/1.9.6')
    );
    $this->assertTrue(
      System::isBuiltInDevServer(
        'PHP 5.5.1 Development Server'
      )
    );
  }

  public function testCommandFinder()
  {
    $this->assertInternalType(
      'bool',
      System::commandExists('whois')
    );
    if(System::isWindows())
    {
      $this->assertTrue(
        System::commandExists('explorer')
      );
    }
    else
    {
      $this->assertTrue(
        System::commandExists('echo')
      );
    }
    $this->assertFalse(
      System::commandExists('madeupcommand2')
    );
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
      $this->assertTrue(System::isFunctionDisabled($check));
    }

    $this->assertFalse(System::isFunctionDisabled('echo'));
  }

  public function testAppEngineDisabledFunctions()
  {
    $test = $_SERVER['SERVER_SOFTWARE'];
    $_SERVER['SERVER_SOFTWARE'] = 'Google App Engine/1.9.6';
    $this->assertTrue(System::isAppEngine());
    $this->assertTrue(System::isFunctionDisabled('phpinfo'));
    $this->assertFalse(
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
    //$this->assertTrue($deltaMs > 0.1 && $deltaMs < 1.5);
  }
}
