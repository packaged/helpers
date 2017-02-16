<?php

class DateTimeHelperTest extends PHPUnit_Framework_TestCase
{
  public function testDate()
  {
    $dates = \Packaged\Helpers\DateTimeHelper::dateRange(
      "2017-01-01"
    );
    $this->assertEquals(["2017-01-01"], $dates);
  }

  public function testBasicDates()
  {
    $dates = \Packaged\Helpers\DateTimeHelper::dateRange(
      "2017-01-01,2017-02-02"
    );
    $this->assertEquals(["2017-01-01", "2017-02-02"], $dates);
  }

  public function testSameMonthRange()
  {
    $dates = \Packaged\Helpers\DateTimeHelper::dateRange(
      "2017-01-01-03"
    );
    $this->assertEquals(["2017-01-01", "2017-01-02", "2017-01-03"], $dates);
  }

  public function testSameYearRange()
  {
    $dates = \Packaged\Helpers\DateTimeHelper::dateRange(
      "2017-01-30-02-01"
    );
    $this->assertEquals(["2017-01-30", "2017-01-31", "2017-02-01"], $dates);
  }

  public function testRange()
  {
    $dates = \Packaged\Helpers\DateTimeHelper::dateRange(
      "2017-01-25-2017-01-29"
    );
    $this->assertEquals(
      ["2017-01-25", "2017-01-26", "2017-01-27", "2017-01-28", "2017-01-29"],
      $dates
    );
  }

  public function testInverseDateRange()
  {
    $dates = \Packaged\Helpers\DateTimeHelper::dateRange(
      "2017-01-03-01"
    );
    $this->assertEquals(["2017-01-01", "2017-01-02", "2017-01-03"], $dates);
  }

  public function testInvalidDateRange()
  {
    $dates = \Packaged\Helpers\DateTimeHelper::dateRange(
      "2017-01-03-"
    );
    $this->assertEquals(["2017-01-03"], $dates);
  }

}
