<?php
    //Include menu options applicable to all pages of the web site
    include("PhpSampleTemplate.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            Create Group
        </title>
    </head>
    <body>
        <?php
            // If this was not a post back show the create group form
            if (!isset($_POST['submit'])) {          
        ?>        
        <form method="post" action="<?php echo($_SERVER['PHP_SELF']);?>">            
            <table>
                <tr><td><b>Display Name:</b></td><td><input type="text" size="20" maxlength="100" name="dname"></td></tr>
                <tr><td><b>Description:</b></td><td><input type="text" size="20" maxlength="100" name="description"></td></tr>
                <tr><td><b>Mail alias:</b></td><td><input type="text" size="20" maxlength="15" name="alias"></td></tr>                
                <tr><td><input type="submit" value="submit" name="submit"></td></tr>
            </table>  
        </form>
               
 <?php
 } else {
            //collect the form parameters which will be set in the case this was a post back.
            $displayName = $_POST["dname"];
            $description = $_POST["description"];
            $alias = $_POST["alias"];

            // Validate that the inputs are non-empty.
            if((empty($displayName)) or (empty($description)) or (empty($alias))) {
                echo('<p>One of the required fields is empty. Please go back to <a href="CreateGroup.php">Create Group</a></p>');
            }
            else {
                $groupEntryInput = array(
                    'displayName'=> $displayName,
                    'description' => $description,
                    'mailNickname' => $alias,
                    'mailEnabled' => 'False',
                    'securityEnabled' => 'True'
                                );
                // Create the group and display a message to the user
                $group = GraphServiceAccessHelper::addEntryToFeed('groups',$groupEntryInput);

                //Check to see if we got back an error.
                if(!empty($group->{'odata.error'}))
                {
                    $message = $group->{'odata.error'}->{'message'};
                    echo('<p>Group creation failed. Service returned error:<b>'.$message->{'value'}. '</b>  Please go back to <a href="CreateGroup.php">Create Group</a></p>');
                }
                else {
                    echo('<p>');
                    echo('<b>Created Group with the following information:</b>');
                    echo('<table><tr><td><b>Display Name:   </b>' . $group->{'displayName'}. '</td><td><b>Description:  </b>' . $group->{'description'} .'</td></tr></table>');
                    echo('<br/> <br/>');
                    echo('You can go back to <a href="CreateGroup.php">Create Group</a> to create more groups.');
                    echo('</p>');
                }
            }
 } 
 ?>
</body>
</html>
