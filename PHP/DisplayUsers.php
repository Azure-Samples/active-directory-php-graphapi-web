<?php
    //Include menu options applicable to all pages of the web site
    include("PhpSampleTemplate.php");
?>

<HTML>
    <head>
        <title>
            Administration Page For Users
        </title>
    </head>

    <BODY>
        <h1>
            Administration Page For Users
        </h1>  
        <a href="CreateUser.php"><b>Create And Add A New User</b></a>    
        <br/><br/>
        <table border="1">
            <tr>
            <th>Display Name</th>
            <th>User Principal Name</th>
            <th>Object ID</th>
            <th>Immutable ID</th>
            <th>Account Enabled</th>        
            <th>Edit Link</th>
            <th>Delete Link</th>
            </tr>  
            <?php
                $users = GraphServiceAccessHelper::getFeed('users');    
                // display any records fetched from the database
                // plus an input line for a new category
                foreach ($users as $user){
                    if ($user->{'accountEnabled'} == 1)
                      {
                          $accountEnabled = 'True';
                      }
                      else
                      {
                          $accountEnabled = 'False';
                      }
                    $editLinkValue = "EditUser.php?id=".$user->objectId;
                    $deleteLinkValue = "DeleteUser.php?id=".$user->objectId;
                    echo('<tr><td>'. $user->{'displayName'}. '</td><td>'. $user->{'userPrincipalName'} .'</td>');
                    echo('<td>'. $user->{'objectId'}.'</td>');
                    echo('<td>'. $user->{'immutableId'}.'</td>');
                    echo ('<td>'. $accountEnabled.'</td>'); 
                    echo('<td>' .'<a href=\''.$editLinkValue.'\'>'. 'Edit User' . '</a></td><td>'
                         .'<a href=\''.$deleteLinkValue.'\'>'. 'Delete User' . '</a></td></tr>');
                }
            ?>
        </table>
    </BODY>
</HTML>