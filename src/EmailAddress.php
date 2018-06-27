<?php
namespace Packaged\Helpers;

class EmailAddress
{
  protected $_username = '';
  protected $_domain = '';
  protected $_title = '';
  protected $_firstName = '';
  protected $_middleName = '';
  protected $_lastName = '';
  protected $_suffix = '';
  protected $_fullName = '';
  protected $_lower = '';
  protected $_base = '';
  protected $_extension = '';
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
    $this->_raw = trim($email);
    return $this;
  }

  public function setName($first = '', $middle = '', $last = '')
  {
    if($last == $first)
    {
      $last = '';
    }
    $this->_providedName = [trim($first), trim($middle), trim($last)];
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
    if(empty($last) || $first == $last)
    {
      $newProvided = explode(
        ' ',
        Strings::splitOnCamelCase(Strings::splitOnUnderscores(str_replace('.', ' ', $first)))
      );
      switch(count($newProvided))
      {
        case 1:
          break;
        case 2:
          $first = $newProvided[0];
          $last = $newProvided[1];
          break;
        default:
          $first = array_shift($newProvided);
          $last = array_pop($newProvided);
          $middle = implode(' ', $newProvided);
      }
    }
    $first = strtolower($first);

    $this->_fullName = Strings::splitOnCamelCase(Strings::splitOnUnderscores(str_replace('.', ' ', $this->_base)));
    $this->_fullName = strtolower(preg_replace('([0-9])', ' ', $this->_fullName));

    $fcheck = preg_replace('([^a-zA-Z])', '', $first);
    if(strlen($fcheck) == 1)
    {
      $titles = ['dr', 'prof'];
      foreach($titles as $title)
      {
        $checkLen = strlen($title) + 1;
        if(substr($this->_fullName, 0, $checkLen) == $title . $fcheck)
        {
          $this->_title = ucfirst($title);
          $this->_fullName = substr($this->_fullName, strlen($title));
          $first = '';
          break;
        }
      }
    }

    $lastFound = strrev(Strings::commonPrefix(strrev(strtolower($this->_fullName)), strrev(strtolower($last))));
    $lastLen = strlen($lastFound);
    if($lastLen > 2)
    {
      $pos = (strlen($lastFound) * -1);
      $newLast = substr($this->_fullName, $pos);
      $this->_fullName = substr($this->_fullName, 0, $pos) . ' ' . $newLast;
      if(empty($middle))
      {
        $parts = explode($lastFound, $last, 2);
        if(count($parts) == 2 && empty($parts[1]))
        {
          $middle = trim(str_replace('.', ' ', $parts[0]));
          $last = $lastFound;
        }
      }
    }
    else if(strlen($first) > 1)
    {
      $this->_fullName = str_ireplace($first, $first . ' ', $this->_fullName);
    }

    $this->_fullName = trim($this->_fullName);

    if(strlen($first) > 1 && strlen($this->_fullName) > 1)
    {
      if($this->_fullName[0] == $first[0] && $this->_fullName[1] != $first[1])
      {
        $this->_fullName = substr($this->_fullName, 0, 1) . ' ' . substr($this->_fullName, 1);
      }
    }
    if(strlen($last) > 1 && strlen($this->_fullName) > 1)
    {
      $lpos = strlen($last);
      if(strtolower(substr($this->_fullName, $lpos * -1)) == strtolower($last))
      {
        $this->_fullName = substr($this->_fullName, 0, $lpos * -1) . ' ' . substr($this->_fullName, $lpos * -1);
      }
    }

    $firstCommon = Strings::commonPrefix($first, $this->_fullName);
    $comLen = strlen($firstCommon);
    $matchStart = strtolower(substr($this->_providedName[0], 0, $comLen)) != $firstCommon;
    if($comLen > 2)
    {
      if($matchStart || strtolower(str_replace(' ', '', $this->_fullName)) == $first)
      {
        $first = $firstCommon;
      }
      $this->_fullName = $first . ' ' . substr($this->_fullName, $comLen);
    }
    $this->_fullName = ucwords(trim(preg_replace('!\s+!', ' ', $this->_fullName)));

    $nameParts = explode(' ', $this->_fullName);

    if(count($nameParts) == 1)
    {
      if(empty($last))
      {
        $lastCommon = strrev(Strings::commonPrefix(strrev(strtolower($fcheck)), strrev(strtolower($nameParts[0]))));
        if($lastCommon != $first)
        {
          $fname = ucfirst(str_replace($lastCommon, '', $fcheck));
          if(!empty($fname))
          {
            $last = $lastCommon;
            $first = $fname;
            array_unshift($nameParts, $fname);
          }
        }
      }
      else if(!empty($first) && strtolower($last) == strtolower($nameParts[0]))
      {
        array_unshift($nameParts, ucfirst($first));
      }
    }

    switch(count($nameParts))
    {
      case 1:
        $this->_firstName = trim($nameParts[0]);
        $this->_middleName = ucfirst($middle);
        $this->_lastName = ucfirst($last);
        break;
      case 2:
        $this->_firstName = trim($nameParts[0]);
        $this->_middleName = ucfirst($middle);
        $this->_lastName = trim($nameParts[1]);
        break;
      default:
        $this->_firstName = trim(array_shift($nameParts));
        $this->_lastName = trim(array_pop($nameParts));
        $this->_middleName = trim(implode(' ', $nameParts));
        break;
    }

    if(Strings::commonPrefix($this->_lastName, $last) == $this->_lastName)
    {
      $this->_lastName = $last;
    }

    if(strlen($first) > strlen($this->_firstName)
      || (strncasecmp(substr($first, 0, 1), substr($this->_firstName, 0, 1), 1) !== 0
        && !empty($first))
    )
    {
      if(strtolower($this->_providedName[0]) != trim(strtolower($first)))
      {
        $this->_firstName = trim(ucwords(strtolower($first)));
      }
      else
      {
        $this->_firstName = $this->_providedName[0];
      }
    }

    if(strlen($last) > strlen($this->_lastName)
      || (strncasecmp(substr($last, 0, 1), substr($this->_lastName, 0, 1), 1) !== 0
        && !empty($last))
    )
    {
      if(strtolower($this->_providedName[2]) != trim(strtolower($last)))
      {
        $this->_lastName = trim(ucwords(strtolower($last)));
      }
      else
      {
        $this->_lastName = $this->_providedName[2];
      }
    }

    if(strlen($middle) > strlen($this->_middleName)
      || (strncasecmp(substr($middle, 0, 1), substr($this->_middleName, 0, 1), 1) !== 0
        && !empty($middle))
    )
    {
      if(strtolower($this->_providedName[1]) != trim(strtolower($middle)))
      {
        $this->_middleName = trim(ucwords(strtolower($middle)));
      }
      else
      {
        $this->_middleName = $this->_providedName[1];
      }
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
  public function getTitle()
  {
    $this->_calculate();
    return $this->_title;
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
