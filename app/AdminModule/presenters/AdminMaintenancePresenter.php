<?php

namespace AdminModule;

use Nette\Forms\Form;
use \AdminPresenter as AdminPresenter;

/**
 * AdminModule admin maintenance presenter.
 *
 * @author     Zippo
 * @package    AdminModule
 */
class AdminMaintenancePresenter extends AdminPresenter {                    
    
    protected $subdomainsArray;
    
    /**
     * Check access and rights here only
     */
    public function startup()
    {
        parent::startup();
        $this->checkAccess(array('admin'));            
        
        // prepare data for output
        $users = $this->db_users->getUsers();                   
        $subdomainsArray = null;
        if ($users) {
            foreach ($users as $user) {
                // exclude admin
                if ($user->id != 0) {                    
                    $subdomainsArray[] = $user->subdomain;
                }
            }            
            
            $this->subdomainsArray = $subdomainsArray;
        }                            
    }      
 
    /**
     * renderDefault for adminMaintenance presenter. 
     */    
    public function renderDefault() {           
    }        
    
    /**
     * Create form for thumbs dir maintenance.
     * 
     * @return \Nette\Application\UI\Form 
     */    
    protected function createComponentDeleteThumbsForm() {
        $form = new \Nette\Application\UI\Form;       
        
        $form->addHidden('numberOfSubdomains', count($this->subdomainsArray));
        
        return $form;
    }     
    
    /**
     * Delete thumbs handler (ajax)
     */
    public function handleDeleteThumbs($index)
    {   
        $this->payload->availCheck = array();                       
        
        \Nette\Diagnostics\Debugger::firelog($this->subdomainsArray[$index]);
        $deleteStatus = $this->extraMethods->deleteThumbs($this->subdomainsArray[$index]);
        // ok
        if ($deleteStatus == 1) {            
            $this->payload->availCheck[] = $index;                                            
        } 
        // error 
        elseif ($deleteStatus == 0) {
            $this->payload->availCheck[] = 999888999;                                      
        } 
        // warning
        elseif ($deleteStatus == -1) {
            $this->payload->availCheck[] = 999999999;                                      
        }             
        
        usleep(200000);        
        $this->terminate();   
    }            
}
