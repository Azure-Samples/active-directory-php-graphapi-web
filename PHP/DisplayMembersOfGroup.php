<?php
    //Include common content applicable to all pages of the web site
    include("PhpSampleTemplate.php");    
 ?>

<HTML>
    <head>        
        <title>
            Display Members of Group
        </title>
    </head>

    <BODY>
        <h1>
            <b>Members belonging to <?php echo($_GET['name'])?> group </b>
        </h1>  
        <table border="1">
            <tr>
            <th>Object Id</th>
            <th>Display Name</th>
            </tr>  
            <?php
                // If this is a post back, add the user to the group.
                if (isset($_POST['submit'])) {
                    // Validate that display name is non-empty.
                    if((empty($_POST['dname']))) {
                        echo('<p><b>Please provide a valid user display name for the user to be added. User display name can not be empty</b></p>');
                    }
                    else {
                        // First get the object id of the user that needs to be createed to this group
                        // Use the $filter syntax from OData.                         
                        $filteredusers = GraphServiceAccessHelper::getFeedWithFilterClause('users','displayName', $_POST['dname']);
                        
                        // If the query did not return any members, display an error to the user.
                        if(count($filteredusers) === 0)
                        {
                            echo('<p><b>Please provide a valid user display name for the user to be added. User display name:\''. $_POST['dname'] .'\' is not valid</b></p>');
                        }
                        else
                        {
                            // We should ideally handle the case where there are multiple members with the same diplay name but let's
                            // make the simple assumption that they are distinct.                         
                            foreach ($filteredusers as $filtereduser){
                                $userid = $filtereduser->{'objectId'};
                            }
                            $useruri = array(
                                'url'=> 'https://graph.windows.net/'.Settings::$appTenantDomainName.'/users(\''.$userid. '\')'                                        
                                );
                            $groupUrl = "groups(" . '\''.($_GET['id']).'\')';
                            $addedLink = GraphServiceAccessHelper::addLinkForEntries($groupUrl, $useruri, 'members'); 
                        }
                    }
                }

                // Get the list of members for this group and diplay. A simplistic assumption that all members
                // are users for this sample.
                $users = GraphServiceAccessHelper::getLinkedFeed('groups',$_GET['id'],'members');    
                foreach ($users as $user){
                    echo('<tr><td>'. $user->{'objectId'}. '</td><td>'. $user->{'displayName'} .'</td></tr>');
                }
            ?>
        </table>
        <br/><br/>
        <form method="post" action="<?php echo($_SERVER['PHP_SELF'].'?id='.$_GET['id']. '&name='.$_GET['name']);?>">            
            <b>To create another user to this group, Provide the display name and click submit(only supported for users with unique display name):<input type="text" size="20" maxlength="100" name="dname"></b>
            <br/><br/>
            <input type="submit" value="submit" name="submit">            
        </form>
    </BODY>
</HTML>