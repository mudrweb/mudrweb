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
    /** @var MudrwebDB\LastSearchItemsRepository */
    private $lastSearchItemsRepository;    
    private $lastSearchDataInArray;
    
    /**
     * Startup settings.
     */    
    public function startup() {
        parent::startup();                           
        
        $this->lastSearchItemsRepository = $this->context->lastSearchItemsRepository;
        $this->lastSearchDataInArray = $this->getLastSearchsDataFromDB();            
    }        
    
    /**
     * renderDefault for search presenter. 
     */        
    public function renderDefault() {
        $this->template->data = $this->searchResultsForUser;                
        $this->template->isNotFirstSearch = $this->isNotFirstSearch;
        if ($this->lastSearchDataInArray[0] != 'dummy') {
            $this->template->lastSearchsData = $this->lastSearchDataInArray;
        } else {
            $this->template->lastSearchsData = null;
        }

        $this->invalidateControl();                   
    }    
    
    /**
     * Get lastSearchData from DB.
     * 
     * @return lastSearchData array
     */
    public function getLastSearchsDataFromDB() {
        $lastSearchsData = $this->lastSearchItemsRepository->getLastSearchItems();
        $lastSearchDataInArray = null;
        if ($lastSearchsData) {
            $lastSearchDataInArray = explode(';', $lastSearchsData->searchData);
        }
        return $lastSearchDataInArray;
    }
    
    /**
     * Add actual lastSearchData array to DB;
     * 
     * @param string array $lastSearchDataInArray 
     */
    public function addLastSearchsDataToDB($lastSearchDataInArray) {
        $counter = 0;
        $itemsCount = count($lastSearchDataInArray);        
        $lastSearchDataString = null;
        foreach ($lastSearchDataInArray as $lastSearchDataItem) {
            $counter++;
            if ($counter < $itemsCount) {
                $lastSearchDataString = $lastSearchDataString . $lastSearchDataItem . ';';
            } else {
                $lastSearchDataString = $lastSearchDataString . $lastSearchDataItem;
            }
        }
        $this->lastSearchItemsRepository->updateLastSearchItems($lastSearchDataString);
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
                ->addRule(Form::REGEXP, 'Hledaný výraz obsahuje nepovolený znak ";" (středník).', '/^[^\;]*$/')
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
        
        // add last search data to lastSearchData array
        // if there is no data in DB (default value is dummy at row with index 1)
        if ($this->lastSearchDataInArray[0] == 'dummy') {            
            array_unshift($this->lastSearchDataInArray, $stringToSearchFor);
            $this->lastSearchDataInArray = array_reverse($this->lastSearchDataInArray);
            array_shift($this->lastSearchDataInArray);
            $this->lastSearchDataInArray = array_reverse($this->lastSearchDataInArray);                    
        } else {
            array_unshift($this->lastSearchDataInArray, $stringToSearchFor);
        }
        // if array items count > 20, remove the oldest item
        if (count($this->lastSearchDataInArray) > 20) {
            $this->lastSearchDataInArray = array_reverse($this->lastSearchDataInArray);
            array_shift($this->lastSearchDataInArray);
            $this->lastSearchDataInArray = array_reverse($this->lastSearchDataInArray);                    
        }
        $this->addLastSearchsDataToDB($this->lastSearchDataInArray);
        
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
                OR titleAfter LIKE '%" . $stringToSearchFor . "%' OR doctorGroup LIKE '%" . $stringToSearchFor . "%' OR email LIKE '%" . $stringToSearchFor . "%' OR street LIKE '%" . $stringToSearchFor . "%'
                OR city LIKE '%" . $stringToSearchFor . "%' OR zip LIKE '%" . $stringToSearchFor . "%' OR region LIKE '%" . $stringToSearchFor . "%' 
                OR phone LIKE '%" . $stringToSearchFor . "%'))";

            $dbQuery = "SELECT idusers FROM users_data WHERE " . $conditions;
            $results = $this->db_users->searchForSomething($dbQuery);                       
            
            $resultsFromUsersDataTable = array();
            foreach ($results as $result) {
                $resultsFromUsersDataTable[] = $result[0];
            }            
            
            //--------------------------- prepare query to search in users table
//            SELECT users.id FROM users JOIN users_data ON users.id = users_data.idusers WHERE (subdomain LIKE '%tes%' AND region ='moravskoslezsky')
            $index = 1;
            $conditions = "(";
            foreach ($dbInputs as $dbInput) {
                // skip first empty             
                $conditions = $conditions . "region = '" . $dbInput . "'";
                if ($index != count($dbInputs)) {
                    $conditions = $conditions . " OR ";
                }
                $index++;
            }                        
            $conditions = $conditions . ")";            
           
            $dbQuery = "SELECT users.id FROM users JOIN users_data ON users.id = users_data.idusers WHERE (subdomain LIKE '%" . $stringToSearchFor . "%' AND " . $conditions . ")";
            $results1 = $this->db_users->searchForSomething($dbQuery);              
                       
            $resultsFromUsersTable = array();
            foreach ($results1 as $result1) {
                $resultsFromUsersTable[] = $result1[0];
            }
            
            // arrays merging and removing duplicities
            // primary is finding by region; if the result == null, than cancel the process
            if ($resultsFromUsersDataTable) {
                $serachResults = array_unique(array_merge($resultsFromUsersDataTable, $resultsFromUsersTable));           
           
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
                        if ($user->id != 0 && $user->id != 1) {
                            $searchResultsPostProcessing[] = array($users_data->titleBefore, $users_data->name, 
                                    $users_data->surname, $users_data->titleAfter, $users_data->doctorGroup, 
                                    $users_data->email, $users_data->street, $users_data->city, 
                                    $this->regionsList[$users_data->region], $users_data->phone, $user->subdomain);                            
                        }
                    }
                }            

                $this->searchResultsForUser = $searchResultsPostProcessing;
                $this->invalidateControl();           
            } else {
                $this->searchResultsForUser = null;
            }                
        } else {
            //---------------------- prepare query to search in users_data table
            $index = 1;
            $conditions = "(";            
            $conditions = $conditions . "name LIKE '%" . $stringToSearchFor . "%' OR surname LIKE '%" . $stringToSearchFor . "%' OR titleBefore LIKE '%" . $stringToSearchFor . "%'
                OR titleAfter LIKE '%" . $stringToSearchFor . "%' OR doctorGroup LIKE '%" . $stringToSearchFor . "%' OR email LIKE '%" . $stringToSearchFor . "%' OR street LIKE '%" . $stringToSearchFor . "%'
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
            
            // arrays merging and removing duplicities
            $serachResults = array_unique(array_merge($resultsFromUsersDataTable, $resultsFromUsersTable));                                             
            
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
                    if ($user->id != 0 && $user->id != 1) {
                        $searchResultsPostProcessing[] = array($users_data->titleBefore, $users_data->name, 
                                $users_data->surname, $users_data->titleAfter, $users_data->doctorGroup,
                                $users_data->email, $users_data->street, $users_data->city, 
                                $this->regionsList[$users_data->region], $users_data->phone, $user->subdomain);
                    }
                }
            }            
            
            $this->searchResultsForUser = $searchResultsPostProcessing;
            $this->invalidateControl();            
        }
    }    
}
