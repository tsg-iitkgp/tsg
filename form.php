<?php include 'database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="theme-color" content="#D02451" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700|Playfair+Display|Roboto+Slab:400,700" rel="stylesheet">
    <link rel="icon" type="image/png" href="favicon.png">
    <title>TSG</title>
  <link rel="stylesheet" type="text/css" href="css/form.css">
</head>
<body>
  <div class="login-page">
    <div class="form">
      <form class="login-form" method="POST">
        <input type="text" name="org_name" placeholder="Name of organisation"/>
        <input type="password" name="security" placeholder="Security code"/>
        <input type="text" name="description" placeholder="Description of the event">
        <input type="text" name="fbpage" placeholder="Link to facebook event post">
        <input type="text" name="time" placeholder="Time and Date of the event">
        <input type="text" name="venue" placeholder="Venue of the event">
        <button id="submit" >SUBMIT</button>
      </form>
    </div>
  </div>
</body>
</html>
<?php
  $myfile=fopen("pass.txt","r");
  $pass=fread($myfile,filesize("pass.txt"));
  fclose($myfile);
  function test_input($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
  $orgname = $user_pass = $desc = $link = $time_event = $venue = "";
  if ($_SERVER["REQUEST_METHOD"] == "POST") 
  {
    if(empty($_POST["org_name"]))
    {
      $message = "Please enter the name of organisation";
      echo "<script type='text/javascript'>alert('$message');</script>";
    }
    else  
      $orgname = test_input($_POST["org_name"]);
    if(empty($_POST["security"]))
    {
      $message = "Please enter the security code provided";
      echo "<script type='text/javascript'>alert('$message');</script>";
      die();
    }
    else
      $user_pass= test_input($_POST["security"]);
    if(empty($_POST["description"]))
    {
      $message = "Please enter description of the event";
      echo "<script type='text/javascript'>alert('$message');</script>";
    }
    else
      $desc = test_input($_POST["description"]);
    $link= test_input($_POST["fbpage"]);
    if(empty($_POST["time"]))
    {
      $message = "Please enter the date and time of the event";
      echo "<script type='text/javascript'>alert('$message');</script>";
    }
    else
      $time_event = test_input($_POST["time"]);
    if(empty($_POST["venue"]))
    {
      $message = "Please enter the venue of the event";
      echo "<script type='text/javascript'>alert('$message');</script>";
    }
    else
      $venue_event = test_input($_POST["venue"]);


    if($pass==$user_pass)
    {
      //insert into database
        $query =<<<EOF
        INSERT INTO tsg_events(organisation, description, link_event, time_event, venue_event) 
        VALUES('$orgname', '$desc', '$link', '$time_event', '$venue_event'); 
EOF;
        $work=$conn->exec($query);
        if ($work==TRUE) 
        {
          //$message = "Your request has been successfully submitted";
          //echo "<script type='text/javascript'>alert('$message');</script>";
        	header("Location: http://www.gymkhana.iitkgp.ac.in/thanks.html");
          //header("Location: http://localhost/tsg-master/thanks.html");
        }
        else 
        {
          echo "Error: " . $query . "<br>" . $conn->error;
        }
        $conn->close();
         
    }
    else
    {
      $message = "Please enter the correct security code";
      echo "<script type='text/javascript'>alert('$message');</script>";
    }
  }
?>
