<?php

class EmailAddressTest extends PHPUnit_Framework_TestCase
{
  /**
   * @dataProvider emailProvider
   */
  public function testEmail($email, $matches)
  {
    $extracted = new \Packaged\Helpers\EmailAddress($email);

    error_log(
      print_r(
        [
          'username'   => $extracted->getUsername(),
          'domain'     => $extracted->getDomain(),
          'firstName'  => $extracted->getFirstName(),
          'middleName' => $extracted->getMiddleName(),
          'lastName'   => $extracted->getLastName(),
          'fullName'   => $extracted->getFullName(),
          'lower'      => $extracted->getLower(),
          'base'       => $extracted->getBase(),
          'extension'  => $extracted->getExtension(),
        ],
        true
      )
    );

    if(isset($matches['username']))
    {
      $this->assertEquals($matches['username'], $extracted->getUsername());
    }
    if(isset($matches['domain']))
    {
      $this->assertEquals($matches['domain'], $extracted->getDomain());
    }
    if(isset($matches['firstName']))
    {
      $this->assertEquals($matches['firstName'], $extracted->getFirstName());
    }
    if(isset($matches['middleName']))
    {
      $this->assertEquals($matches['middleName'], $extracted->getMiddleName());
    }
    if(isset($matches['lastName']))
    {
      $this->assertEquals($matches['lastName'], $extracted->getLastName());
    }
    if(isset($matches['fullName']))
    {
      $this->assertEquals($matches['fullName'], $extracted->getFullName());
    }
    if(isset($matches['lower']))
    {
      $this->assertEquals($matches['lower'], $extracted->getLower());
    }
    if(isset($matches['base']))
    {
      $this->assertEquals($matches['base'], $extracted->getBase());
    }
    if(isset($matches['extension']))
    {
      $this->assertEquals($matches['extension'], $extracted->getExtension());
    }
  }

  public function emailProvider()
  {
    return [
      [
        'john@exAmPle.cOm',
        [
          'username'   => 'john',
          'domain'     => 'example.com',
          'firstName'  => 'John',
          'middleName' => '',
          'lastName'   => '',
          'fullName'   => 'John',
          'lower'      => 'john@example.com',
          'base'       => 'john',
          'extension'  => '',
        ],
      ],
      [
        'john.smith@exAmPle.cOm',
        [
          'username'   => 'john.smith',
          'domain'     => 'example.com',
          'firstName'  => 'John',
          'middleName' => '',
          'lastName'   => 'Smith',
          'fullName'   => 'John Smith',
          'lower'      => 'john.smith@example.com',
          'base'       => 'john.smith',
          'extension'  => '',
        ],
      ],
      [
        'john.smith+2134@exAmPle.cOm',
        [
          'username'   => 'john.smith',
          'domain'     => 'example.com',
          'firstName'  => 'John',
          'middleName' => '',
          'lastName'   => 'Smith',
          'fullName'   => 'John Smith',
          'lower'      => 'john.smith+2134@example.com',
          'base'       => 'john.smith',
          'extension'  => '2134',
        ],
      ],
      [
        'JohnSmith@exAmPle.cOm',
        [
          'username'   => 'johnsmith',
          'domain'     => 'example.com',
          'firstName'  => 'John',
          'middleName' => '',
          'lastName'   => 'Smith',
          'fullName'   => 'John Smith',
          'lower'      => 'johnsmith@example.com',
          'base'       => 'JohnSmith',
          'extension'  => '',
        ],
      ],
      [
        'JohnBonSmith@exAmPle.cOm',
        [
          'username'   => 'johnbonsmith',
          'domain'     => 'example.com',
          'firstName'  => 'John',
          'middleName' => 'Bon',
          'lastName'   => 'Smith',
          'fullName'   => 'John Bon Smith',
          'lower'      => 'johnbonsmith@example.com',
          'base'       => 'JohnBonSmith',
          'extension'  => '',
        ],
      ],
    ];
  }
}
