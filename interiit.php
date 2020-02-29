<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>

        <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-150093374-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-150093374-1');
</script>

<script
    src="https://code.jquery.com/jquery-3.3.1.js"
    integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
    crossorigin="anonymous">
</script>
<script> 
$(function(){

  $("#header").load("header.html"); 
  $("#footer").load("footer.html"); 
});
</script> 
<style>
@media only screen and (max-width: 1100px) {
            #header { 
                position: absolute;
                left: 0px;
            }
            #mob-nav {
                display: block;
            }
                
            #not-on-mob {
                display: none;
                visibility: hidden;
            }
        }
</style>


        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta name="theme-color" content="#D02451" />

        <meta name="description" content="Official Website of Technology Students Gymkhana, IIT Kharagpur" />

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website" />
        <meta property="og:url" content="http://www.gymkhana.iitkgp.ac.in" />
        <meta property="og:title" content="Technology Students' Gymkhana, Indian Institute of Technology Kharagpur" /> 
        <meta property="og:description" content="Official Website of Technology Students Gymkhana, IIT Kharagpur" />
        <meta property="og:image" content="favicon.png" />

        <!-- Twitter -->
        <meta property="twitter:card" content="website" />
        <meta property="twitter:url" content="http://www.gymkhana.iitkgp.ac.in" />
        <meta property="twitter:title" content="Technology Students' Gymkhana, Indian Institute of Technology Kharagpur" /> 
        <meta property="twitter:description" content="Official Website of Technology Students Gymkhana, IIT Kharagpur" />
        <meta property="twitter:image" content="favicon.png" />

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700|Playfair+Display|Roboto+Slab:400,700" rel="stylesheet">
        <link rel="stylesheet" href="fontello/css/fontello.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" type="image/png" href="favicon.png">
        <title>TSG</title>
    </head>
    <body class="interiit">
        <div class="container page z-depth-5">
            
        <div id="header">     
        </div>

            <div class="content">
                <h1><strong><u>Inter IIT</strong><u></h1>

                <div class="row">
                <div class="col s12 tab-container">
                    <ul class="tabs center-align">
                   <!--  <li class="tab"><a class="active" href="#aquatics">Aquatics</a></li> -->
                        <li class="tab col s4"><a class="active" href="#sports">Sports</a></li>
                        <li class="tab col s4"><a href="#tech">Technology</a></li>
                        <li class="tab col s4"><a href="#socult">SoCult</a></li>

                    </ul>
                </div>
                <!-- <div id="aquatics" class="col s12 row">
                    <div class="col s12">
                        <h2>Aquatics 2018</h2>
                        <h4>Updates</h4>
                        <ul class="bullet"> -->

                       <!-- </ul>
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
                    </div> -->
                </div>
                <div id="sports" class="col s4">
                    <!-- <h2>Sports</!-->
                    <h3 class="center-align"> To Be Held at Indian Institute of Technology Kharagpur</h3>
                    <!-- <img src="static/images/interiit sports filler.jpg" alt=""> -->
                    <iframe src="https://www.interiit.com/" frameborder="0" allowfullscreen
    style="width:100%;height:80em;"></iframe>
                </div>
                <div id="tech" class="col s4">
                    <!-- <h2>Technology</h2> -->
                    <h3 class="center-align"> To Be Held at Indian Institute of Technology Roorkee</h3>
                    <!-- <img src="static/images/interiit tech filler.jpg" alt=""> -->
                    <iframe src="http://www.interiittech.org/" frameborder="0" allowfullscreen
    style="width:100%;height:80em;"></iframe>
                </div>
                <div id="socult" class="col s4">
                    <!-- <h2>Social and Cultural</h2> -->
                    <h3 class="center-align"> To Be Held at Indian Institute of Technology Bombay</h3>
                    <!-- <img src="static/images/interiit socult filler.jpg" alt=""> -->
                    <iframe src="https://www.metakgp.org/w/Inter_IIT_Cultural_Meet" frameborder="0" allowfullscreen
    style="width:100%;height:80em;"></iframe>
                </div>

                </div>
                <!-- <div class="carrousel">
                </div> -->
            </div>
        </div>
        
        <div id="footer"></div>

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
