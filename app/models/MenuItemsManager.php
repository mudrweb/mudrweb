<?php

/**
 * MenuItemsManager base class.
 */
class MenuItemsManager extends Nette\Object {
    
    /**
     * Database handling.     
     */
    public $database;
    public function __construct(Nette\Database\Connection $database) {
        $this->database = $database;
    }

    /**
     * Get all menuItems for current user ($idusers).
     * 
     * @param int $idusers
     * @return menuItems for current user 
     */
    public function getMenuItemsByIdusers($idusers) {
        if (is_numeric($idusers)) {
            $menuItems = $this->database->query('SELECT id, itemId, itemName, itemPublished FROM menuItems WHERE idusers=? ORDER BY itemId ASC', $idusers)->fetchAll();                                    
            return $menuItems;
        } else {            
            throw new \Nette\Application\ToolException('Unable to get MenuItems.
                    Wrong input. method: getMenuitemsByIdusers($idusers)', 500);
        }
    }
    
    /**
     * Get all published menuItems for current user ($idusers).
     * 
     * @param int $idusers
     * @return published menuItems for current user 
     */
    public function getPublishedMenuItemsByIdusers($idusers) {
        if (is_numeric($idusers)) {
            $menuItem = $this->database->query('SELECT id, itemId FROM menuItems WHERE idusers=? ORDER BY itemId ASC', $idusers)->fetchAll();                                             
            return $menuItems;               
        } else {            
            throw new \Nette\Application\ToolException('Unable to get MenuItems.
                    Wrong input. method: getPublishedMenuItemsByIdusers($idusers)', 500);
        }
    }
    
    /**
     * Get all published menuItems for current user ($subdomain).
     * 
     * @param string $subdomain
     * @return list of menuItems 
     */
    public function getPublishedMenuItemsBySubdomain($subdomain) {
        if (is_string($subdomain)) {
            $menuItems = $this->database->query('SELECT menuItems.itemId,  menuItems.itemName, menuItems.itemNameRouteCs from menuItems LEFT JOIN users ON menuItems.idusers = users.id WHERE subdomain=? AND itemPublished="yes" ORDER BY menuItems.itemId ASC', $subdomain)->fetchAll();            
            return $menuItems;           
        } else {            
            throw new \Nette\Application\ToolException('Unable to get MenuItems.
                    Wrong input. method: getPublishedMenuItemsBySubdomain($subdomain)', 500);
        }
    }    
    
    /**
     * Get menuItem by $itemId for current user ($idusers).
     * 
     * @param int $idusers
     * @param int $itemId
     * @return menuItem 
     */
    public function getMenuItemById($idusers, $itemId) {
        if (is_numeric($idusers) && is_numeric($itemId)) {
            $menuItem = $this->database->query('SELECT * FROM menuItems WHERE idusers=? AND itemId=?', $idusers, $itemId)->fetch();            
//                            table('menuItems')
//                            ->where('idusers', $idusers)
//                            ->where('itemId', $itemId)->fetch();
            return $menuItem;                        
        } else {            
            throw new \Nette\Application\ToolException('Unable to get MenuItem.
                    Wrong input. method: getMenuItemById($idusers, $itemId)', 500);
        }
    }

    /**
     * Update menuItem defined by menuItem $id.
     * 
     * @param data $dataArray 
     */
    public function updateItem($dataArray) {
        if ($dataArray) {
            $this->database->exec('UPDATE menuItems SET itemName=?, itemContent=?, itemNameRouteCs=? WHERE id=?', $dataArray[1], $dataArray[2], $dataArray[3], $dataArray[0]);            
        } else {            
            throw new \Nette\Application\ToolException('Unable to update current menuItem.
                    Wrong input. method: updateItem($dataArray)', 500);
        }
    }

    /**
     * Publish menuItem defined by menuItem $id.
     * 
     * @param data $dataArray 
     */
    public function publishItem($dataArray) {
        if ($dataArray) {
            $this->database->exec('UPDATE menuItems SET itemPublished=? WHERE id=?', $dataArray[1], $dataArray[0]);            
        } else {            
            throw new \Nette\Application\ToolException('Unable to publish current menuItem.
                    Wrong input. method: publishItem($dataArray)', 500);
        }
    }
   
    /**
     * Save last changes datetime for current menuItem (menuItem $id).
     * 
     * @param int $id 
     */
    public function saveLastChangesMenuItem($id) {
        if (is_numeric($id)) {
            $changeDateTime = date("Y-m-d H:i:s");
            $this->database->exec('UPDATE menuItems SET lastChange=? WHERE id=?', $changeDateTime, $id);                               
        } else {            
            throw new \Nette\Application\ToolException('Unable to update menuItem data.
                    Wrong input. method: saveLastChangesMenuItem($id)', 500);
        }
    }   
    
    /**
     * Add new menuItems set (6 menuItems).
     * 
     * @param int $idusers 
     */
    public function addNewMenuItemsSet($idusers){
        if (is_numeric($idusers)) {
            $this->database->exec('INSERT INTO menuItems', array(               
               'idusers' => $idusers,
               'itemId' => 1,
               'itemName' => 'Úvod',
               'itemContent' => '',              
               'itemPublished' => 'yes', 
               'itemNameRouteCs' => 'uvod'
            ));       
            
            $i = 2;
            for ($i = 2; $i < 7; $i++) {
               $itemNameRouteCs = 'polozka' . $i;
               $this->database->exec('INSERT INTO menuItems', array(               
                   'idusers' => $idusers,
                   'itemId' => $i,
                   'itemName' => 'Položka' . $i,
                   'itemContent' => '',              
                   'itemPublished' => 'no',                
                   'itemNameRouteCs' => $itemNameRouteCs
               )); 
            }            
        } else {            
            throw new \Nette\Application\ToolException('Unable to add new menuItems set.
                    Wrong input. method: addNewMenuItemsSet($idusers)', 500);
        }        
    }
}
