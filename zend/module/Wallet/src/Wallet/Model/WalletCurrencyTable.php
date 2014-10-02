<?php

/**
 * Work Angel Test
 * Based on Zend Framework 2
 * 
 * @author Michal Gacki
 * @todo Add some common functions to not repeat myself.
 */

namespace Wallet\Model;

use Zend\Db\TableGateway\TableGateway;

class WalletCurrencyTable {

    /**
     * Table gateway
     * @var TableGateway 
     */
    protected $tableGateway;

    /**
     * Model constructor
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    /**
     * ORM fetch all
     * @return type
     */
    public function fetchAll() {
        
        $resultSet = $this->tableGateway->select();
        
        return $resultSet;
        
    }

    /**
     * SELECT abstract
     * Get Currency record as object
     * @param integer $id
     * @return WalletCurrency
     * @throws \Exception
     */
    public function getWalletCurrency($id) {
        
        $id = intval($id);
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception('Could not find row '.$id);
        }
        
        return $row;
        
    }
    
    /**
     * SELECT abstract
     * Get Currency record as object
     * @param array $where Associative where array
     * @return WalletCurrency
     * @throws \Exception
     */
    public function getWalletCurrencyCustom($where) {
        
        $rowset = $this->tableGateway->select($where);
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        
        return $row;
        
    }

    /**
     * INSERT abstract
     * Add new Currency record
     * @param \Wallet\Model\WalletCurrency $wallet_currency
     * @throws \Exception
     */
    public function saveWalletCurrency(WalletCurency $wallet_currency) {
        
        $data = array(
            'order'                 => $wallet_currency->getOrder(),
            'code'                  => $wallet_currency->getCode(),
            'code_alternative'      => $wallet_currency->getCodeAlternative(),
            'font_symbol'           => $wallet_currency->getFontSymbol(),
            'name'                  => $wallet_currency->getName(),
            'default'               => $wallet_currency->getDefault(),
            'status'                => $wallet_currency->getStatus()
        );

        $id = intval($wallet_currency->id);
        
        if ($id == 0) {
            $this->tableGateway->insert($data);
        }
        else {
            if ($this->getWalletCurency($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            }
            else {
                throw new \Exception('WalletCurrency id does not exist');
            }
        }
        
    }

    /**
     * DELETE abstract
     * Remove WalletCurrency
     * @param integer $id WalletCurrency ID
     */
    public function deleteWalletCurrency($id) {

        $this->tableGateway->delete(array('id' => intval($id)));
        
    }

}
