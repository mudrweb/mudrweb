<?php

namespace AdminModule;

use Nette\Forms\Form;
use \AdminPresenter as AdminPresenter;

/**
 * AdminModule menuItem6 presenter.
 *
 * @author     Zippo
 * @package    AdminModule
 */
class Item6Presenter extends AdminPresenter {
     
    /**
     * Check access and rights here only
     */
    public function startup()
    {
        parent::startup();
        $this->checkAccess(array('uživatel'));      
    }
    
    /**
     * renderDefault for current menuItem. 
     */
    public function renderDefault() {	  
        // get menuItem data
        $menuItem = $this->db_menuItems->getMenuItemById($this->user->getId(),6);                
        if ($menuItem) {
            $this->menuItem = $menuItem;
            $this->itemName = $menuItem->itemName;
            $this->itemContent = $menuItem->itemContent;
            $this->itemId = $menuItem->id;
            $this->itemPublished_req = $this->menuItem->itemPublished;            

            // check menuItem publish status
            if ($this->menuItem->itemPublished == 'yes') {
                $this->template->status = 1;
                $this->itemPublished_req = '(Ne)Publikovat';                
                $this->itemPublished_status = 'no';
            } else {
                $this->template->status = null;
                $this->itemPublished_req = 'Publikovat';                
                $this->itemPublished_status = 'yes';
            }
        } else {
            throw new \Nette\Application\BadRequestException('Unable to load menuItem 6 (AdminModule - menuItem6 presenter).', 404);
        }
    }
    
    /**
     * Create form for current menuItem.
     * 
     * @return \Nette\Application\UI\Form 
     */
    protected function createComponentEditForm() {
        $form = new \Nette\Application\UI\Form;
//        $form->addText('name', 'Name', 15);
//        $form->getElementPrototype()->class('ajax');
        $form->addHidden('itemId', $this->itemId);
        $form->addHidden('itemPublished_status', $this->itemPublished_status);
        $form->addText('itemName', 'Název:', 30, 30)                
                ->addRule(Form::FILLED, 'Musíte zadat název položky.')
                ->addRule(Form::MAX_LENGTH, 'Maximální povolená délka vstupu je 20 znaků.', 20)
                ->addRule(callback('\AdminPresenter::itemnamecheck'),'Název obsahuje nepovolené znaky. Použijte prosím pouze písmena, čísla nebo mezeru.')                                                                                
                ->setValue($this->itemName)
                ->setAttribute('class', 'input_style');        
        $form->addTextArea('text', 'Obsah:', 90, 18)                
                ->addRule(Form::FILLED, 'Musíte zadat text.')
                ->setValue($this->itemContent)
                ->getControlPrototype()->class('mceEditor');                        
        $form->addSubmit('submit', 'Uložit změny')
                ->setAttribute('class', 'button')
                ->onClick[] = callback($this, 'saveChanges');
         
        $form->addSubmit('publish', $this->itemPublished_req)
                ->setAttribute('class', 'button')
                ->onClick[] = callback($this, 'publishItem');        
        
        return $form;
    }

    /**
     * Save changes for current menuItem.
     * 
     * @param \Nette\Forms\Controls\Button $button 
     */
    public function saveChanges(\Nette\Forms\Controls\Button $button)
    {   
        // get data from form
        $data = $button->form->getValues();                
        // prepare unified itemName for route
        $itemNameRouteCs = $this->prepareUnifiedItemNameForRoute($data->itemName);
        if ($itemNameRouteCs) {                        
            // prepare data for update
            $dataArray = array(intval($data->itemId), $data->itemName, $data->text, $itemNameRouteCs);
            // update current menuItem
            $this->db_menuItems->updateItem($dataArray);
            //save time of last change
            $this->db_menuItems->saveLastChangesMenuItem($data->itemId);

            $this->flashMessage('Změny byly úspěšně uloženy.', 'info');
            $this->redirect('this');
        } else {
            $this->redirect('this');
        }
    }
    
    /**
     * Publish item.
     * 
     * @param \Nette\Forms\Controls\Button $button 
     */    
    public function publishItem(\Nette\Forms\Controls\Button $button)
    {   
        // get data from form
        $data = $button->form->getValues();
              
        if ($data->itemPublished_status == 'yes') {
            // prepare data for update        
            $dataArray = array(intval($data->itemId), 'yes');                            
            // publish current menuItem
            $this->db_menuItems->publishItem($dataArray);
            $this->flashMessage('Položka byla úspěšně publikována.', 'info');
        } elseif ($data->itemPublished_status == 'no') {
            // prepare data for update        
            $dataArray = array(intval($data->itemId), 'no');                            
            // publish current menuItem
            $this->db_menuItems->publishItem($dataArray);
            $this->flashMessage('Publikace položky byla úspěšně ukončena.', 'info');
        }
        $this->redirect('this');                
    }
}
