<?php

use \BasePresenter as BasePresenter;

/**
 * Emails presenter.
 *
 * @author     Zippo
 * @package    MainModule
 */
class EmailsPresenter extends BasePresenter
{    
    /**
     * Startup settings.
     */    
    public function startup() {
        parent::startup();  
                
        $inputToken = $this->getParam('token');
        $identifier = substr($inputToken, 0, 2);
        $token = substr($inputToken, 2);
        
        // reg confirmation (rc)
        if ($identifier == 'rc') {
            $user = $this->db_users->getUserByRegistrationToken($token);                        
            if ($user) {
                if ($user->program == 'demo') {
                    $this->template->program = 'DEMOverze';
                } elseif ($user->program == 'basic') {
                    $this->template->program = 'Základní verze';
                }
                $this->template->dateOfReg = date_format($user->dateOfRegistration, 'd.m.Y');                
                $this->setView('reg_user_done');
            } else {
                $this->redirect('Default:default');
            }
        } 
        // acc activation
        elseif ($identifier == 'aa') {
            $user = $this->db_users->getUserByRegistrationToken($token);
            if ($user) {
                if ($user->program == 'demo') {
                    $this->template->program = 'DEMOverze';
                } elseif ($user->program == 'basic') {
                    $this->template->program = 'Základní verze';
                }
                $this->template->dateOfReg = date_format($user->dateOfRegistration, 'd.m.Y');
                $this->template->subdomain = 'http://' . $user->subdomain . '.mudrweb.cz';
                $this->template->subdomain_name = $user->subdomain . '.mudrweb.cz';
                $this->setView('acc_active');
            } else {
                $this->redirect('Default:default');
            }                    
        }
        // acc deactivation
        elseif ($identifier == 'ad') {
            $user = $this->db_users->getUserByRegistrationToken($token);
            if ($user) {
                if ($user->program == 'demo') {
                    $this->template->program = 'DEMOverze';
                } elseif ($user->program == 'basic') {
                    $this->template->program = 'Základní verze';
                }

                $this->template->dateOfReg = date_format($user->dateOfRegistration, 'd.m.Y');
                $this->template->subdomain = 'http://' . $user->subdomain . '.mudrweb.cz';                
                $this->template->subdomain_name = $user->subdomain . '.mudrweb.cz';
                $this->setView('acc_inactive');
            } else {
                $this->redirect('Default:default');
            }                  
        }
        // acc deactivation notification
        elseif ($identifier == 'an') {
            $user = $this->db_users->getUserByRegistrationToken($token);
            if ($user) {
                if ($user->program == 'demo') {
                    $this->template->program = 'DEMOverze';
                } elseif ($user->program == 'basic') {
                    $this->template->program = 'Základní verze';
                }

                $this->template->dateOfReg = date_format($user->dateOfRegistration, 'd.m.Y');
                $this->template->subdomain = 'http://' . $user->subdomain . '.mudrweb.cz';
                $this->template->subdomain_name = $user->subdomain . '.mudrweb.cz';
                $this->template->validTo = date_format($user->dateTo, 'd.m.Y');
                $this->setView('acc_notify1');
            } else {
                $this->redirect('Default:default');
            }                  
        }        
    }        
}
