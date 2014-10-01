<?php

/**
 * Work Angel Test
 * Based on Zend Framework 2
 * 
 * Michal Gacki
 */

return array(
    'controllers' => array(
        'invokables' => array(
            'Wallet\Controller\Wallet' => 'Wallet\Controller\WalletController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'wallet' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/wallet[/][:action][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Wallet\Controller\Wallet',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'wallet' => __DIR__.'/../view',
        )
    )
);
