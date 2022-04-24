<?
include "config/setting.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><? echo "$title"; ?></title>
<link rel="stylesheet" type="text/css" href="main.css" />
</head>

<body>

   <!-- Begin Wrapper -->
   <div id="wrapper">
   
         <!-- Begin Header -->
         <div id="header">
		 	   <h1 align="center">
		       <? echo "$header"; ?>	 
			   </h1>
		 </div>
		 <!-- End Header -->
		 
		 <!-- Begin Left Column -->
		 <div id="leftcolumn">
		 
		       <?
			    // Cek Lupa Password
				if ($_GET[modul]=='lupapass'){
				  include "pertanyaan.php";
				}
				else{
				  include "login.php";
				}
			   ?>
		 
		 </div>
		 <!-- End Left Column -->
		 
		 <!-- Begin Right Column -->
		 <div id="rightcolumn">
		       
	          <div align="center"><img src="images/depan.png" />
			      <br />
			      <img src="images/little_orange_guy_e0.gif" />	 
		   
		      </div>
		 </div>
		 <!-- End Right Column -->
		 
		 <!-- Begin Footer -->
		 <div id="footer">
		       <p align="center">
			   <? echo "$footer"; ?>	
			   </p> 
	     </div>
		 <!-- End Footer -->
		 
   </div>
   <!-- End Wrapper -->
   
</body>
</html>
