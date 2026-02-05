<?php
namespace Packaged\Tests;

use Packaged\Helpers\FQDN;
use PHPUnit\Framework\TestCase;

class FQDNTest extends TestCase
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
    $fq = new FQDN($fqdn);
    static::assertEquals($domain, $fq->domain());
    static::assertEquals($tld, $fq->tld());
    static::assertEquals($sub, $fq->subDomain());
  }

  public function fqdnProvider()
  {
    return [
      ['localhost', 'localhost', null, null],
      ['domain.com', 'domain', 'com', null],
      ['my.domain.com', 'domain', 'com', 'my'],
      ['my.domain.co.uk', 'domain', 'co.uk', 'my'],
      ['my.domain.ltd.uk', 'domain', 'ltd.uk', 'my'],
      ['second.my.domain.com', 'domain', 'com', 'second.my'],
    ];
  }

  public function testDefinedTlds()
  {
    $fq = new FQDN('my.test.random.tld');

    $fq->defineTlds(['random.tld', 'x.y'], true);
    static::assertEquals(['random.tld', 'x.y'], $fq->getDefinedTlds());

    $fq->defineTlds(['random.tld']);
    static::assertEquals(['random.tld'], $fq->getDefinedTlds());

    static::assertEquals('random.tld', $fq->tld());
    static::assertEquals('test', $fq->domain());
    static::assertEquals('my', $fq->subDomain());

    static::assertEquals(['random.tld'], $fq->getDefinedTlds());

    $fq = new FQDN('my.test.source.google');
    $fq->defineTlds(['google']);
    static::assertEquals('google', $fq->tld());
    static::assertEquals('source', $fq->domain());
    static::assertEquals('my.test', $fq->subDomain());
  }

  public function testDefinedStarTlds()
  {
    $fq = new FQDN('my.test.qa.random.tld');
    $fq->defineTlds(['*.random.tld', 'x.y'], true);
    static::assertEquals('test', $fq->domain());
    static::assertEquals('my', $fq->subDomain());
    static::assertEquals('qa.random.tld', $fq->tld());
  }

  public function testUrl()
  {
    $fq = new FQDN('http://my.test.co.uk/webpage.html');
    static::assertEquals("test", $fq->domain());
    static::assertEquals("co.uk", $fq->tld());
    static::assertEquals("my", $fq->subDomain());
  }

  public function testFullDomain()
  {
    $fq = new FQDN('www.example.com');
    static::assertEquals('example.com', $fq->fullDomain());
  }
}
