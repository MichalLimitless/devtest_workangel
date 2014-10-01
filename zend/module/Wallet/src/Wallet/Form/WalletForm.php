<?php

/**
 * Work Angel Test
 * Based on Zend Framework 2
 * 
 * Michal Gacki
 */

namespace Wallet\Form;

use Zend\Form\Form;

class WalletForm extends Form {

    /**
     * Open new wallet form
     * @param string $name
     */
    public function __construct($name = null, $wallet_currencies = null) {

        parent::__construct('wallet');
        
        $this->add(array(
            'name' => 'currency_id_default',
            'type' => 'Select',
            'options' => array(
                'label' => 'Select wallet currency:',
                'value_options' => $wallet_currencies
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value'     => 'Next',
                'id'        => 'submitbutton',
            ),
        ));
        
    }

}
