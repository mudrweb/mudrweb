<?php

use Nette\Forms\Form;
use \BasePresenter as BasePresenter;


/**
 * guestBook presenter.
 *
 * @author     Zippo
 * @package    MainModule
 */
class GuestBookPresenter extends BasePresenter
{    
    /**
     * renderDefault for guestBook presenter. 
     */        
    public function renderDefault() {
        // get guestBook data and check guestBook user name existence
        $guestBook = $this->db_guestBook->getGuestBookById($this->userId);             
        if ($guestBook) {                    
            $this->template->guestBookUserName = $guestBook->guestBookUserName;       
        } else {
            $this->template->guestBookUserName = 'Správce';
        }                      
        
        // paginator setup        
        $paginator = $this['paginator']->getPaginator();  
        // number of questions
        $countQuestions = $this->db_guestBook->countQuestions($this->userId);
        if ($countQuestions) {
            $paginator->itemCount = $countQuestions["COUNT(*)"];       
        } else {
            $paginator->itemCount = 100;
        }
        
        $dataArray = null;
        $last = null;
        $i = 0;
        // get all questions
        $questions = $this->db_guestBook->getQuestions($paginator->itemsPerPage, $paginator->offset, $this->userId);
        if ($questions) {
            foreach ($questions as $question) {      
                $dataArray[$i][0] = $question;
                // get answers for current question
                $answers = $this->db_guestBook->getAnswersByIdguestBook_q($question->id);             
                if ($answers) {
                    if (count($answers) > 0) {
                        foreach ($answers as $answer) {                    
                            $dataArray[$i][1] = $answer;
                            if ($question->id == $last) {
                                // found >= 2nd answer for current question, fill element [0]
                                $dataArray[$i][0] = null;
                            }
                            
                            $last = $question->id;
                            $i++;        
                        }         
                    } 
                    // no answer for current question, fill element [1]
                    else {    
                        $dataArray[$i][1] = null;
                    }

                    $i++;
                } else {
                    $dataArray = null;
                }
            }
        } else {
            $dataArray = null;
        }
                
        $this->template->data = $dataArray;        
        if ($countQuestions) {
            $this->template->countQuestions = $countQuestions["COUNT(*)"];
        } else {
            $this->template->countQuestions = 100;
        }        
        $this->invalidateControl();       
    }

    /**
     * Create paginator component.
     * 
     * @return \VisualPaginator 
     */
    protected function createComponentPaginator()
    {
        $visualPaginator = new \VisualPaginator();
        $visualPaginator->paginator->itemsPerPage = 4;
        return $visualPaginator;
    } 
    
    /**
     * Create form for question.
     * 
     * @return \Nette\Application\UI\Form 
     */
    protected function createComponentQuestionForm() {
        $form = new \Nette\Application\UI\Form;          
        
        $form->addHidden('userId', $this->user->getId());                            
        
        $form->getElementPrototype()->class('ajax');        
        
        $form->addText('name', 'Zadejte Vaše jméno:', 30, 30)                
                ->addRule(Form::FILLED, 'Musíte zadat jméno.')                
                ->addRule(Form::MAX_LENGTH, 'Hlavní nadpis: Maximální povolená délka jména je 30 znaků.', 30)
                ->setAttribute('class', 'textarea_guestb_name');                                        
        $form->addTextArea('question', 'Vaše otázka:', 52, 40)                
                ->addRule(Form::FILLED, 'Musíte zadat otázku.')                                
                ->setAttribute('class', 'textarea_default_desc');                        
        
        $form->addSubmit('submit', 'Odeslat otázku')
                ->setAttribute('class', 'button')
                ->onClick[] = callback($this, 'saveChanges');               

        $form->addSubmit('reset', 'Zrušit')
                ->setAttribute('class', 'button')
                ->setValidationScope(FALSE)
                ->onClick[] = callback($this, 'returnBack');        
        
        return $form;
    }           
    
    /**
     * Save new question.
     * 
     * @param \Nette\Forms\Controls\Button $button 
     */
    public function saveChanges(\Nette\Forms\Controls\Button $button)
    {   
        // get data from form
        $data = $button->form->getValues();                        
        // prepare data for update
        $dataArray = array($data->name, $data->question, $this->userId);                                        
                        
        // save new question
        $this->db_guestBook->saveQuestion($dataArray);                               
        $this->flashMessage('Vaše otázka byla úspěšně odeslána.', 'info');                          
        
        if (!$this->isAjax()) {                        
            $this->redirect('this');
        } else {           
            $this->invalidateControl('list');
        }            
    }    
    
    /**
     * Return back to guestBook.
     */    
    public function returnBack(\Nette\Forms\Controls\Button $button) {
        if (!$this->isAjax()) {                        
            $this->redirect('this');
        } else {           
            $this->invalidateControl('list');
            $button->getForm()->setValues(array(), TRUE);            
        }    
//        $this->redirect('this');
    }       
}
