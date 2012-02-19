<?php

use Nette\Forms\Form;
use \BasePresenter as BasePresenter;

/**
 * enuItem4 presenter.
 *
 * @author     Zippo
 * @package    MainModule
 */
class Item4Presenter extends BasePresenter {

    // menuItem data
    var $menuItem;    
    
    /**
     * renderDefault for default presenter. 
     */    
    public function renderDefault() {
        // get menuItem data
        $menuItem = $this->db_menuItems->getMenuItemById($this->userId, 4);

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
            throw new \Nette\Application\BadRequestException('Unable to load menuItem 4 (MainModule - menuItem4 presenter).', 404);
        }
    }    
}
