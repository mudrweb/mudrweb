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
                $dateFrom = date_format($user->dateFrom, 'Y-m-d');
                if (($user->accountStatus == 'pending') && ($todaysDate >= $dateFrom)) {
                    $this->db_users->updateRegistrationProcessStatus(intval($user->id), 'active');    
                }
                
                // check dateTo - set user's account status from active->inactive
                $dateToStopIt = date('Y-m-d', strtotime('-1 day'));
                $dateTo = date_format($user->dateTo, 'Y-m-d');
                if (($user->accountStatus == 'active') && ($dateToStopIt >= $dateTo)) {
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
                
                // account deactivation START //////////////////////////////////
                // 1st notification -> 14 days before
                $dateFor1stNotification = strtotime($dateTo);
                $dateFor1stNotification = strtotime("-14 days", $dateFor1stNotification);
                $dateFor1stNotification = date('Y-m-d', $dateFor1stNotification);        
                if ($dateFor1stNotification == $todaysDate) {                    
                    if ($user) {
                        $user_data = $this->db_users->getUsersDataById(intval($user->id));

                        // send email
                        $template = parent::createTemplate();
                        $template->setFile($this->getContext()->params['appDir'] . '/templates/xemails/acc_notify1.latte');
                        $template->registerFilter(new Nette\Latte\Engine());

                        if ($user->program == 'demo') {
                            $template->program = 'DEMOverze';
                        } elseif ($user->program == 'basic') {
                            $template->program = 'Základní verze';
                        }
                        
                        $template->dateOfReg = date_format($user->dateOfRegistration, 'd.m.Y');
                        $template->subdomain = $user->subdomain . '.mudrweb.cz';
                        $template->validTo = date_format($user->dateTo, 'd.m.Y');                        
                        
                        $mail = new \Nette\Mail\Message;
                        $mail->setFrom('MUDRweb.cz - účet <support@mudrweb.cz>')
                                ->addTo($user_data->email)
                                ->setHtmlBody($template)
                                ->send();
                        
                        $this->db_users->updateAccDeactNotificationCounter(intval($user->id), 1);
                    }                           
                }
                
                // 2nd notification -> 7 days before
                $dateFor2ndtNotification = strtotime($dateTo);
                $dateFor2ndtNotification = strtotime("-7 days", $dateFor2ndtNotification);
                $dateFor2ndtNotification = date('Y-m-d', $dateFor2ndtNotification);        
                if ($dateFor2ndtNotification == $todaysDate) {                    
                    if ($user) {
                        $user_data = $this->db_users->getUsersDataById(intval($user->id));

                        // send email
                        $template = parent::createTemplate();
                        $template->setFile($this->getContext()->params['appDir'] . '/templates/xemails/acc_notify1.latte');
                        $template->registerFilter(new Nette\Latte\Engine());

                        if ($user->program == 'demo') {
                            $template->program = 'DEMOverze';
                        } elseif ($user->program == 'basic') {
                            $template->program = 'Základní verze';
                        }
                        
                        $template->dateOfReg = date_format($user->dateOfRegistration, 'd.m.Y');
                        $template->subdomain = $user->subdomain . '.mudrweb.cz';
                        $template->validTo = date_format($user->dateTo, 'd.m.Y');                        
                        
                        $mail = new \Nette\Mail\Message;
                        $mail->setFrom('MUDRweb.cz - účet <support@mudrweb.cz>')
                                ->addTo($user_data->email)
                                ->setHtmlBody($template)
                                ->send();
                        
                        $this->db_users->updateAccDeactNotificationCounter(intval($user->id), 2);
                    }                           
                }     
                
                // 3rd notification -> 1 day before
                $dateFor3rdNotification = strtotime($dateTo);
                $dateFor3rdNotification = strtotime("-1 day", $dateFor3rdNotification);
                $dateFor3rdNotification = date('Y-m-d', $dateFor3rdNotification);        
                if ($dateFor3rdNotification == $todaysDate) {                    
                    if ($user) {
                        $user_data = $this->db_users->getUsersDataById(intval($user->id));

                        // send email
                        $template = parent::createTemplate();
                        $template->setFile($this->getContext()->params['appDir'] . '/templates/xemails/acc_notify1.latte');
                        $template->registerFilter(new Nette\Latte\Engine());

                        if ($user->program == 'demo') {
                            $template->program = 'DEMOverze';
                        } elseif ($user->program == 'basic') {
                            $template->program = 'Základní verze';
                        }
                        
                        $template->dateOfReg = date_format($user->dateOfRegistration, 'd.m.Y');
                        $template->subdomain = $user->subdomain . '.mudrweb.cz';
                        $template->validTo = date_format($user->dateTo, 'd.m.Y');                        
                        
                        $mail = new \Nette\Mail\Message;
                        $mail->setFrom('MUDRweb.cz - účet <support@mudrweb.cz>')
                                ->addTo($user_data->email)
                                ->setHtmlBody($template)
                                ->send();
                        
                        $this->db_users->updateAccDeactNotificationCounter(intval($user->id), 3);
                    }                           
                }
                // account deactivation END ////////////////////////////////////
            }
        } else {
            throw new \Nette\Application\BadRequestException('Unable to load users (MainModule - default presenter).', 404);        
        }

        $this->terminate();
    }    
}
