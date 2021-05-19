<?php
namespace Packaged\Tests;

use DateInterval;
use DateTime;
use Packaged\Helpers\DateTimeHelper;
use PHPUnit\Framework\TestCase;

class DateTimeHelperTest extends TestCase
{
  public function testDate()
  {
    $dates = DateTimeHelper::dateRange(
      "2017-01-01"
    );
    static::assertEquals(["2017-01-01"], $dates);
  }

  public function testBasicDates()
  {
    $dates = DateTimeHelper::dateRange(
      "2017-01-01,2017-02-02"
    );
    static::assertEquals(["2017-01-01", "2017-02-02"], $dates);
  }

  public function testSameMonthRange()
  {
    $dates = DateTimeHelper::dateRange(
      "2017-01-01-03"
    );
    static::assertEquals(["2017-01-01", "2017-01-02", "2017-01-03"], $dates);
  }

  public function testSameYearRange()
  {
    $dates = DateTimeHelper::dateRange(
      "2017-01-30-02-01"
    );
    static::assertEquals(["2017-01-30", "2017-01-31", "2017-02-01"], $dates);
  }

  public function testRange()
  {
    $dates = DateTimeHelper::dateRange(
      "2017-01-25-2017-01-29"
    );
    static::assertEquals(
      ["2017-01-25", "2017-01-26", "2017-01-27", "2017-01-28", "2017-01-29"],
      $dates
    );
  }

  public function testInverseDateRange()
  {
    $dates = DateTimeHelper::dateRange(
      "2017-01-03-01"
    );
    static::assertEquals(["2017-01-01", "2017-01-02", "2017-01-03"], $dates);
  }

  public function testInvalidDateRange()
  {
    $dates = DateTimeHelper::dateRange(
      "2017-01-03-"
    );
    static::assertEquals(["2017-01-03"], $dates);
  }

  public function testStringToTimeRangee()
  {
    $format = "Y-m-d";
    $compare = date($format, strtotime('-1 day'));
    $compare .= ',';
    $compare .= date($format, strtotime('14 days ago'));
    $compare .= '-';
    $compare .= date($format, strtotime('7 days ago'));
    $compare .= ',';
    $compare .= date($format, strtotime('-7days'));
    $compare .= ',';
    $compare .= date($format, strtotime('10 days ago'));
    $compare .= '-';
    $compare .= date($format, strtotime('-9 days'));
    $compare .= ',5 horse-6 horses,2017-01-01,2017-01-02,8 carrots,-9 horseshoes';

    $dates = DateTimeHelper::stringToTimeRange(
      "-1 day,14 days ago-7 days ago,-7days,10 days ago--9 days,5 horse-6 horses,2017-01-01,2017-01-02,8 carrots,-9 horseshoes"
    );
    static::assertEquals($compare, $dates);
  }

  public function testHumanDateRange()
  {
    $dates = DateTimeHelper::dateRange(
      DateTimeHelper::stringToTimeRange(
        "-1 day,14 days ago-7 days ago,-7days,10 days ago--9 days"
      )
    );

    $compare = [];
    $format = "Y-m-d";
    $compare[] = date($format, strtotime('-1 day'));
    $compare[] = date($format, strtotime('14 days ago'));
    $compare[] = date($format, strtotime('13 days ago'));
    $compare[] = date($format, strtotime('12 days ago'));
    $compare[] = date($format, strtotime('11 days ago'));
    $compare[] = date($format, strtotime('10 days ago'));
    $compare[] = date($format, strtotime('9 days ago'));
    $compare[] = date($format, strtotime('8 days ago'));
    $compare[] = date($format, strtotime('7 days ago'));
    $compare[] = date($format, strtotime('-7days'));
    $compare[] = date($format, strtotime('10 days ago'));
    $compare[] = date($format, strtotime('-9 days'));

    static::assertEquals($compare, $dates);
  }

  public function testIntervalToSeconds()
  {
    $y0 = DateTimeHelper::intervalToSeconds(new DateInterval('P1Y'));
    static::assertEquals(31536000, $y0);
    $y0s = DateTimeHelper::intervalToSeconds(new DateInterval('P1Y'), DateTime::createFromFormat('U', 0));
    static::assertEquals($y0, $y0s);

    $leapTest = DateTimeHelper::intervalToSeconds(
      new DateInterval('P1Y'),
      new DateTime('1 Jan 1972 00:00') // leap year
    );
    static::assertEquals(31622400, $leapTest);
  }

  /**
   * @param $microtime
   * @param $uniqid
   * @param $hasEntropy
   *
   * @dataProvider uniqidProvider
   */
  public function testUniqid2microtime($microtime, $uniqid, $hasEntropy)
  {
    $time = DateTimeHelper::uniqidToMilliseconds($uniqid, $hasEntropy);
    static::assertEquals($microtime, $time, '', 10);
  }

  public function uniqidProvider()
  {
    return [
      [DateTimeHelper::milliseconds(), uniqid(), false],
      [DateTimeHelper::milliseconds(), uniqid('PRE'), false],
      [DateTimeHelper::milliseconds(), uniqid('', true), true],
      [DateTimeHelper::milliseconds(), uniqid('PRE', true), true],
    ];
  }

  public function testMilliseconds()
  {
    static::assertIsInt( DateTimeHelper::milliseconds());
  }

  public function testToSeconds()
  {
    static::assertEquals(DateTimeHelper::toSeconds(1466159101859), 1466159101);
  }
}
