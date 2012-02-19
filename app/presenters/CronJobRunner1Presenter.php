<?php

use \BasePresenter as BasePresenter;

/**
 * CronJobRunner1 presenter - check of user's account status.
 *
 * @author     Zippo
 * @package    MainModule
 */
class CronJobRunner1Presenter extends BasePresenter
{        
    public function actionStatusCheck()
    {
        if (!$this->getContext()->params['consoleMode']) {
            $this->redirect('Default:default');
        }

        $users = $this->db_users->getUsers();
        if ($users) {
            foreach ($users as $user) {
                // check dateFrom - set user's account status from pending->active
                $todaysDate = date('Y-m-d');
                if (($user->accountStatus == 'pending') && ($todaysDate >= $user->dateFrom)) {
                    $this->db_users->updateRegistrationProcessStatus(intval($user->id), 'active');    
                }
                
                // check dateTo - set user's account status from active->inactive
                $dateToStopIt = date('Y-m-d', strtotime('-1 day'));
                if (($user->accountStatus == 'active') && ($dateToStopIt >= $user->dateTo)) {
                    $this->db_users->updateRegistrationProcessStatus(intval($user->id), 'inactive');    
                }                
            }
        } else {
            throw new \Nette\Application\BadRequestException('Unable to load users (MainModule - default presenter).', 404);        
        }

        $this->terminate();
    }    
}
