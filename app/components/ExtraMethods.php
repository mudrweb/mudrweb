<?php

/**
 * Base class for Extra Methods - generatePass/Salt, computeHash, 
 * subdomain handling, route name unifier.
 *
 * @author     Zippo
 * @package    none
 */
class ExtraMethods extends Nette\Object {    
    /**
     * Computes salted password hash for user.
     * 
     * @param string $password
     * @param string $salt
     * @return hashed password
     */    
    public function calculateHash($password, $salt) {
        return sha1($password . str_repeat($salt, 10));
    }

    /**
     * Generates password.
     * 
     * @return password 
     */
    public function generatePassword() {
        $args = array(
        'length'                =>   10,
        'alpha_upper_include'   =>   TRUE,
        'alpha_lower_include'   =>   TRUE,
        'number_include'        =>   TRUE,
        'symbol_include'        =>   TRUE,
        );
        $data = new chip_password_generator($args);
        
        $password = $data->get_password();
        return $password;
    }    
    
    /**
     * Generates salt.
     * 
     * @return salt 
     */
    public function generateSalt() {
        $args = array(
        'length'                =>   16,
        'alpha_upper_include'   =>   TRUE,
        'alpha_lower_include'   =>   TRUE,
        'number_include'        =>   TRUE,
        'symbol_include'        =>   TRUE,
        );
        $data = new chip_password_generator($args);
        
        $salt = $data->get_password();
        return $salt;
    }   
    
    /**
     * Generates sponsoring number.
     * 
     * @return password 
     */
    public function generateSponsoringNumber() {
        $args = array(
        'length'                =>   4,
        'alpha_upper_include'   =>   FALSE,
        'alpha_lower_include'   =>   FALSE,
        'number_include'        =>   TRUE,
        'symbol_include'        =>   FALSE,
        );
        $data = new chip_password_generator($args);
        
        $password = $data->get_password();
        return $password;
    }       

    /**
     * Generates dummy string (for example for temp password and 
     * FTP password builder)
     * 
     * @param int $numberOfChars
     * @return type 
     */
    public function generateDummyString($numberOfChars) {
        $args = array(
        'length'                =>   $numberOfChars,
        'alpha_upper_include'   =>   TRUE,
        'alpha_lower_include'   =>   TRUE,
        'number_include'        =>   TRUE,
        'symbol_include'        =>   TRUE,
        );
        $data = new chip_password_generator($args);
        
        $dummyString = $data->get_password();
        return $dummyString;
    }           
    
    /**
     * Copy current file to real subdomain (in ftp root):
     * - .htaccess, robots.txt from user_data_realSub folder      
     * - sitemap.xml, index.php from subodmain folder
     * 
     * @param string $subdomain
     * @param string $filename 
     */
    public function copyFileToRealSubdomain($subdomain, $filename) {
        $wwwDir = Nette\Environment::getContext()->params['wwwDir'];      
        
        //data
        $paths = "";
        $ftp_server = "eam.hukot.cz";
        $ftp_user_name = "mudrweb.cz";
        $ftp_user_pass = "p40PL7yaX";

        set_time_limit(80);         
        // set up a connection to ftp server
        $conn_id = ftp_connect($ftp_server);
        // get file to be copied
        if ($filename == 'sitemap.xml') {
            $file = $wwwDir . '/' .$subdomain. '/' . $filename;
        } elseif ($filename == 'index.php') {
            $file = $wwwDir . '/' . $subdomain . '/realSubdomain.' . $filename;
        } else {
            $file = $wwwDir . '/user_data_realSub/' . $filename;
        }
        // login with username and password
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

        // check connection and login result
        if ((!$conn_id) || (!$login_result)) {
             \Nette\Diagnostics\Debugger::log('FTP connection has encountered an error!'); 
             ftp_quit($conn_id);
             exit;
         } else {
             \Nette\Diagnostics\Debugger::log('FTP connection was successful - copy file!');                           
         }           
                
        ftp_put($conn_id, '/' . $subdomain . '/www/' . $filename, $file, FTP_BINARY);        
        
        \Nette\Diagnostics\Debugger::log('File: ' . $file . ' was successfuly copied to: /' . $subdomain . '/www/' . $filename);
        
        ftp_close($conn_id);
    }
    
    /**
     * Check real subdomain existence.
     * 
     * @param string $subdomain
     * @return bool status
     */
    public function checkRealSubdomainExistence($subdomain) {
      //data
      $paths = "";      
      $ftp_server = "eam.hukot.cz";
      $ftp_user_name = "mudrweb.cz";
      $ftp_user_pass = "p40PL7yaX";            
      
      set_time_limit(80);      
      // set up a connection to ftp server
      $conn_id = ftp_connect($ftp_server);
      // login with username and password
      $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
      
      // check connection and login result
      if ((!$conn_id) || (!$login_result)) {
             \Nette\Diagnostics\Debugger::log('FTP connection has encountered an error!'); 
             ftp_quit($conn_id);
             exit;
         } else {
             \Nette\Diagnostics\Debugger::log('FTP connection was successful - real subdomain existence check!');                           
         }    
      
//      // turn passive mode on
//      $passive = ftp_pasv($conn_id, true);
      
      $fileList = ftp_nlist($conn_id, ".");
      
      if (in_array($subdomain, $fileList)) {
          \Nette\Diagnostics\Debugger::log('Real subdomain: ' . $subdomain . ' exists.');
          return TRUE;
      } else {
          \Nette\Diagnostics\Debugger::log('Real subdomain: ' . $subdomain . ' does not exist.');          
          return FALSE;
      }      
      
      ftp_close($conn_id);        
    }    
    
    /**
     * Delete subdomain.
     * 
     * @param string $subdomain
     * @return bool status
     */
    public function deleteSubdomain($subdomain) {
      //data
      $paths = "";      
      $ftp_server = "eam.hukot.cz";
      $ftp_user_name = "mudrweb.cz";
      $ftp_user_pass = "p40PL7yaX";            
      
      set_time_limit(80);      
      // set up a connection to ftp server
      $conn_id = ftp_connect($ftp_server);
      // login with username and password
      $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
      
      // check connection and login result
      if ((!$conn_id) || (!$login_result)) {
             \Nette\Diagnostics\Debugger::log('FTP connection has encountered an error!'); 
             ftp_quit($conn_id);
             exit;
         } else {
             \Nette\Diagnostics\Debugger::log('FTP connection was successful - delete subdomain!');                           
      }          
            
      $fileList = ftp_nlist($conn_id, "/www");                 
      
      if (in_array('/www/' . $subdomain, $fileList)) {            
          if ($this->deleteDirRecursive($conn_id, '/www/' . $subdomain)) {
              \Nette\Diagnostics\Debugger::log('Subdomain: ' . $subdomain . ' was successfuly deleted.');
              return TRUE;
          } else {
              \Nette\Diagnostics\Debugger::log('Unable to delete Subdomain: ' . $subdomain);                                               
              return FALSE;
          }
      }
         
      ftp_close($conn_id);        
    }       
    
    /**
     * Create subdomain in /www folder.
     * 
     * @param string $subdomain
     * @return bool status 
     */
    public function createSubdomain($subdomain) {
      //data
      $paths = "";      
      $ftp_server = "eam.hukot.cz";
      $ftp_user_name = "mudrweb.cz";
      $ftp_user_pass = "p40PL7yaX";            
      
      set_time_limit(80);      
      // set up a connection to ftp server
      $conn_id = ftp_connect($ftp_server);
      // login with username and password
      $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
      
      // check connection and login result
      if ((!$conn_id) || (!$login_result)) {
             \Nette\Diagnostics\Debugger::log('FTP connection has encountered an error!'); 
             ftp_quit($conn_id);
             exit;
         } else {
             \Nette\Diagnostics\Debugger::log('FTP connection was successful - create subdomain!');                           
         }          
      
      if (ftp_mkdir($conn_id, '/www/' . $subdomain)) {
          \Nette\Diagnostics\Debugger::log('Subdomain: ' . $subdomain . ' was successfuly created.');                           
          return TRUE;
      } else {
          \Nette\Diagnostics\Debugger::log('Unable to create Subdomain: ' . $subdomain);                                     
          return FALSE;
      }
         
      ftp_close($conn_id);        
    }      
    
    /**
     * Delete the provided directory and all its contents from the FTP-server.
     *
     * @param string $path            Path to the directory on the FTP-server relative to the current working directory
     */
    function deleteDirRecursive($resource, $path) {
        $result_message = "";
        $list = ftp_nlist($resource, $path);
        if (empty($list)) {
            $list = $this->RawlistToNlist(ftp_rawlist($resource, $path), $path . ( substr($path, strlen($path) - 1, 1) == "/" ? "" : "/" ));
        }
        if ($list[0] != $path) {
            $path .= ( substr($path, strlen($path) - 1, 1) == "/" ? "" : "/" );
            foreach ($list as $item) {
                if ($item != $path . ".." && $item != $path . ".") {
                    $result_message .= $this->deleteDirRecursive($resource, $item);
                }
            }
            if (ftp_rmdir($resource, $path)) {
                $result_message .= "Successfully deleted $path <br />\n";
            } else {
                $result_message .= "There was a problem while deleting $path <br />\n";
            }
        } else {
            if (ftp_delete($resource, $path)) {
                $result_message .= "Successfully deleted $path <br />\n";
            } else {
                $result_message .= "There was a problem while deleting $path <br />\n";
            }
        }
        return $result_message;
    }

    /**
     * Convert a result from ftp_rawlist() to a result of ftp_nlist().
     *
     * @param array $rawlist        Result from ftp_rawlist();
     * @param string $path          Path to the directory on the FTP-server relative to the current working directory
     * @return array                An array with the paths of the files in the directory
     */
    function RawlistToNlist($rawlist, $path) {
        $array = array();
        foreach ($rawlist as $item) {
            $filename = trim(substr($item, 55, strlen($item) - 55));
            if ($filename != "." || $filename != "..") {
                $array[] = $path . $filename;
            }
        }
        return $array;
    }
    
    /**
     * Archive subdomain -> rename it to subdomain_archivationToken
     * 
     * @param string $subdomain
     * @return bool status
     */
    public function archiveSubdomain($subdomain) {
      //data
      $paths = "";      
      $ftp_server = "eam.hukot.cz";
      $ftp_user_name = "mudrweb.cz";
      $ftp_user_pass = "p40PL7yaX";            
      
      set_time_limit(80);      
      // set up a connection to ftp server
      $conn_id = ftp_connect($ftp_server);
      // login with username and password
      $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
      
      // check connection and login result
      if ((!$conn_id) || (!$login_result)) {
             \Nette\Diagnostics\Debugger::log('FTP connection has encountered an error!'); 
             ftp_quit($conn_id);
             exit;
         } else {
             \Nette\Diagnostics\Debugger::log('FTP connection was successful - archive subdomain!');                           
      }          
            
      $fileList = ftp_nlist($conn_id, "/www");                 
      
      if (in_array('/www/' . $subdomain, $fileList)) {            
          $archivationToken = $this->generatePassword();
          if (ftp_rename($conn_id, '/www/' . $subdomain, '/www/' . $subdomain . '_' . $archivationToken)) {
              \Nette\Diagnostics\Debugger::log('Subdomain: ' . $subdomain . ' was successfuly archived.');
              return TRUE;
          } else {
              \Nette\Diagnostics\Debugger::log('Unable to archive Subdomain: ' . $subdomain);                                               
              return FALSE;
          }
      }
         
      ftp_close($conn_id);        
    }
    
    /**
     * Delete .thumbs dir for all users.
     * 
     * @param string $subdomain
     * @return bool status
     */
    public function deleteThumbs($subdomain) {
        $wwwDir = WWW_DIR;        

        try {
            if (is_dir($wwwDir . '/user_uploads/' . $subdomain . '/.thumbs')) {
                $this->rrmdir($wwwDir . '/user_uploads/' . $subdomain . '/.thumbs');
                \Nette\Diagnostics\Debugger::log('Thumbs dir for subdomain: ' . $subdomain . ' was successfuly deleted.');
                // ok
                return 1;            
            } else {
                \Nette\Diagnostics\Debugger::log('Thumbs dir for subdomain: ' . $subdomain . ' was not found.');
                // warning - thumbs dir not found (already deleted or missing)
                return -1;
            }                       
        } catch(Exception $e) {
            \Nette\Diagnostics\Debugger::log('Unable to delete thumbs dir for subdomain: ' . $subdomain);                                               
            // error
            return 0;                            
        }
    }
    
    /**
     * Remove dir and its content - recursion.
     * 
     * @param string $dir 
     */
    function rrmdir($dir) {
        $fp = opendir($dir);
        if ($fp) {
            while ($f = readdir($fp)) {
                $file = $dir . "/" . $f;
                if ($f == "." || $f == "..") {
                    continue;
                }
                else if (is_dir($file)) {
                    $this->rrmdir($file);
                }
                else {
                    unlink($file);
                }
            }
            closedir($fp);
            rmdir($dir);
        }
    }
       
    /**
     * Backup the db OR just a table.
     * 
     * @param type $host
     * @param type $user
     * @param type $pass
     * @param type $name
     * @param type $tables 
     */
    public function backup_tables($host, $user, $pass, $name, $tables = '*') {

        $link = mysql_connect($host, $user, $pass);
        mysql_select_db($name, $link);

        //get all of the tables
        if ($tables == '*') {
            $tables = array();
            $result = mysql_query('SHOW TABLES');
            while ($row = mysql_fetch_row($result)) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }

        // find 'users' table and swap it with the last table (foreign keys
        // and dropping workaround as users table needs to be dropped as very
        // last one)
        $usersTableIndexInArray = array_search('users', $tables);
        $indexOfLastTableInArray = count($tables) - 1;
        $tmp = $tables[$usersTableIndexInArray];
        $tables[$usersTableIndexInArray] = $tables[$indexOfLastTableInArray];
        $tables[$indexOfLastTableInArray] = $tmp;

        $return = null;
        //cycle through
        foreach ($tables as $table) {
            // set coding to avoid diacritics problem
            mysql_query("SET character_set_client=utf8");
            mysql_query("SET character_set_connection=utf8");
            mysql_query("SET character_set_results=utf8");
            $result = mysql_query('SELECT * FROM ' . $table);
            $num_fields = mysql_num_fields($result);

            $return.= 'DROP TABLE IF EXISTS mudr.' . $table . ';';

            $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE mudr.' . $table));
            // add table existence check
            $row2[1] = str_replace("CREATE TABLE", "CREATE TABLE IF NOT EXISTS", $row2[1]);

            // find last ','
            $start = strrpos($row2[1], ",");
            // find 'CONSTRAINT' string to identify part to be removed
            $constraintStrinFound = strrpos($row2[1], "CONSTRAINT");
            // if 'CONSTRAINT' string was found -> remove foreign keys 
            // conditions
            if ($start > 0 && $constraintStrinFound > 0) {
                $end = strpos($row2[1], "ENGINE=InnoDB");
                $stringToDelete = substr($row2[1], $start, $end - $start - 3);
                $row2[1] = str_replace($stringToDelete, "", $row2[1]);
            }
            $return.= "\n\n" . $row2[1] . ";\n\n";

            for ($i = 0; $i < $num_fields; $i++) {
                while ($row = mysql_fetch_row($result)) {
                    $return.= 'INSERT INTO ' . $table . ' VALUES(';
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = str_replace("\n", "\\n", $row[$j]);
                        if (isset($row[$j])) {
                            $return.= '"' . $row[$j] . '"';
                        } else {
                            $return.= '""';
                        }
                        if ($j < ($num_fields - 1)) {
                            $return.= ',';
                        }
                    }
                    $return.= ");\n";
                }
            }
            $return.="\n\n\n";
        }

        // add foreign keys conditions at the end
        $return.= "
        --
        -- `guestBook`
        --
        ALTER TABLE `guestBook`
        ADD CONSTRAINT `fk_questBook_users1` FOREIGN KEY (`idusers`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

        --
        -- `guestBook_a`
        --
        ALTER TABLE `guestBook_a`
        ADD CONSTRAINT `fk_guestBook_a_guestBook_q1` FOREIGN KEY (`idguestBook_q`) REFERENCES `guestBook_q` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

        --
        -- `menuItems`
        --
        ALTER TABLE `menuItems`
        ADD CONSTRAINT `fk_menuItem_users1` FOREIGN KEY (`idusers`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

        --
        -- `users_data`
        --
        ALTER TABLE `users_data`
        ADD CONSTRAINT `fk_users_data_users1` FOREIGN KEY (`idusers`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

        --
        -- `users_websiteData`
        --
        ALTER TABLE `users_websiteData`
        ADD CONSTRAINT `fk_users_websiteData_users1` FOREIGN KEY (`idusers`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;            
        \n\n\n        
        ";

        //save file
        $exportedDataFilename = "mudrwebDBBackup-" . time() . "-" . (md5(implode(',', $tables))) . ".sql";
        $handle = fopen('/CORE/mudrweb.cz/www/log_cron/' . $exportedDataFilename, 'w+');
        fwrite($handle, $return);
        fclose($handle);
        
        return $exportedDataFilename;
    }
    
    /**
     * Add file with defined name and path to new zip archive with defined name 
     * and location
     * 
     * @param type $pathToStoreArchive
     * @param string $archiveName
     * @param type $pathToFileToBeAddedToArchive
     * @param type $fileToBeAddedToArchive 
     */
    public function addFileToZipArchive($pathToStoreArchive, $archiveName, $pathToFileToBeAddedToArchive, $fileToBeAddedToArchive) {
        // create zip package
        $zip = new ZipArchive();
        
        $archiveName = $pathToStoreArchive . $archiveName . ".zip";  
        if ($zip->open($archiveName, \ZipArchive::CREATE)!==TRUE) {
            exit("cannot open <$archiveName>\n");
        }        
        $zip->addFile($pathToFileToBeAddedToArchive . $fileToBeAddedToArchive, $fileToBeAddedToArchive);
        $zip->close();                  
    }     
}    
