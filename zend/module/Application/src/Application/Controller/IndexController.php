<?php

/**
 * Work Angel Test
 * Based on Zend Framework 2
 * 
 * @author Michal Gacki
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\Form\Form;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use User\Model\User;
use \User\Controller\UserController;

/**
 * Main application
 */
class IndexController extends AbstractActionController {
    
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

    /**
     * Homepage action - welcome page
     * @todo check with User module if user is logged and display another view
     * @route /
     * @return ViewModel
     */
    public function indexAction() {
        error_reporting(E_ALL);
ini_set('display_errors', '1');
        
        /**
         * Get User module
         */
        $user = $this->getUserModule();
        
        /**
         * If user isn't logged, redirect to login screen
         */
        if ($user->isUserLoggedIn()) {
            $view = new ViewModel();
            $view->setTemplate('application/index/index_logged_in.phtml');
            
            return $view;
        }
        
        return new ViewModel(array());
        
    }

}
