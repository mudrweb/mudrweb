<?php

use Nette\Forms\Form;
use \BasePresenter as BasePresenter;

/**
 * menuItem1 presenter.
 *
 * @author     Zippo
 * @package    MainModule
 */
class Item1Presenter extends BasePresenter {

    // menuItem data
    var $menuItem;    
    
    /**
     * renderDefault for menuItem1 presenter. 
     */    
    public function renderDefault() {
        // get menuItem data
        $menuItem = $this->db_menuItems->getMenuItemById($this->userId, 1);
                            
        if ($menuItem) {
            $this->menuItem = $menuItem;

            // check menuItem publish status
            if ($this->menuItem->itemPublished == 'yes') {
                $this->template->status = 1;
                
                // prepare itemContent for output
                $this->template->content = $this->menuItem->itemContent;
            } else {
                $this->template->status = null;
            }
        } else {
            throw new \Nette\Application\BadRequestException('Unable to load menuItem 1 (MainModule - menuItem1 presenter).', 404);
        }
    }    
}
