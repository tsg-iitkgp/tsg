<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <meta name="theme-color" content="#D02451" />

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700|Playfair+Display|Roboto+Slab:400,700" rel="stylesheet">

        <link rel="stylesheet" href="fontello/css/fontello.css">


        <link rel="stylesheet" href="css/style.css">

        <link rel="icon" type="image/png" href="favicon.png">

        <title>TSG</title>
    </head>

    <body class="interiit">
        <div class="container page z-depth-5">
            <nav class="navigation">
                <div class="nav-wrapper">
                    <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                    <a href="index.php" class="brand-logo left valign-wrapper">
                        <img src="./static/images/IIT_Kharagpur_Logo.svg" alt="" class="responsive-img">
                        <!-- <span>Technology Students’ Gymkhana</span> -->
                    </a>
                    <ul id="nav-mobile" class="hide-on-med-and-down right">
                        <!-- <li><a href="index.html" class="waves-effect waves-light">Home</a></li> -->
                        <!-- CSS :after inserts the Appropriate text -->
                        <li class="active"><a href="interiit.php" class="waves-effect waves-light">Inter IIT</a></li>
                        <li><a href="gc.php" class="waves-effect">GC</a></li>
                        <li><a href="fests.html" class="waves-effect">Fests</a></li>
                        <li><a href="societies.html" class="waves-effect">Societies</a></li>
                        <li><a href="contacts.html" class="waves-effect">Contacts</a></li>
                        <li><a href="blog/" class="waves-effect">Blog</a></li>
                    </ul>
                </div>
            </nav>

            <div class="content">
                <h1>Inter IIT</h1>


                
                <div class="row">
                <div class="col s12 tab-container">
                    <ul class="tabs">
                    <li class="tab"><a class="active" href="#aquatics">Aquatics</a></li>
                    <li class="tab"><a href="#sports">Sports</a></li>
                    <li class="tab"><a href="#tech">Technology</a></li>
                    <li class="tab"><a href="#socult">SoCult</a></li>
                    <li class="tab"><a href="#yesteryear">Point's Tally</a></li>
                    </ul>
                </div>
                <div id="aquatics" class="col s12 row">
                    <div class="col s12">
                        <h2>Aquatics 2018</h2>
                        <h4>Updates</h4>
                        <ul class="bullet">
                            <?php
                              $mysqli = new mysqli("localhost", "dibya", "dibyaWP@99", "wp_myblog");
                              $result = $mysqli->query("SELECT * FROM aquatics_news");
                              
                              while($row = mysqli_fetch_array($result))
                              {
                                echo "<li>".$row['news']."</li>";
                                // echo "<br />";
                              }
                              $result->close();
                              $mysqli->close();
                          ?>

                        </ul>
                    </div>
                    <div class="col m6 s12">
                        <h4>Women's contingent</h4>
                        <ul class="bullet">
                            <li>Oindrila Saha</li>
                            <li>Kalyani Ingle</li>
                            <li>Aditi Sen</li>
                            <li>Supriti Sen</li>
                            <li>Barsa Majmder</li>
                        </ul>
                    </div>

                    <div class="col m6 s12">
                        <h4>Men's contingent</h4>
                        <ul class="bullet">
                            <li>Amlan Shil</li>
                            <li>Gitanshu Bhatia</li>
                            <li>Shaswat Gangwal</li>
                            <li>Anirudh Agrawal</li>
                            <li>Indresh</li>
                            <li>Raj Prabhu</li>
                            <li>Utkarsh Sah</li>
                            <li>Soham Chandorkar</li>
                            <li>Pushpak Roy</li>
                            <li>Harsh Choudhary</li>
                            <li>Rahul Saxena</li>
                            <li>Arpan Dey</li>
                            <li>Shubham Pandey</li>
                        </ul>
                    </div>

                </div>
                <div id="sports" class="col s12">
                    <h2>Sports</h2>
                    Page will be updated soon!
                </div>
                <div id="tech" class="col s12">
                    <h2>Technology</h2>
                    Page will be updated soon!
                </div>
                <div id="socult" class="col s12">
                    <h2>Social and Cultural</h2>
                    Page will be updated soon!
                </div>
                <div id="yesteryear" class="col s12">
                    <h2>Last year's Point Tally</h2>
                    <a class="waves-effect waves-light btn" href="https://docs.google.com/spreadsheets/d/1uWW-CS3e6o9UNgkmmlKyUbaAVWsOSfEiOJfV7ELvQX0/edit?usp=drivesdk">INTER IIT POINT'S TALLY</a><br>
                    <div class="flex-container">
                    <img class="interiit-img materialboxed" src="static/images/interiit socult.jpg">
                    <img class="interiit-img materialboxed" src="static/images/interiit tech.jpg">
                    <img class="interiit-img materialboxed" src="static/images/interiit sports.jpg">
                    <img class="interiit-img materialboxed" src="static/images/interiit sports individual.jpg">
                    </div>
                </div>
                </div>

                <!-- <div class="carrousel">
                </div> -->


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
                            <li><a class="grey-text text-lighten-3" href="http://www.iitkgp.ac.in/files/acad_cal1819.pdf">Academic Calendar 18-19</a></li>
                            <li><a class="grey-text text-lighten-3" href="http://www.iitkgp.ac.in/files/acad_cal1718.pdf">Academic Calendar 17-18</a></li>
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
           <li><a href="index.php">Home</a></li>
           <li class="active"><a href="interiit.php">Inter IIT</a></li>
           <li><a href="gc.php">GC</a></li>
           <li><a href="fests.html">Fests</a></li>
           <li><a href="societies.html">Societies</a></li>
           <li><a href="contacts.html">Contacts</a></li>
           <li><a href="blog/">Blog</a></li>
          
       </ul>

        <div class="footer center">
            <div class="footer-copyright">
            © 2018 TSG IIT Kharagpur
            </div>
        </div>

        <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="materialize/js/bin/materialize.min.js"></script>
        <script type="text/javascript">

            $(document).ready(function(){
                $('.sidenav').sidenav();                
                $('.tabs').tabs({
                    swipeable: false
                });
                $('.materialboxed').materialbox();
            });

        </script>
    </body>

</html>
