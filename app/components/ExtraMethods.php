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
     * Copy current file to real subdomain (in ftp root):
     * - .htaccess, robots.txt from user_data_realSub folder      
     * - sitemap.xml, index.php from subodmain folder
     * 
     * @param string $subdomain
     * @param string $filename 
     */
    public function copyFileToRealSubdomain($subdomain, $filename) {
        $wwwDir = $this->getContext()->params['wwwDir'];

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
             ftp_quit($Connect);
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
             ftp_quit($Connect);
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
             ftp_quit($Connect);
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
             ftp_quit($Connect);
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
}    