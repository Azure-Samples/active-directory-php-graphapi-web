<?php

// A class that provides authortization token for apps that need to access Azure Active Directory Graph Service.
class AuthorizationHelperForAADGraphService
{
    // Post the token generated from the symettric key and other information to STS URL and construct the authentication header
    public static function getAuthenticationHeader($appTenantDomainName, $appPrincipalId, $password){
        // Password
        $clientSecret = urlencode(settings::$password);
        // Information about the resource we need access for which in this case is graph.
        $graphId = 'https://graph.windows.net';
        $protectedResourceHostName = 'graph.windows.net';
        $graphPrincipalId = urlencode($graphId);
        // Information about the app
        $clientPrincipalId = urlencode($appPrincipalId);
        
        // Construct the body for the STS request
        $authenticationRequestBody = 'grant_type=client_credentials&client_secret='.$clientSecret
                  .'&'.'resource='.$graphPrincipalId.'&'.'client_id='.$clientPrincipalId;
        
        //Using curl to post the information to STS and get back the authentication response    
        $ch = curl_init();
        // set url 
        $stsUrl = 'https://login.windows.net/'.$appTenantDomainName.'/oauth2/token?api-version=1.0';        
        curl_setopt($ch, CURLOPT_URL, $stsUrl); 
        // Get the response back as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        // Mark as Post request
        curl_setopt($ch, CURLOPT_POST, 1);
        // Set the parameters for the request
        curl_setopt($ch, CURLOPT_POSTFIELDS,  $authenticationRequestBody);
        
        // By default, HTTPS does not work with curl.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // read the output from the post request
        $output = curl_exec($ch);         
        // close curl resource to free up system resources
        curl_close($ch);      
        // decode the response from sts using json decoder
        $tokenOutput = json_decode($output);
        return 'Authorization:' . $tokenOutput->{'token_type'}.' '.$tokenOutput->{'access_token'};
    }
}

?>
