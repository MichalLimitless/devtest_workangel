<?php

/**
 * Work Angel Test
 * Based on Zend Framework 2
 * 
 * Michal Gacki
 */

namespace Wallet\Model;

class WalletItem {
    
    //--------------------------------------------------------------------------
    /**
     * Model definitions
     */
    //--------------------------------------------------------------------------

    /**
     * Currency internal ID
     * @var integer
     */
    protected $id = null;
    
    /**
     * Wallet ID relation
     * @var integer
     */
    protected $wallet_id = 0;
    
    /**
     * Currency ID relation
     * @var integer
     */
    protected $currency_id = 1;
    
    /**
     * Amount
     * @var float
     */
    protected $amount = 0;
    
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
     * Currency production status
     * @var string
     * @example active|removed
     */
    protected $status = 'active';

    //--------------------------------------------------------------------------
    /**
     * Basic getters and setters
     */
    //--------------------------------------------------------------------------

    /**
     * Get item internal ID
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set item internal ID
     * @param integer $id Unique ID
     */
    public function setId($id) {
        $this->id = intval($id);
    }
    
    /**
     * Get Wallet ID 
     * @return integer
     */
    public function getWalletId() {
        return $this->order;
    }
    
    /**
     * Set Wallet ID 
     * @param integer $wallet_id
     */
    public function setWalletId($wallet_id) {
        $this->wallet_id = intval($wallet_id);
    }
    
    /**
     * Get Currency ID 
     * @return integer
     */
    public function getCurrencyId() {
        return $this->order;
    }
    
    /**
     * Set Currency ID 
     * @param integer $currency_id
     */
    public function setCurrencyId($currency_id) {
        $this->currency_id = intval($currency_id);
    }
    
    /**
     * Get amount
     * @return float
     */
    public function getAmount() {
        return $this->amount;
    }
    
    /**
     * Set amount
     * @param float $amount
     */
    public function setAmount($amount) {
        $this->amount = $amount;
    }
    
    /**
     * Get wallet item creation date in format YYYY-MM-DD.
     * All dates are stored in UTC time.
     * @return string
     */
    public function getDateCreated() {
        return $this->date_created;
    }

    /**
     * Set wallet item creation date
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
     * Get wallet item removal date in format YYYY-MM-DD.
     * All dates are stored in UTC time.
     * @return string
     */
    public function getDateRemoved() {
        return $this->date_removed;
    }

    /**
     * Set wallet item removal date
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
     * Get item status. 
     * Returns "active" if the item is added or "removed" if deleted.
     * @return string 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set item status
     * @param string $status Item status active|inactive
     */
    public function setStatus($status = 'active') {

        if (in_array($status, array('active', 'removed'))) {
            $this->status = $status;
        }
        
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

        if (!empty($data['wallet_id'])) {
            $this->setWalletId($data['wallet_id']);
        }

        if (!empty($data['currency_id'])) {
            $this->setCurrencyId($data['currency_id']);
        }

        if (!empty($data['amount'])) {
            $this->setAmount($data['amount']);
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
