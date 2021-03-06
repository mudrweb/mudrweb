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
            //$user = $this->database->table('users')->get($id);
            $user = $this->database->query('SELECT * FROM users WHERE id=?', $id)->fetch();
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
     * Get user by $registrationToken.
     * 
     * @param string $registrationToken
     * @return user 
     */
    public function getUserByRegistrationToken($registrationToken) {
        if ($registrationToken) {
            $user = $this->database->table('users')
                    ->where('registrationToken', $registrationToken)->fetch();
            return $user;
        } else {
            throw new \Nette\Application\ToolException('Unable to get User.
                    Wrong input. method: getUserByRegistrationToken($registrationToken)', 500);
        }
    }    
    
    /**
     * Get user by $usersSponsoringNumber.
     * 
     * @param string $usersSponsoringNumber
     * @return user
     * @throws \Nette\Application\ToolException 
     */
    public function getUserBySponsoringNumber($usersSponsoringNumber) {
        if ($usersSponsoringNumber) {
            $user = $this->database->table('users')
                    ->where('usersSponsoringNumber', $usersSponsoringNumber)->fetch();
            return $user;
        } else {
            throw new \Nette\Application\ToolException('Unable to get User.
                    Wrong input. method: getUserBySponsoringNumber($usersSponsoringNumber)', 500);
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
               'passwordTemp' => $dataArray[8],
               'salt' => $dataArray[2],
               'role' => 'uživatel',   
               'usersSponsor' => $dataArray[7],
               'usersSponsorIsReseller' => $dataArray[9],
               'usersSponsoringNumber' => $dataArray[6],
               'superUserActive' => 0,
               'subdomain' => $dataArray[3],
               'dateOfRegistration' => $registrationDateTime,
               'program' => $dataArray[4],
               'advertisement' => 'no',
               'registrationToken' => $dataArray[5],
               'dateOfActivation' => '1971-00-00 00:00:00',
               'passwordResent' => '1971-00-00 00:00:00',
               'paymentReceived' => 'no',
               'dateOfPayment' => '1971-00-00', 
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
     * Update advertisement.
     * 
     * @param int $id
     * @param string $advert 
     */    
    public function updateAdvertisement($id, $advert) {
        if (is_numeric($id) && is_string($advert)) {            
            $this->database->exec('UPDATE users SET advertisement=? WHERE id=?', $advert, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update user advertisement.
                    Wrong input. method: updateAdvertisement($id, $advert)', 500);
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
    
    /**
     * Reset temporary password -> set it to null.
     * 
     * @param int $id     
     */    
    public function resetTemporaryPassword($id) {
        if (is_numeric($id)) {            
            $this->database->exec('UPDATE users SET passwordTemp=null WHERE id=?', $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to reset temporary password.
                    Wrong input. method: resetTemporaryPassword($id)', 500);
        }
    }       
    
    /**
     * Update FTP password.
     * 
     * @param int $id
     * @param string $newFTPpassword 
     */    
    public function updateFTPPassword($id, $newFTPpassword) {
        if (is_numeric($id) && is_string($newFTPpassword)) {            
            $this->database->exec('UPDATE users SET passwordFTP=? WHERE id=?', $newFTPpassword, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update user FTP password.
                    Wrong input. method: updateFTPPassword($id, $newFTPpassword)', 500);
        }
    }         
    
    /**
     * Update program type.
     * 
     * @param int $id
     * @param string $program 
     */    
    public function updateProgramType($id, $program) {
        if (is_numeric($id) && is_string($program)) {            
            $this->database->exec('UPDATE users SET program=? WHERE id=?', $program, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update program type.
                    Wrong input. method: updateProgramType($id, $program)', 500);
        }
    }       
    
    /**
     * Update users used referral bonus value.
     * 
     * @param int $id
     * @param string $usedReferralBonusValue
     */    
    public function updateUsersUsedReferralBonus($id, $usedReferralBonusValue) {
        if (is_numeric($id) && is_numeric($usedReferralBonusValue)) {            
            $this->database->exec('UPDATE users SET usedReferralBonus=? WHERE id=?', $usedReferralBonusValue, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update users used referral bonus value.
                    Wrong input. method: updateUsersUsedReferralBonus($id, $usedReferralBonusValue)', 500);
        }
    }          
    
    /**
     * Update payment received status.
     * 
     * @param int $id
     * @param string $status 
     */    
    public function updatePaymentStatus($id, $status, $dateOfPayment) {
        if (is_numeric($id) && is_string($status) && is_string($dateOfPayment)) {            
            $this->database->exec('UPDATE users SET paymentReceived=?, dateOfPayment=? WHERE id=?', $status, $dateOfPayment, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update user payment status.
                    Wrong input. method: updatePaymentStatus($id, $status, $dateOfPayment)', 500);
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
                name=?, surname=?, titleBefore=?, titleAfter=?, doctorGroup=?, 
                street=?, city=?, zip=?, region=?, phone=?, email=?, 
                ic=?, dic=?, streetInvoice=?, cityInvoice=?, zipInvoice=?, 
                addressMatch=?, lastChange=?
                WHERE idusers=?', 
                $dataArray[1], $dataArray[2], $dataArray[3], $dataArray[4], $dataArray[5], 
                $dataArray[6], $dataArray[7], $dataArray[8], $dataArray[9], $dataArray[10], 
                $dataArray[11], $dataArray[12], $dataArray[13], $dataArray[14], $dataArray[15],
                $dataArray[16], $dataArray[17], $changeDateTime, $dataArray[0]);            
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
               'doctorGroup' => $dataArray[11],
               'email' => $dataArray[5],                
               'street' => $dataArray[6],                
               'city' => $dataArray[7],                
               'zip' => $dataArray[8],  
               'region' => $dataArray[9],  
               'phone' => $dataArray[10],
               'ic' => $dataArray[12],
               'dic' => $dataArray[13],
               'addressMatch' => 1
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
     * Get all layouts from DB.
     * 
     * @return users
     */
    public function getLayoutsRawData() {
        return $this->database->table('layouts');
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
                    ->order('layout_desc');                    
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
    
    /**
     * Add new layout.
     * 
     * @param data $dataArray 
     */
    public function addLayout($dataArray) {
        if (isset($dataArray)) {              
            $this->database->exec('INSERT INTO layouts', array(               
               'layout' => $dataArray[0],
               'layout_group' => $dataArray[1],
               'layout_desc' => $dataArray[2]
            ));                 
        } else {            
            throw new \Nette\Application\ToolException('Unable to add new layout.
                    Wrong input. method: addLayout($dataArray)', 500);
        }
    }         
    
    /**
     * Delete layout.
     */
    public function deleteLayout($id) {
        if (is_numeric($id)) {        
            $this->database->table('layouts')->find($id)->delete();                        
        } else {
            throw new \Nette\Application\ToolException('Unable to delete layout.
                    Wrong input. method: deleteLayout($id)', 500);  
        }              
    }        
    
    /**
     * Update layout name.
     * 
     * @param int $id
     * @param string $name 
     */    
    public function updateLayoutName($id, $name) {
        if (is_numeric($id) && is_string($name)) {            
            $this->database->exec('UPDATE layouts SET layout=? WHERE id=?', $name, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update layout name.
                    Wrong input. method: updateLayoutName($id, $name)', 500);
        }
    }      
    
    /**
     * Update layout group.
     * 
     * @param int $id
     * @param string $group 
     */    
    public function updateLayoutGroup($id, $group) {
        if (is_numeric($id) && is_string($group)) {            
            $this->database->exec('UPDATE layouts SET layout_group=? WHERE id=?', $group, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update layout group.
                    Wrong input. method: updateLayoutGroup($id, $group)', 500);
        }
    }         

    /**
     * Update layout description.
     * 
     * @param int $id
     * @param string $desc
     */    
    public function updateLayoutDesc($id, $desc) {
        if (is_numeric($id) && is_string($desc)) {            
            $this->database->exec('UPDATE layouts SET layout_desc=? WHERE id=?', $desc, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update layout description.
                    Wrong input. method: updateLayoutDesc($id, $desc)', 500);
        }
    }           
    
    /**
     * Search for data according to user input (conditions included in query string).
     * 
     * @param query string $queryString
     * @throws \Nette\Application\ToolException 
     */
    public function searchForSomething($queryString) {
        if (isset($queryString)) {               
            $results = $this->database->query($queryString)->fetchAll();
            return $results;
        } else {            
            throw new \Nette\Application\ToolException('Unable to perform search procedure.
                    Wrong input. method: searchForSomething($queryString)', 500);
        }             
    }
    
    /**
     * Return number of user's referrers (referrer = person who used user's 
     * ref. number in registration process).
     * 
     * @param int $id
     * @return number of users
     * @throws \Nette\Application\ToolException 
     */    
    public function getNumberOfUsersReferrers($id) {
        if (is_numeric($id)) {                      
            $results = $this->database->exec('SELECT COUNT(id) FROM `users` WHERE (usersSponsor=? AND paymentReceived="yes") GROUP BY id', $id);                        
            return $results;
        } else {
            throw new \Nette\Application\ToolException('Unable to get number of referrers for current user.
                    Wrong input. method: getNumberOfUsersReferrers($id)', 500);
        }
    }
    
    /**************************** Resellers **********************************/    
    
    /**
     * Get reseller by $resellersSponsoringNumber.
     * 
     * @param string $resellersSponsoringNumber
     * @return reseller
     * @throws \Nette\Application\ToolException 
     */
    public function getResellerBySponsoringNumber($resellersSponsoringNumber) {
        if ($resellersSponsoringNumber) {
            $reseller = $this->database->table('resellers')
                    ->where('resellersSponsoringNumber', $resellersSponsoringNumber)->fetch();
            return $reseller;
        } else {
            throw new \Nette\Application\ToolException('Unable to get Reseller.
                    Wrong input. method: getResellerBySponsoringNumber($resellersSponsoringNumber)', 500);
        }
    }      
    
    /**
     * Get all resellers from DB.
     * 
     * @return resellers
     */
    public function getResellers() {
        return $this->database->table('resellers');
    }  
    
    /**
     * Get reseller by reseller $id.
     *
     * @param int $id
     * @return reseller 
     */
    public function getResellerById($id) {         
        if (is_numeric($id)) {            
            $reseller = $this->database->query('SELECT * FROM resellers WHERE id=?', $id)->fetch();
            return $reseller;            
        } else {            
            throw new \Nette\Application\ToolException('Unable to get Reseller.
                    Wrong input. method: getResellerById($id)', 500);
        }      
    }     
    
    /**
     * Add new reseller.
     * 
     * @param data $dataArray 
     */
    public function addReseller($dataArray) {
        if (isset($dataArray)) {              
            $this->database->exec('INSERT INTO resellers', array(               
               'fullName' => $dataArray[0],
               'fullAddress' => $dataArray[1],
               'accountNumber' => $dataArray[2],
               'phone' => $dataArray[3],
               'email' => $dataArray[4],                
               'resellersSponsoringNumber' => $dataArray[5]                                
            ));                 
        } else {            
            throw new \Nette\Application\ToolException('Unable to add new reseller.
                    Wrong input. method: addReseller($dataArray)', 500);
        }
    }      
    
    /**
     * Delete reseller.
     */
    public function deleteReseller($id) {
        if (is_numeric($id)) {        
            $this->database->table('resellers')->find($id)->delete();                        
        } else {
            throw new \Nette\Application\ToolException('Unable to delete reseller.
                    Wrong input. method: deleteReseller($id)', 500);  
        }              
    }      
    
    /**
     * Update reseller name.
     * 
     * @param int $id
     * @param string $name 
     */    
    public function updateResellerName($id, $name) {
        if (is_numeric($id) && is_string($name)) {            
            $this->database->exec('UPDATE resellers SET fullName=? WHERE id=?', $name, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update reseller name.
                    Wrong input. method: updateResellerName($id, $name)', 500);
        }
    }     
    
    /**
     * Update reseller address.
     * 
     * @param int $id
     * @param string $address 
     */    
    public function updateResellerAddress($id, $address) {
        if (is_numeric($id) && is_string($address)) {            
            $this->database->exec('UPDATE resellers SET fullAddress=? WHERE id=?', $address, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update reseller address.
                    Wrong input. method: updateResellerAddress($id, $address)', 500);
        }
    }     
    
    /**
     * Update reseller account number.
     * 
     * @param int $id
     * @param string $accountNumber 
     */    
    public function updateResellerAccountNumber($id, $accountNumber) {
        if (is_numeric($id) && is_string($accountNumber)) {            
            $this->database->exec('UPDATE resellers SET accountNumber=? WHERE id=?', $accountNumber, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update reseller account number.
                    Wrong input. method: updateResellerAccountNumber($id, $accountNumber)', 500);
        }
    }         
    
    /**
     * Update reseller phone.
     * 
     * @param int $id
     * @param string $phone 
     */    
    public function updateResellerPhone($id, $phone) {
        if (is_numeric($id) && is_string($phone)) {            
            $this->database->exec('UPDATE resellers SET phone=? WHERE id=?', $phone, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update reseller phone.
                    Wrong input. method: updateResellerPhone($id, $phone)', 500);
        }
    }       
    
    /**
     * Update reseller email.
     * 
     * @param int $id
     * @param string $email
     */    
    public function updateResellerEmail($id, $email) {
        if (is_numeric($id) && is_string($email)) {            
            $this->database->exec('UPDATE resellers SET email=? WHERE id=?', $email, $id);                        
        } else {
            throw new \Nette\Application\ToolException('Unable to update reseller email.
                    Wrong input. method: updateResellerEmail($id, $email)', 500);
        }
    }       
}
