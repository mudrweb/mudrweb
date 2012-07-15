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

        $listOfInvoiceRecipients = array();
        $users = $this->db_users->getUsers();
        if ($users) {
            foreach ($users as $user) {
                if ($user->dateFrom && $user->dateTo) {
                    // check dateFrom - set user's account status from pending->active
                    $todaysDate = date('Y-m-d');
                    $dateFrom = date_format($user->dateFrom, 'Y-m-d');
                    
                    // account activation START ////////////////////////////////
                    if (($user->accountStatus == 'pending') && ($todaysDate >= $dateFrom)) {
                        $this->db_users->updateRegistrationProcessStatus(intval($user->id), 'active');
                        
                        if ($user) {
                            $user_data = $this->db_users->getUsersDataById(intval($user->id));

                            // send email
                            $template = parent::createTemplate();
                            $template->setFile($this->getContext()->params['appDir'] . '/templates/xemails/acc_active.latte');
                            $template->registerFilter(new Nette\Latte\Engine());

                            if ($user->program == 'demo') {
                                $template->program = 'DEMOverze';
                            } elseif ($user->program == 'basic') {
                                $template->program = 'Základní verze';
                            } elseif ($user->program == 'premium') {
                                $template->program = 'Premium verze';
                            }

                            $template->dateOfReg = date_format($user->dateOfRegistration, 'd.m.Y');
                            $template->subdomain = 'http://' . $user->subdomain . '.mudrweb.cz';
                            $template->subdomain_name = $user->subdomain . '.mudrweb.cz';                           
                            $template->token = 'aa' . $user->registrationToken;
                            $template->username = $user->username;
                            $template->password = substr($user->passwordTemp, 2, - 3);                        
                            $template->usersSponsoringNumber = $user->usersSponsoringNumber;
                            
                            $mail = new \Nette\Mail\Message;
                            $mail->setFrom('MUDRweb.cz - účet <support@mudrweb.cz>')
                                    ->addTo($user_data->email)
                                    ->setHtmlBody($template)
                                    ->send();
                            
                            $this->db_users->resetTemporaryPassword(intval($user->id));     
                            
                            $this->logger->logMessage(ILogger::INFO, '>>> Subodmain ' . $user->subdomain . ' successfuly activated. [cron]');
                        }
                    }
                    // account activation END ////////////////////////////////
                    
                    // check dateTo - set user's account status from active->inactive
                    $dateToStopIt = date('Y-m-d', strtotime('-1 day'));
                    $dateTo = date_format($user->dateTo, 'Y-m-d');

                    // account deactivation START //////////////////////////////
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
                            } elseif ($user->program == 'premium') {
                                $template->program = 'Premium verze';
                            }

                            $template->dateOfReg = date_format($user->dateOfRegistration, 'd.m.Y');
                            $template->subdomain = 'http://' . $user->subdomain . '.mudrweb.cz';
                            $template->subdomain_name = $user->subdomain . '.mudrweb.cz';
                            $renewTo = strtotime($user->dateTo);
                            $renewTo = strtotime("+1 month", $renewTo);
                            $renewTo = date('d.m.Y', $renewTo);                
                            $template->renewTo = $renewTo;                            
                            $template->token = 'ad' . $user->registrationToken;

                            $mail = new \Nette\Mail\Message;
                            $mail->setFrom('MUDRweb.cz - účet <support@mudrweb.cz>')
                                    ->addTo($user_data->email)
                                    ->setHtmlBody($template)
                                    ->send();
                            
                            $this->logger->logMessage(ILogger::INFO, '>>> Subodmain ' . $user->subdomain . ' successfuly deactivated. [cron]');
                        }
                    }
                    // account deactivation END ////////////////////////////////
                    
                    // account deactivation notification START /////////////////
                    // 1st notification -> 14 days before
                    $dateFor1stNotification = strtotime($dateTo);
                    $dateFor1stNotification = strtotime("-14 days", $dateFor1stNotification);
                    $dateFor1stNotification = date('Y-m-d', $dateFor1stNotification);
                    if (($user->accountStatus == 'active') && ($dateFor1stNotification == $todaysDate)) {
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
                            } elseif ($user->program == 'premium') {
                                $template->program = 'Premium verze';
                            }

                            $template->dateOfReg = date_format($user->dateOfRegistration, 'd.m.Y');
                            $template->subdomain = 'http://' . $user->subdomain . '.mudrweb.cz';
                            $template->subdomain_name = $user->subdomain . '.mudrweb.cz';
                            $template->validTo = date_format($user->dateTo, 'd.m.Y');
                            $renewTo = strtotime($user->dateTo);
                            $renewTo = strtotime("+1 month", $renewTo);
                            $renewTo = date('d.m.Y', $renewTo);                
                            $template->renewTo = $renewTo;                            
                            $template->token = 'an' . $user->registrationToken;

                            $mail = new \Nette\Mail\Message;
                            $mail->setFrom('MUDRweb.cz - účet <support@mudrweb.cz>')
                                    ->addTo($user_data->email)
                                    ->setHtmlBody($template)
                                    ->send();

                            $this->db_users->updateAccDeactNotificationCounter(intval($user->id), 1);
                            
                            $this->logger->logMessage(ILogger::INFO, '>>> 1st notification for subodmain ' . $user->subdomain . ' successfuly sent. [cron]');
                        }
                    }

                    // 2nd notification -> 7 days before
                    $dateFor2ndtNotification = strtotime($dateTo);
                    $dateFor2ndtNotification = strtotime("-7 days", $dateFor2ndtNotification);
                    $dateFor2ndtNotification = date('Y-m-d', $dateFor2ndtNotification);
                    if (($user->accountStatus == 'active') && ($dateFor2ndtNotification == $todaysDate)) {
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
                            } elseif ($user->program == 'premium') {
                                $template->program = 'Premium verze';
                            }

                            $template->dateOfReg = date_format($user->dateOfRegistration, 'd.m.Y');
                            $template->subdomain = 'http://' . $user->subdomain . '.mudrweb.cz';
                            $template->subdomain_name = $user->subdomain . '.mudrweb.cz';
                            $template->validTo = date_format($user->dateTo, 'd.m.Y');
                            $renewTo = strtotime($user->dateTo);
                            $renewTo = strtotime("+1 month", $renewTo);
                            $renewTo = date('d.m.Y', $renewTo);                
                            $template->renewTo = $renewTo;                            
                            $template->token = 'an' . $user->registrationToken;

                            $mail = new \Nette\Mail\Message;
                            $mail->setFrom('MUDRweb.cz - účet <support@mudrweb.cz>')
                                    ->addTo($user_data->email)
                                    ->setHtmlBody($template)
                                    ->send();

                            $this->db_users->updateAccDeactNotificationCounter(intval($user->id), 2);
                            
                            $this->logger->logMessage(ILogger::INFO, '>>> 2nd notification for subodmain ' . $user->subdomain . ' successfuly sent. [cron]');
                        }
                    }

                    // 3rd notification -> 1 day before
                    $dateFor3rdNotification = strtotime($dateTo);
                    $dateFor3rdNotification = strtotime("-1 day", $dateFor3rdNotification);
                    $dateFor3rdNotification = date('Y-m-d', $dateFor3rdNotification);
                    if (($user->accountStatus == 'active') && ($dateFor3rdNotification == $todaysDate)) {
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
                            } elseif ($user->program == 'premium') {
                                $template->program = 'Premium verze';
                            }

                            $template->dateOfReg = date_format($user->dateOfRegistration, 'd.m.Y');
                            $template->subdomain = 'http://' . $user->subdomain . '.mudrweb.cz';
                            $template->subdomain_name = $user->subdomain . '.mudrweb.cz';
                            $template->validTo = date_format($user->dateTo, 'd.m.Y');
                            $renewTo = strtotime($user->dateTo);
                            $renewTo = strtotime("+1 month", $renewTo);
                            $renewTo = date('d.m.Y', $renewTo);                
                            $template->renewTo = $renewTo;                            
                            $template->token = 'an' . $user->registrationToken;

                            $mail = new \Nette\Mail\Message;
                            $mail->setFrom('MUDRweb.cz - účet <support@mudrweb.cz>')
                                    ->addTo($user_data->email)
                                    ->setHtmlBody($template)
                                    ->send();

                            $this->db_users->updateAccDeactNotificationCounter(intval($user->id), 3);
                            
                            $this->logger->logMessage(ILogger::INFO, '>>> 3rd notification for subodmain ' . $user->subdomain . ' successfuly sent. [cron]');
                        }
                    }
                    // account deactivation END ////////////////////////////////
                                        
                    // account archivation START ///////////////////////////////
                    // after 1 month of inactive status move from inactive->archive
                    $dateForArchivation = strtotime($dateTo);
                    $dateForArchivation = strtotime("+1 month", $dateForArchivation);
                    $dateForArchivation = date('Y-m-d', $dateForArchivation);
                    if (($user->accountStatus == 'inactive') && ($todaysDate >= $dateForArchivation)) {
                        $this->db_users->updateRegistrationProcessStatus(intval($user->id), 'archive');
                        
                        // archive subomdain using ftp
                        $this->extraMethods->archiveSubdomain($user->subdomain);
                        
                        $this->logger->logMessage(ILogger::INFO, '>>> Subodmain ' . $user->subdomain . ' successfuly archived. [cron]');
                    }
                    // account archivation END /////////////////////////////////
                    
                    // invoice recipient list START ////////////////////////////
                    $dateForInvoiceResend = strtotime($dateTo);
                    $dateForInvoiceResend = strtotime("-1 month", $dateForInvoiceResend);
                    $dateForInvoiceResend = date('Y-m-d', $dateForInvoiceResend);
                    if ($dateForInvoiceResend == $todaysDate) {
                        $user_data = $this->db_users->getUsersDataById(intval($user->id));
                        $listOfInvoiceRecipients[] = array($user_data->name, $user_data->surname, $user_data->email); 
                    }
                    // invoice recipient list END //////////////////////////////
                }
            }
        } else {
            throw new \Nette\Application\BadRequestException('Unable to load users (MainModule - default presenter).', 404);        
        }
        
        // invoice resend remainder START //////////////////////////////////////
        if ($listOfInvoiceRecipients) {
            $this->logger->logMessage(ILogger::INFO, '>>> List of users to resend invoice: [cron]');
            $template = "Zalohovou fakturu je dnes potreba zaslat uzivatelum (kterym platnost uctu vyprsi za 30 dni):\n\n";
            $counter = 0;
            foreach ($listOfInvoiceRecipients as $recipient) {
                $template .= $counter . '. ' . $recipient[0] . ' ' . $recipient[1] . ' - ' . $recipient[2];
                $template .= "\n";
                $this->logger->logMessage(ILogger::INFO, '>>>>>> ' . $counter . '. ' . $recipient[0] . ' ' . $recipient[1] . ' - ' . $recipient[2]);                
                $counter++;                
            }            
                        
            $mail = new \Nette\Mail\Message;
            $mail->setFrom('MUDRweb.cz - invoice resend reminder <admin@mudrweb.cz>')
                    ->addTo('mudrweb@gmail.com')
                    ->setSubject('Seznam uživatelů pro zaslání zálohové faktury')
                    ->setBody($template)
                    ->send();
        }        
        // invoice resend remainder END ////////////////////////////////////////
        
        $this->terminate();
    }    
}
