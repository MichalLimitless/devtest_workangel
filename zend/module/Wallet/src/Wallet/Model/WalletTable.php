<?php

/**
 * Work Angel Test
 * Based on Zend Framework 2
 * 
 * Michal Gacki
 */

namespace Wallet\Model;

use Zend\Db\TableGateway\TableGateway;

class WalletTable {

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
     * Get Wallet record as object
     * @param integer $id
     * @return Wallet
     * @throws \Exception
     */
    public function getWallet($id) {
        
        $id = intval($id);
        $rowset = $this->tableGateway->select(array('id' => $id, 'status' => 'active'));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception('Could not find row '.$id);
        }
        
        return $row;
        
    }
    
    /**
     * SELECT abstract
     * Get Wallet record as object
     * @param array $where Associative array
     * @return Wallet
     * @throws \Exception
     */
    public function getWalletCustom($where) {
        
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
     * Add new Wallet record
     * @param \Wallet\Model\Wallet $wallet
     * @throws \Exception
     */
    public function saveWallet(Wallet $wallet) {
        
        $data = array(
            'user_id'               => $wallet->getUserId(),
            'currency_id_default'   => $wallet->getCurrencyIdDefault(),
            'date_created'          => $wallet->getDateCreated(),
            'date_removed'          => $wallet->getDateRemoved(),
            'status'                => $wallet->getStatus()
        );

        $id = intval($wallet->getId());
        
        if ($id == 0) {
            $this->tableGateway->insert($data);
        }
        else {
            if ($this->getWallet($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            }
            else {
                throw new \Exception('Wallet id does not exist');
            }
        }
        
    }

    /**
     * DELETE abstract
     * Mark Wallet record as removed
     * @param integer $id Wallet ID
     */
    public function deleteWallet($id) {
        
        $date_removed = new \DateTime();
        $date_removed->setTimezone(new \DateTimeZone('UTC'));
        
        $data = array(
            'date_removed'  => $date_removed->format('Y-m-d H:i:s'),
            'status'        => 'removed'
        );
        
        $id = intval($id);

        if ($this->getWallet($id)) {
            $this->tableGateway->update($data, array('id' => $id));
        }
        else {
            throw new \Exception('Wallet id does not exist');
        }
        
        //$this->tableGateway->delete(array('id' => intval($id)));
        
    }
    
    public function deleteWalletCustom($where) {
        
        $where['status'] = 'active';
        
        $date_removed = new \DateTime();
        $date_removed->setTimezone(new \DateTimeZone('UTC'));
        
        $data = array(
            'date_removed'  => $date_removed->format('Y-m-d H:i:s'),
            'status'        => 'removed'
        );
        
        $this->getWalletCustom($where);
        $this->tableGateway->update($data, $where);

    }

}
