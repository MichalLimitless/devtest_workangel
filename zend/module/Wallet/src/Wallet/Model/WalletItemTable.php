<?php

/**
 * Work Angel Test
 * Based on Zend Framework 2
 * 
 * Michal Gacki
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

        $id = intval($wallet_currency->id);
        
        if ($id == 0) {
            $this->tableGateway->insert($data);
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
