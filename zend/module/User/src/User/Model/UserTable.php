<?php

/**
 * Work Angel Test
 * Based on Zend Framework 2
 * 
 * @author Michal Gacki
 * @todo Add some common functions to not repeat myself.
 */

namespace User\Model;

use Zend\Db\TableGateway\TableGateway;

class UserTable {

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
     * Get User record as object
     * @param integer $id User ID
     * @return User
     * @throws \Exception
     */
    public function getUser($id) {
        
        $id = intval($id);
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception('Could not find row '.$id);
        }
        
        return $row;
        
    }
    
    public function getUserCustom($array) {
        
        $rowset = $this->tableGateway->select($array);
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        
        return $row;
        
    }

    /**
     * INSERT abstract
     * Add new User record
     * @param \User\Model\User $user
     * @throws \Exception
     */
    public function saveUser(User $user) {
        
        $data = array(
            'email'         => $user->getEmail(),
            'password'      => $user->getPassword(),
            'salt'          => $user->getSalt(),
            'date_created'  => $user->getDateCreated(),
            'date_removed'  => $user->getDateRemoved(),
            'timezone'      => $user->getTimezone(),
            'status'        => $user->getStatus()
        );

        $id = intval($user->getId());
        
        if ($id == 0) {
            $this->tableGateway->insert($data);
        }
        else {
            if ($this->getUser($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            }
            else {
                throw new \Exception('User id does not exist');
            }
        }
        
    }

    /**
     * DELETE abstract
     * Mark User record as removed
     * @param integer $id User ID
     */
    public function deleteUser($id) {
        
        $date_removed = new \DateTime();
        $date_removed->setTimezone(new \DateTimeZone('UTC'));
        
        $data = array(
            'date_removed'  => $date_removed->format('Y-m-d H:i:s'),
            'status'        => 'removed'
        );
        
        $id = intval($id);

        if ($this->getUser($id)) {
            $this->tableGateway->update($data, array('id' => $id));
        }
        else {
            throw new \Exception('User id does not exist');
        }
        
    }

}
