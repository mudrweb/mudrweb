<?php

namespace AdminModule;

use Nette\Application\UI,
	Nette\Security as NS;
use Nette\Application\UI\Form;
use \AdminPresenter as AdminPresenter;


/**
 * Forgotten password presenters.
 *
 * @author     Zippo
 * @package    AdminModule
 */
class ForgottenPasswordPresenter extends AdminPresenter
{
    
    /**
     *  Startup settings.
     */   
    public function startup() {
        parent::startup();
        
        \Nette\Forms\Container::extensionMethod('addTextCaptcha', array('\TextCaptcha\TextCaptcha', 'addTextCaptcha'));
        \TextCaptcha\TextCaptcha::setSession(\Nette\Environment::getSession());
        \TextCaptcha\TextCaptcha::setBackend(new \TextCaptcha\ArrayBackend);
        \TextCaptcha\TextCaptcha::setLanguage("cs");                  
    }
    
//    // uživatelský validátor: testuje, zda je hodnota dělitelná argumentem
//    function divisibilityValidator($item, $arg)
//    {
//        return $item->value % $arg === 0;
//    }
    
    /**
     * Sign in form component factory.
     * 
     * @return Nette\Application\UI\Form
     */
    protected function createComponentForgottenPasswordForm() {
        $form = new Form;
        $form->addText('subdomain', 'Název Vaší stránky:', 30, 30)
                ->setRequired('Zadejte název Vaší stránky.')
                ->setAttribute('class', 'input_style_fpass');
        
//        $form->addText('test', 'Test')
//                ->addRule('ForgottenPasswordPresenter::divisibilityValidator', 'Číslo musí být dělitelné %d.', 8)
//                ->addRule(Form::FILLED, 'Name is mandatory.');
//                ->addRule(function (\Nette\Forms\Controls\TextInput $control) {
//                    return (bool) ($control->getValue() % 2);
//                    }, 'Musíte uvést liché číslo');
//                ->setAttribute('class', 'input_style_fpass');
        
        $form->addTextCaptcha()
                ->setAttribute('class', 'input_style_fpass_captcha');

        $form->addSubmit('send', ' Odeslat ')
                ->setAttribute('class', 'button')
                ->onClick[] = callback($this, 'resendCredentials');

        $form->addSubmit('back', ' Návrat zpět ')        
                ->setAttribute('class', 'button')
                ->setValidationScope(FALSE)
                ->onClick[] = callback($this, 'returnBack');                                       
        
        $form->addProtection('Vypršel časový limit, odešlete prosím formulář znovu.', 60);
        
        return $form;
    }    

    /**
     * Send user login and new password (randomly generated).
     */
    public function resendCredentials(\Nette\Forms\Controls\Button $button) {
        // get data from form
        $data = $button->form->getValues();   
        
        // store password resend attempt datetime + ip address for current 
        // subdomain - real or not
        $this->db_stats->savePassResendAttempt($data->subdomain, $this->getHttpRequest()->remoteAddress);
        
        // get user data from DB
        $userFromDB = $this->db_users->getUserBySubdomain($data->subdomain);
        if ($userFromDB) {
            // generate new password - 10 chars         
            $newPassword = $this->extraMethods->generatePassword();            
            // generate new salt - 16 chars
            $newSalt = $this->extraMethods->generateSalt();            
            // calculate password hash
            $hashedPassword = $this->extraMethods->calculateHash($newPassword, $newSalt);
            // prepare data for user's profile update - password hash and salt           
            $dataArray = array(intval($userFromDB->id), $hashedPassword, $newSalt);
            
            // get user's data
            $userFromDB_data = $this->db_users->getUsersDataById($userFromDB->id);
            if ($userFromDB_data) {            
                // check password resent limit
                $nowDateTime = new \Nette\DateTime("now");                
                $resentDateTime = date_format($userFromDB->passwordResent, "Y-m-d H:i:s");
                if ((strtotime($nowDateTime->format("Y-m-d H:i:s")) - strtotime($resentDateTime)) > 86400) {
                    // store resent datetime
                    $this->db_users->passwordResentDateTime($userFromDB->id);

                    // find @
                    $rollmop = strpos($userFromDB_data->email, '@');
                    $fromRollmopToEnd = substr($userFromDB_data->email, $rollmop);
                    $fromStartToRoolmop = substr($userFromDB_data->email, 0, $rollmop);

                    // number of displayed chars - can be modified
                    $numOfDisplayedChars = 4;
                    if (strlen($fromStartToRoolmop) >= $numOfDisplayedChars) {
                        $emailPartToDisplay = "";
                        for ($i = 0; $i < $rollmop - $numOfDisplayedChars; $i++) {
                            $emailPartToDisplay .= '*';
                        }
                        $emailPartToDisplay .= substr($userFromDB_data->email, $rollmop - 4);
                    } else {
                        $emailPartToDisplay = substr($userFromDB_data->email, 0);
                    }

                    // status message
                    $message = 'Nové heslo bylo úspěšně odesláno na Váš e-mail: ';
                    $message .= $emailPartToDisplay;

                    // update user's profile
                    $this->db_users->changePassword($dataArray);                    
                    
                    // send email
                    $template = parent::createTemplate();
                    $template->setFile($this->getContext()->params['appDir'] . '/templates/xemails/pass_resend.latte');
                    $template->registerFilter(new \Nette\Latte\Engine());        
                    
                    $template->username = $userFromDB->username;
                    $template->password = $newPassword;
                    $template->subdomain = $userFromDB->subdomain . '.mudrweb.cz';        

                    $mail = new \Nette\Mail\Message;
                    $mail->setFrom('MUDRweb.cz - účet <support@mudrweb.cz>')
                            ->addTo($userFromDB_data->email)                
                            ->setHtmlBody($template)
                            ->send();                     
                    
                    $this->flashMessage($message, 'info_fpass');
                    $this->redirect('this');
                } else {
                    $this->flashMessage('Limit pro odeslání hesla byl vyčerpán! 
                                V případě otázek kontaktujte prosím naši podporu - support@mudrweb.cz.', 'warning_fpass');
                    $this->redirect('this');
                }
            } else {
                throw new \Nette\Application\BadRequestException('Unable to load user data (AdminModule - forgotten password presenter).', 404);
            }
        } else {
            $this->flashMessage('Zadaná stránka neexistuje nebo byl použit nesprávný formát!', 'warning_fpass');
            $this->redirect('this');
        }        
    }
    
    /**
     * Return back to login page.
     */
    public function returnBack(\Nette\Forms\Controls\Button $button) {
        $this->redirect(':Admin:Default:');
    }
}
