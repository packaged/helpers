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
    $this->assertInternalType('bool', \Packaged\Helpers\System::isHHVM());
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
    $this->assertTrue(\Packaged\Helpers\System::isBuiltInDevServer());
    $_SERVER['SERVER_SOFTWARE'] = $test;
    $this->assertFalse(
      \Packaged\Helpers\System::isBuiltInDevServer('Google App Engine/1.9.6')
    );
    $this->assertTrue(
      \Packaged\Helpers\System::isBuiltInDevServer(
        'PHP 5.5.1 Development Server'
      )
    );
  }

  public function testCommandFinder()
  {
    $this->assertInternalType(
      'bool',
      \Packaged\Helpers\System::commandExists('whois')
    );
    if(\Packaged\Helpers\System::isWindows())
    {
      $this->assertTrue(
        \Packaged\Helpers\System::commandExists('explorer')
      );
    }
    else
    {
      $this->assertTrue(
        \Packaged\Helpers\System::commandExists('echo')
      );
    }
    $this->assertFalse(
      \Packaged\Helpers\System::commandExists('madeupcommand2')
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
      $this->assertTrue(\Packaged\Helpers\System::isFunctionDisabled($check));
    }

    $this->assertFalse(\Packaged\Helpers\System::isFunctionDisabled('echo'));
  }

  public function testAppEngineDisabledFunctions()
  {
    $test                       = $_SERVER['SERVER_SOFTWARE'];
    $_SERVER['SERVER_SOFTWARE'] = 'Google App Engine/1.9.6';
    $this->assertTrue(\Packaged\Helpers\System::isAppEngine());
    $this->assertTrue(\Packaged\Helpers\System::isFunctionDisabled('phpinfo'));
    $this->assertFalse(
      \Packaged\Helpers\System::isFunctionDisabled(
        'phpinfo',
        'phpinfo,parse_str'
      )
    );
    $_SERVER['SERVER_SOFTWARE'] = $test;
  }
}
