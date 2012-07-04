<?php

use \BasePresenter as BasePresenter;

/**
 * CronJobRunner2 presenter - DB backup.
 *
 * @author     Zippo
 * @package    MainModule
 */
class CronJobRunner2Presenter extends BasePresenter
{        
    public function actionBackupDB()
    {
        if (!$this->getContext()->params['consoleMode']) {
            $this->redirect('Default:default');
        }

        // backup DB
        $host = $this->context->container->parameters['database']['host'];
        $user = $this->context->container->parameters['database']['user'];
        $pass = $this->context->container->parameters['database']['password'];
        $dbname = $this->context->container->parameters['database']['dbname'];
        $tables = '*';
        $mudrwebDBBackupFileName = null;
        $mudrwebDBBackupFileName = $this->extraMethods->backup_tables($host, $user, $pass, $dbname, $tables);    
        
        // add created *.sql file to zip archive
        if ($mudrwebDBBackupFileName) {
            $creationDateTime = date('d-m-Y_H:i:s');
            $archiveName = 'mudrwebDBBackup_' . $creationDateTime;            
            $this->extraMethods->addFileToZipArchive('/CORE/mudrweb.cz/www/log_cron/', $archiveName, '/CORE/mudrweb.cz/www/log_cron/', $mudrwebDBBackupFileName);            
            
            // send zip archive to specified email address
            $mail = new \Nette\Mail\Message;
            $mail->setFrom('MUDRweb.cz - DB backup <admin@mudrweb.cz>')
                    ->addTo('mudrweb@gmail.com')
                    ->setSubject('Záloha DB')
                    ->setBody("Viz priloha...");                    
            $mail->addAttachment('/CORE/mudrweb.cz/www/log_cron/' . $archiveName . '.zip');
            $mail->send();      
            
            $this->logger->logMessage(ILogger::INFO, '>>> DB backup - ' . $creationDateTime . ' - successfuly finished. [cron]');            
        }        
        
        $this->terminate();
    }    
}
