<?php

use \BasePresenter as BasePresenter;

/**
 * Mailings presenter.
 *
 * @author     Zippo
 * @package    MainModule
 */
class MailingsPresenter extends BasePresenter
{    
    /**
     * Startup settings.
     */    
    public function startup() {
        parent::startup();  
                
        $inputToken = $this->getParam('token');
        $identifier = substr($inputToken, 0, 2);
        $token = substr($inputToken, 3);
        
        // 1st mailing
        if ($token == '201210') {               
            $this->setView('201210');            
        } else {
            $this->redirect('Default:default');            
        }
    }        
}
