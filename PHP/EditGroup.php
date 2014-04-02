<?php
    //Include menu options applicable to all pages of the web site
    include("PhpSampleTemplate.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            Edit Group
        </title>
    </head>
    <body>
        <?php            
            // If this was not a post back show the edit group form
       if (!isset($_POST['submit'])) {
                $group = GraphServiceAccessHelper::getEntry('groups',$_GET['id']);
                echo('<form method="post" action="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id']. '">');            
                echo('<table>');
                echo('<tr><td><b>Display Name:</b></td><td><input type="text" size="20" maxlength="100" name="dname" value="'. $group->{'displayName'}.'"></td></tr>');
                echo('<tr><td><b>Description:</b></td><td><input type="text" size="20" maxlength="15" name="description" value="'. $group->{'description'}.'"></td></tr>');  
                echo ('<input name="id" type="hidden" value='.$_GET['id'].'>');              
                echo('<tr><td><input type="submit" value="submit" name="submit"></td></tr>');
                echo('</table>');   
                echo('</form>');
       }
       else {
      
            // Validate that the inputs are non-empty.
            if((empty($_POST["dname"])) or (empty($_POST["description"]))) {
                echo('<p>One of the required fields is empty. Please go back to <a href="EditGroup.php'.'?id='.$_POST['id'].'">Update Group</a></p>');
            }
            else {
                //collect the form parameters which will be set in the case this was a post back.
                $displayName = $_POST["dname"];
                $description = $_POST["description"];
                $groupEntryInput = array(
                    'displayName'=> $displayName,
                    'description' => $description
                            );
                // Create the group and display a message
                $group = GraphServiceAccessHelper::updateEntry('groups',$_POST['id'],$groupEntryInput);
                
                //Check to see if we got back an error.
                if(!empty($group->{'odata.error'}))
                {
                    $message = $group->{'odata.error'}->{'message'};
                    echo('<p>Group update failed. Service returned error:<b>'.$message->{'value'}. '</b> Please go back to <a href="EditGroup.php'.'?id='.$_POST['id'].'">Update Group</a></p>');
                }
                else {
                    echo('<p>');
                    echo('<b>Updated the Group with the following information:</b>');
                    echo('<br/>');
                    echo('<b>Display Name:   </b>' . $group->{'displayName'});
                    echo('<br/>');
                    echo( '<b>Description:  </b>' . $group->{'description'});
                    echo('<br/> <br/>');
                    echo('You can go back to <a href="DisplayGroups.php">Manage Groups</a> to continue managing Group information.');
                    echo('</p>');
                }
       }
   }
 
 ?>        
</body>
</html>
