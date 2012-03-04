<?php

/**
 * UsersManager base class.
 */
class UsersManager extends Nette\Object {
    
    /**
     * Database handling.     
     */
    public $database;
    public function __construct(Nette\Database\Connection $database) {
        $this->database = $database;
    }
    
    /**
     * Authenticator service using users table.
     * 
     * @return Authenticator 
     */
    public function createAuthenticatorService() {
        return new Authenticator($this->database->table('users'));
    }
   
    /****************************** Users *************************************/    
    
    /**
     * Get all users from DB.
     * 
     * @return users
     */
    public function getUsers() {
        return $this->database->table('users');
    }    
    
    /**
     * Get user by user $id.
     *
     * @param int $id
     * @return user 
     */
    public function getUserById($id) {         
        if (is_numeric($id)) {
            $user = $this->database->table('users')->get($id);
            return $user;            
        } else {            
            throw new \Nette\Application\ToolException('Unable to get User.
                    Wrong input. method: getUserById($id)', 500);
        }      
    }        
        
    /**
     * Get user by $subdomain.
     * 
     * @param string $subdomain
     * @return user 
     */
    public function getUserBySubdomain($subdomain) {
        if ($subdomain) {
            $user = $this->database->table('users')
                    ->where('subdomain', $subdomain)->fetch();
            return $user;
        } else {
            throw new \Nette\Application\ToolException('Unable to get User.
                    Wrong input. method: getUserBySubdomain($subdomain)', 500);
        }
    }

    /**
     * Get user by $username.
     * 
     * @param string $username
     * @return user 
     */
    public function getUserByUsername($username) {
        if ($username) {
            $user = $this->database->table('users')
                    ->where('username', $username)->fetch();
            return $user;
        } else {
            throw new \Nette\Application\ToolException('Unable to get User.
                    Wrong input. method: getUserByUsername($username)', 500);
        }
    }    
    
    /**
     * Save last login datetime for current user.
     * 
     * @param int $id
     */
    public function saveLastUserLogin($id) {
        if (is_numeric($id)) {
            $changeDateTime = date("Y-m-d H:i:s");
            $this->database->exec('UPDATE users SET lastLogin=? WHERE id=?', $changeDateTime, $id);                               
        } else {            
            throw new \Nette\Application\ToolException('Unable to save last login data for current user.
                    Wrong input. method: saveLastUserLogin($id)', 500);
        }
    }
    
    /**
     * Save last logout datetime for current user.
     * 
     * @param int $id 
     */
    public function saveLastUserLogout($id) {
        if (is_numeric($id)) {
            $changeDateTime = date("Y-m-d H:i:s");
            $this->database->exec('UPDATE users SET lastLogout=? WHERE id=?', $changeDateTime, $id);                               
        } else {            
            throw new \Nette\Application\ToolException('Unable to save last logout data for current user.
                    Wrong input. method: saveLastUserLogout($id)', 500);
        }
    }
    
    /**
     * From User's Profile section of admin panel.
     * Update user's password hash and salt.
     * 
     * @param data $dataArray 
     */
    public function changePassword($dataArray) {
        if (isset($dataArray)) {   
            $changeDateTime = date("Y-m-d H:i:s");
            $this->database->exec('UPDATE users SET password=?, salt=?, passwordChanged=? WHERE id=?', $dataArray[1], $dataArray[2], $changeDateTime, $dataArray[0]);            
        } else {            
            throw new \Nette\Application\ToolException('Unable to update user password.
                    Wrong input. method: changePassword($dataArray)', 500);
        }
    }        
    
    /**
     * From Login Form. 
     * Save password resent datetime for current user ($id). Used
     * for security check - 1 resend per day.
     * 
     * @param int $id 
     */    
    public function passwordResentDateTime($id) {
            $resentDateTime = date("Y-m-d H:i:s");
            $this->database->exec('UPDATE users SET passwordResent=? WHERE id=?', $resentDateTime, $id);                               
    }
    
    /**
     * Add new user.
     * 
     * @param data $dataArray 
     */
    public function addUser($dataArray) {
        if (isset($dataArray)) {   
            $registrationDateTime = date("Y-m-d H:i:s");            
            $this->database->exec('INSERT INTO users', array(               
               'accountStatus' => 'pending',
               'username' => $dataArray[0],
               'password' => $dataArray[1],
               'salt' => $dataArray[2],
               'role' => 'uživatel',       
               'superUserActive' => 0,
               'subdomain' => $dataArray[3],
               'dateOfRegistration' => $registrationDateTime,
               'program' => $dataArray[4],
               'registrationToken' => $dataArray[5],
               'passwordResent' => '1971-00-00 00:00:00',
               'maintenanceMode' => 'off',
               'subdomainStatus' => 'N/A',
               'realSubdomainStatus' => 'N/A',
               'notificationCounter' => 0
            ));                 
        } else {            
            throw new \Nette\Application\ToolException('Unable to add new user.
                    Wrong input. method: addUser($dataArray)', 500);
        }
    }       
    
    /**
     * Update status of user registration process.
     * 
     * @param int $id
     * @param string $status 
     */    
    public function updateRegistrationProcessStatus($id, $status) {
        if (is_numeric($id) && is_string($status)) {  
            if ($status == 'active') {
                $registrationDateTime = date("Y-m-d H:i:s");
                $this->database->exec('UPDATE users SET accountStatus=?, dateOfActivation=? WHERE id=?', $status, $registrationDateTime, $id);                        
            } else {
                $this->database->exec('UPDATE users SET accountStatus=? WHERE id=?', $status, $id);                        
            }
        } else {
            throw new \Nette\Application\ToolException('Unable to update user reg process status.
                    Wrong input. method: updateRegistrationProcessStatus($id, $status)', 500);
        }
    }      

    /**
     * Set user's dateFrom.
     * 
     * @param int $id
     * @param string $dateFrom 
     */    
    public function setUserDateFrom($id, $dateFrom) {
        if (is_numeric($id) && is_string($dateFrom)) {            
            $this->database->exec('UPDATE users SET dateFrom=? WHERE id=?', $dateFrom, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to set users dateFrom.
                    Wrong input. method: setUserDateFrom($id, $dateFrom)', 500);
        }
    }    

    /**
     * Set user's dateTo.
     * 
     * @param int $id
     * @param string $dateTo
     */    
    public function setUserDateTo($id, $dateTo) {
        if (is_numeric($id) && is_string($dateTo)) {            
            $this->database->exec('UPDATE users SET dateTo=? WHERE id=?', $dateTo, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to set users dateTo.
                    Wrong input. method: setUserDateTo($id, $dateTo)', 500);
        }
    }        
    
    /**
     * Update status of maintenance mode.
     * 
     * @param int $id
     * @param string $status 
     */    
    public function updateMaintenanceModeStatus($id, $status) {
        if (is_numeric($id) && is_string($status)) {            
            $this->database->exec('UPDATE users SET maintenanceMode=? WHERE id=?', $status, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update user maintenance mode status.
                    Wrong input. method: updateMaintenanceModeStatus($id, $status)', 500);
        }
    }    
    
    /**
     * Update status of subdomain - copied files.
     * 
     * @param int $id
     * @param string $status 
     */    
    public function updateSubdomainStatus($id, $status) {
        if (is_numeric($id) && is_string($status)) {            
            $this->database->exec('UPDATE users SET subdomainStatus=? WHERE id=?', $status, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update subdomain status.
                    Wrong input. method: updateSubdomainStatus($id, $status)', 500);
        }
    }   
    
    /**
     * Update status of realSubdomain - copied files.
     * 
     * @param int $id
     * @param string $status 
     */    
    public function updateRealSubdomainStatus($id, $status) {
        if (is_numeric($id) && is_string($status)) {            
            $this->database->exec('UPDATE users SET realSubdomainStatus=? WHERE id=?', $status, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update real subdomain status.
                    Wrong input. method: updateRealSubdomainStatus($id, $status)', 500);
        }
    }         
    
    /**
     * Update status of superUser activity.
     * 
     * @param int $id
     * @param string $status 
     */    
    public function updateSuperUserActivityStatus($id, $status) {
        if (is_numeric($id) && is_numeric($status)) {            
            $this->database->exec('UPDATE users SET superUserActive=? WHERE id=?', $status, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update super user activity status.
                    Wrong input. method: updateSuperUserActivityStatus($id, $status)', 500);
        }
    }  
    
    /**
     * Update account deactivation notification counter and datetime.
     * 
     * @param int $id
     * @param int $notifyNumber
     * @throws \Nette\Application\ToolException 
     */
    public function updateAccDeactNotificationCounter($id, $notifyNumber) {
        if (is_numeric($id) && is_numeric($notifyNumber)) {   
            $updateDateTime = date("Y-m-d H:i:s");
            $this->database->exec('UPDATE users SET notificationCounter=?, notificationDate=? WHERE id=?', $notifyNumber, $updateDateTime, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update notification counter.
                    Wrong input. method: updateAccDeactNotificationCounter($id, $notifyNumber)', 500);
        }
    }      
    
    /**************************** UsersData ***********************************/           
    
    /**
     * Get user data by $idusers.
     * 
     * @param int $idusers
     * @return user 
     */
    public function getUsersDataById($idusers) {
        if (is_numeric($idusers)) {           
            $user = $this->database->query('SELECT * FROM users_data WHERE idusers=?', $idusers)->fetch();
            return $user;            
        } else {            
            throw new \Nette\Application\ToolException('Unable to get Users data.
                    Wrong input. method: getUsersDataById($idusers)', 500);
        }  
    }
    
    /**
     * Update user profile.
     * 
     * @param data $dataArray 
     */
    public function changeUserProfileInfo($dataArray) {
        if (isset($dataArray)) {   
            $changeDateTime = date("Y-m-d H:i:s");
            $this->database->exec('
                UPDATE users_data SET 
                name=?, surname=?, titleBefore=?, titleAfter=?, street=?, city=?, zip=?, phone=?, email=?, lastChange=? WHERE idusers=?', 
                $dataArray[1], $dataArray[2], $dataArray[3], $dataArray[4], $dataArray[5], $dataArray[6], $dataArray[7], $dataArray[8], $dataArray[9], $changeDateTime, $dataArray[0]);            
        } else {            
            throw new \Nette\Application\ToolException('Unable to update user profile info.
                    Wrong input. method: changeUserProfileInfo($dataArray)', 500);
        }
    }

    /**
     * Add new user data.
     * 
     * @param data $dataArray 
     */
    public function addUserData($dataArray) {
        if (isset($dataArray)) {               
            $this->database->exec('INSERT INTO users_data', array(               
               'idusers' => $dataArray[0],
               'name' => $dataArray[1],
               'surname' => $dataArray[2],
               'titleBefore' => $dataArray[3],
               'titleAfter' => $dataArray[4],
               'email' => $dataArray[5],                
               'street' => $dataArray[6],                
               'city' => $dataArray[7],                
               'zip' => $dataArray[8],  
               'phone' => $dataArray[9],                
            ));                 
        } else {            
            throw new \Nette\Application\ToolException('Unable to add new user data.
                    Wrong input. method: addUserData($dataArray)', 500);
        }        
    }    
    
    /************************* UsersWebsitedata *******************************/           
    
    /**
     * Get user website data by $idusers.
     * 
     * @param int $idusers
     * @return user website data 
     */
    public function getUserWebsiteDataById($idusers) {
        if (is_numeric($idusers)) {
            $user = $this->database->query('SELECT * FROM users_websiteData WHERE idusers=?', $idusers)->fetch();            
//                    table('users_websiteData')->where('idusers', $idusers)->fetch();
            return $user;            
        } else {            
            throw new \Nette\Application\ToolException('Unable to get User website data.
                    Wrong input. method: getUserWebsiteDataById($idusers)', 500);
        }     
    }
    
    /**
     * Update user website data ($idusers).
     * 
     * @param data $dataArray 
     */
    public function updateUserWebsiteData($dataArray) {
        if (isset($dataArray)) {
            $this->database->exec('UPDATE users_websiteData SET header1=?, header2=?, layout=?, title=?, description=?, keywords=?, headerImage=?, colourScheme=? WHERE idusers=?', $dataArray[1], $dataArray[2], $dataArray[3], $dataArray[4], $dataArray[5], $dataArray[6], $dataArray[7], $dataArray[8], $dataArray[0]);            
        } else {           
            throw new \Nette\Application\ToolException('Unable to update current user.
                    Wrong input. method: updateUserWebsiteData($dataArray)', 500);
        }
    }
    
    /**
     * Save last changes time for current user ($idusers).
     * 
     * @param int $idusers 
     */
    public function saveLastChangesForUserWebsiteData($idusers) {
        if (is_numeric($idusers)) {
            $changeDateTime = date("Y-m-d H:i:s");
            $this->database->exec('UPDATE users_websiteData SET lastChange=? WHERE idusers=?', $changeDateTime, $idusers);                               
        } else {            
            throw new \Nette\Application\ToolException('Unable to update user data.
                    Wrong input. method: saveLastChangesForUserWebsiteData($idusers)', 500);
        }
    }
    
    /**
     * Add new user websiteData.
     * 
     * @param data $dataArray 
     */
    public function addUserWebsiteData($dataArray) {
        if (isset($dataArray)) {               
            $this->database->exec('INSERT INTO users_websiteData', array(               
               'idusers' => $dataArray[0],
               'layout' => $dataArray[1],
               'layout_group' => $dataArray[2],
               'header1' => $dataArray[3],
               'header2' => $dataArray[4],                
               'title' => $dataArray[5],                
               'description' => $dataArray[6],                
               'keywords' => $dataArray[7],                 
            ));                 
        } else {            
            throw new \Nette\Application\ToolException('Unable to add new user websiteData.
                    Wrong input. method: addUserWebsiteData($dataArray)', 500);
        }        
    }    
    
    /**
     * Get list of all layouts for current user (main group (kardio...) || 
     * user specific group defined by subdomain (xa...)).
     * 
     * @param string $layout_group
     * @param string $subdomain
     * @return array of layouts 
     */
    public function getLayouts($layout_group, $subdomain) {        
        if (isset($layout_group) && isset($subdomain)) {
            $layouts = $this->database->table('layouts')
//                ->fetchall("SELECT * FROM layouts WHERE layout_group='all'");// AND group = ?', $subdomain);
                    ->where('(layout_group = ?) OR (layout_group = ?)', array($layout_group, $subdomain))
                    ->order('layout');
            if ($layouts) {
                return $layouts;
            } else {                
                throw new \Nette\Application\ToolException('Unable to get layouts.
                    method: getLayouts($layout_group, $subdomain)', 500);
            }
        } else {
            throw new \Nette\Application\ToolException('Unable to get layouts.
                    Wrong input. method: getLayouts($layout_group, $subdomain)', 500);
        }        
    }    

    /**
     * Get layout by layout $id.
     *
     * @param int $id
     * @return layout 
     */
    public function getLayoutById($id) {        
        if (is_numeric($id)) {
            $layout = $this->database->table('layouts')->where('id', $id)->fetch();
            return $layout;            
        } else {              
            throw new \Nette\Application\ToolException('Unable to get layout.
                    Wrong input. method: getLayoutById($id)', 500);
        }      
    }        
    
    /**
     * Get layout user description by layout name.
     *  
     * @param string $layout
     * @return layout description 
     */
    public function getLayout_descByLayout($layout) {
        if (is_string($layout)) {
            $layout = $this->database->table('layouts')->where('layout', $layout)->fetch();
            $layout_desc = $layout->layout_desc;
            return $layout_desc;
        } else {            
            throw new \Nette\Application\ToolException('Unable to get layout description.
                    Wrong input. method: getLayout_descByLayout($layout)', 500);
        } 
    }             
    
    /**
     * Get all layouts by specified group.
     * 
     * @param string $group
     * @return layout 
     */
    public function getLayoutsBylayoutGroup($group) {
        if (is_string($group)) {
            $layouts = $this->database->table('layouts')
                    ->where('(layout_group = ?)', array($group))
                    ->order('id');
            return $layouts;
        } else {            
            throw new \Nette\Application\ToolException('Unable to get layouts.
                    Wrong input. method: getLayoutsBylayoutGroup($group)', 500);
        }         
    }    
}
