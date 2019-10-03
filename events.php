<?php include 'database.php'; ?>
<?php
   $query = $conn->query("select * from tsg_events");

       $dyn_table = '<table border="1"cellpadding="10">';
    while($row = mysqli_fetch_assoc($query)){
      $organisation=$row['organisation'];
      $description=$row['description'];
      $link_event=$row['link_event'];
      $time_event=$row['time_event'];
      $venue_event=$row['venue_event'];

        $dyn_table .= '<tr><td width="304px" height="150px">'.$organisation.'</td>';
       $dyn_table .= '<td width="304px">'.$description.'</td>';
      $dyn_table .= '<td width="304px"><a href="$link_event">'.$link_event.'</td>';
      $dyn_table .= '<td width="304px">'.$time_event.'</td>';
      $dyn_table .= '<td width="304px">'.$venue_event.'</td>';

  }
    $dyn_table .= '</tr></table>';
?>
 
<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <meta name="theme-color" content="#D02451" />

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700|Playfair+Display|Roboto+Slab:400,700" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/events.css">
		<link rel="icon" type="image/png" href="favicon.png">
		<title>TSG</title>
    <style type="text/css">
      
      table{
        width: 100%;
      }
      th{
        text-align: center;
      }
      tr{
        text-align: center;
        background-color: rgba(242,59,36,0.7);
        font-size: 1.5em;
      }
      td:hover {background-color: #f5f5f5;}
      table, th, td{
        border:10px solid white;
      }
      a{
        color: blue;
      }
    </style>
</head>
<body>
   <script type="text/javascript" src="js/index.js"></script>
        <div class="container page z-depth-5">
            <nav class="navigation">
                <div class="nav-wrapper">
                    <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                    <a href="#" class="brand-logo left valign-wrapper">
                        <img src="./static/images/IIT_Kharagpur_Logo.svg" alt="" class="responsive-img">
                        <span>Technology Students’ Gymkhana</span>
                    </a>
                    <ul id="nav-mobile" class="hide-on-med-and-down right">
                        <li class="active"><a href="index.html" class="waves-effect waves-light">Home</a></li>
                        <li><a href="interiit.php" class="waves-effect">Inter IIT</a></li>

                        <li><a href="events.php" class="waves-effect">Events</a></li>
                        <li><a href="form.php" class="waves-effect">Form</a></li>
                        
                        <li><a href="gc.php" class="waves-effect">GC</a></li>
                        <li><a href="fests.html" class="waves-effect">Fests</a></li>
                        <li><a href="societies.html" class="waves-effect">Societies</a></li>
                        <li><a href="contacts.html" class="waves-effect">Contacts</a></li>
                        <li><a href="blog/" class="waves-effect">Blog</a></li>
                    </ul>
                </div>
            </nav>



            
            
            
            
            
            <div class="content">
                
                <h2 align="center">Events_Records</h2>
                <?php echo $dyn_table; ?>


            </div>
        </div>

        <div class="links container z-depth-3">
            <div class="container white-text">
                <div class="row">
                    <div class="col l4 s12">
                        <h5>Contact Us</h5>
                        <address>
                            <strong>Technology Students' Gymkhana</strong><br>
                            IIT Kharagpur, Kharagpur<br>
                            West Bengal - 721302<br>
                        </address>
                        <p class="grey-text text-lighten-4"></p>
                    </div>
                    <div class="col l4 s6">
                        <ul>
                            <li><a class="grey-text text-lighten-3" href="http://www.apna.iitkgp.ac.in/">Apna IIT KGP</a></li>
                            <li><a class="grey-text text-lighten-3" href="http://www.iitkgp.ac.in/files/acad_cal1920.pdf">Academic Calendar 19-20</a></li>
                            <li><a class="grey-text text-lighten-3" href="http://www.iitkgp.ac.in/files/acad_cal1819.pdf">Academic Calendar 18-19</a></li>
                            <li><a class="grey-text text-lighten-3" href="http://www.iitkgp.ac.in/iitkgp-announcements;jsessionid=13FBA3CA280A81F185131F0CBC27CE0F">Announcements</a></li>
                            <li><a class="grey-text text-lighten-3" href="https://erp.iitkgp.ernet.in/ERPWebServices/curricula/specialisationList.jsp?stuType=UG">Academic Curriculum (UG)</a></li>
                            <li><a class="grey-text text-lighten-3" href="https://erp.iitkgp.ernet.in/ERPWebServices/curricula/specialisationList.jsp?stuType=PG">Academic Curriculum (PG)</a></li>
                            <li><a class="grey-text text-lighten-3" href="http://www.counsellingcentre.iitkgp.ac.in/">Counselling Centre</a></li>
                            <li><a class="grey-text text-lighten-3" href="https://wiki.metakgp.org/w/Yellow_pages">Yellow Pages</a></li>
                            <li><a class="grey-text text-lighten-3" href="https://www.mykgp.com/">MY KGP</a></li>
                            <li><a class="grey-text text-lighten-3" href="http://www.bcrth.iitkgp.ac.in/">BC Roy Hospital</a></li>
                            <li><a class="grey-text text-lighten-3" href="http://www.library.iitkgp.ernet.in/">Library</a></li>
                            <li><a class="grey-text text-lighten-3" href="http://www.cdc.iitkgp.ac.in/">CDC</a></li>
                            <li><a class="grey-text text-lighten-3" href="http://www.swgiitkgp.in/freshers_forum/">SWG Forum</a></li>
                            <li><a class="grey-text text-lighten-3" href="http://www.dak.iitkgp.ernet.in/phd/">Doctorates Info System</a></li>
                            <li><a class="grey-text text-lighten-3" href="https://erp.iitkgp.ernet.in/DupCertReqPortal/auth/welcome.htm">Degree Verification/Transcripts/Certificates</a></li>
                        </ul>
                    </div>
                    <div class="col l4 s6">
                        <ul>
                           <li><a class="grey-text text-lighten-3" href="https://erp.iitkgp.ernet.in/">ERP</a></li>
                           <li><a class="grey-text text-lighten-3" href="https://erp.iitkgp.ernet.in/StudentPerformanceV2/auth/login.htm">For Parents/Guardians</a></li>
                           <li><a class="grey-text text-lighten-3" href="http://www.iitkgp.ac.in/for-students;jsessionid=13FBA3CA280A81F185131F0CBC27CE0F">For Students</a></li>
                           <li><a class="grey-text text-lighten-3" href="http://www.tgh.iitkgp.ac.in/">Guest House</a></li>
                           <li><a class="grey-text text-lighten-3" href="http://www.iitkgp.ac.in/holidays;jsessionid=13FBA3CA280A81F185131F0CBC27CE0F">Holidays</a></li>
                           <li><a class="grey-text text-lighten-3" href="http://www.iitkgp.ac.in/internal-complaints-committee;jsessionid=13FBA3CA280A81F185131F0CBC27CE0F">Internal Complaints Committee</a></li>
                           <li><a class="grey-text text-lighten-3" href="http://noticeboard.iitkgp.ernet.in/">Internal Notice Board</a></li>
                           <li><a class="grey-text text-lighten-3" href="http://www.iitkgp.ac.in/media;jsessionid=13FBA3CA280A81F185131F0CBC27CE0F">Media</a></li>
                           <li><a class="grey-text text-lighten-3" href="http://www.iitkgp.ac.in/rajbhasha">Rajbhasha Vibhag</a></li>
                           <li><a class="grey-text text-lighten-3" href="https://erp.iitkgp.ernet.in/SupplierFacilities/login.htm">Vendor Registration under GST</a></li>
                           <li><a class="grey-text text-lighten-3" href="http://www.sric.iitkgp.ac.in/main.php">Sponsored Research</a></li>
                           <li><a class="grey-text text-lighten-3" href="https://iitkgpmail.iitkgp.ac.in/">Web Mail</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed-action-btn center">
            <a href="#about" class="btn-floating pulse waves-effect waves-light deep-purple"><i class="material-icons">expand_more</i></a>
        </div>

        <ul class="sidenav" id="mobile-demo">
             <div class="header-sidenav row valign-wrapper" style="position: relative; top: 1.5rem;">
                <div class="col s5 m3 center">
                    <img src="./static/images/IIT_Kharagpur_Logo.svg" alt="IIT Icon" class="responsive-img">
                </div>
                <div class="col m9 s7 valign-wrapper">
                    <h1>Technology Students' Gymkhana</h1>
                    <!-- <h2>Indian Institute of Technology Kharagpur</h2> -->
                </div>
            </div>
            <li class="active"><a href="index.php">Home</a></li>
            <li><a href="interiit.php">Inter IIT</a></li>
            <li><a href="gc.php">GC</a></li>
            <li><a href="fests.html">Fests</a></li>
            <li><a href="societies.html">Societies</a></li>
            <li><a href="contacts.html">Contacts</a></li>
            <li><a href="blog/">Blog</a></li>
           
        </ul>

        <div class="footer center">
            <div class="footer-copyright">
            © 2019 TSG IIT Kharagpur
            </div>
        </div>

        <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="materialize/js/bin/materialize.min.js"></script>
        <script type="text/javascript" src="js/index.js"></script>

 
</body>
</html>