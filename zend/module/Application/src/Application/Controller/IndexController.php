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

class IndexController extends AbstractActionController {
    
    public function indexAction() {
        return new ViewModel();
    }
}
