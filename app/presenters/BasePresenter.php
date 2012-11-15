<?php

use Navigation\Navigation;
use Nette\Http\Url;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

/**
 * Base class for MainModule presenters.
 *
 * @author     Zippo
 * @package    MainModule
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @persistent */
    public $lang;    
    
    // user's Id - available in MainModule
    var $userId = 0;       
    
    // define db drivers
    protected $db_users;
    protected $db_menuItems;
    protected $db_stats;
    protected $db_guestBook;         
    
    protected $extraMethods;    

    // list of regions
    protected $regionsList;    
    
    // list of doctor groups
    protected $doctorGroupsList;

    protected $logger;    
    
    /**
     * Startup settings.
     */    
    public function startup() {
        parent::startup();                                    
        
        // ********************************************************************
        // Website settings start
        // ********************************************************************                        
        // list of regions
        $this->regionsList = $this->context->container->parameters['globalSettings']['regionList'];
        
        // list of doctor groups
        $this->doctorGroupsList = $this->context->container->parameters['globalSettings']['doctorGroups'];
        
        // ********************************************************************
        // Website settings end
        // ********************************************************************        
        
        // logger
        $this->logger = $this->getService('logger');    
        $this->logger->logDir = WWW_DIR . '/log_cron';         
        
        // extra methods holder
        $this->extraMethods = new ExtraMethods;                                
        
        // create DB driver to access db manager methods
        $this->db_users = $this->getService('usersmanager');
        $this->db_menuItems = $this->getService('menuitemsmanager');
        $this->db_stats = $this->getService('statsmanager');        
        $this->db_guestBook = $this->getService('guestbookmanager');       
        
        // set global vars
        $this->template->sharedBasePath = Nette\Environment::getVariable('sharedBasePath');      
        
        // select layout - default / user specific 
        if (DOMAIN != 'main' && DOMAIN != 'index.cronjobrunner1' && DOMAIN != 'index.cronjobrunner2') {                        
            // get user by domain name
            $user = $this->db_users->getUserBySubdomain(DOMAIN);                                                
            
            // check account validity
            if ($user->accountStatus == 'pending') {       
                $this->setView('pending');
            } elseif ($user->accountStatus == 'inactive') {
                $user = $this->db_users->getUserBySubdomain(DOMAIN);
                if ($user) {
                    $todaysDate = date('Y-m-d');
                    if ($todaysDate <= $user->dateTo) {
                        $this->template->conditions = true;
                        $this->setView('inactive');
                    } else {
                        $this->template->conditions = false;
                        if ($user->dateTo) {
                            $dateTo = date_format($user->dateTo, 'd.m.Y');                        
                        } else {
                            $dateTo = 'N/A';
                        }
                        $this->template->dateTo = $dateTo;
                        $this->setView('inactive');
                    }
                }
            } elseif ($user->accountStatus == 'archive') {
            }                        
            
            // if user exists set specific layout
            if ($user) {                  
                // set $userId                
                $this->userId = $user->id;               
                // set user layout
                $user_websiteData = $this->db_users->getUserWebsiteDataById($user->id);
                if ($user_websiteData) {
                    $this->setLayout($user_websiteData->layout);                
                    
                    // set description
                    $this->template->description = $user_websiteData->description;
                    // set keywords
                    $this->template->keywords = $user_websiteData->keywords;
                    // set title
                    $this->template->title = $user_websiteData->title;
                    // set header
                    $this->template->header = $user_websiteData->header1;
                    // set underHeader
                    $this->template->underHeader = $user_websiteData->header2;
                    // set user specific header.css
                    $this->template->pathToHeaderCSS = $user_websiteData->headerImage;                                    
                    // set user specific colour_scheme.css
                    $this->template->pathToColourSchemeCSS = $user_websiteData->colourScheme;                                                        
                }
            }    
        } else {            
            // set layout
            $this->setLayout('layout');
        }
    }

    /**
     * Function display and hide (after few sec) info, warning and error messages on the screen
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
     * Function creates application main menu
     * 
     * @param string $name - component name
     */
    protected function createComponentNavigation($name) 
    {
        $nav = new Navigation($this, $name);               
        
        // get menuItems for logged user
        $menuItems = $this->db_menuItems->getPublishedMenuItemsBySubdomain(DOMAIN);                        
        
        if ($menuItems) {
            // prepare data for output and generate menu
            $menuItemIndex = 1;
            $sec_array = array();
            foreach ($menuItems as $menuItem) {                
                $sec_array[$menuItemIndex] = $nav->add($menuItem->itemName, $this->link("Item" . $menuItem->itemId . ":"), 'yes');
                if ($this->name === 'Item' . $menuItem->itemId) {
                    $nav->setCurrent($sec_array[$menuItemIndex]);
                }
                $menuItemIndex++;
            }
        }
        
        // get guestBook for current user
        $guestBook = $this->db_guestBook->getGuestBookById($this->userId);
        if ($guestBook) {
            // is guestBook published?
            if ($guestBook->guestBookPublished == "yes") {
                $sec_array[$menuItemIndex + 1] = $nav->add("On-line Poradna", $this->link("GuestBook:"), 'yes');
                if ($this->name === 'GuestBook') {
                    $nav->setCurrent($sec_array[$menuItemIndex + 1]);
                }
            }
        }
    }

    /**
     * Transform url to presenter according to lang (cs is default).
     * 
     * @param string $url
     * @param Nette\Application\Request $request
     * @return string presenter
     */
    public static function urlToPresenter($url, Nette\Application\Request $request) {        
        // get subdomain
        $scriptPath = \Nette\Environment::getHttpContext()->request->url->scriptPath;
        // remove "/"
        $scriptPath = str_replace("/", "", $scriptPath);
        
        $UtoPArray = array();
        $session = \Nette\Environment::getService('session');
        $section = $session->getSection('Base');        
        // main page or user specific ?
        if ($scriptPath != '') {   
            // data stored in cache ?
            if (isset($section->UtoPArray)) {
                // current url found in cache ?
                if (array_key_exists($url, $section->UtoPArray)) {
                    $UtoPArray = $section->UtoPArray;                    
                } else {
                    // get all user's menuItems
                    $db_menuItems = \Nette\Environment::getService('menuitemsmanager');
                    $menuItems = $db_menuItems->getPublishedMenuItemsBySubdomain($scriptPath);            

                    // prepare transformation array: url -> presenter            
                    if ($request->parameters['lang'] == 'cs') {            
                        foreach ($menuItems as $menuItem) {                     
                            $UtoPArray[$menuItem->itemNameRouteCs] = 'Item' . $menuItem->itemId;
                        }
                        $UtoPArray['online-poradna'] = 'GuestBook';                
                    }
                    $UtoPArray['Default'] = 'Item1';  
                    $section->UtoPArray = $UtoPArray;                    
                }                
            } else {                                 
                // get all user's menuItems
                $db_menuItems = \Nette\Environment::getService('menuitemsmanager');
                $menuItems = $db_menuItems->getPublishedMenuItemsBySubdomain($scriptPath);            

                // prepare transformation array: url -> presenter            
                if ($request->parameters['lang'] == 'cs') {            
                    foreach ($menuItems as $menuItem) {                     
                        $UtoPArray[$menuItem->itemNameRouteCs] = 'Item' . $menuItem->itemId;
                    }
                    $UtoPArray['online-poradna'] = 'GuestBook';                
                }
                $UtoPArray['Default'] = 'Item1';  
                $section->UtoPArray = $UtoPArray;
            }
        } elseif ($url != 'admin') {
            if ($request->parameters['lang'] == 'cs') {
                $UtoPArray['registrace'] = 'Registration';
                $UtoPArray['cenik'] = 'PriceList';
                $UtoPArray['smluvni-podminky'] = 'Terms';
                $UtoPArray['o-nas'] = 'AboutUs';
                $UtoPArray['kontakt'] = 'Contact';
                $UtoPArray['hledani'] = 'Search';
                $UtoPArray['dotazy'] = 'Faq';
                $UtoPArray['emails'] = 'Emails';
                $UtoPArray['help'] = 'Help';
            }
            $UtoPArray['Default'] = 'Default';
        }        
        
        if (array_key_exists($url, $UtoPArray)) {
            $url = $UtoPArray[$url];
        } else {
//            $url = null;
        }

        $s = strtolower($url);
        $s = preg_replace('#([.-])(?=[a-z])#', '$1 ', $s);
        $s = ucwords($s);
        $s = str_replace('. ', ':', $s);
        $s = str_replace('- ', '', $s);
        
        return $s;
    }
    
    /**
     * Transform presenter to url according to lang (cs is default).
     * 
     * @param string $presenter
     * @param Nette\Application\Request $request
     * @return string url 
     */
    public static function presenterToUrl($presenter, Nette\Application\Request $request) {
        // get subdomain
        $scriptPath = \Nette\Environment::getHttpContext()->request->url->scriptPath;
        // remove "/"
        $scriptPath = str_replace("/", "", $scriptPath);
                
        // main page or user specific ?
        $PtoUArray = array();    
        $session = \Nette\Environment::getService('session');
        $section = $session->getSection('Base');           
        if ($scriptPath != '') {      
            // data stored in cache ?
            if (isset($section->PtoUArray)) {
                // current presenter found in cache ?
                if (array_key_exists($presenter, $section->UtoPArray)) {            
                    $PtoUArray = $section->PtoUArray;
                } else {
                    // get all user's menuItems
                    $db_menuItems = \Nette\Environment::getService('menuitemsmanager');
                    $menuItems = $db_menuItems->getPublishedMenuItemsBySubdomain($scriptPath);

                    // prepare transformation array: url -> presenter
                    if ($request->parameters['lang'] == 'cs') {            
                        foreach ($menuItems as $menuItem) {                
                            $PtoUArray['Item' . $menuItem->itemId] = $menuItem->itemNameRouteCs;
                        }
                        $PtoUArray['GuestBook'] = 'online-poradna';                
                    }
                    $section->PtoUArray = $PtoUArray;
                }
            } else {
                    // get all user's menuItems
                    $db_menuItems = \Nette\Environment::getService('menuitemsmanager');
                    $menuItems = $db_menuItems->getPublishedMenuItemsBySubdomain($scriptPath);

                    // prepare transformation array: url -> presenter
                    if ($request->parameters['lang'] == 'cs') {            
                        foreach ($menuItems as $menuItem) {                
                            $PtoUArray['Item' . $menuItem->itemId] = $menuItem->itemNameRouteCs;
                        }
                        $PtoUArray['GuestBook'] = 'online-poradna';                
                    }
                    $section->PtoUArray = $PtoUArray;                
            }
        } else { 
            if (isset($request->parameters['lang'])) {
                if ($request->parameters['lang'] == 'cs') {                                    
                    $PtoUArray['Registration'] = 'registrace';      
                    $PtoUArray['PriceList'] = 'cenik';
                    $PtoUArray['Terms'] = 'smluvni-podminky';
                    $PtoUArray['AboutUs'] = 'o-nas';
                    $PtoUArray['Contact'] = 'kontakt';                    
                    $PtoUArray['Search'] = 'hledani';                  
                    $PtoUArray['Faq'] = 'dotazy';                  
                    $PtoUArray['Emails'] = 'emails';
                    $PtoUArray['Help'] = 'help';
                }                
            }            
            $PtoUArray['Default'] = 'Default';
        }
        
        if (array_key_exists($presenter, $PtoUArray)) {
            $presenter = $PtoUArray[$presenter];
        } else {
//            $presenter = null;
        }

        $s = strtr($presenter, ':', '.');
        $s = preg_replace('#([^.])(?=[A-Z])#', '$1-', $s);
        $s = strtolower($s);
        $s = rawurlencode($s);

        return $s;
    }    
}

/**
 * Custom Router class to process input address.
 */
class FilterRoute extends Nette\Application\Routers\Route
{
    const WAY_IN = 'in';
    const WAY_OUT = 'out';        

    /** @var array */
    private $filters = array();   

    /**
     * @param Nette\Web\IHttpRequest $httpRequest
     * @return Nette\Application\PresenterRequest|NULL
     */
    public function match(Nette\Http\IRequest $httpRequest)
    {
        $appRequest = parent::match($httpRequest);
        if (!$appRequest) {
            return $appRequest;
        }

        if ($params = $this->doFilterParams($this->getRequestParams($appRequest), $appRequest, self::WAY_IN)) {
            return $this->setRequestParams($appRequest, $params);
        }

        return NULL;
    }

    /**
     * @param Nette\Application\PresenterRequest $appRequest
     * @param Nette\Web\Uri $refUri
     * @return string
     */
    public function constructUrl(Nette\Application\Request $appRequest, Url $refUri)
    {        
        if ($params = $this->doFilterParams($this->getRequestParams($appRequest), $appRequest, self::WAY_OUT)) {
            $appRequest = $this->setRequestParams($appRequest, $params);            
            return parent::constructUrl($appRequest, $refUri);                        
        }

        return NULL;
    }

    /**
     * @param string $param
     * @param callable $in
     * @param callable $out
     * @return SmarterRoute
     */
    public function addFilter($param, $in, $out = NULL)
    {
        $this->filters[$param] = array(
                self::WAY_IN => callback($in),
                self::WAY_OUT => $out ? callback($out) : NULL
            );

        return $this;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param Nette\Application\PresenterRequest $appRequest
     * @return array
     */
    private function getRequestParams(Nette\Application\Request $appRequest)
    {
        $params = $appRequest->getParameters();
        $metadata = $this->getDefaults();

        $presenter = $appRequest->getPresenterName();
        $params[self::PRESENTER_KEY] = $presenter;

        if (isset($metadata[self::MODULE_KEY])) { // try split into module and [submodule:]presenter parts
            $module = $metadata[self::MODULE_KEY];
            if (isset($module['fixity']) && strncasecmp($presenter, $module[self::VALUE] . ':', strlen($module[self::VALUE]) + 1) === 0) {
                $a = strlen($module[self::VALUE]);
            } else {
                $a = strrpos($presenter, ':');
            }

            if ($a === FALSE) {
                $params[self::MODULE_KEY] = '';
            } else {
                $params[self::MODULE_KEY] = substr($presenter, 0, $a);
                $params[self::PRESENTER_KEY] = substr($presenter, $a + 1);
            }
        }

        return $params;
    }

    /**
     * @param Nette\Application\PresenterRequest $appRequest
     * @param array $params
     * @return Nette\Application\PresenterRequest
     */
    private function setRequestParams(Nette\Application\Request $appRequest, array $params)
    {
        $metadata = $this->getDefaults();

        if (!isset($params[self::PRESENTER_KEY])) {
            throw new \InvalidStateException('Missing presenter in route definition.');
        }
        if (isset($metadata[self::MODULE_KEY])) {
            if (!isset($params[self::MODULE_KEY])) {
                throw new \InvalidStateException('Missing module in route definition.');
            }
            $presenter = $params[self::MODULE_KEY] . ':' . $params[self::PRESENTER_KEY];
            unset($params[self::MODULE_KEY], $params[self::PRESENTER_KEY]);

        } else {
            $presenter = $params[self::PRESENTER_KEY];
            unset($params[self::PRESENTER_KEY]);
        }

        $appRequest->setPresenterName($presenter);
        $appRequest->setParameters($params);

        return $appRequest;
    }

    /**
     * @param array $params
     * @param Nette\Application\PresenterRequest $request
     * @param string $way
     */
    private function doFilterParams($params, Nette\Application\Request $request, $way)
    {
        // tady mám k dispozici všechny parametry
        foreach ($this->getFilters() as $param => $filters) {
            if (!isset($params[$param]) || !isset($filters[$way])) {
                continue; // param not found
            }

            $params[$param] = call_user_func($filters[$way], (string) $params[$param], $request);
            if ($params[$param] === NULL) {
                return NULL; // rejected by filter
            }
        }

        return $params;
    }
}
