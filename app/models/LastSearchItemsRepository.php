<?php

namespace MudrwebDB;
use Nette;

/**
 * SearchItemsRepository base class.
 */
class LastSearchItemsRepository extends Repository
{
    /**
     * Get last search items.
     * 
     * @return Nette\Database\Table\ActiveRow
     */
    public function getLastSearchItems() {
        return $this->getTable()->fetch();
    }    
    
    /**
     * Update last search items with lastest data.
     * 
     * @param text $lastSearchDataString 
     */
    public function updateLastSearchItems($lastSearchDataString) {
        $this->findBy(array('id' => 1))->update(array('searchData' => $lastSearchDataString));
    }
}
