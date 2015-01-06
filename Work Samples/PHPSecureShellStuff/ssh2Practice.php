<?php

$path = '/home/richard/Downloads';
set_include_path(get_include_path() . ":" . $path);

include('Net/SSH2.php');

function conAuth($con,$usr,$passwd)
    {
        if(!ssh2_auth_password($con,$usr, $passwd)) 
                            {
                                    echo "</br>fail: unable to authenticate</br>";
                            } 
                        else 
                            {
                                    // allright, we're in!
                                   echo "</br>okay:  logged in....";
                            }
                                        
    }

function ssh2Execute($con, $comnd, $usr, $passwd)
{
                echo "</br></br>METHOD 1 -- EXECUTE A COMMAND</br>";
              

                if(!($con))
                    {
                        //echo "This= ".$con;
                        echo "</br>fail:  unable to establish connection or connection value is null</br>";
                    }
               else 
                    {
                        // try to authenticate with username-richard, password-syp3rt
                       conAuth($con,$usr,$passwd);
                                        
                            // execute a command
                        if($comnd != "")
                            {
                                if(!($stream = ssh2_exec($con, $comnd)))
                                    {
                                        echo "</br>fail:  unable to execute command</br>";
                                    }
                                else 
                                    {
                                        // collect returning data from command
                                        stream_set_blocking($stream, false);
                                        $data = '';
                                                                      
                                        while ($buf = fread($stream, 4096))
                                            {
                                                $data .= $buf;
                                            }
                                }
                            }
                        else
                            echo "</br>No commands were passed in";
                    }
                                
                                
                               
                    // To Prevent Buffer from Running Dry
                    $time_start  = time();
                    while (true)
                          {
                                $data .= fread($stream, 4096);
                                if (strpos($data, "__COMMAND_FINISHED__") !== false)
                                {
                                    echo "</br>okay: command finished</br>";
                                    break;
                                }
                                if ((time() - $time_start) > 10) 
                                {
                                    echo "</br>fail:  timeout of 10 seconds has been reached</br>";
                                    break;
                                }
                                
                        };
                               
                                
                        echo "</br>This is Updated File";
                        echo "</br>Returned from Server: ".$data;
                              
                                
            
                      
                      
                        fclose($stream); 
                        return;
                
      
      }

 /*     
function ssh2ShellEx($con, $comnd, $usr, $passwd)
{
// If Interactive Shell is Needed with the Router
//***************************************************************************


    if(!($con)) 
        {
            echo "fail:unable to establish connection\n";
        }
   else 
        {
            // try to authenticate with username- richard, password - syp3rt
            if(!ssh2_auth_password($con, $usr,$passwd))
                {
                    echo "fail:unable to authenticate\n";
                } 
            else 
                {
                        // alright, we're in!
                        echo "okay:  logged in...\n");
                        
                        // create shell_exec
                        if (!($shell = ssh2_shell($con, 'vt102', null, 80, 40, SSH2_TERM_UNIT_CHARS)))
                            {
                                echo "fail: unable to establish shell\n";
                            } 
                        else 
                            {
                                stream_set_blocking($shell, true);
                                // send a command
                                fwrite($shell, $comnd);
                                sleep(1);
                            
                                // & collect returning data
                                $data = "";
                                while ($buf = fread($shell, 4096)) 
                                    {
                                        $data .= $buf;
                                    };
                                    
                                echo "Returned from Server: ".$data;
                                fclose($shell);
                            } 
                }  
        }
                    
  }         
     
*/
    
     function ssh2FileSend($con, $usr, $passwd)
        {
               echo "</br></br>METHOD 2 -- SENDING A FILE  Over SSH";
               if(!function_exists("ssh2_scp_send")) die("Function ssh2_scp_send does not exist");
               else 
                   echo "</br>ssh2_scp_send function exists";
                   
               conAuth($con,$usr,$passwd);    
               if(!($fileSend = ssh2_scp_send($con,'/home/richard/source.dat', 'dest.dat', 0644)))
                    echo "</br>File was not sent.";
                else
                    echo "</br>Apparently file was sent";
                                      //  echo "</br>Apparently file was sent, check for /tmp/dest.dat";
               return;
                                      
        }
                    

    
    
   function serverConnect($remoteServer)
       {
           
            if(!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
                // log in at server (localhost for now)
            
            if(!($con = ssh2_connect($remoteServer,22))) die("server is not available"); 
            else 
                {
                    echo "Server connection is made.";
                    return $con;
                }
        }

    

  function fileEncryptionDES($fileToEncrypt, $keyString)
	{
	  require_once ('Crypt/DES.php');
	  $fileString = file_get_contents($fileToEncrypt);
	  echo "</br></br>File Contents: ".$fileString;
	  $des = new Crypt_DES();
	  echo "</br></br>METHOD 3 - Encrypt and Then Decrypt a File";  
	 
	  $des->setKey($keyString);

	  echo "</br>Encrypted File Content:</br>  ".$des->encrypt($fileString);

	  echo "</br></br>Decrypted File Content:</br> ".$des->decrypt($des->encrypt($fileString));
         }


   function fileEncryptionRSA($fileToEncrypt)
	{			    
		 require_once ('Crypt/RSA.php');
		 if(!class_exists("Crypt_RSA")) die("</br></br>The class does not exist");
		 else
		    {
			    $rsa = new Crypt_RSA();
			    extract($rsa->createKey());
		   
			    $fileString = file_get_contents($fileToEncrypt);
			    echo "</br></br>File Contents:</br> ".$fileString;
					   
		            echo "</br></br>Private Key:</br>  ".$privatekey;
			    echo "</br></br>Public Key:</br>  ".$publickey;


			    $rsa->loadKey($publickey);
			    $ciphertext = $rsa->encrypt($fileString);

			    echo "</br></br>Encrypted File Contents:</br> ".$ciphertext;

			    $rsa->loadKey($privatekey);
			    
			    echo "</br></br>Decrypted File Contents:</br> ".$rsa->decrypt($ciphertext);
		    }
	}


  function rsaVerifySignature($fileToEncrypt)
	{
		
	    $rsa = new Crypt_RSA();
	    extract($rsa->createKey());
   
	    $fileString = file_get_contents($fileToEncrypt);
	    echo "</br></br>File Contents:</br> ".$fileString;
		
	    $plaintext = 'just Checking';

	    $rsa->loadKey($privatekey);
	    $signature = $rsa->sign($plaintext);

	    $rsa->loadKey($publickey);
	    echo $rsa->verify($plaintext, $signature) ? '</br></br>Signature Verified' : '</br></br>Signature Unverified';

        }

    $usr = "richard";
    $passwd = "syp3rt";
    $comnd = 'ls ; echo "__COMMAND_FINISHED__"';
    $remoteServer = "192.168.2.134";
    $remoteServer2 = '127.0.0.1';
    

    // Connect to Remote Server
    $con = serverConnect($remoteServer2);
    ssh2Execute($con, $comnd,$usr,$passwd);
   
    // Send File to Remote Server
    $con = serverConnect($remoteServer2);
    ssh2FileSend($con,$usr,$passwd);
    
    // Encrypt File on Remote Server
   $fileToEncrypt = '/home/richard/source.dat';  
   $keyString = "fgsdfhdfg";
   fileEncryptionDES($fileToEncrypt, $keyString);
   
   
   //Private/Public Key Encryption
   echo "</br></br>METHOD - 4 - Private/Public Key Encryption";
   fileEncryptionRSA($fileToEncrypt);

   //Private/Public Key Encryption with Signature
   echo "</br></br>METHOD - 5 - Signature Verification";
   rsaVerifySignature($fileToEncrypt);
   

    
  

  
    
       
   
        
?>
