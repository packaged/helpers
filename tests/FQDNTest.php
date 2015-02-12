<?php

class FQDNTest extends PHPUnit_Framework_TestCase
{
  /**
   * @dataProvider fqdnProvider
   *
   * @param $fqdn
   * @param $domain
   * @param $tld
   * @param $sub
   */

  public function testFqdn($fqdn, $domain, $tld, $sub)
  {
    $fq = new \Packaged\Helpers\FQDN($fqdn);
    $this->assertEquals($domain, $fq->domain());
    $this->assertEquals($tld, $fq->tld());
    $this->assertEquals($sub, $fq->subDomain());
  }

  public function fqdnProvider()
  {
    return [
      ['localhost', 'localhost', null, null],
      ['domain.com', 'domain', 'com', null],
      ['my.domain.com', 'domain', 'com', 'my'],
      ['my.domain.co.uk', 'domain', 'co.uk', 'my'],
      ['second.my.domain.com', 'domain', 'com', 'second.my'],
    ];
  }

  public function testDefinedTlds()
  {
    $fq = new \Packaged\Helpers\FQDN('my.test.random.tld');

    $fq->defineTlds(['random.tld', 'x.y'], true);
    $this->assertEquals(['random.tld', 'x.y'], $fq->getDefinedTlds());

    $fq->defineTlds(['random.tld']);
    $this->assertEquals(['random.tld'], $fq->getDefinedTlds());

    $this->assertEquals('random.tld', $fq->tld());
    $this->assertEquals('test', $fq->domain());
    $this->assertEquals('my', $fq->subDomain());

    $this->assertEquals(['random.tld'], $fq->getDefinedTlds());
  }

  public function testUrl()
  {
    $fq = new \Packaged\Helpers\FQDN('http://my.test.co.uk/webpage.html');
    $this->assertEquals("test", $fq->domain());
    $this->assertEquals("co.uk", $fq->tld());
    $this->assertEquals("my", $fq->subDomain());
  }
}
