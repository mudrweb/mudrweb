<?php

namespace AdminModule;

use Nette\Forms\Form;
use \AdminPresenter as AdminPresenter;

/**
 * AdminModule profile changes presenter.
 *
 * @author     Zippo
 * @package    AdminModule
 */
class ProfilePresenter extends AdminPresenter {            
    
    private $name;
    private $surname;
    private $userTitleBefore;    
    private $userTitleAfter;    
    private $doctorGroup;
    private $street;
    private $city;
    private $zip;
    private $region;
    private $phone;
    private $email;


    /**
     * Check access and rights here only
     */
    public function startup()
    {
        parent::startup();
        $this->checkAccess(array('uživatel'));                                  
    }
      
    /**
     * renderDefault for profile section. 
     */
    public function renderDefault() {
        $userData = $this->db_users->getUsersDataById($this->user->getId());
        // get personal information data
        if ($userData) {
            $this->name = $userData->name;
            $this->surname = $userData->surname;
            $this->userTitleBefore = $userData->titleBefore;
            $this->userTitleAfter = $userData->titleAfter;
            $this->doctorGroup = $userData->doctorGroup;
            $this->street = $userData->street;
            $this->city = $userData->city;
            $this->zip = $userData->zip;
            $this->region = $userData->region;
            $this->phone = $userData->phone;
            $this->email = $userData->email;
        } else {
            throw new \Nette\Application\BadRequestException('Unable to load user data (AdminModule - profile presenter).', 404);
        }  
    }
    
    /**
     * Create form for password change.
     * 
     * @return \Nette\Application\UI\Form 
     */
    protected function createComponentEditPassForm() {
        $form = new \Nette\Application\UI\Form;       
        $form->addHidden('userId', $this->user->getId());                                     
                
        $form->addPassword('oldPassword', 'Aktuální heslo:', 52, 40)                
                ->addRule(Form::FILLED, 'Zadejte prosím Vaše aktuální heslo.')
                ->addRule(Form::MAX_LENGTH, 'Aktuální heslo: Maximální povolená délka aktuálního hesla je 40 znaků.', 40)
                ->setAttribute('class', 'input_fpass');                       
        
        $form->addPassword('newPassword', 'Nové heslo:', 52, 40)                
                ->addRule(Form::FILLED, 'Zadejte prosím nové heslo.')                                                
                ->addRule(Form::MIN_LENGTH, 'Minimální požadovaná délka nového hesla je 8 znaků.', 8)
                ->addRule(Form::MAX_LENGTH, 'Nové heslo: Maximální povolená délka nového hesla je 40 znaků.', 40)
                ->setAttribute('class', 'password');
        
        $form->addPassword('newPassword1', 'Nové heslo (zopakovat):', 52, 40)                
                ->addRule(Form::FILLED, 'Zopakujte prosím nové heslo.')     
                ->addRule(Form::MIN_LENGTH, 'Minimální požadovaná délka zopakovaného hesla je 8 znaků.', 8)
                ->addRule(Form::MAX_LENGTH, 'Nové heslo (zopakovat): Maximální povolená délka zopakovaného hesla je 40 znaků.', 40)
                ->setAttribute('class', 'input_fpass');
        
        $form->addSubmit('submit', 'Změnit heslo')
                ->setAttribute('class', 'button')
                ->onClick[] = callback($this, 'changePass');               
        
        $form->addProtection('Vypršel časový limit, odešlete prosím formulář znovu.', 60);
        
        return $form;
    }
    
    /**
     * Create form for personal information changes.
     * 
     * @return \Nette\Application\UI\Form 
     */
    protected function createComponentEditPInfoForm() {
        $form = new \Nette\Application\UI\Form;       
        $form->addHidden('userId', $this->user->getId());                                     
                
        $form->addText('name', 'Jméno:', 45, 45)                
                ->addRule(Form::FILLED, 'Musíte zadat jméno.')                
                ->addRule(Form::MAX_LENGTH, 'Jméno: Maximální povolená délka jména je 45 znaků.', 45)
                ->setDefaultValue($this->name)
                ->setAttribute('class', 'input_style_pinfo'); 

        $form->addText('surname', 'Příjmení:', 45, 45)                
                ->addRule(Form::FILLED, 'Musíte zadat příjmení.')                
                ->addRule(Form::MAX_LENGTH, 'Příjmení: Maximální povolená délka příjmení je 45 znaků.', 45)
                ->setDefaultValue($this->surname)
                ->setAttribute('class', 'input_style_pinfo');         

        $form->addText('titleBefore', 'Titul před:', 12, 12)                                                
                ->addRule(Form::MAX_LENGTH, 'Titul před: Maximální povolená délka titulu před jménem je 12 znaků.', 12)
                ->setDefaultValue($this->userTitleBefore)
                ->setAttribute('class', 'input_style_pinfo');                            

        $form->addText('titleAfter', 'Titul za:', 12, 12)                                                
                ->addRule(Form::MAX_LENGTH, 'Titul za: Maximální povolená délka titulu za jménem je 12 znaků.', 12)
                ->setDefaultValue($this->userTitleAfter)
                ->setAttribute('class', 'input_style_pinfo');     
        
        $doctorGroupIdFoundByDesc = null;        
        $usedUserSpecificDoctorGroup = false;
        $doctorGroup = null;
        // found group by id in 2D array
        foreach ($this->doctorGroupsList as $doctorGroup => $value) {            
            if (array_search($this->doctorGroup, $this->doctorGroupsList[$doctorGroup])) {
                $doctorGroupIdFoundByDesc = array_search($this->doctorGroup, $this->doctorGroupsList[$doctorGroup]);
                $doctorGroup = 'Vaše odbornost';
                break;
            }
        }
        
        if (!$doctorGroupIdFoundByDesc) {
            $doctorGroupIdFoundByDesc = 'xxx';
            $usedUserSpecificDoctorGroup = true;
            $doctorGroup = $this->doctorGroup;
        }                
        
        $form->addHidden('usedUserSpecificDoctorGroup', $usedUserSpecificDoctorGroup);
        
        $form->addSelect('doctorGroup', 'Odbornost:', $this->doctorGroupsList)                    
                ->setDefaultValue($doctorGroupIdFoundByDesc)
                ->setAttribute('class', 'input_style_select');         

        $form->addText('extraDoctorGroup', 'Odbornost+:', 40, 40)                
                ->addRule(Form::FILLED, 'Musíte zadat odbornost nebo vybrat ze seznamu.')
                ->addRule(Form::MAX_LENGTH, 'Odbornost+: Maximální povolená délka odbornosti je 40 znaků.', 40)   
                ->setDefaultValue($doctorGroup)
                ->setAttribute('class', 'input_style_pinfo');         
        
        $form->addText('street', 'Ulice a číslo:', 50, 50)                
                ->addRule(Form::FILLED, 'Musíte zadat ulici a číslo.')                
                ->addRule(Form::MAX_LENGTH, 'Ulice a číslo: Maximální povolená délka ulice s číslem je 50 znaků.', 50)
                ->setDefaultValue($this->street)
                ->setAttribute('class', 'input_style_pinfo');         
        
        $form->addText('city', 'Město:', 50, 50)                
                ->addRule(Form::FILLED, 'Musíte zadat město.')                
                ->addRule(Form::MAX_LENGTH, 'Město: Maximální povolená délka města je 50 znaků.', 50)
                ->setDefaultValue($this->city)
                ->setAttribute('class', 'input_style_pinfo');         
        
        $form->addText('zip', 'PSČ:', 5, 5)                
                ->addRule(Form::FILLED, 'Musíte zadat PSČ.')  
                ->addRule(Form::INTEGER, 'PSČ musí být číslo.')
                ->addRule(Form::MAX_LENGTH, 'PSČ: Minimální požadovaná délka PSČ je 5 znaků.', 5)                
                ->addRule(Form::MAX_LENGTH, 'PSČ: Maximální povolená délka PSČ je 5 znaků.', 5)
                ->setDefaultValue($this->zip)
                ->setAttribute('class', 'input_style_pinfo');         
        
        $form->addSelect('region', 'Kraj:', $this->regionsList)                
                ->setDefaultValue($this->region)
                ->setAttribute('class', 'input_style_select');                
        
        $form->addText('phone', 'Telefon:', 9, 9)                
                ->addRule(Form::FILLED, 'Musíte zadat telefonní číslo.')                                                
                ->addRule(Form::INTEGER, 'Telefonní číslo musí být číslo.')                                
                ->addRule(Form::MAX_LENGTH, 'Telefon: Maximální povolená délka telefonního čísla je 9 znaků.', 9)
                ->setDefaultValue($this->phone)
                ->setAttribute('class', 'input_style_pinfo');                 

        $form->addText('email', 'Kontaktní e-mail:', 30, 30)       
                ->addRule(Form::FILLED, 'Musíte zadat kontaktní e-mail.')
                ->addRule(Form::EMAIL, 'Musíte zadat e-mail v platném formátu (napr. jozef.novak@gmail.com).')                
                ->addRule(Form::MAX_LENGTH, 'Kontaktní e-mail: Maximální povolená kontaktního e-mailu je 30 znaků.', 30)                                             
                ->setDefaultValue($this->email)                
                ->setAttribute('class', 'input_style_pinfo');             
        
        $form->addSubmit('submit', 'Změnit osobní údaje')
                ->setAttribute('class', 'button')
                ->onClick[] = callback($this, 'changePInfo');               
        
        $form->addProtection('Vypršel časový limit, odešlete prosím formulář znovu.', 60);
        
        return $form;
    }
    
    /**
     * Save password change.
     * 
     * @param \Nette\Forms\Controls\Button $button 
     */
    public function changePass(\Nette\Forms\Controls\Button $button)
    {   
        // get data from form
        $data = $button->form->getValues();                                   
                
        // update current user password
        // get user data from DB
        $userFromDB = $this->db_users->getUserById($this->user->getId());
        if ($userFromDB) {
            // check old password validity
            $oldPassword_hashed = $this->extraMethods->calculateHash($data->oldPassword, $userFromDB->salt);
            if ($oldPassword_hashed == $userFromDB->password) {
                // check new password and repeated password equality                
                if ($data->newPassword == $data->newPassword1) {
                    // prepare data for update
                    // store new salt
                    $newSalt = $this->extraMethods->generateSalt();
                    // store hashed new password
                    $hashedNewPassword = sha1($data->newPassword . str_repeat($newSalt, 10));
                    $dataArray = array(intval($data->userId), $hashedNewPassword, $newSalt);

                    $this->db_users->changePassword($dataArray);
                    $this->flashMessage('Vaše heslo bylo úspěšně změněno.', 'info');
                    $this->redirect('this');
                } else {
                    $this->flashMessage('Zadané NOVÉ heslo se nezhoduje se zopakovaným heslem!', 'warning');
                    $this->redirect('this');
                }
            } else {
                $this->flashMessage('Zadané AKTUÁLNÍ heslo je neplatné!', 'warning');
                $this->redirect('this');
            }
        } else {
            $this->flashMessage('Vyskytla se chyba!', 'warning');
            $this->redirect('this');
        }
    }
    
    /**
     * Save changes for user profile.
     * 
     * @param \Nette\Forms\Controls\Button $button 
     */
    public function changePInfo(\Nette\Forms\Controls\Button $button)
    {   
        // get data from form
        $data = $button->form->getValues();                                   
        
        $doctorGroupFoundById = null;
        // doctor group chosen from list or set manually?
        if ($data->doctorGroup != 'xxx') {
            // found group by id in 2D array
            foreach ($this->doctorGroupsList as $doctorGroup => $value) {
                if (array_key_exists($data->doctorGroup, $this->doctorGroupsList[$doctorGroup])) {
                    $doctorGroupFoundById = $this->doctorGroupsList[$doctorGroup][$data->doctorGroup];
                }
            }
        } else {
            $doctorGroupFoundById = $data->extraDoctorGroup;
        }

        // prepare data for update
        $dataArray = array(intval($data->userId), $data->name, $data->surname, 
                     $data->titleBefore, $data->titleAfter, $doctorGroupFoundById, $data->street, $data->city, $data->zip,
                     $data->region, $data->phone, $data->email);  
        
        // update user profile        
        $this->db_users->changeUserProfileInfo($dataArray);
        $this->flashMessage('Osobní údaje byly úspěšně změněny.', 'info');            
        $this->redirect('this');             
    }   
}
