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

class WalletItemTable {

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
     * Get Wallet Item record as object
     * @param integer $id
     * @return WalletCurrency
     * @throws \Exception
     */
    public function getWalletItem($id) {
        
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
     * Get Wallet Item record as object
     * @param $array $where Associative where array
     * @return WalletItem
     * @throws \Exception
     */
    public function getWalletItemCustom($where) {
        
        $rowset = $this->tableGateway->select($where);
        
        return $rowset;
        
    }
    
    /**
     * SELECT abstract
     * Get Wallet Item record as object
     * @param $array $where Associative where array
     * @return WalletItem
     * @throws \Exception
     */
    public function getWalletItemCustomOne($where) {
        
        $where['status'] = 'active';
        
        $rowset = $this->tableGateway->select($where);
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        
        return $row;
        
    }

    /**
     * INSERT abstract
     * Add new item 
     * @param \Wallet\Model\WalletItem $wallet_item
     * @throws \Exception
     */
    public function saveWalletItem(WalletItem $wallet_item) {
        
        $data = array(
            'wallet_id'         => $wallet_item->getWalletId(),
            'currency_id'       => $wallet_item->getCurrencyId(),
            'amount'            => $wallet_item->getAmount(),
            'date_created'      => $wallet_item->getDateCreated(),
            'date_removed'      => $wallet_item->getDateRemoved(),
            'status'            => $wallet_item->getStatus()
        );

        $id = intval($wallet_item->getId());
        
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
            
            return $id;
        }
        else {
            if ($this->getWalletItem($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            }
            else {
                throw new \Exception('WalletItem id does not exist');
            }
        }
        
    }

    /**
     * DELETE abstract
     * Remove WaletItem
     * @param integer $id WalletItem ID
     */
    public function deleteWalletItem($id) {

        $date_removed = new \DateTime();
        $date_removed->setTimezone(new \DateTimeZone('UTC'));
        
        $data = array(
            'date_removed'  => $date_removed->format('Y-m-d H:i:s'),
            'status'        => 'removed'
        );
        
        $id = intval($id);

        if ($this->getWalletItem($id)) {
            $this->tableGateway->update($data, array('id' => $id));
        }
        else {
            throw new \Exception('Wallet id does not exist');
        }
        
    }

}
