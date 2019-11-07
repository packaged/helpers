<?php

namespace Packaged\Tests;

use Packaged\Helpers\ExceptionHelper;
use Packaged\Tests\Objects\Thing;
use PHPUnit\Framework\TestCase;
use stdClass;

class ExceptionHelperTest extends TestCase
{
  /**
   * @dataProvider dataProvider
   *
   * @param $arguments
   * @param $expected
   */
  public function testExceptionTrace($arguments, $expected)
  {
    $tail = '#2 /vendor/phpunit/phpunit/src/Framework/TestCase.php(1062): ReflectionMethod->invokeArgs(Object(Packaged\Tests\ExceptionHelperTest), Array)
#3 /vendor/phpunit/phpunit/src/Framework/TestCase.php(913): PHPUnit_Framework_TestCase->runTest()
#4 /vendor/phpunit/phpunit/src/Framework/TestResult.php(686): PHPUnit_Framework_TestCase->runBare()
#5 /vendor/phpunit/phpunit/src/Framework/TestCase.php(868): PHPUnit_Framework_TestResult->run(Object(Packaged\Tests\ExceptionHelperTest))
#6 /vendor/phpunit/phpunit/src/Framework/TestSuite.php(733): PHPUnit_Framework_TestCase->run(Object(PHPUnit_Framework_TestResult))
#7 /vendor/phpunit/phpunit/src/Framework/TestSuite.php(733): PHPUnit_Framework_TestSuite->run(Object(PHPUnit_Framework_TestResult))
#8 /vendor/phpunit/phpunit/src/Framework/TestSuite.php(733): PHPUnit_Framework_TestSuite->run(Object(PHPUnit_Framework_TestResult))
#9 /vendor/phpunit/phpunit/src/TextUI/TestRunner.php(517): PHPUnit_Framework_TestSuite->run(Object(PHPUnit_Framework_TestResult))
#10 /vendor/phpunit/phpunit/src/TextUI/Command.php(186): PHPUnit_TextUI_TestRunner->doRun(Object(PHPUnit_Framework_TestSuite), Array, true)
#11 /vendor/phpunit/phpunit/src/TextUI/Command.php(116): PHPUnit_TextUI_Command->run(Array, true)
#12 /vendor/phpunit/phpunit/phpunit(52): PHPUnit_TextUI_Command::main()
#13 {main}';

    try
    {
      $this->_someException(...$arguments);
    }
    catch(\Throwable $e)
    {
      $this->assertEquals(
        "#0 /tests/ExceptionHelperTest.php(35): Packaged\Tests\ExceptionHelperTest->_someException({$expected[0]})
#1 [internal function]: Packaged\\Tests\\ExceptionHelperTest->testExceptionTrace(Array, Array)
$tail",
        $this->_normalize($e->getTraceAsString())
      );
      $this->assertEquals(
        "#0 /tests/ExceptionHelperTest.php(35): Packaged\Tests\ExceptionHelperTest->_someException({$expected[1]})
#1 [internal function]: Packaged\\Tests\\ExceptionHelperTest->testExceptionTrace(Array, Array)
$tail",
        $this->_normalize(ExceptionHelper::getTraceAsString($e))
      );
    }
  }

  public function dataProvider()
  {
    $res = tmpfile();
    $resOutput = (string)$res;
    return [
      [
        [12345, 'string', null, ['array'], $res],
        ["12345, 'string', NULL, Array, $resOutput", "12345, 'string', NULL, Array, $resOutput (stream)"],
      ],
      [
        [12345, '123456789012345678901234567890', ['array']],
        ["12345, '123456789012345...', Array", "12345, '123456789012345678901234567890', Array"],
      ],
      [['123456789012345678901234567890'], ["'123456789012345...'", "'123456789012345678901234567890'"]],
      [[new stdClass()], ["Object(stdClass)", "Object(stdClass)"]],
      [[new Thing('', '', '', '')], ["Object(Packaged\Tests\Objects\Thing)", "Object(Packaged\Tests\Objects\Thing)"]],
    ];
  }

  private function _someException(...$args)
  {
    throw new \Exception('test exception');
  }

  private function _normalize(string $str)
  {
    return preg_replace('/^(#\d+) .+?\/helpers/m', '$1 ', $str);
  }
}
