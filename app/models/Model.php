<?php

/**
 * Model base class.
 */
class Model extends Nette\Object {
    
    /**
     * Database handling.     
     */
    public $database;
    public function __construct(Nette\Database\Connection $database) {
        $this->database = $database;
    }

    /**
     * Get all users from DB.
     * 
     * @return users
     */
    public function getUsers() {
        return $this->database->table('users');
    }
    
    /**
     * Get user by Id.
     *
     * @param int $idusers
     * @return user 
     */
    public function getUserById($idusers) {        
        if (is_numeric($idusers)) {
            $user = $this->database->table('users')->where('idusers', $idusers)->fetch();
            return $user;            
        } else {
            throw new \Nette\Application\ToolException('Unable to get User.
                    Wrong input. method: getUserById($idusers)', 500);
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
     * Get user by $idusers
     * 
     * @param int $idusers
     * @return user 
     */
    public function getUsersDataById($idusers) {
        if (is_numeric($idusers)) {
            $user = $this->database->table('users_data')->where('idusers', $idusers)->fetch();
            return $user;            
        } else {
            throw new \Nette\Application\ToolException('Unable to get Users data.
                    Wrong input. method: getUsersDataById($idusers)', 500);
        }  
    }

    /**
     * Update user.
     * 
     * @param data $dataArray 
     */
    public function updateUserWebsiteData($dataArray) {
        if (isset($dataArray)) {
            $this->database->exec('UPDATE users_websitedata SET header1=?, header2=?, layout=? WHERE idusers=?', $dataArray[1], $dataArray[2], $dataArray[3], $dataArray[0]);            
        } else {
            throw new \Nette\Application\ToolException('Unable to update current user.
                    Wrong input. method: updateUserData($dataArray)', 500);
        }
    }
    
    /**
     * Get all menuItems for current user.
     * 
     * @param int $idusers
     * @return menuItems for current user 
     */
    public function getMenuItemsByIdusers($idusers) {
        if (is_numeric($idusers)) {
            return $this->database->table('menuItems')->where('idusers', $idusers);
        } else {
            throw new \Nette\Application\ToolException('Unable to get MenuItems.
                    Wrong input. method: getMenuitemsByIdusers($idusers)', 500);
        }
    }
    
    /**
     * Get all published menuItems for current user.
     * 
     * @param int $idusers
     * @return published menuItems for current user 
     */
    public function getPublishedMenuItemsByIdusers($idusers) {
        if (is_numeric($idusers)) {
            $menuItems = $this->database->table('menuItems')
                    ->where('idusers', $idusers)
                    ->where('itemPublished', 'yes');
            return $menuItems;           
        } else {
            throw new \Nette\Application\ToolException('Unable to get MenuItems.
                    Wrong input. method: getMenuitemsByIdusers($idusers)', 500);
        }
    }
    
    /**
     * Get menuItem by $menuItemId.
     * 
     * @param int $idusers
     * @param int $menuItemId
     * @return menuItem 
     */
    public function getMenuItemById($idusers, $menuItemId) {
        if (is_numeric($idusers) && is_numeric($menuItemId)) {
            $menuItem = $this->database->table('menuItems')
                            ->where('idusers', $idusers)
                            ->where('itemId', $menuItemId)->fetch();
            return $menuItem;                        
        } else {
            throw new \Nette\Application\ToolException('Unable to get MenuItem.
                    Wrong input. method: getMenuItemById($id)', 500);
        }
    }

    /**
     * Update menuItem.
     * 
     * @param data $dataArray 
     */
    public function updateItem($dataArray) {
        if ($dataArray) {
            $this->database->exec('UPDATE menuitems SET itemName=?, itemContent=? WHERE idmenuItem=?', $dataArray[1], $dataArray[2], $dataArray[0]);            
        } else {
            throw new \Nette\Application\ToolException('Unable to update current menuItem.
                    Wrong input. method: updateItem($dataArray)', 500);
        }
    }

    /**
     * Publish menuItem defined by Id.
     * 
     * @param data $dataArray 
     */
    public function publishItem($dataArray) {
        if ($dataArray) {
            $this->database->exec('UPDATE menuitems SET itemPublished=? WHERE idmenuItem=?', $dataArray[1], $dataArray[0]);            
        } else {
            throw new \Nette\Application\ToolException('Unable to publish current menuItem.
                    Wrong input. method: publishItem($dataArray)', 500);
        }
    }
        
    /**
     * Get list of all layouts.
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
     * Get layout description by layout.
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
     * Save last changes time for current user.
     * 
     * @param int $idusers 
     */
    public function saveLastChangesForUserWebsiteData($idusers) {
        if (is_numeric($idusers)) {
            $changeDateTime = date("Y-m-d H:i:s");
            $this->database->exec('UPDATE users_websitedata SET lastChange=? WHERE idusers=?', $changeDateTime, $idusers);                               
        } else {
            throw new \Nette\Application\ToolException('Unable to update user data.
                    Wrong input. method: saveLastChangesUser($idusers)', 500);
        }
    }
    
    /**
     * Save last changes time for current menuItem.
     * 
     * @param int $idmenuItem 
     */
    public function saveLastChangesMenuItem($idmenuItem) {
        if (is_numeric($idmenuItem)) {
            $changeDateTime = date("Y-m-d H:i:s");
            $this->database->exec('UPDATE menuitems SET lastChange=? WHERE idmenuItem=?', $changeDateTime, $idmenuItem);                               
        } else {
            throw new \Nette\Application\ToolException('Unable to update user data.
                    Wrong input. method: saveLastChangesUser($idusers)', 500);
        }
    }
    
    /**
     * Set where to find user data.
     * 
     * @return Authenticator 
     */
    public function createAuthenticatorService() {
        return new Authenticator($this->database->table('users'));
    }
    
    /**
     * Save last login time for current user.
     * 
     * @param int $idusers 
     */
    public function saveLastUserLogin($idusers) {
        if (is_numeric($idusers)) {
            $changeDateTime = date("Y-m-d H:i:s");
            $this->database->exec('UPDATE users SET lastLogin=? WHERE idusers=?', $changeDateTime, $idusers);                               
        } else {
            throw new \Nette\Application\ToolException('Unable to save last login data for current user.
                    Wrong input. method: saveLastUserLogin($idusers)', 500);
        }
    }
    
    /**
     * Save last logout time for current user.
     * 
     * @param int $idusers 
     */
    public function saveLastUserLogout($idusers) {
        if (is_numeric($idusers)) {
            $changeDateTime = date("Y-m-d H:i:s");
            $this->database->exec('UPDATE users SET lastLogout=? WHERE idusers=?', $changeDateTime, $idusers);                               
        } else {
            throw new \Nette\Application\ToolException('Unable to save last logout data for current user.
                    Wrong input. method: saveLastUserLogout($idusers)', 500);
        }
    }
    
    /**
     * Update user's password hash and salt.
     * 
     * @param data $dataArray 
     */
    public function changePassword($dataArray) {
        if (isset($dataArray)) {            
            $this->database->exec('UPDATE users SET password=?, salt=? WHERE idusers=?', $dataArray[1], $dataArray[2], $dataArray[0]);            
        } else {
            throw new \Nette\Application\ToolException('Unable to update user password.
                    Wrong input. method: changePassword($dataArray)', 500);
        }
    }
    
    /**
     * Get user website data by $idusers.
     * 
     * @param int $idusers
     * @return user website data 
     */
    public function getUserWebsiteDataById($idusers) {
        if (is_numeric($idusers)) {
            $user = $this->database->table('users_websitedata')->where('idusers', $idusers)->fetch();
            return $user;            
        } else {
            throw new \Nette\Application\ToolException('Unable to get User website data.
                    Wrong input. method: getUserDataById($idusers)', 500);
        }     
    }
}
