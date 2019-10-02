<?php include 'database.php'; ?>
<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <meta name="theme-color" content="#D02451" />

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700|Playfair+Display|Roboto+Slab:400,700" rel="stylesheet">
        <link rel="stylesheet" href="css/events.css">
		<link rel="icon" type="image/png" href="favicon.png">
		<title>TSG</title>
</head>
<style type="text/css">
	@import url('https://fonts.googleapis.com/css?family=Fira+Sans:400');

*,
*:before,
*:after{
  box-sizing: border-box;
}

* {
  user-select: none; 
  -webkit-tap-highlight-color: rgba(0,0,0,0); 
  transform-style: preserve-3d; 
  margin: 0;
  padding: 0;
}

*:focus {
  outline: none!important; 
}

body{
  margin: 0;
  padding: 0;
  background: #131313;
  color: #f5f5f5;
  font-family: 'Fira Sans';
  text-align: left;
  overflow-x: hidden;
  overflow-y: hidden;
}

.container{
  width:100vw;
  height: 100vh;
  display: flex;
  flex-flow: row;
  perspective: 1000px;
  perspective-origin: center;
}

.col{
  height:  100%;
  transition: transform 600ms cubic-bezier(0.390, 0.575, 0.565, 1.000), opacity 300ms ease;
  transform: translateZ(34px) scale(.98); 
  position: relative;
}

.col:nth-of-type(1){
  transform: rotateY(5deg) translateZ(34px) scale(.98);
}

.col:hover,
.col:focus{
  transform: translateZ(34px);
  transition: transform 300ms cubic-bezier(0.390, 0.575, 0.565, 1.000), opacity 300ms ease;
}

.card-container{
  position: relative;
  width: calc(100% - 25px);
  height:  calc(28.3% - 25px);
  margin: 17px;
  padding: 8px;
  text-align: center;
  opacity: .89;
}

.card-container:hover,
.card-container:focus{
  opacity: 1;
}

.overlay{
  display: block;
  position: absolute;
  cursor: pointer;
  width: 50%;
  height: 50%;
  z-index: 1;
  transform: translateZ(34px);
}

.overlay:nth-of-type(1){
  left: 0;
  top: 0;
}


.overlay:nth-of-type(1):hover ~ .card,
.overlay:nth-of-type(1):focus ~ .card{
  transform-origin: right top;
  transform: rotateX(3deg) rotateY(-3deg) translateZ(0);
}


.card{
  border-radius: 5px;
  height: 100%;
  transition: all 300ms ease-out;
  align-items: center;
  flex-direction: column;
  align-items: flex-start;
  justify-content: flex-end;
  position: relative;
  z-index: 0;
  opacity: .89;
  padding: 13px 21px;
}

.card:before,
.card:after{
  content: '';
  position: absolute;
  left:0;
  top: 0;
  display: block;
  width: 100%;
  height: 100%;
  opacity: .21;
  transition: transform 300ms ease;
  transform: scale(.98);
}

.card:before{
  background-size: cover;
  background-position: 50% 50%;
}

.card-container:hover .card:before,
.card-container:hover .card:after,
.card-container:focus .card:before,
.card-container:focus .card:after{
  opacity: .34;
  transform: scale(1);
}

.col:nth-of-type(1) .card-container:nth-of-type(1) .card:before{
      background-image: url('https://www.nasa.gov/sites/default/files/styles/full_width/public/thumbnails/image/pia22474-2000.jpg?itok=cajl1lYH');
}


.card:after{
  background: linear-gradient(to bottom, rgba(0,0,0,0) 50%,rgba(0,0,0,0.89) 100%);
}

h4{
  text-align: left;
  font-size: 18px;
  font-weight:400;
  transform: translateY(5px);
  transition: transform 450ms cubic-bezier(0.390, 0.575, 0.565, 1.000);
  max-width: 320px;
  outline: 1px solid transparent;
  color: black;
}

.overlay:hover ~ .card h4,
.overlay:focus ~ .card h4{
  transform: translateZ(144px);
}

.card > span{
  font-size: 34px;
  opacity: 0;
  transform: translateX(-3px);
  transition: all 300ms ease;
}

.card-container:hover > .card > h4,
.card-container:focus > .card > h4{
  transform: translateY(-13px);
}

.card-container:hover > .card > span,
.card-container:focus > .card > span{
 opacity: 1;
    transform: translateX(3px);
  animation: slideRight 300ms ease;
}

@media (max-width: 768px){
  body{
    overflow-y: scroll;
  }
  h4{
    font-size: 17px;
  }
}

@media (max-width: 540px){
  .container{
    flex-flow: column;
    perspective: none;
  }
  
  .col:nth-of-type(1),
  .col:nth-of-type(2),
  .col:nth-of-type(3),
  .col:hover{
      transform: none;
  }
  
  h4{
    padding-bottom: 8px;
    font-size: 18px;
  }
  
.card > span{
      display: none;
  }
}

@media (max-height: 599px){
.card > span{
      display: none;
  }
}

@media (max-width: 860px) and (max-height: 540px){
  h4{
    font-size: 14px;
  }
}

@media (max-width: 620px) and (max-height: 540px){
  h4{
    font-size: 13px;
  }
}
</style>
<body>
	<?php 
		$query="select * from tsg_events";
		$results = $conn->query($query) or die ($conn->error.__LINE__);
	?>
	<table>
		<tr>
			<th><h4>Events_Records</h4></th>
		</tr>		
		<?php
			while($rows=mysqli_fetch_assoc($results))
			{
		?>
				
		

	</table>
	<div class="container">
  
  <div class="col">

    <div class="card-container">
      <div class="overlay"></div>
      <div class="overlay"></div>
      <div class="overlay"></div>
      <div class="overlay"></div>
      <div class="card">
        <h4><b><?php echo $rows['organisation']; ?></b><br><?php echo $rows['link_event']; ?><br><?php echo $rows['time_event']; ?><br><?php echo $rows['venue_event']; ?></h4>
        <span class"chev">&rsaquo;</span>
      </div>
    
  </div>
  
</div>
<?php		
			}
		?>
</body>
</html>