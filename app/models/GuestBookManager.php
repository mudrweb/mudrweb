<?php

/**
 * GuestBookManager base class.
 */
class GuestBookManager extends Nette\Object {
    
    /**
     * Database handling.     
     */
    public $database;
    public function __construct(Nette\Database\Connection $database) {
        $this->database = $database;
    }
    
    /**
     * Get current user guestBook.
     * 
     * @param int $idusers
     * @return guestBook 
     */
    public function getGuestBookById($idusers) {
        if (is_numeric($idusers)) {
            $guestBook = $this->database->table('guestBook')->select('id, guestBookUserName, guestBookPublished')->where('idusers', $idusers)->fetch();
            return $guestBook;            
        } else {            
            throw new \Nette\Application\ToolException('Unable to get GuestBook.
                    Wrong input. method: getGuestBookById($idusers)', 500);
        }              
    }
    
    /**
     * Add new guestBook.
     * 
     * @param data $dataArray 
     */
    public function addGuestBook($dataArray) {
        if (isset($dataArray)) {               
            $this->database->exec('INSERT INTO guestBook', array(               
               'idusers' => $dataArray[0],
               'guestBookUserName' => $dataArray[1],
               'guestBookPublished' => 'no',
            ));                 
        } else {                
            throw new \Nette\Application\ToolException('Unable to add new GuestBook.
                    Wrong input. method: addGuestBook($dataArray)', 500);
        }      
    }    
    
    /**
     * Get all answers for current question.
     * 
     * @param int $idguestBook_q
     * @return array of answers 
     */
    public function getAnswersByIdguestBook_q($idguestBook_q) {
        if (is_numeric($idguestBook_q)) {
            return $this->database->table('guestBook_a')
                    ->where('idguestBook_q',$idguestBook_q);
        } else {            
            throw new \Nette\Application\ToolException('Unable to get answer.
                    Wrong input. method: getAnswersByIdguestBook_q($idguestBook_q)', 500);
        }  
    }
    
    /**
     * Get all questions according to params - items per page and current 
     * paginator offset - where to start and how many questions choose.  
     *      
     * @param int $itemsPerPage
     * @param int $offset
     * @param int $idusers
     * @return array of questions 
     */
    public function getQuestions($itemsPerPage, $offset, $idusers) {
        if (is_numeric($itemsPerPage) && is_numeric($offset)) {
            return $data = $this->database->query("SELECT * FROM 
                guestBook_q WHERE idusers=? ORDER BY id DESC LIMIT $itemsPerPage OFFSET $offset", $idusers);        
        } else {            
            throw new \Nette\Application\ToolException('Unable to get questions.
                    Wrong input. method: getQuestions($itemsPerPage, $offset)', 500);
        }  
    }
    
    /**
     * Get count of questions in DB.
     * 
     * @param int $idusers
     * @return number of questions in DB 
     */
    public function countQuestions($idusers) {
        return $this->database->query('SELECT COUNT(*) FROM guestBook_q WHERE idusers=?', $idusers)->fetch();
    }    
    
    /**
     * Save answer for current question.
     * 
     * @param data $dataArray 
     */
    public function addAnswer($dataArray) {
        if (isset($dataArray)) {
            $dateTime = date("Y-m-d H:i:s");
            $this->database->exec('INSERT INTO guestBook_a', array(
                        'idguestBook_q' => $dataArray[0],
                        'answer' => $dataArray[1],
                        'dateTime' => $dateTime
                    )                     
                );        
        } else {            
            throw new \Nette\Application\ToolException('Unable to save answer.
                    Wrong input. method: addAnswer($dataArray)', 500);
        }
    }    
    
    /**
     * Delete current answer.
     * 
     * @param id $id
     */
    public function deleteAnswer($id) {
        if (is_numeric($id)) {
            $this->database->exec('DELETE FROM guestBook_a WHERE id=?', $id);
        } else {            
            throw new \Nette\Application\ToolException('Unable to delete answer.
                    Wrong input. method: deleteAnswer($id)', 500);
        }  
    }    
    
    /**
     * Delete current question.
     * 
     * @param int $id
     */
    public function deleteQuestion($id) {
        if (is_numeric($id)) {
            $this->database->exec('DELETE FROM guestBook_q WHERE id=?', $id);
        } else {            
            throw new \Nette\Application\ToolException('Unable to delete question.
                    Wrong input. method: deleteQuestion($id)', 500);
        }              
    }    
    
    /**
     * Change guestBook user name.
     * 
     * @param data $dataArray 
     */
    public function changeGuestBookUserName($dataArray) {
       if ($dataArray) {
           $changeDateTime = date("Y-m-d H:i:s");
           $this->database->exec('UPDATE guestBook SET guestBookUserName=?, lastChange=? WHERE idusers=?', $dataArray[1], $changeDateTime, $dataArray[0]);
       } else {           
           throw new \Nette\Application\ToolException('Unable to change guestBook user name.
                   Wrong input. method: changeGuestBookUserName($dataArray)', 500);            
       }
    }
    
    /**
     * Publish guestBook defined by guestBook $id.
     * 
     * @param data $dataArray 
     */
    public function publishGuestBook($dataArray) {
        if ($dataArray) {
            $this->database->exec('UPDATE guestBook SET guestBookPublished=? WHERE id=?', $dataArray[1], $dataArray[0]);            
        } else {            
            throw new \Nette\Application\ToolException('Unable to publish guestBook.
                    Wrong input. method: publishGuestBook($dataArray)', 500);
        }
    }    

    /**
     * Save new question.
     * 
     * @param data $dataArray 
     */
    public function saveQuestion($dataArray) {
       if ($dataArray) {
            $dateTime = date("Y-m-d H:i:s");
            $this->database->exec('INSERT INTO guestBook_q', array(
                    'name' => $dataArray[0],
                    'question' => $dataArray[1],
                    'dateTime' => $dateTime,
                    'idusers' => $dataArray[2]
                )                     
            );                    
        } else {            
            throw new \Nette\Application\ToolException('Unable to save users question.
                    Wrong input. method: saveQuestion($dataArray)', 500);            
        }
    }
         
    
//    public function getQuestionsAndAnswers($itemsPerPage, $offset) {
//        $data = $this->database->query("SELECT guestbook_q.idguestbook_q, guestbook_a.idguestbook_a FROM 
//            guestBook_q LEFT JOIN guestBook_a USING (idguestbook_q) ORDER BY idguestbook_q DESC LIMIT $itemsPerPage OFFSET $offset")->fetchAll();
//        return $data;
//    }
//    
 
}