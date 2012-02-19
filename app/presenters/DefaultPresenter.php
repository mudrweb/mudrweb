<?php

use \BasePresenter as BasePresenter;

/**
 * Default presenter.
 *
 * @author     Zippo
 * @package    MainModule
 */
class DefaultPresenter extends BasePresenter
{    
    /**
     * Startup settings.
     */    
    public function startup() {
        parent::startup();       
        
    if (DOMAIN != 'main') {
            // get user by domain name
            $user = $this->db_users->getUserBySubdomain(DOMAIN);    
            
            // if user exists get published menuItems
            if ($user) {
                $menuItems = $this->db_menuItems->getPublishedMenuItemsByIdusers($user->id)->order('itemId');                                        
                $menuItemIndex = 1;
                $menuItemsArray = array();
                // get Id of first published menuItem                
                if ($menuItems) {
                    foreach ($menuItems as $menuItem) {
                        $menuItemsArray[$menuItemIndex] = $menuItem->itemId;
                        $menuItemIndex++;
                    }   
                    // redirect user to first published menuItem
                    $this->redirect('Item' . $menuItemsArray[1] . ':');                    
                } else {
                    $this->redirect('Item1:');                    
                }                                                           
            } else {
                throw new \Nette\Application\BadRequestException('Unable to load user profile (MainModule - default presenter).', 404);
            } 
        } else {
            // main page part for default presenter
        }           
    }        
}
