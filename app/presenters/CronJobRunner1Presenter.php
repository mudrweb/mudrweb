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
                    
                    if ($user) {
                        $user_data = $this->db_users->getUsersDataById(intval($user->id));

                        // send email
                        $template = parent::createTemplate();
                        $template->setFile($this->getContext()->params['appDir'] . '/templates/xemails/acc_inactive.latte');
                        $template->registerFilter(new Nette\Latte\Engine());

                        if ($user->program == 'demo') {
                            $template->program = 'DEMOverze';
                        } elseif ($user->program == 'basic') {
                            $template->program = 'Základní verze';
                        }
                        $template->dateOfReg = date_format($user->dateOfRegistration, 'd.m.Y');

                        $template->subdomain = $user->subdomain . '.mudrweb.cz';

                        $mail = new \Nette\Mail\Message;
                        $mail->setFrom('MUDRweb.cz - účet <support@mudrweb.cz>')
                                ->addTo($user_data->email)
                                ->setHtmlBody($template)
                                ->send();
                    }                    
                }                
            }
        } else {
            throw new \Nette\Application\BadRequestException('Unable to load users (MainModule - default presenter).', 404);        
        }

        $this->terminate();
    }    
}
