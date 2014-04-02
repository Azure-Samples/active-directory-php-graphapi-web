<?php
    //Include menu options applicable to all pages of the web site
    include("PhpSampleTemplate.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            Delete User
        </title>
    </head>
    <body>
        <?php            
            // If this was not a post back show the delete user form
       if (!isset($_POST['submit'])) {
                $user = GraphServiceAccessHelper::getEntry('users',$_GET['id']);
                echo('<H1>Do you really want to delete this user?<H1>');
                echo('<form method="post" action="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id']. '">');            
                echo('<table>');
                echo('<tr><td><b>Display Name:</b></td><td><input type="text" disabled size="20" maxlength="100" name="dname" value="'. $user->{'displayName'}.'"></td></tr>');
                echo('<tr><td><b>Mail Alias:</b></td><td><input type="text" disabled size="20" maxlength="15" name="alias" value="'. $user->{'mailNickname'}.'"></td></tr>');
                echo ('<input name="id" type="hidden" value='.$_GET['id'].'>');
                echo('<tr><td><input type="submit" value="Delete" name="submit"></td></tr>');
                echo('</table>');   
                echo('</form>');
       }
       else {
                // Delete the user and display a message
                $user = GraphServiceAccessHelper::deleteEntry('users',$_POST['id']);                
                //Check to see if we got back an error.
                if(!empty($user->{'odata.error'}))
                {
                    $message = $user->{'odata.error'}->{'message'};
                    echo('<p>User deletion failed. Service returned error:<b>'.$message->{'value'}. '</b> Please go back to <a href="DeleteUser.php'.'?id='.$_POST['id'].'">Delete User</a></p>');
                }
                else {
                    echo('<p>');
                    echo('<b>Deleted the User with the following Key:</b>'.$_POST['id'] );
                    echo('<br/><br/><br/>');
                    echo('You can go back to <a href="DisplayUsers.php">Manage Users</a> to continue managing User information.');
                    echo('</p>');
                }
       }
 
 ?>        
</body>
</html>
