<?php

/**
 * Work Angel Test
 * Based on Zend Framework 2
 * 
 * @author Michal Gacki
 */

namespace User\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class User implements InputFilterAwareInterface {
    
    //--------------------------------------------------------------------------
    /**
     * Model definitions
     */
    //--------------------------------------------------------------------------

    /**
     * User internal ID
     * @var integer
     */
    protected $id = 0;
    
    /**
     * User email
     * @var string
     */
    protected $email = null;
    
    /**
     * User password
     * @var string
     */
    protected $password = null;
    
    /**
     * Password salt
     * @var string
     */
    protected $salt = null;
    
    /**
     * Date added
     * @var string
     */
    protected $date_created = null;
    
    /**
     * Date removed
     * @var string
     */
    protected $date_removed = null;
    
    /**
     * User timezone
     * @var string
     */
    protected $timezone = 'UTC';
    
    /**
     * Status
     * @var string
     * @example active|removed|inactive
     */
    protected $status = 'active';
    
    /**
     * Zend input filter
     * @var type 
     */
    protected $inputFilter;
    
    //--------------------------------------------------------------------------
    /**
     * Basic getters and setters
     */
    //--------------------------------------------------------------------------
    
    /**
     * Get user ID
     * @return integer
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Set user ID
     * @param integer $id Unique ID
     */
    public function setId($id) {
        $this->id = intval($id);
    }
    
    /**
     * Get user email
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set user email
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }
    
    /**
     * Get user hashed password
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }
    
    /**
     * Set user hashed password. Salt will be generated automatically.
     * @param string $password Plain password to hash
     */
    public function setPassword($password) {
        $this->password = $password;
    }
    
    /**
     * Get user password salt
     * @return type
     */
    public function getSalt() {
        return $this->salt;
    }
    
    /**
     * Set user password salt.
     * DO NOT use this method alone. Look: setPassword
     * @param string $salt Salt
     */
    public function setSalt($salt) {
        $this->salt = $salt;
    }
    
    /**
     * Get wallet creation date in format YYYY-MM-DD.
     * All dates are stored in UTC time.
     * @return string
     */
    public function getDateCreated() {
        return $this->date_created;
    }
    
    /**
     * Set wallet creation date
     * @param string $date_created Date in any format. Be aware of user timezone
     * @param string [$timezone='UTC'] New timezone if you know what you are doing
     */
    public function setDateCreated($date_created, $timezone = 'UTC') {
        
        $date = new \DateTime($date_created);
        
        if ($timezone !== null) {
            $timezone = new \DateTimeZone($timezone);
            $date->setTimezone($timezone);
        }
        
        $this->date_created = $date->format('Y-m-d H:i:s');
        
    }
    
    /**
     * Get wallet removal date in format YYYY-MM-DD.
     * All dates are stored in UTC time.
     * @return string
     */
    public function getDateRemoved() {
        return $this->date_removed;
    }
    
    /**
     * Set wallet removal date
     * @param string $date_removed Date in any format. Be aware of user timezone
     * @param string [$timezone='UTC'] New timezone if you know what you are doing
     */
    public function setDateRemoved($date_removed, $timezone = 'UTC') {
        
        $date = new \DateTime($date_removed);
        
        if ($timezone !== null) {
            $timezone = new \DateTimeZone($timezone);
            $date->setTimezone($timezone);
        }
        
        $this->date_removed = $date->format('Y-m-d H:i:s');
        
    }
    
    /**
     * Get user timezone
     * @return string
     */
    public function getTimezone() {
        return $this->timezone;
    }
    
    /**
     * Set user timezone
     * @param string [$timezone='UTC'] New timezone
     */
    public function setTimezone($timezone = 'UTC') {
        $this->timezone = $timezone;
    }
    
    /**
     * Get wallet status. 
     * Returns "active" if the wallet is active or "inactive" if not.
     * @return string 
     */
    public function getStatus() {
        return $this->status;
    }
    
    /**
     * Set wallet status
     * @param string $status Wallet status active|inactive
     */
    public function setStatus($status = 'active') {
        
        if (in_array($status, array('active', 'inactive', 'removed'))) {
            $this->status = $status;
        }
        
    }
    
    /**
     * Set input filter
     * @param \User\Model\InputFilterInterface $inputFilter
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    /**
     * Get input filter
     * @return type
     */
    public function getInputFilter() {
        
        if (!$this->inputFilter) {
            
            $inputFilter = new InputFilter();

            /**
             * `email`
             * is required
             */
            $inputFilter->add(array(
                'name' => 'email',
                'required' => true
            ));
            
            /**
             * `password`
             * is required
             * @todo checking passwords equality in user register
             */
            $inputFilter->add(array(
                'name' => 'password',
                'required' => true
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    
    //--------------------------------------------------------------------------
    /**
     * Main methods
     */
    //--------------------------------------------------------------------------
    
    /**
     * Exchange array mapping for Zend
     * @param type $data
     */
    public function exchangeArray($data) {
        
        if (!empty($data['id'])) {
            $this->setId($data['id']);
        }
        
        if (!empty($data['email'])) {
            $this->setEmail($data['email']);
        }
        
        if (!empty($data['password'])) {
            $this->setPassword($data['password']);
        }
        
        if (!empty($data['salt'])) {
            $this->setSalt($data['salt']);
        }
        
        if (!empty($data['date_created'])) {
            $this->setDateCreated($data['date_created']);
        }
        
        if (!empty($data['date_removed'])) {
            $this->setDateRemoved($data['date_removed']);
        }
        
        if (!empty($data['timezone'])) {
            $this->setTimezone($data['timezone']);
        }
        
        if (!empty($data['status'])) {
            $this->setStatus($data['status']);
        }
        
    }
    
    /**
     * Get array copy
     * @return array
     */
    public function getArrayCopy() {
        return get_object_vars($this);
    }

}
