<?php

/**
 * Work Angel Test
 * Based on Zend Framework 2
 * 
 * Michal Gacki
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Model\User;
use User\Form\LoginForm;
use User\Form\RegisterForm;
use Zend\Http\Client;

/**
 * @todo Delete this
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

class UserController extends AbstractActionController {
    
    /**
     * User table object
     * @var UserTable
     */
    protected $userTable;
    
    /**
     * Logged user handler
     * @var User
     */
    protected $loggedUser = null;
    
    //--------------------------------------------------------------------------
    /**
     * Database controller
     */
    //--------------------------------------------------------------------------

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
    
    public function setUserTable($userTable) {
        $this->userTable = $userTable;
    }
    
    //--------------------------------------------------------------------------
    /**
     * Passwords
     */
    //--------------------------------------------------------------------------
    
    /**
     * Random salt generator
     * @author Michal Gacki
     * @return type
     */
    public function getRandomSalt() {
        
        $time = new \DateTime();
        $chars = array('!', '@', '$', '^', '&', '*', '(', ')', '-', '+', '.', ',', '?', '/', '{', '}', '[', ']');
        $salt = \str_split(\substr(\md5(\sha1(\base64_encode(\mt_rand(1000, 10000).'$#^$#^%$*%#*%^#$&'.\sha1($time->getTimestamp().\md5($time->format('Y-m-d H:i:s')))))), 0, 20));

        $salt_array = \array_merge($chars, $salt);

        for ($i = 0, $n = \mt_rand(2, 10); $i < $n; $i++) {
            \shuffle($salt_array);
        }

        $password_salt = '';
        for ($i = 0, $n = 40; $i < $n; $i++) {
            if (!empty($salt_array[$i])) {
                $password_salt .= $salt_array[$i];
            }
        }
        
        return $password_salt;
        
    }
    
    /**
     * Password hasher (with salt)
     * @param string $password Plain password
     * @param string $salt Unique salt
     * @return string
     */
    public function getSaltedHash($password, $salt) {
        
        return $password_hash = \sha1(\md5(\sha1($password.'@R@#T$GSSWARG$#Hf23%$#*%^(^&}:\'\':!fds<'.$salt)));
        
    }
    
    /**
     * Authorize user credentials
     * @param User $User
     * @param string $email Providen email
     * @param string $password Providen password
     */
    public function authorizeUser($User, $email, $password, $auto_login = true) {
        
        if (!empty($User->getEmail()) && !empty($User->getPassword())) {
            
            /**
             * Compare passwords
             */
            if ($User->getPassword() == $this->getSaltedHash($password, $User->getSalt())) {

                if ($auto_login) {
                    $client = new Client();

                    /**
                     * @todo Provide better auth system. This is just time saving
                     */
                    $json_data = new \stdClass();
                    $json_data->email = $email;
                    $json_data->password = $password;
                    $json_data = json_encode($json_data);

                    /**
                     * @fixme Change cookie adding method to Zend HTTP Client
                     */
                    setcookie('user', base64_encode($json_data), time()+3600, '/');
                }
                
                return true;
            }
 
        }
        
        return false;
        
    }
    
    public function getUserCredentials() {
        
        if (empty($_COOKIE['user'])) {
            return false;
        }
        
        $credentials = json_decode(base64_decode($_COOKIE['user']));
        
        if (empty($credentials)) {
            return false;
        }
        
        return $credentials;
        
    }
    
    /**
     * Check if user is logged in
     * @fixme Change cookie to Zend REQUEST
     * @param User $User
     * @return boolean
     */
    public function isUserLoggedIn($User = null) {
        //--------------------------------------------------------------------------------------------------------------------
        if ($this->loggedUser === null) {
            
            if (!$credentials = $this->getUserCredentials()) {
                return false;
            }
            
            $this->loggedUser = $this->getLoggedUser($User, $credentials);

            return $this->authorizeUser($this->loggedUser, $credentials->email, $credentials->password, false);
            
        }
        else {
            return true;
        }
    }
    
    /**
     * Get logged user data
     * @return User
     */
    public function getLoggedUser($User = null, $credentials = null) {
        
        if ($this->loggedUser === null) {

            if ($credentials === null) {
                if (!$credentials = $this->getUserCredentials()) {
                    return false;
                }
            }
            
            if ($User === null) {
                $User = $this->getUserTable()->getUserCustom(array('email' => $credentials->email));

                if (!$User) {
                    return false;
                }
                else {
                    $this->loggedUser = $User;
                }
            }   
            
        }
        
        return $this->loggedUser;
        
    }
    
    //--------------------------------------------------------------------------
    /**
     * Actions controller
     */
    //--------------------------------------------------------------------------

    public function loginAction() {

        $form = new LoginForm();
        $request = $this->getRequest();
        
        $is_logged = $this->isUserLoggedIn();
        
        /**
         * Form sent
         */
        if ($request->isPost()) {
            
            $user = new User();
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());

            /**
             * If form is valid
             * @todo Email validation
             */
            if ($form->isValid()) {
                $post_data = $form->getData();
                
                if ($user = $this->getUserTable()->getUserCustom(array('email' => $post_data['email']))) {
                
                    if ($this->authorizeUser($user, $post_data['email'], $post_data['password'])) {
                        return $this->redirect()->toRoute('wallet', array('action' => 'add'));
                    }
                }

                return $this->redirect()->toRoute('user', array('action' => 'login'));
            }
            
        }
        
        return array('form' => $form);
        
    }

    /**
     * User logout
     * @fixme Change cookie method to Zend HTTP Client
     * @return string
     */
    public function logoutAction() {
        
        setcookie('user', '', time()-3600, '/');
        
        return $this->redirect()->toRoute('home');
        
    }

    /**
     * User register
     * @return string
     */
    public function registerAction() {
        
        $form = new RegisterForm();
        $request = $this->getRequest();
        
        /**
         * Form sent
         * @todo Append another InputFilter with password checking
         */
        if ($request->isPost()) {
            
            $user = new User();
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());

            /**
             * If form is valid:
             */
            if ($form->isValid()) {
                $form_data = $form->getData();
                $user_data = array(
                    'email'     => $form_data['email'],
                    'password'  => $form_data['password']
                );
                
                /**
                 * Additional user data
                 */
                $salt = $this->getRandomSalt();
                $form_data['salt'] = $salt;
                $form_data['password'] = $this->getSaltedHash($form_data['password'], $salt);
                $date_time = new \DateTime();
                $date_time->setTimezone(new \DateTimeZone('UTC'));
                $form_data['date_created'] = $date_time->format('Y-m-d H:i:s');
                        
                $user->exchangeArray($form_data);
                $this->getUserTable()->saveUser($user);
                
                /**
                 * Auto log in
                 */
                $this->authorizeUser($user, $user_data['email'], $user_data['password']);
                
                return $this->redirect()->toRoute('wallet', array('action' => 'add'));
            }
            
        }
        
        if ($this->isUserLoggedIn()) {
            return $this->redirect()->toRoute('wallet', array('action' => 'add'));
        }
        
        return array('form' => $form);
        
    }

}
