<?php

namespace AdminModule;

use Nette\Forms\Form;
use \AdminPresenter as AdminPresenter;

/**
 * GuestBook default presenter.
 *
 * @author     Zippo
 * @package    AdminModule
 */
class GuestBookPresenter extends AdminPresenter {            
    
    // local vars
    private $guestBook;
    private $guestBookId;
    private $guestBookUserName;
    private $guestBookPublished;
    private $guestBookPublished_req;
    private $guestBookPublished_status;
    
    /**
     * Check access and rights here only
     */
    public function startup()
    {
        parent::startup();
        $this->checkAccess(array('uživatel'));                                 
    }
    
    /**
     * renderDefault for guestBook. 
     */
    public function renderDefault() {
        // get guestBook data
        $guestBook = $this->db_guestBook->getGuestBookById($this->user->getId());             
        
        if ($guestBook) {
            $this->guestBook = $guestBook;
            $this->guestBookId = $guestBook->id;
            $this->guestBookUserName = $guestBook->guestBookUserName;
            $this->guestBookPublished = $guestBook->guestBookPublished;
            $this->guestBookPublished_req = $this->guestBook->guestBookPublished;            

            // check guestBook publish status
            if ($this->guestBook->guestBookPublished == 'yes') {
                $this->template->status = 1;
                $this->guestBookPublished_req = '(Ne)Publikovat';                
                $this->guestBookPublished_status = 'no';
            } else {
                $this->template->status = null;
                $this->guestBookPublished_req = 'Publikovat';                
                $this->guestBookPublished_status = 'yes';
            }
        } else {
            throw new \Nette\Application\BadRequestException('Unable to load guestBook (AdminModule - guestBook presenter).', 404);
        }       
        
        // check guestBook user name existence
        if ($this->guestBookUserName) {
            $this->template->guestBookUserName = $this->guestBookUserName;
        } else {
            $this->template->guestBookUserName = 'Správce';
        }
        
//        $this['paginator']->page = $this['paginator']->paginator->page = 2;

        // paginator setup        
        $paginator = $this['paginator']->getPaginator();  
        // number of questions
        $countQuestions = $this->db_guestBook->countQuestions($this->user->getId());        
        if ($countQuestions) {
            $paginator->itemCount = $countQuestions["COUNT(*)"];               
        } else {
            $paginator->itemCount = 100;
        }
        
        $dataArray = null;
        $last = null;
        $i = 0;
        // get all questions
        $questions = $this->db_guestBook->getQuestions($paginator->itemsPerPage, $paginator->offset, $this->user->getId());        
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
     * Create form for user name change - name displayed in guestBook.
     * 
     * @return \Nette\Application\UI\Form 
     */
    protected function createComponentEditForm() {
        $form = new \Nette\Application\UI\Form;            
        
        $form->addHidden('guestBookId', $this->guestBookId);                            
        $form->addHidden('guestBookPublished_status', $this->guestBookPublished_status);
        
        $form->addText('adminName', 'Zadejte Vaše jméno zobrazované v diskuzi:', 40, 30)                
                ->addRule(Form::FILLED, 'Musíte zadat jméno.')                
                ->addRule(Form::MAX_LENGTH, 'Hlavní nadpis: Maximální povolená délka jména je 30 znaků.', 30)
                ->setValue($this->guestBookUserName)
                ->setAttribute('class', 'textarea_guestb_name');                                                               
        
        $form->addSubmit('submit', 'Změnit jméno')
                ->setAttribute('class', 'button')
                ->onClick[] = callback($this, 'changeName');               
        
        $form->addSubmit('publish', $this->guestBookPublished_req)
                ->setAttribute('class', 'button')
                ->onClick[] = callback($this, 'publishGuestBook'); 
        
        return $form;
    }

    /**
     * Create form for answer.
     * 
     * @return \Nette\Application\UI\Form 
     */
    protected function createComponentRespondForm() {
        $form = new \Nette\Application\UI\Form;       

        $form->addHidden('questionId');                               

        $form->getElementPrototype()->class('ajax');        
        
        $form->addTextArea('answer', 'Vaše odpověď:', 52, 40)                
                ->addRule(Form::FILLED, 'Musíte zadat odpověď.')                                
                ->setDefaultValue('')                
                ->setAttribute('class', 'textarea_response');                        
        
        $form->addSubmit('submitResponse', 'Odeslat odpověď')
                ->setAttribute('class', 'button')
                ->onClick[] = callback($this, 'addAnswer');    
        
        $form->addSubmit('back', 'Zrušit')        
                ->setAttribute('class', 'button')
                ->setValidationScope(FALSE)
                ->onClick[] = callback($this, 'returnBack');         
        
        return $form;
    }     
    
    /**
     * Save changes for guestBook user name - admin name displayed in guestBook.
     * 
     * @param \Nette\Forms\Controls\Button $button 
     */
    public function changeName(\Nette\Forms\Controls\Button $button)
    {   
        // get data from form
        $data = $button->form->getValues();                        
        // prepare data for update
        $dataArray = array($this->user->getId(), $data->adminName);                                        
                        
        // update current guestBook user name + lastChange timestamp        
        $this->db_guestBook->changeGuestBookUserName($dataArray);                               
        $this->flashMessage('Vaše jméno pro diskuzi bylo úspěšně změněno.', 'info');            
        $this->redirect('this');                     
    }   
    
    
    /**
     * Publish guestBook
     * 
     * @param \Nette\Forms\Controls\Button $button 
     */
    public function publishGuestBook(\Nette\Forms\Controls\Button $button)
    {   
        // get data from form
        $data = $button->form->getValues();
                      
        if ($data->guestBookPublished_status == 'yes') {
            // prepare data for update        
            $dataArray = array(intval($data->guestBookId), 'yes');                            
            // publish current menuItem
            $this->db_guestBook->publishGuestBook($dataArray);
            $this->flashMessage('Návštěvní kniha byla úspěšně publikována.', 'info');
        } elseif ($data->guestBookPublished_status == 'no') {
            // prepare data for update        
            $dataArray = array(intval($data->guestBookId), 'no');                            
            // publish current menuItem
            $this->db_guestBook->publishGuestBook($dataArray);
            $this->flashMessage('Publikace Návštěvní knihy byla úspěšně ukončena.', 'info');
        }
        $this->redirect('this');                
    }    

    /**
     * Save answer for current question.
     * 
     * @param \Nette\Forms\Controls\Button $button 
     */
    public function addAnswer(\Nette\Forms\Controls\Button $button)
    {   
        // get data from form
        $data = $button->form->getValues();                        
        // prepare data for update
        $dataArray = array($data->questionId, $data->answer);                                                                                      
       
        // update current user data + lastChange timestamp for user website        
        $this->db_guestBook->addAnswer($dataArray);                                           
        $this->flashMessage('Vaše odpověď byla úspěšně uložena.', 'info');                             
        
        if (!$this->isAjax()) {                        
            $this->redirect('this');
        } else {           
            $this->invalidateControl('list');
        }               
    }
    
    /**
     * Delete current question and all connected answers.
     * 
     * @param int $id 
     */
    public function handledeleteQuestion($id)
    {
        $idguestBook_q = intval($id);        
                
        // find and delete all answers connected to current question
        $answersForQuestion = $this->db_guestBook->getAnswersByIdguestBook_q($idguestBook_q);
        if ($answersForQuestion) {
            foreach ($answersForQuestion as $oneAnswerForQuestion) {
                $this->db_guestBook->deleteAnswer($oneAnswerForQuestion->idguestBook_a);
            }
        } 

        // delete current question        
        $this->db_guestBook->deleteQuestion($idguestBook_q); 
        $this->flashMessage('Otázka a s ní související odpovědi byly úspěšně odstraněny.', 'info');            
        $this->redirect('this');                 
        
        if (!$this->isAjax()) {                        
            $this->redirect('this');
        } else {            
            $this->invalidateControl('list');                           
        }             
    }    
    
    /**
     * Delete current answer.
     * 
     * @param int $id 
     */
    public function handledeleteAnswer($id)
    {
        $idguestBook_a = intval($id);        

        // delete current answer        
        $this->db_guestBook->deleteAnswer($idguestBook_a); 
        $this->flashMessage('Odpověď byla úspěšně odstraněna.', 'info');                        
        
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
