<?php
    //Require other files.
    require_once 'Settings.php';
    require_once 'AuthorizationHelperForGraph.php';   

    class GraphServiceAccessHelper
    {
        // Constructs a Http GET request to a feed passed in as paremeter.
        // Returns the json decoded respone as the objects that were recieved in feed.
        public static function getFeed($feedName){
            // initiaze curl which is used to make the http request.
            $ch = curl_init();
            // Add authorization and other headers. Also set some common settings.
            self::AddRequiredHeadersAndSettings($ch);
            // set url 
            $feedURL = "https://graph.windows.net/".Settings::$appTenantDomainName."/".$feedName;
            $feedURL = $feedURL."?".Settings::$apiVersion;
            curl_setopt($ch, CURLOPT_URL, $feedURL);
            //Enable fiddler to capture request
            //curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');

            // $output contains the output string
            $output = curl_exec($ch);
            // close curl resource to free up system resources 
            curl_close($ch);      
            $jsonOutput = json_decode($output);
            // There is a field for odata metadata that we ignore and just consume the value
            return $jsonOutput->{'value'};
        }

        // Constructs a Http GET request to a linked feed based on the source entry and navigation property name.        
        public static function getLinkedFeed($sourceFeedName, $sourceEntryId, $navigationPropertyName){
            //initiaze curl which is used to make the http request
            $ch = curl_init();
            // Add authorization and other headers. Also set some common settings.
            self::AddRequiredHeadersAndSettings($ch);
            // create URL to the linked feed by using the navigation property name and the key value for the source entry.
            $feedURL = "https://graph.windows.net/".Settings::$appTenantDomainName."/".$sourceFeedName .'(\''. $sourceEntryId . '\')' .'/'.$navigationPropertyName;
            $feedURL = $feedURL."?".Settings::$apiVersion;
            curl_setopt($ch, CURLOPT_URL, $feedURL);
            //Enable fiddler to capture request
            //curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');

            // $output contains the output string 
            $output = curl_exec($ch);
            // close curl resource to free up system resources 
            curl_close($ch);      
            $jsonOutput = json_decode($output);
            // There is a field for odata metadata that we ignore and just consume the value
            return $jsonOutput->{'value'};
        }


        // Constructs a Http GET request to a feed passed in as paremeter and uses the field information as the filter clause.
        // Returns the json decoded respone as the objects that were recieved in feed.        
        public static function getFeedWithFilterClause($feedName, $fieldName, $fieldValue){
            //initiaze curl which is used to make the http request
            $ch = curl_init();
            // Add authorization and other headers. Also set some common settings.
            self::AddRequiredHeadersAndSettings($ch);
            // set url based on the filter clause. This uses the standard OData syntax to create the filter clause.
            $feedURL = "https://graph.windows.net/".Settings::$appTenantDomainName."/".$feedName .'?$filter='.$fieldName.urlencode(' eq ')
                       .'(\''.urlencode($fieldValue).'\')';
            $feedURL = $feedURL."&api-version=2013-04-05";
            curl_setopt($ch, CURLOPT_URL, $feedURL);
            //Enable fiddler to capture request
            //curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');

            // $output contains the output string 
            $output = curl_exec($ch);
            // close curl resource to free up system resources 
            curl_close($ch);      
            $jsonOutput = json_decode($output);
            // There is a field for odata metadata that we ignore and just consume the value
            return $jsonOutput->{'value'};
        }

        // Constructs a Http GET request to fetch an entry based on the feed name and the key value passed in.
        // Returns the json decoded respone as the objects that were recieved in feed.
        public static function getEntry($feedName, $keyValue){
            // initiaze curl which is used to make the http request
            $ch = curl_init();
            // Add authorization and other headers. Also set some common settings.
            self::AddRequiredHeadersAndSettings($ch);
            // Create url for the entry based on the feedname and the key value
            $feedURL = 'https://graph.windows.net/'.Settings::$appTenantDomainName.'/'.$feedName.'(\''. $keyValue .'\')';
            $feedURL = $feedURL."?".Settings::$apiVersion;
            curl_setopt($ch, CURLOPT_URL, $feedURL);             
            //Enable fiddler to capture request
            //curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');

            // $output contains the output string 
            $output = curl_exec($ch);
            // close curl resource to free up system resources 
            curl_close($ch);      
            $jsonOutput = json_decode($output);
            return $jsonOutput;
        }

        // Constructs a HTTP POST request for creating and adding an entry 
        // to a feed based on the feed name and data passed in.
        public static function addEntryToFeed($feedName, $entry){
            //initiaze curl which is used to make the http request
            $ch = curl_init();
            // Add authorization and other headers. Also set some common settings.
            self::AddRequiredHeadersAndSettings($ch);
            // set url
            $feedURL = "https://graph.windows.net/".Settings::$appTenantDomainName.'/'.$feedName;
            $feedURL = $feedURL."?".Settings::$apiVersion;
            curl_setopt($ch, CURLOPT_URL, $feedURL);
            // Mark as Post request
            curl_setopt($ch, CURLOPT_POST, 1);            
            $data = json_encode($entry);
            // Set the data for the post request
            curl_setopt($ch, CURLOPT_POSTFIELDS,  $data);
            //Enable fiddler to capture request
            //curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');
            // read the output from the post request
            $output = curl_exec($ch);
            // close curl resource to free up system resources
            curl_close($ch);      
            // decode the response json decoder
            $createdEntry = json_decode($output);
            return $createdEntry;
        }

        // Constructs a HTTP POST request for creating and adding a link
        // between two existing entries using the source and target URLs and the navigation property name.
        public static function addLinkForEntries($sourceEntryUrl, $targetEntryUrl, $navigationPropertyName){
            //initiaze curl which is used to make the http request
            $ch = curl_init();
            // Add authorization and other headers. Also set some common settings.
            self::AddRequiredHeadersAndSettings($ch);
            // set url
            $feedURL = 'https://graph.windows.net/'.Settings::$appTenantDomainName.'/'.$sourceEntryUrl.'/$links/'.$navigationPropertyName;
            $feedURL = $feedURL."?".Settings::$apiVersion;
            curl_setopt($ch, CURLOPT_URL, $feedURL);
            // Mark as Post request
            curl_setopt($ch, CURLOPT_POST, 1);
            $data = json_encode($targetEntryUrl);
            // Set the data for the post request
            curl_setopt($ch, CURLOPT_POSTFIELDS,  $data);
            //Enable fiddler to capture request
            //curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');
            

            // read the output from the post request
            $output = curl_exec($ch); 
            // close curl resource to free up system resources
            curl_close($ch);
            // decode the response json decoder
            $createdEntry = json_decode($output);
            return $createdEntry;
        }

        // Constructs a HTTP PATCH request for updating an entry.
        public static function updateEntry($feedName, $keyValue, $entry){
            //initiaze curl which is used to make the http request
            $ch = curl_init();
            self::AddRequiredHeadersAndSettings($ch);
            // set url
            $feedURL = "https://graph.windows.net/".Settings::$appTenantDomainName.'/'.$feedName.'(\''. $keyValue .'\')';
            $feedURL = $feedURL."?".Settings::$apiVersion;
            curl_setopt($ch, CURLOPT_URL, $feedURL); 
            
            // Mark as Patch request
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
                        //Enable fiddler to capture request
            //curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');

            $data = json_encode($entry);
            // Set the data for the request
            curl_setopt($ch, CURLOPT_POSTFIELDS,  $data);
            // read the output from the request
            $output = curl_exec($ch); 
            // close curl resource to free up system resources
            curl_close($ch);      
            // decode the response json decoder
            $udpatedEntry = json_decode($output);
            return $udpatedEntry;
        }

        // Constructs a HTTP DELETE request for deleting an entry.
        public static function deleteEntry($feedName, $keyValue){
            //initiaze curl which is used to make the http request
            $ch = curl_init();
                        self::AddRequiredHeadersAndSettings($ch);
            // set url
            $feedURL = "https://graph.windows.net/".Settings::$appTenantDomainName.'/'.$feedName.'(\''. $keyValue .'\')';
            $feedURL = $feedURL."?".Settings::$apiVersion;
            curl_setopt($ch, CURLOPT_URL, $feedURL); 

                        //Enable fiddler to capture request
            //curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');

            // Mark as Post request
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            // read the output from the post request
            $output = curl_exec($ch); 
            // close curl resource to free up system resources
            curl_close($ch);      
            // decode the response json decoder
            $deletedEntry = json_decode($output);
            return $deletedEntry;
        }

        // Constructs a Http GET request get changes for the type passed in.
        // Returns the json decoded respone as the objects that were recieved in feed.
        public static function getDeltaLinkFeed($feedURL, $feedName){
            if ($feedName == NULL)
            {
                $feedName = "directoryObjects";
            }

            // initiaze curl which is used to make the http request.
            $ch = curl_init();
            // Add authorization and other headers. Also set some common settings.
            self::AddRequiredHeadersAndSettings($ch);
            // set url 
            if ($feedURL == NULL)
            {
                $feedURL = "https://graph.windows.net/".Settings::$appTenantDomainName."/".$feedName;
                $feedURL = $feedURL."?deltaLink=";
            }

            // add api-version always to indicate the target version
            $feedURL = $feedURL."&api-version=2013-04-05";

            curl_setopt($ch, CURLOPT_URL, $feedURL);
            // $output contains the output string
            $output = curl_exec($ch);
            // close curl resource to free up system resources 
            curl_close($ch);      
            $jsonOutput = json_decode($output);
            // There is a field for odata metadata that we ignore and just consume the value
            return $jsonOutput;
        }

        // Add required headers like authorization header, service version etc.
        public static function AddRequiredHeadersAndSettings($ch)
        {
            //Generate the authentication header
            $authHeader = AuthorizationHelperForAADGraphService::GetAuthenticationHeader(Settings::$appTenantDomainName, Settings::$appPrincipalId, Settings::$password);
            // Add authorization header, request/response format header( for json) and a header to request content for Update and delete operations.  
            curl_setopt($ch, CURLOPT_HTTPHEADER, array($authHeader,  'Accept:application/json;odata=minimalmetadata',
                                                        'Content-Type:application/json;odata=minimalmetadata', 'Prefer:return-content'));
            // Set the option to recieve the response back as string.
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            // By default https does not work for CURL.
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
    }
?>

