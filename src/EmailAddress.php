<?php
namespace Packaged\Helpers;

class EmailAddress
{
  protected $_username;
  protected $_domain;
  protected $_firstName;
  protected $_middleName;
  protected $_lastName;
  protected $_fullName;
  protected $_lower;
  protected $_base;
  protected $_extension;
  protected $_providedName = ['', '', ''];
  protected $_calculated = false;
  protected $_raw;

  public function __construct($email)
  {
    $this->setEmail($email);
  }

  public function setEmail($email)
  {
    $this->_calculated = false;
    $this->_raw = $email;
    return $this;
  }

  public function setName($first = '', $middle = '', $last = '')
  {
    $this->_providedName = [$first, $middle, $last];
    return $this;
  }

  protected function _calculate()
  {
    if(!$this->_calculated)
    {
      if(stristr($this->_raw, '@'))
      {
        list($username, $domain) = explode('@', trim($this->_raw), 2);
      }
      else
      {
        $username = $this->_raw;
        $domain = '';
      }
      $this->_domain = strtolower($domain);
      if(stristr($username, '+'))
      {
        list($this->_base, $this->_extension) = explode('+', $username, 2);
      }
      else
      {
        $this->_base = $username;
      }

      $this->_username = strtolower($this->_base);

      $this->_lower = strtolower($this->_raw);
      $this->_calculateName();
      $this->_calculated = true;
    }
  }

  protected function _calculateName()
  {
    list($first, $middle, $last) = $this->_providedName;
    $first = strtolower($first);

    $this->_fullName = Strings::splitOnCamelCase(
      Strings::splitOnUnderscores(str_replace('.', ' ', $this->_base))
    );

    $this->_fullName = strtolower(
      preg_replace('([0-9])', ' ', $this->_fullName)
    );

    if(strlen($first) > 0)
    {
      $this->_fullName = str_replace(
        $first,
        $first . ' ',
        $this->_fullName
      );
    }

    if(strlen($first) > 1 && strlen($this->_fullName) > 1)
    {
      if($this->_fullName[0] == $first[0] && $this->_fullName[1] != $first[1])
      {
        $this->_fullName = substr($this->_fullName, 0, 1)
          . ' ' . substr($this->_fullName, 1);
      }
    }

    $this->_fullName = ucwords(
      trim(preg_replace('!\s+!', ' ', $this->_fullName))
    );

    $nameParts = explode(' ', $this->_fullName);
    switch(count($nameParts))
    {
      case 1:
        $this->_firstName = $nameParts[0];
        break;
      case 2:
        $this->_firstName = $nameParts[0];
        $this->_lastName = $nameParts[1];
        break;
      default:
        $this->_firstName = array_shift($nameParts);
        $this->_lastName = array_pop($nameParts);
        $this->_middleName = implode(' ', $nameParts);
        break;
    }

    if(strlen($first) > strlen($this->_firstName)
      || (substr($first, 0, 1) != substr($this->_firstName, 0, 1)
        && !empty($first))
    )
    {
      $this->_firstName = ucwords(strtolower($first));
    }

    if(strlen($last) > strlen($this->_lastName)
      || (substr($last, 0, 1) != substr($this->_lastName, 0, 1)
        && !empty($last))
    )
    {
      $this->_lastName = ucwords(strtolower($last));
    }

    if(strlen($middle) > strlen($this->_middleName)
      || (substr($middle, 0, 1) != substr($this->_middleName, 0, 1)
        && !empty($middle))
    )
    {
      $this->_middleName = ucwords(strtolower($middle));
    }
  }

  /**
   * @return mixed
   */
  public function getUsername()
  {
    $this->_calculate();
    return $this->_username;
  }

  /**
   * @return mixed
   */
  public function getDomain()
  {
    $this->_calculate();
    return $this->_domain;
  }

  /**
   * @return mixed
   */
  public function getFirstName()
  {
    $this->_calculate();
    return $this->_firstName;
  }

  /**
   * @return mixed
   */
  public function getMiddleName()
  {
    $this->_calculate();
    return $this->_middleName;
  }

  /**
   * @return mixed
   */
  public function getLastName()
  {
    $this->_calculate();
    return $this->_lastName;
  }

  /**
   * @return mixed
   */
  public function getFullName()
  {
    $this->_calculate();
    return implode(
      ' ',
      array_filter([$this->_firstName, $this->_middleName, $this->_lastName])
    );
  }

  /**
   * @return mixed
   */
  public function getLower()
  {
    $this->_calculate();
    return $this->_lower;
  }

  /**
   * @return mixed
   */
  public function getBase()
  {
    $this->_calculate();
    return $this->_base;
  }

  /**
   * @return mixed
   */
  public function getExtension()
  {
    $this->_calculate();
    return $this->_extension;
  }

  public function getBaseEmail()
  {
    $this->_calculate();
    return $this->_base . '@' . $this->_domain;
  }
}
