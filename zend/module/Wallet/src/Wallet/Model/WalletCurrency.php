<?php

/**
 * Work Angel Test
 * Based on Zend Framework 2
 * 
 * @author Michal Gacki
 */

namespace Wallet\Model;

class WalletCurrency {
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
     * Ordering value
     * @var integer
     */
    protected $order = null;

    /**
     * Currency code
     * @var string 
     */
    protected $code = null;

    /**
     * Currency code symbol
     * @var string Unicode
     */
    protected $code_alternative = null;
    
    /**
     * FontAwesome symbol (or other font symbol)
     * @var type 
     */
    protected $font_symbol = null;
    
    /**
     * Currency name
     * @var string
     */
    protected $name = null;
    
    /**
     * Currency as default
     * @var string Constain boolean value is currency default
     */
    protected $default = false;
    
    /**
     * Currency production status
     * @var string
     * @example active|inactive
     */
    protected $status = 'active';

    //--------------------------------------------------------------------------
    /**
     * Basic getters and setters
     */
    //--------------------------------------------------------------------------

    /**
     * Get currency internal ID
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set currency internal ID
     * @param integer $id Unique ID
     */
    public function setId($id) {
        $this->id = intval($id);
    }
    
    /**
     * Get order value
     * @return integer
     */
    public function getOrder() {
        return $this->order;
    }
    
    /**
     * Set order value
     * @param integer $order
     */
    public function setOrder($order) {
        $this->order = intval($order);
    }
    
    /**
     * Get currency code
     * @return string
     */
    public function getCode() {
        return $this->code;
    }
    
    /**
     * Set currency code
     * @param string $code
     */
    public function setCode($code) {
        $this->code = $code;
    }
    
    /**
     * Get currency code symbol
     * @return string Unicode
     */
    public function getCodeAlternative() {
        return $this->code_alternative;
    }
    
    /**
     * Set currency code symbol
     * @param string $code_alternative Unicode symbol
     */
    public function setCodeAlternative($code_alternative) {
        $this->code_alternative = $code_alternative;
    }
    
    /**
     * Get currency FontAwesome class name
     * @return string
     */
    public function getFontSymbol() {
        return $this->font_symbol;
    }
    
    /**
     * Set currency FontAwesome class name
     * @param string $font_symbol
     */
    public function setFontSymbol($font_symbol) {
        $this->font_symbol = $font_symbol;
    }
    
    /**
     * Get currency name
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set currency name
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Is currency default?
     * @return type
     */
    public function getDefault() {
        return $this->default;
    }

    /**
     * Set currency default status
     * @param boolean $default
     */
    public function setDefault($default = false) {
        $this->default = (bool)$default;
    }
    
    /**
     * Get currency status. 
     * Returns "active" if the wallet is active or "inactive" if not.
     * @return string 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set currency status
     * @param string $status Currency status active|inactive
     */
    public function setStatus($status = 'active') {

        if (in_array($status, array('active', 'inactive'))) {
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

        if (!empty($data['order'])) {
            $this->setOrder($data['order']);
        }

        if (!empty($data['code'])) {
            $this->setCode($data['code']);
        }

        if (!empty($data['code_alternative'])) {
            $this->setCodeAlternative($data['code_alternative']);
        }

        if (!empty($data['font_symbol'])) {
            $this->setFontSymbol($data['font_symbol']);
        }
        
        if (!empty($data['name'])) {
            $this->setName($data['name']);
        }
        
        if (!empty($data['default'])) {
            $this->setDefault($data['default']);
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
