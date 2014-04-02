<?php
    //Include menu options applicable to all pages of the web site
    include("PhpSampleTemplate.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            Create User
        </title>
    </head>
    <body>
        <?php
            // If this was not a post back show the create user form
            if (!isset($_POST['submit'])) {          
        ?>        
        <form method="post" action="<?php echo($_SERVER['PHP_SELF']);?>">            
            <table>
                <tr><td><b>Display Name:</b></td><td><input type="text" size="20" maxlength="100" name="dname"></td></tr>
                <tr><td><b>Mail alias:</b></td><td><input type="text" size="20" maxlength="15" name="alias"></td></tr>
                <tr><td><b>Password:</b></td><td><input type="password" size="20" maxlength="15" name="password"></td></tr>
                <tr><td><b>Force Password Change on Next Login:</b></td></tr>
                <tr><td><b>True:</b></td><td><input type="radio" value="True" name="forcePasswordChangeOnNextLogin" checked></td></tr>
                <tr><td><b>False:</b></td><td><input type="radio" value="False" name="forcePasswordChangeOnNextLogin"></td></tr>
                <tr><td><b>Account Enabled:</b></td></tr>                
                <tr><td><b>True:</b></td><td><input type="radio" value="True" name="accountenabled" checked></td></tr>
                <tr><td><b>False:</b></td><td><input type="radio" value="False" name="accountenabled"></td></tr>
                <tr><td><input type="submit" value="submit" name="submit"></td></tr> 
            </table>
        </form>        
 <?php
 } else {
            // Validate that the inputs are non-empty.
            if((empty($_POST["dname"])) or (empty($_POST["alias"])) or (empty($_POST["accountenabled"])) or (empty($_POST["forcePasswordChangeOnNextLogin"])) or (empty($_POST["password"]))) {
                echo('<p>One of the required fields is empty. Please go back to <a href="CreateUser.php">Create User</a></p>');
            }
            else {
                //collect the form parameters which will be set in the case this was a post back.
                $displayName = $_POST["dname"];
                $alias = $_POST["alias"];
                $accountEnabled = $_POST["accountenabled"];
                $passwordProfile = array(
                                    'password' => $_POST["password"],
                                    'forceChangePasswordNextLogin' => $_POST["forcePasswordChangeOnNextLogin"],
                                    );

                $userEntryInput = array(
                    'displayName'=> $displayName,
                    'userPrincipalName' => $alias.'@'.Settings::$appTenantDomainName ,
                    'mailNickname' => $alias,
                    'passwordProfile' => $passwordProfile,                    
                    'accountEnabled' => $accountEnabled
                            );
                // Create the user and display a message
                $user = GraphServiceAccessHelper::addEntryToFeed('users',$userEntryInput);
                
                //Check to see if we got back an error.
                if(!empty($user->{'odata.error'}))
                {
                    $message = $user->{'odata.error'}->{'message'};
                    echo('<p>User creation failed. Service returned error:<b>'.$message->{'value'}. '</b>  Please go back to <a href="createUser.php">Create User</a></p>');
                }
                else {
                    echo('<p>');
                    echo('<b>Created User with the following information:</b>');
                    echo('<table><tr><td><b>Display Name:   </b>' . $user->{'displayName'}. '</td><td><b>User Principal Name:  </b>' . $user->{'userPrincipalName'} .'</td></tr></table>');
                    echo('<br/> <br/>');
                    echo('You can go back to <a href="CreateUser.php">Create User</a> to create more users.');
                    echo('</p>');
                }
            }
 }
 ?>        
</body>
</html>
