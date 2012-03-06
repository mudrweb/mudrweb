<?php

use Nette\Forms\Form;
use \BasePresenter as BasePresenter;

/**
 * Search presenter.
 *
 * @author     Zippo
 * @package    MainModule
 */
class SearchPresenter extends BasePresenter
{    
    /**
     * Startup settings.
     */    
    public function startup() {
        parent::startup();                           
    }        
    
    /**
     * renderDefault for search presenter. 
     */        
    public function renderDefault() {
        // paginator setup        
        $paginator = $this['paginator']->getPaginator();          
        $paginator->itemCount = 6;
        
        $dataBuffer = array(
            '0' => 'test0',
            '1' => 'test1',
            '2' => 'test2',
            '3' => 'test3',
            '4' => 'test4',
            '5' => 'test5',
        );
        
        $index = 0;
        for ($i = $paginator->offset; $i < $paginator->offset + $paginator->itemsPerPage; $i++) {
            $data[$index] = $dataBuffer[$i];
            $index++;
        }        

        $this->template->data = $data;
        $this->invalidateControl();
    }    
    
    /**
     * Create paginator component.
     * 
     * @return \VisualPaginator 
     */
    protected function createComponentPaginator()
    {
        $visualPaginator = new VisualPaginator();
        $visualPaginator->paginator->itemsPerPage = 3;
        return $visualPaginator;
    }     
    
    protected function createComponentSearchForm() {
        $form = new Nette\Application\UI\Form;       

//        $form->getElementPrototype()->class('ajax');        
        
        $form->addText('searchInput', 'Hledaný výraz:', 30, 30)                
                ->addRule(Form::FILLED, 'Musíte zadat hledaný výraz.')                
                ->addRule(Form::MIN_LENGTH, 'Minimální požadovaná délka hledaného výrazu jsou 3 znaky.', 3)                
                ->setAttribute('class', 'input_style_pinfo');  
        
        $form->addSubmit('submit', 'Hledej')
                ->setAttribute('class', 'button')
                ->onClick[] = callback($this, 'searchFor');               

        $form->addProtection('Vypršel časový limit, odešlete prosím formulář znovu.', 900);               
        
        return $form;        
    }     

    public function searchFor(\Nette\Forms\Controls\Button $button)
    {   
        // get data from form
        $data = $button->form->getValues(); 
        
        dump($data);
    }    
}
