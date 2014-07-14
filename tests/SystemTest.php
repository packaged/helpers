<?php

class SystemTest extends PHPUnit_Framework_TestCase
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
    $this->assertInternalType('bool', \Packaged\Helpers\System::isHipHop());
    $this->assertInternalType('bool', \Packaged\Helpers\System::isMac());
    $this->assertInternalType('bool', \Packaged\Helpers\System::isWindows());
  }

  public function testIsAppEngine()
  {
    $test                       = $_SERVER['SERVER_SOFTWARE'];
    $_SERVER['SERVER_SOFTWARE'] = 'Google App Engine/1.9.6';
    $this->assertTrue(\Packaged\Helpers\System::isAppEngine());
    $_SERVER['SERVER_SOFTWARE'] = $test;
    $this->assertTrue(
      \Packaged\Helpers\System::isAppEngine('Google App Engine/1.9.6')
    );
    $this->assertFalse(
      \Packaged\Helpers\System::isAppEngine('PHP 5.5.1 Development Server')
    );
  }

  public function testIsBuiltInWebServer()
  {
    $test                       = $_SERVER['SERVER_SOFTWARE'];
    $_SERVER['SERVER_SOFTWARE'] = 'PHP 5.5.1 Development Server';
    $this->assertTrue(\Packaged\Helpers\System::isBuildInDevServer());
    $_SERVER['SERVER_SOFTWARE'] = $test;
    $this->assertFalse(
      \Packaged\Helpers\System::isBuildInDevServer('Google App Engine/1.9.6')
    );
    $this->assertTrue(
      \Packaged\Helpers\System::isBuildInDevServer(
        'PHP 5.5.1 Development Server'
      )
    );
  }
}
