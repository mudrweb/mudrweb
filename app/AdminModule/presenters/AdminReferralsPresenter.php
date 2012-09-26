<?php

namespace AdminModule;

use Nette\Forms\Form;
use \AdminPresenter as AdminPresenter;

/**
 * AdminModule admin referrals presenter.
 *
 * @author     Zippo
 * @package    AdminModule
 */
class AdminReferralsPresenter extends AdminPresenter {            
    
    /**
     * Check access and rights here only
     */
    public function startup()
    {
        parent::startup();
        $this->checkAccess(array('admin'));                            
    }      
 
    public function renderDefault() {      
        // prepare data for output
        $users = $this->db_users->getUsers();                   
        $usersArray = array();
        if ($users) {
            foreach ($users as $user) {
                // exclude admin
                if ($user->id != 0) {
                    $users_data = $this->db_users->getUsersDataById($user->id);
                    if ($users_data) {
                       // change date format 
                       if ($user->dateFrom != null) {
                            $dateFrom = $user->dateFrom;
                            $dateFrom = $dateFrom->format('d.m.Y');
                       } else {
                            $dateFrom = '00.00.0000';
                       }
                       if ($user->dateTo != null) {                           
                            $dateTo = $user->dateTo;
                            $dateTo = $dateTo->format('d.m.Y');                            
                       } else {
                            $dateTo = '00.00.0000';
                       }                       
                      
                       if ($user->dateOfRegistration) {
                           $dateOfRegistration = date_format($user->dateOfRegistration, 'd.m.Y H:i:s');
                       } else {
                           $dateOfRegistration = NULL;
                       }                
                       if ($user->dateOfActivation) {
                           $dateOfActivation = date_format($user->dateOfActivation, 'd.m.Y H:i:s');
                       } else {
                           $dateOfActivation = NULL;
                       }
                       
                       $numberOfReferrers = $this->db_users->getNumberOfUsersReferrers($user->id);
                       $referralBonus = $numberOfReferrers * 20;
                       
                       // array items
                       $usersArray[] = array(intval($user->id), $users_data->name, $users_data->surname,
                            $user->subdomain, $user->program, $dateOfRegistration, $user->accountStatus, 
                            $dateOfActivation, $dateFrom, $dateTo, $users_data->titleBefore, $users_data->titleAfter,
                            $users_data->street, $users_data->city, $users_data->zip, $users_data->ic, $referralBonus);                
                    } else {
                        throw new \Nette\Application\BadRequestException('Unable to load user websiteData (AdminModule - adminReferrals presenter).', 404);                    
                    }
                }
            }
        } else {
            throw new \Nette\Application\BadRequestException('Unable to load user profile (AdminModule - adminUsers presenter).', 404);
        }
        $this->template->users = $usersArray;          
    }                  
}
