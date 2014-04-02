<?php
    //Include menu options applicable to all pages of the web site
    include("PhpSampleTemplate.php");
?>

<HTML>
    <head>
        <title>
            Administration Page For Groups
        </title>
    </head>

    <BODY>
        <h1>
            Administration Page For Groups
        </h1>  
        <a href="CreateGroup.php"><b>Create And Add A New Group</b></a>
        <br/><br/>
        <table border="1">
            <tr>
            <th>Display Name</th>
            <th>Description</th>
            <th>Mail Enabled</th>
            <th>Edit Link</th>
            <th>Delete Link</th>
            </tr>  
            <?php
                $groups = GraphServiceAccessHelper::getFeed('groups');    
                foreach ($groups as $group){
                    $editLinkValue = "EditGroup.php?id=".$group->objectId;
                    $deleteLinkValue = "DeleteGroup.php?id=".$group->objectId;
                    $groupmembersLinkValue = "DisplayMembersOfGroup.php?id=".$group->objectId . '&name='.urlencode($group->{'displayName'});
                    if ($group->{'mailEnabled'} == 1){
                        $mailEnabled = 'True';
                    }
                    else
                    {
                        $mailEnabled = 'False';
                    }

                    echo('<tr><td><a href='.$groupmembersLinkValue.'>'. $group->{'displayName'}. '</a></td><td>'. $group->{'description'}
                         .'</td><td>'.$mailEnabled.'</td><td>' .'<a href=\''.$editLinkValue.'\'>'. 'Edit Group' . '</a></td><td>'
                         .'<a href=\''.$deleteLinkValue.'\'>'. 'Delete Group' . '</a></td></tr>');
                }
            ?>
            </table>            
    </BODY>
</HTML>