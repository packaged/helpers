<?php
namespace Packaged\Helpers;

/**
 * Fully qualified domain name
 */
class FQDN
{
  protected $_fqdn;
  protected $_processed;

  protected $_domain;
  protected $_subdomain;
  protected $_tld;

  protected $_definedTlds = [];
  protected $_knownTlds = [
    'co'  => 'co',
    'com' => 'com',
    'org' => 'org',
    'me'  => 'me',
    'gov' => 'gov',
    'net' => 'net',
    'edu' => 'edu'
  ];

  public function __construct($fqdn)
  {
    $this->setFqdn($fqdn);
  }

  public function setFqdn($fqdn)
  {
    if(filter_var($fqdn, FILTER_VALIDATE_URL))
    {
      $fqdn = parse_url($fqdn, 1);
    }
    $this->_fqdn = strtolower($fqdn);
    $this->_domain = $this->_subdomain = $this->_tld = null;
    $this->_processed = false;
    return $this;
  }

  /**
   * Define accepted TLDs for use when determining tlds
   *
   * @param array $tlds
   * @param bool  $append
   *
   * @return FQDN
   */
  public function defineTlds(array $tlds, $append = false)
  {
    $tlds = array_combine($tlds, $tlds);
    if($append)
    {
      $this->_definedTlds = array_merge($this->_definedTlds, $tlds);
    }
    else
    {
      $this->_definedTlds = $tlds;
    }
    return $this;
  }

  /**
   * Returns a list of user defined TLDs, used for calculating domain parts
   *
   * @return array
   */
  public function getDefinedTlds()
  {
    return array_keys($this->_definedTlds);
  }

  /**
   * Take the host string and split into subdomain , domain & tld
   *
   * @return $this
   */
  protected function _prepareHost()
  {
    if($this->_processed)
    {
      return $this;
    }

    $parts = array_reverse(explode('.', $this->_fqdn));
    $this->_processed = true;

    if(count($parts) == 1)
    {
      $this->_domain = $parts[0];
      return $this;
    }

    foreach($parts as $i => $part)
    {
      if(empty($this->_tld))
      {
        $this->_tld = $part;
        continue;
      }

      if(empty($this->_domain))
      {
        if($i < 2
          && (strlen($part) == 2
            || isset($this->_definedTlds[$part . '.' . $this->_tld])
            || isset($this->_knownTlds[$part])
          )
        )
        {
          $this->_tld = $part . '.' . $this->_tld;
        }
        else
        {
          $this->_domain = $part;
        }
        continue;
      }

      if(empty($this->_subdomain))
      {
        $this->_subdomain = $part;
      }
      else
      {
        $this->_subdomain = $part . '.' . $this->_subdomain;
      }
    }

    return $this;
  }

  /**
   * Sub Domain e.g. www.
   *
   * @return string|null
   */
  public function subDomain()
  {
    $this->_prepareHost();
    return $this->_subdomain;
  }

  /**
   * Main domain, excluding sub domains and tlds
   *
   * @return string
   */
  public function domain()
  {
    $this->_prepareHost();
    return $this->_domain;
  }

  /**
   * Top Level Domain
   *
   * @return string
   */
  public function tld()
  {
    $this->_prepareHost();
    return $this->_tld;
  }
}
