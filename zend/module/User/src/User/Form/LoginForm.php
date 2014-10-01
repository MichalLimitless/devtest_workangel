<?php

/**
 * Work Angel Test
 * Based on Zend Framework 2
 * 
 * Michal Gacki
 */

namespace User\Form;

use Zend\Form\Form;

class LoginForm extends Form {

    /**
     * Create login form
     * @param string $name
     */
    public function __construct($name = null) {

        parent::__construct('user');

        $this->add(array(
            'name' => 'email',
            'type' => 'Email',
            'options' => array(
                'label' => 'Your e-mail'
            )
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'options' => array(
                'label' => 'Your password',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Log in'
            )
        ));
        
    }

}
