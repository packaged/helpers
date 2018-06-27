<?php

class EmailAddressTest extends PHPUnit_Framework_TestCase
{
  /**
   * @param       $email
   * @param       $matches
   * @param array $name
   *
   * @dataProvider emailProvider
   */
  public function testEmail($email, $matches, $name = ['', '', ''])
  {
    $extracted = new \Packaged\Helpers\EmailAddress($email);
    $extracted->setName($name[0], $name[1], $name[2]);

    if(isset($matches['username']))
    {
      $this->assertEquals($matches['username'], $extracted->getUsername());
    }

    if(isset($matches['domain']))
    {
      $this->assertEquals($matches['domain'], $extracted->getDomain());
    }

    if(isset($matches['title']))
    {
      $this->assertEquals($matches['title'], $extracted->getTitle());
    }

    if(isset($matches['firstName']))
    {
      $this->assertEquals($matches['firstName'], $extracted->getFirstName());
    }
    else if(!empty($name[0]))
    {
      if(strlen($name[0]) >= strlen($extracted->getFirstName()))
      {
        $this->assertEquals(ucwords($name[0]), $extracted->getFirstName());
      }
    }

    if(isset($matches['middleName']))
    {
      $this->assertEquals($matches['middleName'], $extracted->getMiddleName());
    }
    else if(!empty($name[1]))
    {
      if(strlen($name[1]) >= strlen($extracted->getMiddleName()))
      {
        $this->assertEquals(ucwords($name[1]), $extracted->getMiddleName());
      }
    }

    if(isset($matches['lastName']))
    {
      $this->assertEquals($matches['lastName'], $extracted->getLastName());
    }
    else if(!empty($name[2]))
    {
      if(strlen($name[2]) >= strlen($extracted->getLastName()))
      {
        $this->assertEquals(ucwords($name[2]), $extracted->getLastName());
      }
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
        'melanie.richards@bob.com',
        [
          'firstName' => 'Melanie',
          'lastName'  => 'Richards',
        ],
        ['Melanierichards', '', 'Melanierichards'],
      ],
      [
        'kev1n_sta@abc.com',
        [
          'firstName' => 'Kevin',
          'lastName'  => 'Stack',
        ],
        ['Kevin', '', 'Stack'],
      ],
      [
        'davy1640@abc.com',
        [
          'firstName' => 'Dave',
          'lastName'  => 'Roberts',
        ],
        ['Dave', '', 'Roberts'],
      ],
      [
        'richevans24@abc.com',
        [
          'firstName' => 'Rich',
          'lastName'  => 'Evans',
        ],
        ['Rich', '', 'Rich'],
      ],
      [
        'wmvanlee23@xtskh.com',
        [
          'firstName' => 'WilgardVan',
          'lastName'  => 'Lee',
        ],
        ['WilgardVan', '', 'Lee'],
      ],
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
      ['andall2001@hotmail.com', []],
      ['c.blennan@hotmail.co.uk', [], ['Christopher', '', '']],
      [
        'yaseenjuiya@hotmail.com',
        [
          'lastName' => 'Juiya',
        ],
        ['Yaseen', '', ''],
      ],
      ['dobie049851@aol.com', [], ['Norman', '', '']],
      ['jepboy43@yahoo.com', [], ['Josephopyd', '', '']],
      [
        'jbarron340@gmail.com',
        [
          'lastName' => 'Barron',
        ],
        ['Joanna', '', ''],
      ],
      [
        'brentdarla@gmail.com',
        [
          'lastName' => 'Darla',
        ],
        ['Brent', '', ''],
      ],
      ['leroylereaux@gmail.com', [], ['Troy', '', '']],
      ['leroylereaux@gmail.com', [], ['Troy', '', '']],
      ['xtianstj@gmail.com', [], ['Chris', '', 'Santos']],
      [
        'tkay@gmail.com',
        ['firstName' => 'Tom', 'lastName' => 'Kay'],
        ['Tom', '', ''],
      ],
      ['oridan80@gmail.com', [], ['Oliver', '', '']],
      [
        'drlaura84@gmail.com',
        [
          'firstName' => 'Laura',
          'title'     => 'Dr',
          'lastName'  => 'Wallace',
        ],
        ['L', '', 'Wallace'],
      ],
      ['bkellis@besouth.net', [], ['B', '', 'Kellis']],
      ['oakleya963@gmail.com', [], ['Ann', '', '']],
      [
        'ginnygerssner@yahoo.com',
        [
          'firstName' => 'Ginny',
          'lastName'  => 'Gerssner',
        ],
        ['Ginny', '', ''],
      ],
      [
        'dennisnesbitt@telkodsa.net',
        [
          'firstName'  => "Dennis",
          'middleName' => "B",
          'lastName'   => "Nesbitt",
        ],
        ['D', '', 'Bnesbitt'],
      ],
      [
        'philip_n_lancasters@gmail.com',
        [
          'firstName'  => 'Philip',
          'middleName' => 'N',
          'lastName'   => 'Lancasters',
        ],
        ['Philip', '', 'N.lancasters'],
      ],
      [
        'rochelle.dickens420@gmail.com',
        [
          'firstName' => 'Rochelle',
          'lastName'  => 'Dickens',
        ],
        ['rochelle', '', 'rochelle'],
      ],
      [
        'tom.kay@bob.com',
        [
          'firstName' => 'Tom',
          'lastName'  => 'Kay',
        ],
        ['T', '', 'K'],
      ],
      [
        'tdakay@bob.com',
        [
          'firstName' => 'Tda',
          'lastName'  => 'Kay',
        ],
        ['T', '', 'Kay'],
      ],
      [
        'chilli75@bob.com',
        [
          'firstName' => 'Paul',
          'lastName'  => 'Chilli',
        ],
        ['Paulchilli', '', ''],
      ],
      [
        'ccouper@bob.com',
        [
          'firstName' => 'C',
          'lastName'  => 'Couper',
        ],
        ['C', '', 'Couper'],
      ],
      [
        'marcosg24@bob.com',
        [
          'firstName' => 'Marcos',
          'lastName'  => 'Gray',
        ],
        ['MarcosGray', '', ''],
      ],
      [
        'tbomb136@abc.com',
        [
          'firstName' => 'Bill',
          'lastName'  => 'Santillo',
        ],
        ['Bill_santillo', '', ''],
      ],
      [
        'davidjlloyd@abc.com',
        [
          'firstName'  => 'David',
          'middleName' => 'J',
          'lastName'   => 'Lloyd',
        ],
        ['David', '', 'Lloyd'],
      ],
      [
        'robert.benson567@abc.com',
        [
          'firstName'  => 'Robert',
          'middleName' => 'A',
          'lastName'   => 'Benson',
        ],
        ['R.a.benson', '', 'R.a.benson'],
      ],
      [
        'metcalf830@abc.com',
        [
          'firstName'  => 'Melvin',
          'middleName' => 'L',
          'lastName'   => 'Metcalf',
        ],
        ['Melvin.l.metcalf', '', ''],
      ],
      [
        'accorrea13@abc.com',
        [
          'firstName'  => 'Alex',
          'middleName' => 'C',
          'lastName'   => 'Correa',
        ],
        ['Alex', '', 'Correa'],
      ],
    ];
  }

  public function testBaseEmail()
  {
    $email = new \Packaged\Helpers\EmailAddress("john.smith+123@domain.com");
    $this->assertEquals("john.smith@domain.com", $email->getBaseEmail());
  }

  public function testUsername()
  {
    $email = new \Packaged\Helpers\EmailAddress("john.smith");
    $this->assertEquals("john.smith", $email->getUsername());
  }
}
