<?php

/**
 * Work Angel Test
 * Based on Zend Framework 2
 * 
 * @author Michal Gacki
 * @todo Separate controller to few smaller ones
 */

namespace Wallet\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Wallet\Model\Wallet;
use Wallet\Model\WalletCurrency;
use Wallet\Model\WalletItem;
use Wallet\Form\WalletForm;
use User\Model\User;
use \User\Controller\UserController;
use Zend\Http\Response;

class WalletController extends AbstractActionController {

    /**
     * Wallet table object
     * @var WalletTable
     */
    protected $walletTable;
    
    /**
     * WalletCurrency table object
     * @var WalletCurrencyTable
     */
    protected $walletCurrencyTable;
    
    /**
     * WalletItem table object
     * @var WalletItemTable 
     */
    protected $walletItemTable;
    
    /**
     * User table object
     * @var UserTable
     */
    protected $userTable;
    
    /**
     * User instance
     * @var User
     */
    protected $userInstance = null;
    
    //--------------------------------------------------------------------------
    /**
     * Database controller
     */
    //--------------------------------------------------------------------------

    /**
     * Get Wallet table from Zend Service Manager
     * @return WalletTable
     */
    public function getWalletTable() {

        if (!$this->walletTable) {
            $sm = $this->getServiceLocator();
            $this->walletTable = $sm->get('Wallet\Model\WalletTable');
        }

        return $this->walletTable;
        
    }
    
    /**
     * Get WalletCurrency table from Zend Service Manager
     * @return WalletCurrencyTable
     */
    public function getWalletCurrencyTable() {

        if (!$this->walletCurrencyTable) {
            $sm = $this->getServiceLocator();
            $this->walletCurrencyTable = $sm->get('Wallet\Model\WalletCurrencyTable');
        }

        return $this->walletCurrencyTable;
        
    }
    
    /**
     * Get WalletItem table from Zend Service Manager
     * @return WalletItemTable
     */
    public function getWalletItemTable() {

        if (!$this->walletItemTable) {
            $sm = $this->getServiceLocator();
            $this->walletItemTable = $sm->get('Wallet\Model\WalletItemTable');
        }

        return $this->walletItemTable;
        
    }
    
    /**
     * Get User table from Zend Service Manager
     * @return UserTable
     */
    public function getUserTable() {

        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('User\Model\UserTable');
        }

        return $this->userTable;
        
    }
    
    /**
     * List all available currencies
     * @return array
     */
    public function getWalletCurrenciesList() {
        
        $wallet_currencies = $this->getWalletCurrencyTable();
        $values = $wallet_currencies->fetchAll();
        $values_options_temp = array();
        
        foreach ($values as $cur) {
            $values_options_temp[] = array(
                $cur->getOrder(),
                $cur->getId(),
                $cur->getCode(),
                $cur->getName()
            );
        }
        
        \sort($values_options_temp);
        
        return $values_options_temp;
        
    }
    
    /**
     * Get user controller
     * @return UserController
     */
    public function getUserModule() {
        
        if ($this->userInstance === null) {
            $userTable = $this->getUserTable();
            $this->userInstance = new \User\Controller\UserController();
            $this->userInstance->setUserTable($userTable);
        }
        
        return $this->userInstance;
        
    }
    
    //--------------------------------------------------------------------------
    /**
     * Actions controller
     */
    //--------------------------------------------------------------------------

    /**
     * Wallet index page
     * @route /wallet/
     * @return ViewModel
     */
    public function indexAction() {
        
        $request = $this->getRequest();
        
        /**
         * Get User module
         */
        $user = $this->getUserModule();
        
        /**
         * If user isn't logged, redirect to login screen
         */
        if (!$user->isUserLoggedIn()) {
            return $this->redirect()->toRoute('user', array('action' => 'login'));
        }
        
        /**
         * Get logged user data
         */
        $loggedUser = $user->getLoggedUser();
        
        /**
         * Check if user has wallet
         * or redirect to wallet add form
         */
        $userWallet = $this->getWalletTable()->getWalletCustom(array('user_id' => $loggedUser->getId()));

        if (!$userWallet || \count($userWallet) <= 0) {
            return $this->redirect()->toRoute('wallet', array('action' => 'add'));
        }
        
        /**
         * Display wallet
         */
        $userWalletCurrency = $this->getWalletCurrencyTable()->getWalletCurrencyCustom(array('id' => $userWallet->getCurrencyIdDefault()));
        
        return new ViewModel(array(
            'wallet'        => $userWallet,
            'currency'      => $userWalletCurrency,
        ));
        
    }
    
    /**
     * Mark wallet as removed
     * @route /wallet/reset/
     * @return type
     */
    public function resetAction() {
        
        /**
         * Get User module
         */
        $user = $this->getUserModule();
        
        /**
         * If user isn't logged, redirect to login screen
         */
        if (!$user->isUserLoggedIn()) {
            return $this->redirect()->toRoute('user', array('action' => 'login'));
        }
        
        /**
         * Get logged user data
         */
        $loggedUser = $user->getLoggedUser();
        
        $wallets = $this->getWalletTable()->deleteWalletCustom(array('user_id' => $loggedUser->getId()));
        
        return $this->redirect()->toRoute('wallet', array('action' => 'add'));
        
    }
    
    /**
     * AJAX
     * Remove amount
     * @route /wallet/remove/:id/
     */
    public function removeAction() {
        
        /**
         * Get User module
         */
        $user = $this->getUserModule();
        
        /**
         * If user isn't logged, redirect to login screen
         */
        if (!$user->isUserLoggedIn()) {
            exit();
        }
        
        /**
         * Get logged user data
         */
        $loggedUser = $user->getLoggedUser();
        
        /**
         * Check if user hasn't any wallet 
         */
        $userWallet = $this->getWalletTable()->getWalletCustom(array('user_id' => $loggedUser->getId()));
 
        \header('Content-Type: application/json');
        
        $input = \json_decode(\file_get_contents('php://input'));

        if (\intval($input->id) > 0) {
            
            $walletItem = $this->getWalletItemTable()->getWalletItemCustomOne(array('id' => $input->id, 'wallet_id' => $userWallet->getId()));
            $this->getWalletItemTable()->deleteWalletItem($walletItem->getId());
            
            \http_response_code(410);
            
        }
        else {
            \http_response_code(400);
        }
        
        exit();
        //$response->setContent($this->layout('wallet/rest/remove.phtml'));
        
    }
    
    /**
     * AJAX
     * Add new item to a wallet
     * @route /wallet/new/
     */
    public function newAction() {
        
        /**
         * Get User module
         */
        $user = $this->getUserModule();
        
        /**
         * If user isn't logged, redirect to login screen
         */
        if (!$user->isUserLoggedIn()) {
            exit();
        }
        
        /**
         * Get logged user data
         */
        $loggedUser = $user->getLoggedUser();
        
        /**
         * Check if user hasn't any wallet 
         */
        $userWallet = $this->getWalletTable()->getWalletCustom(array('user_id' => $loggedUser->getId()));
 
        \header('Content-Type: application/json');
        
        $input = \json_decode(\file_get_contents('php://input'));
        
        if (\floatval($input->amount) == $input->amount) {
            
            $walletItem = new WalletItem();
            $walletItem->setWalletId($userWallet->getId());
            $walletItem->setCurrencyId($userWallet->getCurrencyIdDefault());
            $walletItem->setAmount($input->amount);
            $walletItem->setDateCreated($input->dateCreated);
            
            $id = $this->getWalletItemTable()->saveWalletItem($walletItem);
            $input->id = $id;
            
            print \json_encode($input, \JSON_PRETTY_PRINT);
            \http_response_code(200);
            
        }
        else {
            \http_response_code(400);
        }
        
        exit();
        
    }
    
    /**
     * AJAX
     * Get items
     * @route /wallet/items/
     */
    public function itemsAction() {
        
        /**
         * Get User module
         */
        $user = $this->getUserModule();
        
        /**
         * If user isn't logged, redirect to login screen
         */
        if (!$user->isUserLoggedIn()) {
            exit();
        }
        
        /**
         * Get logged user data
         */
        $loggedUser = $user->getLoggedUser();
        
        /**
         * Check if user hasn't any wallet 
         */
        $userWallet = $this->getWalletTable()->getWalletCustom(array('user_id' => $loggedUser->getId()));
 
        \header('Content-Type: application/json');

        $userWalletItemTable = $this->getWalletItemTable();
        $userWalletItem = $userWalletItemTable->getWalletItemCustom(array('wallet_id' => $userWallet->getId()));
                    
        $response = new \stdClass();
        $response->active = array();
        $response->removed = array();

        foreach ($userWalletItem as $item) {
            
            $current_item = array(
                'id'            => $item->getId(),
                'amount'        => $item->getAmount(),
                'dateCreated'   => $item->getDateCreated(),
                'dateRemoved'   => $item->getDateRemoved()
            );
            
            if ($item->getStatus() == 'removed') {
                $response->removed[] = $current_item;
            }
            else {
                $response->active[] = $current_item;
            }
        }
        
        print \json_encode($response, \JSON_PRETTY_PRINT);
        
        \http_response_code(200);
        
        exit();
        
    }
    
    /**
     * Add new wallet
     * @route /wallet/add/
     * @return array
     */
    public function addAction() {
        
        $request = $this->getRequest();
        
        /**
         * Get User module
         */
        $user = $this->getUserModule();
        
        /**
         * If user isn't logged, redirect to login screen
         */
        if (!$user->isUserLoggedIn()) {
            return $this->redirect()->toRoute('user', array('action' => 'login'));
        }
        
        /**
         * Get logged user data
         */
        $loggedUser = $user->getLoggedUser();
        
        /**
         * Check if user hasn't any wallet 
         */
        $userWallet = $this->getWalletTable()->getWalletCustom(array('user_id' => $loggedUser->getId()));

        if ($userWallet && count($userWallet) > 0) {
            return $this->redirect()->toRoute('wallet');
        }
        else {
            /**
             * Map wallet currencies as form options
             */
            $values_options = array();
            $values_options_temp = $this->getWalletCurrenciesList();

            foreach ($values_options_temp as $cur) {
                $values_options[$cur[1]] = $cur[2].' ('.$cur[3].')';
            }
           
            /**
             * Create form
             */
            $form = new WalletForm(null, $values_options);
            
            /**
             * Form sent
             */
            if ($request->isPost()) {

                $wallet = new Wallet();
                $form->setInputFilter($wallet->getInputFilter());
                $form->setData($request->getPost());

                /**
                 * If form is valid:
                 * -> Add new record
                 * -> Redirect to the wallet
                 */
                if ($form->isValid()) {
                    $form_data = $form->getData();
                    
                    /**
                     * Additional wallet data
                     */
                    $form_data['user_id'] = $loggedUser->getId();
                    $date_time = new \DateTime();
                    $date_time->setTimezone(new \DateTimeZone('UTC'));
                    $form_data['date_created'] = $date_time->format('Y-m-d H:i:s');

                    $wallet->exchangeArray($form_data);
                    $this->getWalletTable()->saveWallet($wallet);

                    return $this->redirect()->toRoute('wallet');
                }

            }
        }
        
        return array('form' => $form);
        
    }

}
