<?php

namespace MudrwebDB;
use Nette;

/**
 * Abstract repository.
 */
abstract class Repository extends Nette\Object {

    /** @var Nette\Database\Connection */
    protected $connection;

    public function __construct(Nette\Database\Connection $db) {
        $this->connection = $db;
    }

    /**
     * Get object representing DB table.
     * 
     * @return Nette\Database\Table\Selection
     */
    protected function getTable() {
        // name of table is derived from the name of class
        preg_match('#(\w+)Repository$#', get_class($this), $m);
        return $this->connection->table(lcfirst($m[1]));
    }

    /**
     * Get all rows of current table.
     * 
     * @return Nette\Database\Table\Selection
     */
    public function findAll() {
        return $this->getTable();
    }

    /**
     * Return rows according to filter, for example array('name' => 'John').
     * 
     * @return Nette\Database\Table\Selection
     */
    public function findBy(array $by) {
        return $this->getTable()->where($by);
    }
}
