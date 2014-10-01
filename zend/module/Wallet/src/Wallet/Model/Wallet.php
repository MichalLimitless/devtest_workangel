<?php

/**
 * Work Angel Test
 * Based on Zend Framework 2
 * 
 * Michal Gacki
 */

namespace Wallet\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Wallet {
    //--------------------------------------------------------------------------
    /**
     * Model definitions
     */
    //--------------------------------------------------------------------------

    /**
     * Wallet internal ID
     * @var integer
     */
    protected $id = null;

    /**
     * User ID
     * @var integer
     * @model User\Model 
     */
    protected $user_id = 0;

    /**
     * Currency ID
     * @var integer 
     */
    protected $currency_id_default = 1;

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
     * Status
     * @var string
     * @example active|removed 
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
     * Get wallet ID
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set wallet ID
     * @param integer $id Unique ID
     */
    public function setId($id) {
        $this->id = intval($id);
    }

    /**
     * Get wallet user ID
     * @return integer
     */
    public function getUserId() {
        return $this->user_id;
    }

    /**
     * Set wallet user ID
     * @param integer $user_id User ID from User\Model
     */
    public function setUserId($user_id) {
        $this->user_id = intval($user_id);
    }

    /**
     * Get wallet default currency ID.
     * User preference.
     * @return integer
     */
    public function getCurrencyIdDefault() {
        return $this->currency_id_default;
    }

    /**
     * Set wallet default currency ID
     * User preference.
     * @param integer [$currency_id_default=1] Currency ID from WalletCurrency\Model
     */
    public function setCurrencyIdDefault($currency_id_default = 1) {
        $this->currency_id_default = intval($currency_id_default);
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

        if (in_array($status, array('active', 'inactive'))) {
            $this->status = $status;
        }
        
    }

    /**
     * Set input filter
     * @param \Wallet\Model\InputFilterInterface $inputFilter
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
             * `currency_id_default`
             * is required
             */
            $inputFilter->add(array(
                'name' => 'currency_id_default',
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

        if (!empty($data['user_id'])) {
            $this->setUserId($data['user_id']);
        }

        if (!empty($data['currency_id_default'])) {
            $this->setCurrencyIdDefault($data['currency_id_default']);
        }

        if (!empty($data['date_created'])) {
            $this->setDateCreated($data['date_created']);
        }

        if (!empty($data['date_removed'])) {
            $this->setDateRemoved($data['date_removed']);
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
