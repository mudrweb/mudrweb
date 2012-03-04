<?php

namespace AdminModule;

use Nette\Forms\Form;
use \AdminPresenter as AdminPresenter;

/**
 * AdminModule admin users presenter.
 *
 * @author     Zippo
 * @package    AdminModule
 */
class AdminUsersPresenter extends AdminPresenter {            
    
    /**
     * Check access and rights here only
     */
    public function startup()
    {
        parent::startup();
        $this->checkAccess(array('admin'));                
    }      
 
    public function renderDefault() {      
        // prepare data for output
        $users = $this->db_users->getUsers();                   
        $usersArray = array();
        if ($users) {
            foreach ($users as $user) {
                // exclude admin
                if ($user->id != 0) {
                    $users_data = $this->db_users->getUsersDataById($user->id);
                    if ($users_data) {
                       // change date format 
                       if ($user->dateFrom != null) {
                            $dateFrom = $user->dateFrom;
                            $dateFrom = $dateFrom->format('d/m/Y');
                       } else {
                            $dateFrom = '00/00/0000';
                       }
                       if ($user->dateTo != null) {                           
                            $dateTo = $user->dateTo;
                            $dateTo = $dateTo->format('d/m/Y');                            
                       } else {
                            $dateTo = '00/00/0000';
                       }                       
                       
                       $usersArray[] = array(intval($user->id), $users_data->name, $users_data->surname,
                            $user->subdomain, date_format($user->dateOfRegistration, 'd/m/Y H:i:s'), $user->accountStatus, $dateFrom, $dateTo, 
                            $user->maintenanceMode, $user->subdomainStatus, $user->realSubdomainStatus, date_format($user->dateOfActivation, 'd/m/Y H:i:s'));                
                    } else {
                        throw new \Nette\Application\BadRequestException('Unable to load user websiteData (AdminModule - adminUsers presenter).', 404);                    
                    }
                }
            }
        } else {
            throw new \Nette\Application\BadRequestException('Unable to load user profile (AdminModule - adminUsers presenter).', 404);
        }
        $this->template->users = $usersArray;          
    }        
    
    /**
     * Submit changes event handler (called by jQuery dataTable)
     */
    public function handleSubmitChanges() {
        //get data
        $id = $_REQUEST['id'];
        $columnId = $_REQUEST['columnId'];
        
        // status and subdomain data
        if ($columnId == 4) {
            $newStatusId = $_REQUEST['value'];               
            $newStatus = null;
            switch ($newStatusId) {
                case 0:
                    $newStatus = 'active';
                    break;
                case 1:
                    $newStatus = 'pending';
                    break;
                case 2:
                    $newStatus = 'inactive';
                    break;           
            }           
            
            if ($newStatus == 'active') {
                // actual user
                $user = $this->db_users->getUserById(intval($id));
                if ($user && !$user->dateOfActivation) {      
                    $user_data = $this->db_users->getUsersDataById(intval($id));
                    
                    // send email
                    $template = parent::createTemplate();
                    $template->setFile($this->getContext()->params['appDir'] . '/templates/xemails/acc_active.latte');
                    $template->registerFilter(new \Nette\Latte\Engine());        
                    
                    if ($user->program == 'demo') {
                        $template->program = 'DEMOverze';
                    } elseif ($user->program == 'basic') {
                        $template->program = 'Základní verze';
                    }
                    $template->dateOfReg = date_format($user->dateOfRegistration, 'd.m.Y');                                        
                    $template->subdomain = 'http://' . $user->subdomain . '.mudrweb.cz';                
                    $template->subdomain_name = $user->subdomain . '.mudrweb.cz';        
                    $template->token = 'aa' . $user->registrationToken;
                    
                    $mail = new \Nette\Mail\Message;
                    $mail->setFrom('MUDRweb.cz - účet <support@mudrweb.cz>')
                            ->addTo($user_data->email)                
                            ->setHtmlBody($template)
                            ->send();   
                }
            } 
//            elseif ($newStatus == 'inactive') {
//                // actual user
//                $user = $this->db_users->getUserById(intval($id));
//                if ($user) {      
//                    $user_data = $this->db_users->getUsersDataById(intval($id));
//                    
//                    // send email
//                    $template = parent::createTemplate();
//                    $template->setFile($this->getContext()->params['appDir'] . '/templates/xemails/acc_inactive.latte');
//                    $template->registerFilter(new \Nette\Latte\Engine());        
//                    
//                    if ($user->program == 'demo') {
//                        $template->program = 'DEMOverze';
//                    } elseif ($user->program == 'basic') {
//                        $template->program = 'Základní verze';
//                    }
//                    $template->dateOfReg = date_format($user->dateOfRegistration, 'd.m.Y');                    
//                    
//                    $template->subdomain = $user->subdomain . '.mudrweb.cz';        
//                    
//                    $mail = new \Nette\Mail\Message;
//                    $mail->setFrom('MUDRweb.cz - účet <support@mudrweb.cz>')
//                            ->addTo($user_data->email)                
//                            ->setHtmlBody($template)
//                            ->send();   
//                }                
//            }
                
            $this->db_users->updateRegistrationProcessStatus(intval($id), $newStatus);                       
        }

        // dateFrom
        if ($columnId == 5) {
            $dateFromAdmin = $_REQUEST['value'];                             
            $dateFrom = date_create_from_format('d/m/Y', $dateFromAdmin);
            $dateFrom = $dateFrom->format('Y-m-d');
            
            $this->db_users->setUserDateFrom(intval($id), $dateFrom);
        }        

        // dateTo
        if ($columnId == 6) {
            $dateToAdmin = $_REQUEST['value'];                             
            $dateTo = date_create_from_format('d/m/Y', $dateToAdmin);
            $dateTo = $dateTo->format('Y-m-d');
            
            $this->db_users->setUserDateTo(intval($id), $dateTo);
        }                
        
        // maintenance mode
        if ($columnId == 8) {
            $mmodeId = $_REQUEST['value'];               
            $mmNewStatus = null;
            switch ($mmodeId) {
                case 0:
                    $mmNewStatus = 'on';
                    
                    // get user profile
                    $user = $this->db_users->getUserById($id);
                    if ($user->maintenanceMode != 'on') {
                        $wwwDir = WWW_DIR;                   
                        $pathToUserSpecificDir = $wwwDir . '/' . $user->subdomain;                        

                        // open index.php file, replace "// require APP_DIR . '/templates/maintenance.phtml';" and save it
                        $indexFile = fopen($pathToUserSpecificDir . '/index.php', 'r');
                        $indexContent = fread($indexFile, filesize($pathToUserSpecificDir . '/index.php'));
                        fclose($indexFile);

                        $updatedIndexContent = str_replace("// require APP_DIR . '/templates/maintenance.phtml';", "require APP_DIR . '/templates/maintenance.phtml';", $indexContent);

                        $updatedFile = fopen($pathToUserSpecificDir . '/index.php', 'w+');
                        fwrite($updatedFile, $updatedIndexContent);
                        fclose($updatedFile);       
                    }
                    break;
                case 1:
                    $mmNewStatus = 'off';
                    
                    // get user profile
                    $user = $this->db_users->getUserById($id);
                    if ($user->maintenanceMode != 'off') {
                        $wwwDir = WWW_DIR;                   
                        $pathToUserSpecificDir = $wwwDir . '/' . $user->subdomain;                        

                        // open index.php file, replace "require APP_DIR . '/templates/maintenance.phtml';" and save it
                        $indexFile = fopen($pathToUserSpecificDir . '/index.php', 'r');
                        $indexContent = fread($indexFile, filesize($pathToUserSpecificDir . '/index.php'));
                        fclose($indexFile);

                        $updatedIndexContent = str_replace("require APP_DIR . '/templates/maintenance.phtml';", "// require APP_DIR . '/templates/maintenance.phtml';", $indexContent);

                        $updatedFile = fopen($pathToUserSpecificDir . '/index.php', 'w+');
                        fwrite($updatedFile, $updatedIndexContent);
                        fclose($updatedFile);                       
                    }
                    break;        
            }           
                        
            $this->db_users->updateMaintenanceModeStatus(intval($id), $mmNewStatus);                    
        }        
        
        if (!$this->isAjax()) {
            $this->redirect('this');
        } else {        
            $this->terminate();            
            $this->invalidateControl('jEditable');
        }
        
//        $date = date_create_from_format('d/m/Y', $data);
//        dump($date->format('Y-m-d H:i:s'));                
//        $date = date('m/d/Y', strtotime('+2 week'));   
    } 
    
    /*
     * Copy files index.php to real subdomain handler (ajax)
     */
    public function handleCopyFilesToRealSubdomain($id, $subdomain)
    {                    
        if ($this->extraMethods->checkRealSubdomainExistence($subdomain)) {
            $this->extraMethods->copyFileToRealSubdomain($subdomain, '.htaccess');
            $this->extraMethods->copyFileToRealSubdomain($subdomain, 'robots.txt');
            $this->extraMethods->copyFileToRealSubdomain($subdomain, 'sitemap.xml');
            $this->extraMethods->copyFileToRealSubdomain($subdomain, 'index.php');        

            $this->db_users->updateRealSubdomainStatus($id, 'Valid');
        }
        
        if (!$this->isAjax()) {
            $this->redirect('this');
        } else {
            $this->invalidateControl('jEditable');
        }
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
            $this->extraMethods->deleteSubdomain($subdomain);             
            
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
}
