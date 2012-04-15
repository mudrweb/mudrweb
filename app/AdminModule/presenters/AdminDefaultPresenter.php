<?php

namespace AdminModule;

use Nette\Forms\Form;
use \AdminPresenter as AdminPresenter;

/**
 * AdminModule admin default presenter.
 *
 * @author     Zippo
 * @package    AdminModule
 */
class AdminDefaultPresenter extends AdminPresenter {            
    
    private $password;    
    private $layout = 'layout_A3.jpg';
    private $layoutDesc = 'A - zelena';
    
    /**
     * Check access and rights here only
     */
    public function startup()
    {
        parent::startup();
        $this->checkAccess(array('admin'));                
    }
    
    /**
     * renderDefault for adminDefault presenter. 
     */
    public function renderDefault() {            
        $this->template->passwordToDisplay = $this->password;     
        $this->template->layoutToDisplay = $this->layout;   
        $this->template->layoutDesc = $this->layoutDesc;   
    }                
    
    /**
     * Callback function to check username availability.
     * 
     * @param TextInput $control
     * @return bool username existence 
     */
    static function userexists($control) {  
        // get all usernames 
        $db_users = \Nette\Environment::getService('usersmanager');
        $users = $db_users->getUsers();
        
        $usernamesArray = array();
        foreach ($users as $user) {
            $usernamesArray[] = $user->username;
        }
        
        $text = $control->value;
        $text = trim($text);
        if ($text !== '') {
            if (in_array($text, $usernamesArray)) {
                return false;
            } else {
                return true;
            }
        }        
//        return true;
    } 
    
    /**
     * Callback function to check subdomain availability.
     * 
     * @param TextInput $control
     * @return bool subdomain existence 
     */
    static function subdomainexists($control) {  
        // get all subdomains 
        $db_users = \Nette\Environment::getService('usersmanager');
        $users = $db_users->getUsers();
        
        $subdomainsArray = array();
        foreach ($users as $user) {
            if ($user->accountStatus != 'archive')
            $subdomainsArray[] = $user->subdomain;
        }
        
        $text = $control->value;
        $text = trim($text);
        if ($text !== '') {
            if (in_array($text, $subdomainsArray)) {
                return false;
            } else {
                return true;
            }
        }      
//        return true;
    }     
    
    /**
     * Create form for new user registration.
     * 
     * @return \Nette\Application\UI\Form 
     */
    protected function createComponentRegUserForm() {
        $form = new \Nette\Application\UI\Form;       

//        $form->getElementPrototype()->class('ajax');        
        
        // login data
        $form->addText('username', 'Uživatelské jméno:', 20, 20)                
                ->addRule(Form::FILLED, 'Musíte zadat uživatelské jméno.')
                ->addRule(Form::MIN_LENGTH, 'Minimální požadovaná délka uživatelského jména je 8 znaků.', 8)                
                ->addRule(Form::MAX_LENGTH, 'Uživatelské jméno: Maximální povolená délka uživatelského jména je 20 znaků.', 20)               
//                ->addRule(
//                    function (\Nette\Forms\Controls\TextInput $control) { 
//                        return (bool) (false);
//                    }, 'asdf')                
//                    
                ->addRule(callback('\AdminModule\AdminDefaultPresenter::userexists'),'Jméno je již použito, zvolte prosím jiné.')                                
                ->setAttribute('class', 'input_style_pinfo');               
        
        $form->addText('subdomain', 'Název stránky:', 30, 30)                
                ->addRule(Form::FILLED, 'Musíte zadat název stránky.')                
                ->addRule(Form::MIN_LENGTH, 'Minimální požadovaná délka názvu stránky je 6 znaků.', 6)                
                ->addRule(Form::MAX_LENGTH, 'Název stránky: Maximální povolená délka názvu stránky je 30 znaků.', 30)               
                ->addRule(callback('\AdminModule\AdminDefaultPresenter::subdomainexists'),'Název stránky je již použit, zvolte prosím jiný.')                                                
                ->setAttribute('class', 'input_style_pinfo');                                      
        
        $form->addText('email', 'Kontaktní e-mail:', 30, 30)       
                ->addRule(Form::FILLED, 'Musíte zadat kontaktní e-mail.')
                ->addRule(Form::EMAIL, 'Musíte zadat existující e-mail v platném formátu (napr. jozef.novak@gmail.com).')                
                ->addRule(Form::MAX_LENGTH, 'Kontaktní e-mail: Maximální povolená kontaktního e-mailu je 30 znaků.', 30)                                                             
                ->setAttribute('class', 'input_style_pinfo');                  

        $form->addPassword('newPassword', 'Heslo:', 52, 40)                
                ->addRule(Form::FILLED, 'Zadejte prosím heslo.')  
                ->addRule(Form::MIN_LENGTH, 'Heslo: Minimální požadovaná délka hesla je 8 znaků.', 8)
                ->addRule(Form::MAX_LENGTH, 'Heslo: Maximální povolená délka hesla je 40 znaků.', 40)                             
                ->setAttribute('class', 'password_regUserPanel');        

//        $form->addPassword('newPassword1', 'Heslo (zopakovat):', 52, 40)                
//                ->addRule(Form::FILLED, 'Potvrďte prosím zadané heslo.')                               
//                ->addRule(Form::MAX_LENGTH, 'Heslo (zopakovat): Maximální povolená délka zopakovaného hesla je 40 znaků.', 40)
//                ->setAttribute('class', 'input_fpass_regUserPanel');        
        
        //personal data        
        $form->addText('name', 'Jméno:', 45, 45)                
                ->addRule(Form::FILLED, 'Musíte zadat jméno.')                
                ->addRule(Form::MAX_LENGTH, 'Jméno: Maximální povolená délka jména je 45 znaků.', 45)               
                ->setAttribute('class', 'input_style_pinfo'); 

        $form->addText('surname', 'Příjmení:', 45, 45)                
                ->addRule(Form::FILLED, 'Musíte zadat příjmení.')                
                ->addRule(Form::MAX_LENGTH, 'Příjmení: Maximální povolená délka příjmení je 45 znaků.', 45)                
                ->setAttribute('class', 'input_style_pinfo');         

        $form->addText('titleBefore', 'Titul před:', 12, 12)                
                ->addRule(Form::MAX_LENGTH, 'Titul před: Maximální povolená délka titulu před jménem je 12 znaků.', 12)                
                ->setAttribute('class', 'input_style_pinfo');                            

        $form->addText('titleAfter', 'Titul za:', 12, 12)                
                ->addRule(Form::MAX_LENGTH, 'Titul za: Maximální povolená délka titulu za jménem je 12 znaků.', 12)                
                ->setAttribute('class', 'input_style_pinfo');                                    

        $form->addSelect('doctorGroup', 'Odbornost:', $this->doctorGroupsList)                    
                ->setDefaultValue('1')
                ->setAttribute('class', 'input_style_select');         

        $form->addText('extraDoctorGroup', 'Odbornost+:', 40, 40)                
                ->addRule(Form::FILLED, 'Musíte zadat odbornost nebo vybrat ze seznamu.')
                ->addRule(Form::MAX_LENGTH, 'Odbornost+: Maximální povolená délka odbornosti je 40 znaků.', 40)   
                ->setDefaultValue('Vaše odbornost')
                ->setAttribute('class', 'input_style_pinfo');           
        
        $form->addText('street', 'Ulice a číslo:', 50, 50)                
                ->addRule(Form::FILLED, 'Musíte zadat ulici.')                
                ->addRule(Form::FILLED, 'Musíte zadat ulici a číslo.')                
                ->addRule(Form::MAX_LENGTH, 'Ulice a číslo: Maximální povolená délka ulice s číslem je 50 znaků.', 50)
                ->setAttribute('class', 'input_style_pinfo');         
        
        $form->addText('city', 'Město:', 50, 50)                
                ->addRule(Form::FILLED, 'Musíte zadat město.')                
                ->addRule(Form::MAX_LENGTH, 'Město: Maximální povolená délka města je 50 znaků.', 50)                
                ->setAttribute('class', 'input_style_pinfo');         
        
        $form->addText('zip', 'PSČ:', 5, 5)                
                ->addRule(Form::FILLED, 'Musíte zadat PSČ.')  
                ->addRule(Form::INTEGER, 'PSČ musí být číslo.')
                ->addRule(Form::MIN_LENGTH, 'PSČ: Minimální požadovaná délka PSČ je 5 znaků.', 5)                                
                ->addRule(Form::MAX_LENGTH, 'PSČ: Maximální povolená délka PSČ je 5 znaků.', 5)                
                ->setAttribute('class', 'input_style_pinfo');   
        
        $form->addSelect('region', 'Kraj:', $this->regionsList)                    
                ->setDefaultValue('jihocesky')
                ->setAttribute('class', 'input_style_select');  
        
        $form->addText('phone', 'Telefon:', 9, 9)                
                ->addRule(Form::FILLED, 'Musíte zadat telefonní číslo.')                                                
                ->addRule(Form::INTEGER, 'Telefonní číslo musí být číslo.')                
                ->addRule(Form::MAX_LENGTH, 'Telefon: Maximální povolená délka telefonního čísla je 9 znaků.', 9)                
                ->setAttribute('class', 'input_style_pinfo');                 
        
        $form->addRadioList('program', 'Program:', array(
                'demo' => 'DEMOverze - 3 měsíce - ZDARMA',
                'basic' => 'Základní verze - 1 rok - 990 Kč',
                'premium' => 'Premium verze - 1 rok - 1190 Kč',
                ))
                ->setDefaultValue('demo')
                ->setAttribute('class', 'programs');        
        
        // website data
        // list of layouts
        $listOfLayoutsArray = array();
        foreach ($this->layout_groups as $layout_group) {
            $layoutsArray = array();
            $layouts = $this->db_users->getLayoutsBylayoutGroup($layout_group);
            if ($layouts) {
                foreach ($layouts as $layout) {                    
                    $layoutsArray[$layout->id] = $layout->layout_desc;
                }
                $listOfLayoutsArray[$layout_group] = $layoutsArray;                
            } else {
                $listOfLayoutsArray[$layout_group] = $layout_group;                
            }           
        }        
        
        $form->addSelect('layouts', 'Vzhled:', $listOfLayoutsArray)
                ->setAttribute('class', 'input_style_select');                
        $form->addText('header1', 'Hlavní nadpis:', 50, 40)                
//                ->addRule(Form::FILLED, 'Musíte zadat hlavní nadpis.')                
                ->addRule(Form::MAX_LENGTH, 'Hlavní nadpis: Maximální povolená délka hlavního nadpisu je 40 znaků.', 40)                
                ->setAttribute('class', 'input_style_pinfo');          
        $form->addText('header2', 'Podnadpis:', 50, 40)                
//                ->addRule(Form::FILLED, 'Musíte zadat podnadpis.')                
                ->addRule(Form::MAX_LENGTH, 'Podnadpis: Maximální povolená délka podnadpisu je 40 znaků.', 40)                
                ->setAttribute('class', 'input_style_pinfo');          
        $form->addTextArea('pageTitle', 'Název stránky (title):', 52, 64)                
//                ->addRule(Form::FILLED, 'Musíte zadat název stránky.')                
//                ->addRule(Form::MIN_LENGTH, 'Název stránky: Minimální požadovaná délka názvu stránky je 10 znaků.', 10)
                ->addRule(Form::MAX_LENGTH, 'Název stránky: Maximální povolená délka názvu stránky je 64 znaků.', 64)                
                ->setAttribute('class', 'textarea_default_title');
        $form->addTextArea('description', 'Popis stránky (description):', 52, 40)                
//                ->addRule(Form::FILLED, 'Musíte zadat popis stránky.')   
//                ->addRule(Form::MIN_LENGTH, 'Popis stránky: Minimální požadovaná délka popisu stránky je 50 znaků.', 50)
                ->addRule(Form::MAX_LENGTH, 'Popis stránky: Maximální povolená délka popisu stránky je 149 znaků.', 149)                
                ->setAttribute('class', 'textarea_default_desc');        
        $form->addTextArea('keywords', 'Klíčová slova (keywords):', 52, 40)                
//                ->addRule(Form::FILLED, 'Musíte zadat klíčová slova stránky.')                                
                ->setAttribute('class', 'textarea_default_keywords');              
        
        $form->addText('referral', 'Refer číslo:', 50, 40)                
                ->addRule(Form::MAX_LENGTH, 'Referenční číslo: Maximální povolená délka referral number je 4 znaků.', 4)                
                ->setAttribute('class', 'input_style_pinfo');           
        
        $form->addSubmit('submit', 'Registrovat uživatele')
                ->setAttribute('class', 'button')
                ->onClick[] = callback($this, 'registerUser');               

        $form->addProtection('Vypršel časový limit, odešlete prosím formulář znovu.', 900);               
        
        return $form;
    }    

    /**
     * Generate password handler (ajax)
     */
    public function handleGeneratePass()
    {
        $this->password = $this->extraMethods->generatePassword();                
        
        if (!$this->isAjax()) {                        
            $this->redirect('this');
        } else {
            $this->invalidateControl('dispPass');
        }
    }    
    
    /**
     * Register user.
     * 
     * @param \Nette\Forms\Controls\Button $button 
     */
    public function registerUser(\Nette\Forms\Controls\Button $button)
    {   
        // get data from form
        $data = $button->form->getValues();

        // prepare data for user reg            
        $salt = $this->extraMethods->generateSalt();
        $hashedPassword = sha1($data->newPassword . str_repeat($salt, 10));

        // check if there is the user with filled Sponsoring Number
        if ($data->referral != '') {
            $sponsor = $this->db_users->getUserBySponsoringNumber($data->referral);
            if ($sponsor) {
                $usersSponsor = $sponsor->id;
            } else {
                $usersSponsor = -1;
            }
        } else {
            $usersSponsor = 0;
        }
        
        if ($usersSponsor >= 0) {
            //1. user
            $token = $this->extraMethods->generatePassword();
            $salt = $this->extraMethods->generateSalt();
            $regToken = $this->extraMethods->calculateHash($token, $salt);
            $sponsoringNumber = $this->extraMethods->generateSponsoringNumber();
            if ($usersSponsor == 0) {
                $usersSponsor = NULL;
            }
            $dataArray_user = array($data->username, $hashedPassword, $salt, $data->subdomain,
                $data->program, $regToken, $sponsoringNumber, $usersSponsor);
            $this->db_users->addUser($dataArray_user);

            //2. users_data
            $user = $this->db_users->getUserBySubdomain($data->subdomain);
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
            $dataArray_users_data = array($user->id, $data->name, $data->surname,
                $data->titleBefore, $data->titleAfter, $data->email, $data->street, $data->city, $data->zip,
                $data->region, $data->phone, $doctorGroupFoundById);
            $this->db_users->addUserData($dataArray_users_data);

            //3. users_websiteData
            $layout = $this->db_users->getLayoutById($data->layouts);
            $dataArray_users_websiteData = array($user->id, $layout->layout, 'all',
                $data->header1, $data->header2, $data->pageTitle, $data->description,
                $data->keywords);
            $this->db_users->addUserWebsiteData($dataArray_users_websiteData);

            //4. menuItems set
            $this->db_menuItems->addNewMenuItemsSet($user->id);

            //5. guestBook                      
            $dataArray_guestBook = array($user->id, $data->name . ' ' . $data->surname);
            $this->db_guestBook->addGuestBook($dataArray_guestBook);

            //6. www part - folders, files
            $this->registerUserWWW($data->subdomain);

            //7. set subdomain status from N/A -> to valid
            $this->db_users->updateSubdomainStatus($user->id, 'Valid');

            $this->flashMessage('Registrace uživatele proběhla úspěšně.', 'info');

            if (!$this->isAjax()) {
                $this->redirect('this');
            } else {
                $this->invalidateControl('formRegUser');
                $this->invalidateControl('dispPass');
                $button->getForm()->setValues(array(), TRUE);
            }
        } else {
            $this->flashMessage('Zadané referenční číslo je neplatné!', 'warning');
        }
    }

    /**
     * Username / subdomain availability check signal handler (called by 
     * ajax request from adequate template).
     * 
     * @param $text status of username availability
     */
    public function handleAvailCheck($text, $flag) {
        $this->payload->availCheck = array();        
                
        $users = $this->db_users->getUsers();
        
        if ($flag == 'username') {
            // get all usernames 
            $usernamesArray = array();
            foreach ($users as $user) {                
                $usernamesArray[] = $user->username;
            }

            // check username availability
            $text = trim($text);
            if ($text !== '') {
                if (in_array($text, $usernamesArray)) {
                    $this->payload->availCheck[] = 'notok';
                } else {
                    $this->payload->availCheck[] = 'ok';
                }
            }
        } elseif ($flag == 'subdomain') {
            // get all subdomains 
            $subdomainsArray = array();
            foreach ($users as $user) {
                if ($user->accountStatus != 'archive')
                $subdomainsArray[] = $user->subdomain;
            }

            // check subdomain availability
            $text = trim($text);
            if ($text !== '') {
                if (in_array($text, $subdomainsArray)) {
                    $this->payload->availCheck[] = 'notok';
                } else {
                    $this->payload->availCheck[] = 'ok';
                }
            }            
        }        
            
        $this->terminate();
    }
    
    /**
     * Register user - www part (folders, files).
     * 
     * @param string $subdomain 
     */
    public function registerUserWWW($subdomain) {
        $subd = $subdomain;
        $wwwDir = WWW_DIR;        
        $pathToNewDir = $wwwDir . '/' . $subd;        
        try {            
            // delete dir if it already exists
//            if (is_dir($pathToNewDir)) {
            // dir existence is checked in delete routine
                $this->extraMethods->deleteSubdomain($subdomain);             
//            }
            
            // create subdomain root
            if ($this->extraMethods->createSubdomain($subdomain)) {
                // create admin folder and index.php for redirect (copy from)
                mkdir($pathToNewDir . '/admin');
                copy($wwwDir . '/user_data/admin.index.php', $pathToNewDir . '/admin/index.php');
                // copy files to subdomain root
                copy($wwwDir . '/user_data/.htaccess', $pathToNewDir . '/.htaccess');                
                copy($wwwDir . '/user_data/robots.txt', $pathToNewDir . '/robots.txt');
                copy($wwwDir . '/user_data/header.css', $pathToNewDir . '/header.css');
                copy($wwwDir . '/user_data/colour_scheme.css', $pathToNewDir . '/colour_scheme.css');
                copy($wwwDir . '/user_data/favicon.ico', $pathToNewDir . '/favicon.ico');

                // open index.php file, replace subdomain part string (toBeReplaced) and save it to subdomain root
                $indexFile = fopen($wwwDir . '/user_data/index.php', 'r');
                $indexContent = fread($indexFile, filesize($wwwDir . '/user_data/index.php'));
                fclose($indexFile);

                $updatedIndexContent = str_replace('toBeReplaced', $subd, $indexContent);

                $updatedFile = fopen($pathToNewDir . '/index.php', 'w+');
                fwrite($updatedFile, $updatedIndexContent);
                fclose($updatedFile);

                // open sitemap.xml, replace subdomain part string (toBeReplaced) and save it to subdomain root
                $sitemapFile = fopen($wwwDir . '/user_data/sitemap.xml', 'r');
                $sitemapContent = fread($sitemapFile, filesize($wwwDir . '/user_data/sitemap.xml'));
                fclose($sitemapFile);

                $updatedSitemapContent = str_replace('toBeReplaced', $subd, $sitemapContent);

                $updatedSitemap = fopen($pathToNewDir . '/sitemap.xml', 'w+');
                fwrite($updatedSitemap, $updatedSitemapContent);
                fclose($updatedSitemap);         

                // -----------------------------------------------------------------
                // real subdomain part files
                // open realSubdomain.index.php, replace subdomain part string (toBeReplaced) and save it to subdomain root            
                $realIndexFile = fopen($wwwDir . '/user_data_realSub/realSubdomain.index.php', 'r');
                $realIndexContent = fread($realIndexFile, filesize($wwwDir . '/user_data_realSub/realSubdomain.index.php'));
                fclose($realIndexFile);

                $updatedRealIndexContent = str_replace('toBeReplaced', $subd, $realIndexContent);

                $updatedRealIndex = fopen($pathToNewDir . '/realSubdomain.index.php', 'w+');
                fwrite($updatedRealIndex, $updatedRealIndexContent);
                fclose($updatedRealIndex);   
            }            
        } catch (Exception $e) {
            throw new \Nette\Application\ToolException('Unable to register user (www part) (AdminModule - adminDefault presenter). ' . $e, 500);
        }        
    }
    
    /**
     * Delete directory and its subdirectories.
     * 
     * @param string $dir 
     */
//    function rmdir_recursive($dir) {
//        foreach(scandir($dir) as $file) {
//            if ('.' === $file || '..' === $file) continue;
//            if (is_dir("$dir/$file")) $this->rmdir_recursive("$dir/$file");
//            else unlink("$dir/$file");
//        }
//        rmdir($dir);
//    }    
    
    /**
     * Layout preview handler.
     * 
     * @param int $id 
     */
    public function handleLayoutPreview($id) {        
        $layoutData = $this->db_users->getLayoutById(intval($id));
        $layout = $layoutData->layout . '.jpg';        
        $this->layout = $layout;
        $this->layoutDesc = $layoutData->layout_desc;
        
        if (!$this->isAjax()) {                        
            $this->redirect('this');
        } else {
            $this->invalidateControl('layoutPreview');
        }                
    }                
}
