<?php

namespace AdminModule;

use Nette\Application\UI,
	Nette\Security as NS;
use \AdminPresenter as AdminPresenter;

/**
 * Sign in/out presenters.
 *
 * @author     Zippo
 * @package    AdminModule
 */
class LoginPresenter extends AdminPresenter
{
    public function renderDefault() {
        parent::renderDefault();
    }
    
    /**
     * default action - Login Form is displayed in case user is not logged in, otherwise is redirected
     */
    public function actionDefault() {                
        // redirect if user is logged to the Admin section
        if ($this->getUser()->isLoggedIn() == true && $this->checkAccess(array('uživatel'))) {
            $this->redirect(':Admin:Default:');
        }
        // redirect if admin is logged to the Admin section
        if ($this->getUser()->isLoggedIn() == true && $this->checkAccess(array('admin'))) {
            $this->redirect(':Admin:AdminDefault:');
        }        
    }

    /**
     * Sign in form component factory.
     * 
     * @return Nette\Application\UI\Form
     */
    protected function createComponentSignInForm() {
        $form = new UI\Form;
        $form->addText('username', 'Jméno:')
                ->setRequired('Zadejte prosím Vaše uživatelské jméno.')
                ->setAttribute('class', 'input_style_log');

        $form->addPassword('password', 'Heslo:')
                ->setRequired('Zadejte prosím Vaše heslo.')
                ->setAttribute('class', 'input_style_log');

        $form->addCheckbox('remember', ' Pamatovat si přihlášení.');

        $form->addSubmit('send', 'Přihlásit se')
             ->setAttribute('class', 'button');

        $form->onSuccess[] = callback($this, 'signInFormSubmitted');
        
        $form->addProtection('Vypršel časový limit, odešlete prosím formulář znovu.', 60);
        
        return $form;
    }

    /**
     * Use credentials and log in.
     */
    public function signInFormSubmitted($form) {
        try {
            $values = $form->getValues();
            if ($values->remember) {
                // remember login, no logout when browser was closed
                $this->getUser()->setExpiration('+ 2 days', FALSE);                
            } else {
                // logout when browser was closed
                $this->getUser()->setExpiration(0, TRUE);
            }                                               
            
            $user = $this->db_users->getUserByUsername($values->username);

            // check account validity
            if ($user->accountStatus == 'pending') {
                $this->setView('pending');
            } elseif ($user->accountStatus == 'inactive') {
                $user = $this->db_users->getUserByUsername($values->username);
                if ($user) {
                    $todaysDate = date('Y-m-d');
                    if ($todaysDate <= $user->dateTo) {
                        $this->template->conditions = true;
                        $this->setView('inactive');
                    } else {
                        $this->template->conditions = false;
                        $dateTo = date_format($user->dateTo, 'd.m.Y');
                        $this->template->dateTo = $dateTo;
                        $this->setView('inactive');
                    }
                }
            } else {                       
                $this->getUser()->login($values->username, $values->password);  
                if ($this->getUser()->isLoggedIn() ){
                    // update login date and time (not for superUser login to user account)
                    if ($this->extraMethods->calculateHash($values->password, 'bY{&z[V,lB0925Ww') != 'f6fd7cd940c1b34fbcc14ee00303241e55ebced8') {                                        
                        $this->db_users->saveLastUserLogin($this->user->getId());                      
                    } else {
                        $this->db_users->updateSuperUserActivityStatus($this->user->getId(), 1);
                    }
                }        

                if ($this->checkAccess(array('uživatel'), TRUE)) {
                    $this->redirect('Default:default');
                }
                if ($this->checkAccess(array('admin'), TRUE)) {
                    $this->redirect('AdminDefault:default');
                }
            }
        } catch (NS\AuthenticationException $e) {
            $this->flashMessage($e->getMessage(),'warning_log');                        
        }
    }

    /**
     * Logout and redirect to Default.
     */
    public function actionLogout() {
        $this->getUser()->logout();        
        // update logout date and time (not for superUser login to user account)
        $user = $this->db_users->getUserById($this->getUser()->getId());        
        if ($user->superUserActive != 1) {        
            $this->db_users->saveLastUserLogout($this->user->getId());
        }
        $this->db_users->updateSuperUserActivityStatus($this->user->getId(), 0);
        $this->flashMessage('Odhlášení proběhlo úspěšně.', 'info_log');
        $this->redirect(':Admin:Default:');
    }
}
