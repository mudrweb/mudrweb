<?php

namespace AdminModule;

use Nette\Forms\Form;
use Nette\Utils\Strings;
use \AdminPresenter as AdminPresenter;

/**
 * AdminModule admin resellers presenter.
 *
 * @author     Zippo
 * @package    AdminModule
 */
class AdminResellersPresenter extends AdminPresenter {            
    
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
        $resellers = $this->db_users->getResellers();
        $resellersArray = array();
        if ($resellers) {
            foreach ($resellers as $reseller) {
                if ($reseller->id != 0) {
                    
                    // check id length and create default format
                    $idLength = Strings::length($reseller->id);                       
                    $idToBeDisplayed = Strings::padLeft($reseller->id, 5, '0');                                              
                                           
                    $resellersArray[] = array(intval($reseller->id), $reseller->fullName, 
                        $reseller->accountNumber, $reseller->fullAddress, $reseller->phone, 
                        $reseller->email, $reseller->resellersSponsoringNumber, $idToBeDisplayed);
                }
            }
        } else {
            throw new \Nette\Application\BadRequestException('Unable to load resellers (AdminModule - adminResellers presenter).', 404);
        }
        $this->template->resellers = $resellersArray; 
        
        $this->template->showAjaxLinks = !$this['confirmForm']->isVisible();
        $this->invalidateControl('links');     
        
        
        $template = $this->createTemplate()->setFile(APP_DIR . "/templates/invoice.latte");
        $pdf = new \PdfResponse($template, $this->context);
        $pdf->test();
//        $pdf->save(WWW_DIR . "/generated/", "testFile123");
    }        
    
    /**
     * Create form for addition of new reseller.
     * 
     * @return \Nette\Application\UI\Form 
     */
    protected function createComponentLayoutForm() {
        $form = new \Nette\Application\UI\Form;              
        
        $form->addText('resellerName', 'Jméno zástupce:', 60, 60)                
                ->addRule(Form::FILLED, 'Musíte zadat jméno zástupce.')                
                ->addRule(Form::MAX_LENGTH, 'Maximální povolená délka jména zástupce je 60 znaků.', 60)               
                ->setAttribute('class', 'input_style_resellerName');  
        
        $form->addTextArea('resellerAddress', 'Adresa:', 52, 40)                
                ->addRule(Form::FILLED, 'Musíte zadat adresu.')                                                
                ->setAttribute('class', 'textarea_resellerAddress');                
        
        $form->addText('resellerAccount', 'Číslo účtu:', 60, 60)                
                ->addRule(Form::FILLED, 'Musíte zadat číslo účtu.')                
                ->addRule(Form::MAX_LENGTH, 'Maximální povolená délka čísla účtu je 45 znaků.', 45)               
                ->setAttribute('class', 'input_style_resellerName');          

        $form->addText('resellerPhone', 'Telefon:', 9, 9)                
                ->addRule(Form::FILLED, 'Musíte zadat telefonní číslo.')                                                
                ->addRule(Form::INTEGER, 'Telefonní číslo musí být číslo.')                
                ->addRule(Form::MAX_LENGTH, 'Maximální povolená délka telefonního čísla je 9 znaků.', 9)                
                ->setAttribute('class', 'input_style_resellerName');           
        
        $form->addText('resellerEmail', 'Kontaktní e-mail:', 30, 30)       
                ->addRule(Form::FILLED, 'Musíte zadat kontaktní e-mail.')
                ->addRule(Form::EMAIL, 'Musíte zadat existující e-mail v platném formátu (napr. jozef.novak@gmail.com).')                
                ->addRule(Form::MAX_LENGTH, 'Maximální povolená kontaktního e-mailu je 30 znaků.', 30)                                                             
                ->setAttribute('class', 'input_style_resellerName');          
        
        $form->addText('resellerSponsoringNumber', 'Ref. číslo:', 60, 60)                
                ->addRule(Form::FILLED, 'Musíte zadat referenční číslo.')                
                ->addRule(Form::MAX_LENGTH, 'Maximální povolená délka referenčního čísla jsou 4 znaky.', 4)               
                ->addRule(Form::REGEXP, 'Referenčního číslo musí být ve tvaru ccnn (napr. wa12).', '/^[a-z][a-z][0-9]*$/')                
                ->setAttribute('class', 'input_style_resellerName');         
        
        $form->addSubmit('submit', 'Přidat zástupce')
                ->setAttribute('class', 'button')
                ->onClick[] = callback($this, 'addReseller');           
        
        $form->getElementPrototype()->onsubmit('CKEDITOR.instances["frmlayoutForm-resellerAddress"].updateElement()');
        
        return $form;
    }            
    
    /**
     * Add reseller.
     * 
     * @param \Nette\Forms\Controls\Button $button 
     */
    public function addReseller(\Nette\Forms\Controls\Button $button)
    {   
        // get data from form
        $data = $button->form->getValues();
        
        $resellers = $this->db_users->getResellers();
        $sponsoringNumbersList = array();
        foreach ($resellers as $reseller) {
            $sponsoringNumbersList[] = $reseller->resellersSponsoringNumber;
        }

        if (!in_array($data->resellerSponsoringNumber, $sponsoringNumbersList)) {
            $dataArray = array($data->resellerName, $data->resellerAddress, $data->resellerAccount,
                $data->resellerPhone, $data->resellerEmail, $data->resellerSponsoringNumber);
            $this->db_users->addReseller($dataArray);

            $this->flashMessage('Nový obchodní zástupce byl úspěšně přidán do databázy.', 'info');
            $this->redirect('this');
        } else {
            $this->flashMessage('Zadané referenční číslo již existuje!', 'warning');
        }
    }    
        
    /**
     * Submit changes event handler (called by jQuery dataTable)
     */
    public function handleSubmitChanges() {
        //get data
        $id = $_REQUEST['id'];
        $columnId = $_REQUEST['columnId'];
        
        // reseller name
        if ($columnId == 1) {
            $name = $_REQUEST['value'];
            
            $this->db_users->updateResellerName(intval($id), $name);
        }                     
        // reseller address
        else if ($columnId == 2) {
            $address = $_REQUEST['value'];
            
            $this->db_users->updateResellerAddress(intval($id), $address);
        }                             
        // reseller account number
        else if ($columnId == 3) {
            $accountNumber = $_REQUEST['value'];
                       
            $this->db_users->updateResellerAccountNumber(intval($id), $accountNumber);
        }                     
        // reseller phone
        else if ($columnId == 4) {
            $phone = $_REQUEST['value'];
                       
            $this->db_users->updateResellerPhone(intval($id), $phone);
        }   
        // reseller email
        else if ($columnId == 5) {
            $email = $_REQUEST['value'];
                       
            $this->db_users->updateResellerEmail(intval($id), $email);
        }   
        
        if (!$this->isAjax()) {
            $this->redirect('this');
        } else {        
            $this->terminate();            
            $this->invalidateControl('jEditable');
        }
    }

    /**
     * Create confirmation dialog form.
     * 
     * @return \ConfirmationDialog 
     */
    public function createComponentConfirmForm() {
        $form = new \ConfirmationDialog();

        $form->getFormElementPrototype()->addClass('ajax');
        $form->dialogClass = 'static_dialog';      

        $form->addConfirmer(
                'delete', array($this, 'confirmedDelete'), function ($dialog, $params) {        
                    return sprintf('Opravdu chcete odstranit obchodního zástupce \'%s\'?', $params['name']);
                });

        return $form;
    }

    /**
     * Delete row event handler
     */
    function confirmedDelete($id) {
        $this->db_users->deleteReseller($id);
        
        $this->flashMessage('Zvolený zástupce byl úspěšně odstraněn z databázy.', 'info');        
        
        if (!$this->isAjax())
            $this->redirect('this');        
    }    
}
