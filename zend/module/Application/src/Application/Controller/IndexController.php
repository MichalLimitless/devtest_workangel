<?php

/**
 * Work Angel Test
 * Based on Zend Framework 2
 * 
 * Michal Gacki
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\Form\Form;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;

/**
 * Main application
 */
class IndexController extends AbstractActionController {

    /**
     * Homepage action - user wallet
     * @route /
     * @return ViewModel
     */
    public function indexAction() {
        
        return new ViewModel();
        
    }

}
