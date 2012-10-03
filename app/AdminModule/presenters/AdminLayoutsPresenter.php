<?php

namespace AdminModule;

use Nette\Forms\Form;
use Nette\Utils\Strings;
use \AdminPresenter as AdminPresenter;

/**
 * AdminModule admin layouts presenter.
 *
 * @author     Zippo
 * @package    AdminModule
 */
class AdminLayoutsPresenter extends AdminPresenter {            
    
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
        $layouts = $this->db_users->getLayoutsRawData();
        $layoutsArray = array();
        if ($layouts) {
            foreach ($layouts as $layout) {
                
                // check id length and create default format
                $idLength = Strings::length($layout->id);
                $idToBeDisplayed = Strings::padLeft($layout->id, 5, '0');

                $layoutsArray[] = array(intval($layout->id), $layout->layout, $layout->layout_group,
                    $layout->layout_desc, $idToBeDisplayed);
            }
        } else {
            throw new \Nette\Application\BadRequestException('Unable to load layouts (AdminModule - adminLayouts presenter).', 404);
        }
        $this->template->layouts = $layoutsArray;          
    }        
    
    /**
     * Create form for addition of new layout.
     * 
     * @return \Nette\Application\UI\Form 
     */
    protected function createComponentLayoutForm() {
        $form = new \Nette\Application\UI\Form;       
        
        // layout name
        $form->addText('layoutName', 'Název vzhledu:', 30, 30)                
                ->addRule(Form::FILLED, 'Musíte zadat název vzhledu.')                
                ->addRule(Form::MAX_LENGTH, 'Název vzhledu: Maximální povolená délka názvu je 45 znaků.', 45)               
                ->setAttribute('class', 'input_style_layoutName');  

        // layout group
        $form->addText('layoutGroup', 'Skupina vzhledu:', 20, 20)                
                ->addRule(Form::FILLED, 'Musíte zadat skupinu vzhledu.')                
                ->addRule(Form::MAX_LENGTH, 'Název vzhledu: Maximální povolená délka skupiny je 30 znaků.', 30)               
                ->setDefaultValue('all')
                ->setAttribute('class', 'input_style_layoutName');         

        $form->addTextArea('layoutDesc', 'Popis vzhledu:', 52, 40)                
                ->addRule(Form::FILLED, 'Musíte zadat popis vzhledu.')                                
                ->addRule(Form::MAX_LENGTH, 'Popis stránky: Maximální povolená délka popisu vzhledu je 130 znaků.', 130)                
                ->setAttribute('class', 'textarea_layout_desc');                
        
        $form->addSubmit('submit', 'Přidat vzhled')
                ->setAttribute('class', 'button')
                ->onClick[] = callback($this, 'addLayout');           
        
        return $form;
    }            
    
    /**
     * Add layout.
     * 
     * @param \Nette\Forms\Controls\Button $button 
     */
    public function addLayout(\Nette\Forms\Controls\Button $button)
    {   
        // get data from form
        $data = $button->form->getValues();
        
        $dataArray = array($data->layoutName, $data->layoutGroup, $data->layoutDesc);
        $this->db_users->addLayout($dataArray);
        
        $this->flashMessage('Nový vzhled byl úspěšně přidán do databázy.', 'info');
        $this->redirect('this');
    }    
    
    /**
     * Delete row event handler (called by jQuery datatable)
     */
    public function handleDeleteLayout($id) {     
        $this->db_users->deleteLayout($id);

        $this->flashMessage('Zvolený vzhled byl úspěšně odstraněn z databázy.', 'info');
        $this->redirect('this');
    }        
    
    /**
     * Submit changes event handler (called by jQuery dataTable)
     */
    public function handleSubmitChanges() {
        //get data
        $id = $_REQUEST['id'];
        $columnId = $_REQUEST['columnId'];
        
        // layout name
        if ($columnId == 1) {
            $layoutName = $_REQUEST['value'];
            
            $this->db_users->updateLayoutName(intval($id), $layoutName);
        }                     
        // layout group
        else if ($columnId == 2) {
            $layoutGroup = $_REQUEST['value'];
            
            $this->db_users->updateLayoutGroup(intval($id), $layoutGroup);
        }                             
        // layout desc
        else if ($columnId == 3) {
            $layoutDesc = $_REQUEST['value'];
            
            $this->db_users->updateLayoutDesc(intval($id), $layoutDesc);
        }                     
        
        if (!$this->isAjax()) {
            $this->redirect('this');
        } else {        
            $this->terminate();            
            $this->invalidateControl('jEditable');
        }        
    }         
}
