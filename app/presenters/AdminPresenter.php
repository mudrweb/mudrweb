<?php

use Navigation\Navigation;
use Nette\Http\Url;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

/**
 * Base class for AdminModule presenters.
 *
 * @author     Zippo
 * @package    AdminModule
 */
abstract class AdminPresenter extends Nette\Application\UI\Presenter
{
    /** @persistent */
    public $lang;  
    
    // logged user data
    protected $user;
       
    // vars for menuItem presenters
    protected $menuItem;  
    protected $itemName;
    protected $itemContent;
    protected $itemId;
    protected $itemPublished_req;    
    protected $itemPublished_status;    
    
    // define db drivers
    protected $db_users;
    protected $db_menuItems;
    protected $db_stats;
    protected $db_guestBook;

    // layout_groups
    protected $layout_groups;   
    
    protected $extraMethods;

    /**
     * Startup settings.
     */
    public function startup() {
        parent::startup();                                         
        
        // ********************************************************************
        // Website settings start
        // ********************************************************************
        
        // define basic layout groups
        $this->layout_groups = array(
            'kardio', 
            'gyneko', 
            'opto'
        );
        sort($this->layout_groups);
        
        // ********************************************************************
        // Website settings end
        // ********************************************************************
        
        // extra methods holder
        $this->extraMethods = new ExtraMethods;
        
        // create DB driver to access db manager methods
        $this->db_users = $this->getService('usersmanager');
        $this->db_menuItems = $this->getService('menuitemsmanager');
        $this->db_stats = $this->getService('statsmanager');
        $this->db_guestBook = $this->getService('guestbookmanager');       
        
        // get presenter name
        $this->template->presenter_name = substr($this->name, strpos($this->name,':')+1);         
        
        // set up user data
        $this->user = $this->getUser();
        // set app namespace - user can be logged in more than one app
        $this->user->getStorage()->setNamespace('mudr');
        
        // set global vars
        $this->template->sharedBasePath = Nette\Environment::getVariable('sharedBasePath');             
        
        //set layout
        $this->setLayout('layout_admin');
                
        if($this->user && $this->user->isLoggedIn() && $this->checkAccess(array('uživatel'), TRUE)) { 
            // check if user tries to open forgotten-password section and redirect him to 
            // default admin page
            $routeData = list($module) = explode(':', $this->getPresenter()->getName());
            if (isset($routeData) && $routeData[1] === 'ForgottenPassword') {
                $this->redirect(':Admin:Default:');
            }
            
            // get user data from DB
            $userFromDB = $this->db_users->getUserById(1);                          
                        
            // set rights and user specific folder for uploads (kcfinder )
            $_SESSION['KCFINDER'] = array();
            $_SESSION['KCFINDER']['disabled'] = false;
            $_SESSION['KCFINDER']['uploadURL'] = "/user_uploads/" . $userFromDB->subdomain;            
            $_SESSION['KCFINDER']['uploadDir'] = "";
                                    
            // set username
            if ($userFromDB) {                
                $this->template->userName = $userFromDB->username;
            } else {
                $this->template->userName = 'N/A';
            }
            
            // get role
            $role = $this->user->getRoles();
            if ($role[0]) {
                $this->template->userRole = $role[0];
            } else {
                $this->template->userRole = '';
            }
            
            // get user specific layout
            $userWebsitedataFromDB = $this->db_users->getUserWebsiteDataById($this->user->getId());
            if ($userWebsitedataFromDB) {
                $this->template->pathToEditorCSS = $userWebsitedataFromDB->layout . '/' . $userWebsitedataFromDB->layout . '_editor.css';                
            } else {
                $this->template->pathToEditorCSS = 'editor.css';
            }
            
            // set preview address
            $this->template->preview = Nette\Environment::getVariable('sharedBasePath') . '/' .$userFromDB->subdomain;
            
            // set last successful login datetime 
            if ($userFromDB->lastLogin) {
                $this->template->lastSuccessfulLogin = date_format($userFromDB->lastLogin, 'd.m.Y H:i:s');
            } else {
                $this->template->lastSuccessfulLogin = 'N/A';
            }
        }        
        
        if($this->user && $this->user->isLoggedIn() && $this->checkAccess(array('admin'), TRUE)) {
            // check if admin tries to open forgotten-password section and redirect him to 
            // default admin page
            $routeData = list($module) = explode(':', $this->getPresenter()->getName());
            if (isset($routeData) && $routeData[1] === 'ForgottenPassword') {
                $this->redirect(':Admin:AdminDefault:');
            }            
            
            // get user data from DB
            $userFromDB = $this->db_users->getUserById($this->user->getId()); 
            
            $this->template->userName = 'pan majster';
            $this->template->userRole = 'admin';
            $this->template->pathToEditorCSS = 'editor.css';
            $this->template->preview = '';
            
            // set last successful login datetime
            $this->template->lastSuccessfulLogin = date_format($userFromDB->lastLogin, 'd.m.Y H:i:s');            
        }
    }

    /**
     * renderDefault for admin presenter.
     */
    public function renderDefault() {       
        //dump(md5('asdf' . str_repeat('11a88b96', 10)));
    }
    
    /**
     * afterRender for admin presenter.
     */    
    public function afterRender()
    {
        if ($this->isAjax() && $this->hasFlashSession())
            $this->invalidateControl('flashes');
    }    

    /**
     * Function display and hide (after few sec) info, warning and error messages on the screen.
     * 
     * @param string $message - message to display
     * @param string $type - possible values(specified in CSS): info, warning or error
     */    
    public function flashMessage($message, $type = 'info')
    {
        $this->invalidateControl("flashes");
        parent::flashMessage($message, $type);
    }      
    
    /**
     * Function creates application main menu.
     * 
     * @param string $name - component name
     */
    protected function createComponentNavigation($name) 
    {
        $nav = new Navigation($this, $name);        
        
        // get menuitems for logged user
        $menuItems = $this->db_menuItems->getMenuItemsByIdusers($this->user->getId());        
  
        // users' admin part
        if ($menuItems) {
            if ($this->user->isLoggedIn() && $this->checkAccess(array('uživatel'), TRUE)) {                
                $sec = $nav->add("Nastavení stránky", $this->link(":Admin:Default:"), 'yes');
                if ($this->name === 'Admin:Default') {
                    $nav->setCurrent($sec);
                }
                
                // prepare data for output and generate menu
                $menuItemIndex = 1;
                $sec_array = array();
                foreach ($menuItems as $menuItem) {                    
                    $sec_array[$menuItemIndex] = $nav->add($menuItem->itemName, $this->link(":Admin:Item" . $menuItemIndex . ":"), $menuItem->itemPublished);
                    if ($this->name === 'Admin:Item' . $menuItemIndex) {
                        $nav->setCurrent($sec_array[$menuItemIndex]);
                    }
                    $menuItemIndex++;
                }
                
                $guestBook = $this->db_guestBook->getGuestBookById($this->user->getId());
                $sec = $nav->add("On-line Poradna", $this->link(":Admin:GuestBook:"), $guestBook->guestBookPublished);
                if ($this->name === 'Admin:GuestBook') {
                    $nav->setCurrent($sec);
                }
                
                $sec = $nav->add("Uživatelský profil", $this->link(":Admin:Profile:"), 'no');
                if ($this->name === 'Admin:Profile') {
                    $nav->setCurrent($sec);
                }
            }
        }
        
        // pure admin part
        if ($this->user->isLoggedIn() && $this->checkAccess(array('admin'), TRUE)) {
            $sec = $nav->add("Registrace uživatele", $this->link(":Admin:AdminDefault:"), 'no');
            if ($this->name === 'Admin:AdminDefault') {
                $nav->setCurrent($sec);
            }
            
            $sec = $nav->add("Správa uživatelů", $this->link(":Admin:AdminUsers:"), 'no');
            if ($this->name === 'Admin:AdminUsers') {
                $nav->setCurrent($sec);
            }            
        }
    }
    
    /**
     * Function authorizes user for some action or page by comparing user roles and array of allowed roles for this action.
     * 
     * @param array $allowedRoles - list of allowed user roles which can display page or do some actions
     * @param Bool $return - if return value is needed
     * @throws \Nette\Application\ForbiddenRequestException - error 403
     */
    public function checkAccess($allowedRoles = array(), $return = FALSE)
    {
        if($this->user->isLoggedIn()) {
            $roles = $this->user->getRoles();            
            if($allowedRoles) {
                $allowedAccess = array_intersect($allowedRoles, $roles);
                if (!count($allowedAccess)) {
                    if($return) {
                       return 0;
                    } else {
                        throw new \Nette\Application\ForbiddenRequestException();
                    }
                } else {
                    if($return) {
                        return 1;
                    }
                }
            }
        } else {
            if($return) {
                return -1;
            } else {
                $this->redirect(':Admin:Login:');
            }
        }
    }
    
    /**
     * List all available layouts for current user.
     * 
     * @param int $usersid
     * @return array of layouts 
     */
    public function listLayouts($usersid) {
        if (is_numeric($usersid)) {
            $user = $this->db_users->getUserById($usersid);
            $user_data = $this->db_users->getUserWebsiteDataById($usersid);
            // get and process layouts
            $layouts = $this->db_users->getLayouts($user_data->layout_group, $user->subdomain);
            $layoutsArray = array();
            if ($layouts) {
                foreach ($layouts as $layout) {
                    $layoutsArray[$layout->layout] = $layout->layout_desc;
                }
                return $layoutsArray;
            } else {
                $layoutsArray[0] = 'Vyskytla se chyba!';
                return $layoutsArray;
            }
        } else {
            $layoutsArray[0] = 'Vyskytla se chyba!';
            return $layoutsArray;
        }
    }               
    
    /**
     * Transform itemName with diacritics to unified url format (sth-sth1).
     * 
     * @param string $itemName
     * @return string $itemNameRouteCs 
     */
    public function prepareUnifiedItemNameForRoute($itemName) {        
        $transfTable = Array(
        'ä' => 'a',
        'Ä' => 'A',
        'á' => 'a',
        'Á' => 'A',
        'à' => 'a',
        'À' => 'A',
        'ã' => 'a',
        'Ã' => 'A',
        'â' => 'a',
        'Â' => 'A',
        'č' => 'c',
        'Č' => 'C',
        'ć' => 'c',
        'Ć' => 'C',
        'ď' => 'd',
        'Ď' => 'D',
        'ě' => 'e',
        'Ě' => 'E',
        'é' => 'e',
        'É' => 'E',
        'ë' => 'e',
        'Ë' => 'E',
        'è' => 'e',
        'È' => 'E',
        'ê' => 'e',
        'Ê' => 'E',
        'í' => 'i',
        'Í' => 'I',
        'ï' => 'i',
        'Ï' => 'I',
        'ì' => 'i',
        'Ì' => 'I',
        'î' => 'i',
        'Î' => 'I',
        'ľ' => 'l',
        'Ľ' => 'L',
        'ĺ' => 'l',
        'Ĺ' => 'L',
        'ń' => 'n',
        'Ń' => 'N',
        'ň' => 'n',
        'Ň' => 'N',
        'ñ' => 'n',
        'Ñ' => 'N',
        'ó' => 'o',
        'Ó' => 'O',
        'ö' => 'o',
        'Ö' => 'O',
        'ô' => 'o',
        'Ô' => 'O',
        'ò' => 'o',
        'Ò' => 'O',
        'õ' => 'o',
        'Õ' => 'O',
        'ő' => 'o',
        'Ő' => 'O',
        'ř' => 'r',
        'Ř' => 'R',
        'ŕ' => 'r',
        'Ŕ' => 'R',
        'š' => 's',
        'Š' => 'S',
        'ś' => 's',
        'Ś' => 'S',
        'ť' => 't',
        'Ť' => 'T',
        'ú' => 'u',
        'Ú' => 'U',
        'ů' => 'u',
        'Ů' => 'U',
        'ü' => 'u',
        'Ü' => 'U',
        'ù' => 'u',
        'Ù' => 'U',
        'ũ' => 'u',
        'Ũ' => 'U',
        'û' => 'u',
        'Û' => 'U',
        'ý' => 'y',
        'Ý' => 'Y',
        'ž' => 'z',
        'Ž' => 'Z',
        'ź' => 'z',
        'Ź' => 'Z'
        );               
        
        $itemNameRoute = strtr($itemName, $transfTable);        
        $itemNameRoute = strtolower($itemNameRoute);
//        dump(preg_match('/^[a-zA-Z0-9]+$/', $itemNameRoute));
        $itemNameRoute = preg_replace('#([.-])(?=[a-z])#', '$1 ', $itemNameRoute);
        $itemNameRoute = ucwords($itemNameRoute);
        $itemNameRoute = str_replace (" ", "", $itemNameRoute);  
        $itemNameRoute = preg_replace('#([^.])(?=[A-Z])#', '$1-', $itemNameRoute);
        $itemNameRoute = strtolower($itemNameRoute);
        $itemNameRoute = rawurlencode($itemNameRoute);
        
        return $itemNameRoute; 
    }   
    
    /**
     * Check itemName for illegal character/s. 
     * 
     * @param type $control
     * @return bool itemName contains illegal character/s  
     */
    static function itemnamecheck($control) {    
        $transfTable = array(
        'ä' => 'a',
        'Ä' => 'A',
        'á' => 'a',
        'Á' => 'A',
        'à' => 'a',
        'À' => 'A',
        'ã' => 'a',
        'Ã' => 'A',
        'â' => 'a',
        'Â' => 'A',
        'č' => 'c',
        'Č' => 'C',
        'ć' => 'c',
        'Ć' => 'C',
        'ď' => 'd',
        'Ď' => 'D',
        'ě' => 'e',
        'Ě' => 'E',
        'é' => 'e',
        'É' => 'E',
        'ë' => 'e',
        'Ë' => 'E',
        'è' => 'e',
        'È' => 'E',
        'ê' => 'e',
        'Ê' => 'E',
        'í' => 'i',
        'Í' => 'I',
        'ï' => 'i',
        'Ï' => 'I',
        'ì' => 'i',
        'Ì' => 'I',
        'î' => 'i',
        'Î' => 'I',
        'ľ' => 'l',
        'Ľ' => 'L',
        'ĺ' => 'l',
        'Ĺ' => 'L',
        'ń' => 'n',
        'Ń' => 'N',
        'ň' => 'n',
        'Ň' => 'N',
        'ñ' => 'n',
        'Ñ' => 'N',
        'ó' => 'o',
        'Ó' => 'O',
        'ö' => 'o',
        'Ö' => 'O',
        'ô' => 'o',
        'Ô' => 'O',
        'ò' => 'o',
        'Ò' => 'O',
        'õ' => 'o',
        'Õ' => 'O',
        'ő' => 'o',
        'Ő' => 'O',
        'ř' => 'r',
        'Ř' => 'R',
        'ŕ' => 'r',
        'Ŕ' => 'R',
        'š' => 's',
        'Š' => 'S',
        'ś' => 's',
        'Ś' => 'S',
        'ť' => 't',
        'Ť' => 'T',
        'ú' => 'u',
        'Ú' => 'U',
        'ů' => 'u',
        'Ů' => 'U',
        'ü' => 'u',
        'Ü' => 'U',
        'ù' => 'u',
        'Ù' => 'U',
        'ũ' => 'u',
        'Ũ' => 'U',
        'û' => 'u',
        'Û' => 'U',
        'ý' => 'y',
        'Ý' => 'Y',
        'ž' => 'z',
        'Ž' => 'Z',
        'ź' => 'z',
        'Ź' => 'Z'
        );     
        
        $itemName = $control->value;
        $itemName = strtr($itemName, $transfTable);        
        $itemName = strtolower($itemName);
        if (preg_match('/^[a-zA-Z0-9\s]+$/', $itemName)) {            
            return true;
        } else {
            return false;
        }
    }    
}    
