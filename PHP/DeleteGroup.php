<?php
    //Include menu options applicable to all pages of the web site
    include("PhpSampleTemplate.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            Delete Group
        </title>
    </head>
    <body>
        <?php            
            // If this was not a post back show the delete group form
       if (!isset($_POST['submit'])) {
                $group = GraphServiceAccessHelper::getEntry('groups',$_GET['id']);
                echo('<H1>Do you really want to delete this group?<H1>');
                echo('<form method="post" action="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id']. '">');            
                echo('<table>');
                echo('<tr><td><b>Display Name:</b></td><td><input type="text" disabled size="20" maxlength="100" name="dname" value="'. $group->{'displayName'}.'"></td></tr>');
                echo('<tr><td><b>Group:</b></td><td><input type="text" disabled size="20" maxlength="15" name="alias" value="'. $group->{'description'}.'"></td></tr>');
                echo ('<input name="id" type="hidden" value='.$_GET['id'].'>');
                echo('<tr><td><input type="submit" value="Delete" name="submit"></td></tr>');
                echo('</table>');   
                echo('</form>');
       }
       else {
                // Delete the group and display a message
                $group = GraphServiceAccessHelper::deleteEntry('groups',$_POST['id']);                
                //Check to see if we got back an error.
                if(!empty($group->{'odata.error'}))
                {
                    $message = $group->{'odata.error'}->{'message'};
                    echo('<p>Group deletion failed. Service returned error:<b>'.$message->{'value'}. '</b> Please go back to <a href="DeleteGroup.php'.'?id='.$_POST['id'].'">Delete Group</a></p>');
                }
                else {
                    echo('<p>');
                    echo('<b>Deleted the Group with the following Key:</b>'.$_POST['id'] );
                    echo('<br/><br/><br/>');
                    echo('You can go back to <a href="DisplayGroups.php">Manage Groups</a> to continue managing Group information.');
                    echo('</p>');
                }
       }
 
 ?>        
</body>
</html>
