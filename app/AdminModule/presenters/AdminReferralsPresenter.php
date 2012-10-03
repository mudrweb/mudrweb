<?php

namespace AdminModule;

use Nette\Forms\Form;
use Nette\Utils\Strings;
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
                       $referralBonus = $numberOfReferrers * $this->referralBonusValuePerItem;
                       
                       $remainingReferralBonus = $referralBonus - $user->usedReferralBonus;
                       
                       // check id length and create default format
                       $idLength = Strings::length($user->id);                       
                       $idToBeDisplayed = Strings::padLeft($user->id, 5, '0');                                              
                       
                       // 20 array items
                       $usersArray[] = array(intval($user->id), $users_data->name, $users_data->surname,
                            $user->usersSponsoringNumber, $user->program, $dateOfRegistration, $user->accountStatus, 
                            $dateOfActivation, $dateFrom, $dateTo, $users_data->titleBefore, $users_data->titleAfter,
                            $users_data->street, $users_data->city, $users_data->zip, $users_data->ic, $referralBonus,
                            $user->usedReferralBonus, $remainingReferralBonus, $idToBeDisplayed);                
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
    
    /**
     * Submit changes event handler (called by jQuery dataTable)
     */
    public function handleSubmitChanges() {
        //get data
        $id = $_REQUEST['id'];
        $columnId = $_REQUEST['columnId'];
        
        //program type
        if ($columnId == 11) {
            $newValue = $_REQUEST['value'];
            
            $numberOfReferrers = $this->db_users->getNumberOfUsersReferrers(intval($id));
            $referralBonus = $numberOfReferrers * $this->referralBonusValuePerItem;
            if (($referralBonus - $newValue) > 0) {
                $this->db_users->updateUsersUsedReferralBonus(intval($id), $newValue);
            }
        }                         
        
        if (!$this->isAjax()) {
            $this->redirect('this');
        } else {                            
            $this->terminate();      
            $this->invalidateControl('jEditable');
        }        
    }
}
