<?php
    //Include menu options applicable to all pages of the web site
    include("GraphServiceAccessHelper.php");
    include("PrintObjectHelper.php");
?>

<HTML>
    <head>
        <title>
            Get changes from AAD.
        </title>
        <STYLE TYPE="text/css">
        <!--
        .header
            {
                background-color:#198199;
                color:white;
                font-weight:bold;
            }
        .key
            {
                background-color: #1d94af
            }
        -->
        </STYLE>

    </head>

    <BODY>
        <h1>
            Get changes from AAD.
        </h1>  
            <?php
                $deltaLink = NULL;
                if (!empty($_GET))
                {
                    $deltaLink =  $_GET["deltaLink"];
                }

                $moreChangesAvailable = FALSE;
                do
                {
                    $response = GraphServiceAccessHelper::getDeltaLinkFeed($deltaLink, "directoryObjects");
                    $changes = $response->{'value'};
                    if (!empty($changes))
                    {
                        foreach ($changes as $change)
                        {
                            PrintObjectHelper::PrintObject($change);
                        }
                    }
                    else
                    {
                        echo 'No changes available at this time.<br>';
                    }

                    $deltaLink = $response->{'aad.nextLink'};                    
                    if (empty($deltaLink) || $deltaLink == NULL)
                    {
                        $deltaLink = $response->{'aad.deltaLink'};
                        $moreChangesAvailable = FALSE;
                    }
                    else
                    {
                        // more changes are available.
                        $moreChangesAvailable = TRUE;
                    }
                }
                while ($moreChangesAvailable == TRUE);
                echo '<br><a href="GetChanges.php?deltaLink='.$deltaLink.'"><b>Check again for new updates.</b></a>';
        ?>
        <br/><br/>
    </BODY>
</HTML>