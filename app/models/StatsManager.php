<?php

/**
 * StatsManager base class.
 */
class StatsManager extends Nette\Object {
    
    /**
     * Database handling.     
     */
    public $database;
    public function __construct(Nette\Database\Connection $database) {
        $this->database = $database;
    }
    
    /**
     * Save datetime of password resend attempt for current ip address.
     * 
     * @param varchar $subdomain
     * @param varchar $ip 
     */
    public function savePassResendAttempt($subdomain, $ip) {
        if (is_string($subdomain) && (is_string($ip))) {
            $attemptDateTime = date("Y-m-d H:i:s");
            $this->database->exec('INSERT INTO passResendAttempts', array(
                    'subdomain' => $subdomain,
                    'ip' => $ip, 
                    'dateTime' => $attemptDateTime
                )                     
            );                    
        } else {            
            throw new \Nette\Application\ToolException('Unable to save pass resend attempt.
                    Wrong input. method: savePassResendAttempt($subodmain)', 500);            
        }
    }
}