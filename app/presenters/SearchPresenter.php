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
    private $isNotFirstSearch = null;
    private $searchResultsForUser;
    
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
//        // paginator setup        
//        $paginator = $this['paginator']->getPaginator();    
//        $this->template->countQuestions = 6;
//        $paginator->itemCount = 6;
        
//        $dataBuffer = array(
//            '0' => 'test0',
//            '1' => 'test1',
//            '2' => 'test2',
//            '3' => 'test3',
////            '4' => 'test4',
////            '5' => 'test5',
//        );
                
//        $index = 0;
//        // number of items from DB >= number of items per page
//        if (count($dataBuffer) < $paginator->itemsPerPage) {
//            $paginator->itemsPerPage = count($dataBuffer);
//        }
//        for ($i = $paginator->offset; $i < $paginator->offset + $paginator->itemsPerPage; $i++) {
//            $data[$index] = $dataBuffer[$i];
//            $index++;
//        }
//
        $this->template->data = $this->searchResultsForUser;                
        $this->template->isNotFirstSearch = $this->isNotFirstSearch;
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
        $visualPaginator->paginator->itemsPerPage = 10;
        return $visualPaginator;
    }     
    
    protected function createComponentSearchForm() {
        $form = new Nette\Application\UI\Form;       

        $form->getElementPrototype()->class('ajax');        
        
        $form->addHidden('searchItems', null);        
        
        $form->addText('searchInput', 'Hledaný výraz:', 50, 50)                
                ->addRule(Form::FILLED, 'Musíte zadat hledaný výraz.')                
                ->addRule(Form::MIN_LENGTH, 'Minimální požadovaná délka hledaného výrazu jsou 3 znaky.', 3)                
                ->setAttribute('class', 'input_style_pinfo');  
        
        $form->addSubmit('submit', 'Hledej')
                ->setAttribute('class', 'button')
                ->onClick[] = callback($this, 'searchFor');               
        
        return $form;        
    }     

    public function searchFor(\Nette\Forms\Controls\Button $button)
    {   
        $this->isNotFirstSearch = 1;
        
        // get data from form
        $data = $button->form->getValues(); 
        
        // preprocess input data
        $stringToSearchFor = $data->searchInput;
        $this->template->stringToSearchFor = $stringToSearchFor;
        $inputItems = $data->searchItems;        
        $dbInputs = explode(',', $inputItems);        
        unset($dbInputs[0]);               
        
        // user selected some region/s
        if (count($dbInputs) > 0) {
            //---------------------- prepare query to search in users_data table
            $index = 1;
            $conditions = "((";
            foreach ($dbInputs as $dbInput) {
                // skip first empty             
                $conditions = $conditions . "region = '" . $dbInput . "'";
                if ($index != count($dbInputs)) {
                    $conditions = $conditions . " OR ";
                }
                $index++;
            }

            $conditions = $conditions . ") AND (";
            $conditions = $conditions . "name LIKE '%" . $stringToSearchFor . "%' OR surname LIKE '%" . $stringToSearchFor . "%' OR titleBefore LIKE '%" . $stringToSearchFor . "%'
                OR titleAfter LIKE '%" . $stringToSearchFor . "%' OR email LIKE '%" . $stringToSearchFor . "%' OR street LIKE '%" . $stringToSearchFor . "%'
                OR city LIKE '%" . $stringToSearchFor . "%' OR zip LIKE '%" . $stringToSearchFor . "%' OR region LIKE '%" . $stringToSearchFor . "%' 
                OR phone LIKE '%" . $stringToSearchFor . "%'))";

            $dbQuery = "SELECT idusers FROM users_data WHERE " . $conditions;
            $results = $this->db_users->searchForSomething($dbQuery);                       
            
            $resultsFromUsersDataTable = array();
            foreach ($results as $result) {
                $resultsFromUsersDataTable[] = $result[0];
            }            
            
            //--------------------------- prepare query to search in users table
            $index = 1;
            $conditions = "(";
            $conditions = $conditions . "subdomain LIKE '%" . $stringToSearchFor . "%')";

            $dbQuery = "SELECT id FROM users WHERE " . $conditions;
            $results1 = $this->db_users->searchForSomething($dbQuery);              
            
            $resultsFromUsersTable = array();
            foreach ($results1 as $result1) {
                $resultsFromUsersTable[] = $result1[0];
            }
            
            // arrays merging
            $serachResults = array_merge($resultsFromUsersDataTable, $resultsFromUsersTable);           
            
            // paginator setup
            $paginator = $this['paginator']->getPaginator();
            $numberOfResults = count($serachResults);
            $this->template->countQuestions = $numberOfResults;
            $paginator->itemCount = $numberOfResults;                       
            
            // number of items from DB < number of items per page ? update paginator
            if ($numberOfResults < $paginator->itemsPerPage) {
                $paginator->itemsPerPage = $numberOfResults;
            }
            
            // process search results to get data to be displayed to user as result
            $searchResultsPostProcessing = array();
            foreach ($serachResults as $serachResult) {
                $user = $this->db_users->getUserById(intval($serachResult));
                $users_data = $this->db_users->getUsersDataById(intval($serachResult));
                if ($user && $users_data) {
                    $searchResultsPostProcessing[] = array($users_data->titleBefore, $users_data->name, 
                            $users_data->surname, $users_data->titleAfter, $users_data->email, 
                            $users_data->street, $users_data->city, $this->regionsList[$users_data->region],
                            $users_data->phone, $user->subdomain);
                }
            }            
            
            $this->searchResultsForUser = $searchResultsPostProcessing;
            $this->invalidateControl();            
        } else {
            //---------------------- prepare query to search in users_data table
            $index = 1;
            $conditions = "(";            
            $conditions = $conditions . "name LIKE '%" . $stringToSearchFor . "%' OR surname LIKE '%" . $stringToSearchFor . "%' OR titleBefore LIKE '%" . $stringToSearchFor . "%'
                OR titleAfter LIKE '%" . $stringToSearchFor . "%' OR email LIKE '%" . $stringToSearchFor . "%' OR street LIKE '%" . $stringToSearchFor . "%'
                OR city LIKE '%" . $stringToSearchFor . "%' OR zip LIKE '%" . $stringToSearchFor . "%' OR region LIKE '%" . $stringToSearchFor . "%' 
                OR phone LIKE '%" . $stringToSearchFor . "%')";

            $dbQuery = "SELECT idusers FROM users_data WHERE " . $conditions;
            $results = $this->db_users->searchForSomething($dbQuery);                       
            
            $resultsFromUsersDataTable = array();
            foreach ($results as $result) {
                $resultsFromUsersDataTable[] = $result[0];
            }            
            
            //--------------------------- prepare query to search in users table
            $index = 1;
            $conditions = "(";
            $conditions = $conditions . "subdomain LIKE '%" . $stringToSearchFor . "%')";

            $dbQuery = "SELECT id FROM users WHERE " . $conditions;
            $results1 = $this->db_users->searchForSomething($dbQuery);              
            
            $resultsFromUsersTable = array();
            foreach ($results1 as $result1) {
                $resultsFromUsersTable[] = $result1[0];
            }
            
            // arrays merging
            $serachResults = array_merge($resultsFromUsersDataTable, $resultsFromUsersTable);           
            
            // paginator setup
            $paginator = $this['paginator']->getPaginator();
            $numberOfResults = count($serachResults);
            $this->template->countQuestions = $numberOfResults;
            $paginator->itemCount = $numberOfResults;                       
            
            // number of items from DB < number of items per page ? update paginator
            if ($numberOfResults < $paginator->itemsPerPage) {
                $paginator->itemsPerPage = $numberOfResults;
            }
            
            // process search results to get data to be displayed to user as result
            $searchResultsPostProcessing = array();
            foreach ($serachResults as $serachResult) {
                $user = $this->db_users->getUserById(intval($serachResult));
                $users_data = $this->db_users->getUsersDataById(intval($serachResult));
                if ($user && $users_data) {
                    $searchResultsPostProcessing[] = array($users_data->titleBefore, $users_data->name, 
                            $users_data->surname, $users_data->titleAfter, $users_data->email, 
                            $users_data->street, $users_data->city, $this->regionsList[$users_data->region],
                            $users_data->phone, $user->subdomain);
                }
            }            
            
            $this->searchResultsForUser = $searchResultsPostProcessing;
            $this->invalidateControl();            
        }
    }    
}
