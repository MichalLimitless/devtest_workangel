<?php

/**
 * Work Angel Test
 * Based on Zend Framework 2
 * 
 * Michal Gacki
 */

namespace Wallet;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Wallet\Model\WalletTable;
use Wallet\Model\WalletCurrencyTable;
use Wallet\Model\WalletItemTable;
use \User\Model\UserTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface {

    /**
     * Zend autoloader
     * @return array
     */
    public function getAutoloaderConfig() {

        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__.'/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__.'/src/'.__NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Zend config include.
     * It's odd solution by the way.
     * @return array
     */
    public function getConfig() {
        return include __DIR__.'/config/module.config.php';
    }

    /**
     * Create ORM Factory
     * @return array
     */
    public function getServiceConfig() {
        
        return array(
            'factories' => array(
                'Wallet\Model\WalletTable' => function($sm) {
            
                    $tableGateway = $sm->get('WalletTableGateway');
                    $table = new WalletTable($tableGateway);
                    
                    return $table;
                },
                'WalletTableGateway' => function ($sm) {
                    
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Wallet());
                    
                    return new TableGateway('wallet', $dbAdapter, null, $resultSetPrototype);
                    
                },
                'Wallet\Model\WalletCurrencyTable' => function($sm) {
            
                    $tableGateway = $sm->get('WalletCurrencyTableGateway');
                    $table = new WalletCurrencyTable($tableGateway);
                    
                    return $table;
                },
                'WalletCurrencyTableGateway' => function ($sm) {
                    
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\WalletCurrency());
                    
                    return new TableGateway('wallet_currency', $dbAdapter, null, $resultSetPrototype);
                    
                },
                'Wallet\Model\WalletItemTable' => function($sm) {
            
                    $tableGateway = $sm->get('WalletItemTableGateway');
                    $table = new WalletItemTable($tableGateway);
                    
                    return $table;
                },
                'WalletItemTableGateway' => function ($sm) {
                    
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\WalletItem());
                    
                    return new TableGateway('wallet_item', $dbAdapter, null, $resultSetPrototype);
                    
                },
                'User\Model\UserTable' => function($sm) {
            
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    
                    return $table;
                },
                'UserTableGateway' => function ($sm) {
                    
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \User\Model\User());
                    
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                    
                },
            ),
        );
                
    }

}
