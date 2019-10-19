<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-150093374-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-150093374-1');
</script>
    <meta charset="UTF-8">
    <title>Notice Board</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#D02451" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700|Playfair+Display|Roboto+Slab:400,700" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="favicon.png">
    
</head>
<body>




    <div class="container page z-depth-5">     
        
        
        <nav class="navigation">
                <div class="nav-wrapper">
                    <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                    <a href="index.php" class="brand-logo left valign-wrapper">
                        <img src="./static/images/IIT_Kharagpur_Logo.svg" alt="" class="responsive-img">
                       
                    </a>
                    <ul id="nav-mobile" class="hide-on-med-and-down right">
                        <li class="active"><a href="notices.php" class="waves-effect">Notices</a></li>  
                        <li><a href="interiit.php" class="waves-effect">Inter IIT</a></li>
                        <li><a href="gc.php" class="waves-effect">GC</a></li>
                        <li><a href="fests.html" class="waves-effect">Fests</a></li>
                        <li><a href="societies.html" class="waves-effect">Societies</a></li>
                        <li><a href="contacts.html" class="waves-effect">Contacts</a></li>
                        <li><a href="blog/" class="waves-effect">Blog</a></li>
                    </ul>
                </div>
            </nav>
        
        <h3 style="text-align: center">Notice Board</h3>

        
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>&emsp;Organisation</th>
                <th>Date and Time</th>
                <th>Venue</th>
                <th>Description</th>
                <th>Link to Facebook Post</th>
            </tr>
            </thead>
            <tbody>
                
            <?php
               class MyDB extends SQLite3 {
                  function __construct() {
                     $this->open('notices.db');  
                        }
                    }

                $db = new MyDB();
                if(!$db) {
                echo $db->lastErrorMsg();
                }

                $sql =<<<EOF
                        SELECT * from tsg_events;
EOF;

                        $ret = $db->query($sql);
                    while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
                    echo "<tr>\n<td>". $row['organisation'] . "</td>\n";
                    echo "<td>". $row['time_event'] ."</td>\n";
                    echo "<td>". $row['venue_event'] ."</td>\n";
                    echo "<td>". $row['description'] ."</td>\n";
                    echo '<td><a href=\"'. $row['link_event'] .'\">'.$row['link_event']."</a></td>\n</tr>";
                    }
                    $db->close();
                ?>
            </tbody>

        </table>

    </div>

</body>
</html>