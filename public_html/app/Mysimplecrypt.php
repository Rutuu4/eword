<?php 
    
    /**
     * 
     */
    class Mysimplecrypt 
    {
        //e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855
        function token($string, $action = 'encrypt_1' )
        {
            //$encrypt_secret_key     = "ktxn975*";
            //$encrypt_secret_iv      = "itxn864*";

            $encrypt_secret_key     = "";
            $encrypt_secret_iv      = "";

            $output = false;
            $encrypt_method = "AES-256-CBC";
            $key = hash( 'sha256', $encrypt_secret_key );
            
            $iv = substr( hash( 'sha256', $encrypt_secret_iv ), 0, 16 ); 
            
            if( $action == 'encrypt_1' ) {
                $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
            }
            else if( $action == 'decrypt_1' ){
                $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
            }
            return $output;
        }

        


    }
?>