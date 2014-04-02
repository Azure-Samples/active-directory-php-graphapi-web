<?php
    //Include menu options applicable to all pages of the web site
    include("PhpSampleTemplate.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            Edit User
        </title>
    </head>
    <body>
        <?php            
            // If this was not a post back show the edit user form
       if (!isset($_POST['submit'])) {
                $user = GraphServiceAccessHelper::getEntry('users',$_GET['id']);
                echo('<form method="post" action="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id']. '">');            
                echo('<table>');
                echo('<tr><td><b>Display Name:</b></td><td><input type="text" size="20" maxlength="100" name="dname" value="'. $user->{'displayName'}.'"></td></tr>');
                echo('<tr><td><b>Mail Alias:</b></td><td><input type="text" size="20" maxlength="15" name="alias" value="'. $user->{'mailNickname'}.'"></td></tr>');                
                echo ('<input name="id" type="hidden" value='.$_GET['id'].'>');
                echo('<tr><td><b>Account Enabled:</b></td></tr>');                
                $checkedTrue = '';
                $checkedFalse = '';
                if($user->{'accountEnabled'} == true)
                {
                    $checkedTrue = 'checked';
                }
                else
                {
                    $checkedFalse = 'checked';
                }
                echo('<tr><td><b>True:</b></td><td><input type="radio" value="True" name="accountenabled"'. $checkedTrue.'></td></tr>');
                echo('<tr><td><b>False:</b></td><td><input type="radio" value="False" name="accountenabled"'. $checkedFalse.'></td></tr>');
                echo('<tr><td><input type="submit" value="submit" name="submit"></td></tr>');
                echo('</table>');   
                echo('</form>');
       }
       else {
      
            // Validate that the inputs are non-empty.
            if((empty($_POST["dname"])) or (empty($_POST["alias"])) or (empty($_POST["accountenabled"]))) {
                echo('<p>One of the required fields is empty. Please go back to <a href="EditUser.php'.'?id='.$_POST['id'].'">Update User</a></p>');
            }
            else {
                //collect the form parameters which will be set in the case this was a post back.
                $displayName = $_POST["dname"];
                $alias = $_POST["alias"];
                $accountEnabled = $_POST["accountenabled"];    
                $userEntryInput = array(
                    'displayName'=> $displayName,
                    'userPrincipalName' => $alias.'@'.Settings::$appTenantDomainName ,
                    'mailNickname' => $alias,                    
                    'accountEnabled' => $accountEnabled
                            );
                // Create the user and display a message
                $user = GraphServiceAccessHelper::updateEntry('users',$_POST['id'],$userEntryInput);
                
                //Check to see if we got back an error.
                if(!empty($user->{'odata.error'}))
                {
                    $message = $user->{'odata.error'}->{'message'};
                    echo('<p>User update failed. Service returned error:<b>'.$message->{'value'}. '</b> Please go back to <a href="EditUser.php'.'?id='.$_POST['id'].'">Update User</a></p>');
                }
                else {
                    echo('<p>');
                    echo('<b>Updated the User with the following information:</b>');
                    echo('<br/>');
                    echo('<b>Display Name:   </b>' . $user->{'displayName'});
                    echo('<br/>');
                    echo( '<b>User Principal Name:  </b>' . $user->{'userPrincipalName'});
                    echo('<br/>');
                    echo( '<b>Account Enabled:  </b>');
                    echo ($user->{'accountEnabled'} ? 'true' : 'false'); 
                    echo('<br/> <br/>');
                    echo('You can go back to <a href="DisplayUsers.php">Manage Users</a> to continue managing User information.');
                    echo('</p>');
                }
       }
   }
 
 ?>        
</body>
</html>
