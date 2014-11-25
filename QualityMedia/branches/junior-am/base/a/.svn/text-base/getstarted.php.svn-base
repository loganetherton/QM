<?php
$opt = @$_REQUEST['opt'];
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>QualityMedia</title>
	<link rel="stylesheet" href="css/reset.css" type="text/css" charset="utf-8"/>
	<link rel="stylesheet" href="css/style.css" type="text/css" charset="utf-8"/>
	
	<!--[if IE 6]>
    <link rel="stylesheet" href="css/ie6.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
    <!--[if IE 7]>
    <link rel="stylesheet" href="css/ie7.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
    <!--[if IE 8]>
    <link rel="stylesheet" href="css/ie8.css" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script>
	$(document).ready(function(){
	  //$("button").click(function(){
		/*$("#div1").load("load_form.php",function(responseTxt,statusTxt,xhr){
		  
		});*/
	  //});

	  $('#form_sub').click(function() {
		if(validateForm()){
			var name=document.forms["frm1"]["name"].value;
			var busi_name=document.forms["frm1"]["busi_name"].value;
			var phone=document.forms["frm1"]["phone"].value;
			var dataString = 'name='+ name + '&busi_name=' + busi_name + '&phone=' + phone;
			
			$.ajax({
				type: "POST",
				url: 'send_mail.php',
				data: dataString,
				success: function(data) {
					if(data){
						
						window.document.location = "getstarted.php?opt=thankyou";
						/*
						$("#business_div").html('<h2 class="thanks">Someone will contact you shortly!<br /> We\'re looking forward to <br />working with you!</h2>');
						$("#conversionScript").attr("src", "http://www.googleadservices.com/pagead/conversion.js");
						$("#GACode img").attr("src", "http://www.googleadservices.com/pagead/conversion/995444632/?value=0&amp;label=a8jQCOjD6QQQmI_V2gM&amp;guid=ON&amp;script=0");
						*/
					}
					
					//$("#subResult").html(result);
				}
			}); 
		}
		  
		 });
	  
    
	  
	});
	 
	
	
	function validateForm()
	{
	var name=document.forms["frm1"]["name"].value;
	var busi_name=document.forms["frm1"]["busi_name"].value;
	var phone=document.forms["frm1"]["phone"].value;
		  if (name==null || name=="" || name=="Your Name")
		  {
		  alert("Please Enter Your Name");
		  document.forms["frm1"]["name"].focus();
		  return false;
		  }
		  if (busi_name==null || busi_name=="" || busi_name=="Business Name")
		  {
		  alert("Please Enter Business Name");
		  document.forms["frm1"]["busi_name"].focus();
		  return false;
		  }
		  if (phone==null || phone=="" || phone=="Phone Number")
		  {
		  alert("Please Enter Phone Number");
		  document.forms["frm1"]["phone"].focus();
		  return false;
		  }
  		  if(phone != ""){
			  var re = new RegExp('-', 'g');
			  phone = phone.replace(re, '');

			  var re = new RegExp(' ', 'g');
			  phone = phone.replace(re, '');
			  
		  }
		  if(isNaN(phone)|| phone.indexOf(" ")!=-1)
		  {
             alert("Please Enter Valid Phone Number");
			 document.forms["frm1"]["phone"].focus();
			 return false;
          }
		  if (phone.length != 10)
		  {
          	alert("Please Enter Valid Phone Number. Ex: 5556667777"); 
			document.forms["frm1"]["phone"].focus();
			return false;
          }
		  return true;
	}
	</script>
    
		
</head>
<body>
	<div class="navtop">
		<div class="container clearfix">
			<div class="right">
				<div class="call">888-435-5518</div>
				<div class="mail"><a href="mailto:helpdesk@qualitymedia.com">helpdesk@qualitymedia.com</a></div>
			</div>
		</div>
	</div>
	<div class="headtop">
		<div class="container clearfix">
			<div class="left"><!--<a href="#">--><h1 class="logo"><img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm_images/logo.png" alt=""/></h1><!--</a>--></div>
			<div class="right"><h2><strong>Repair</strong> and <strong>Build</strong> Your Yelp Reputation Now!</h2></div>
		</div>
	</div>
	<div class="signwrap">
		<div class="signbox">
			<div class="business-boxed">
				<div class="business-form" id="business_div">
					
				
					<?php if($opt == "thankyou"):?>
										<h2 class="thanks">Someone will contact you shortly!<br /> We're looking forward to <br />working with you!</h2>

					<!-- Google Code for Getstarted Conversion Page -->
					<script type="text/javascript">
					/* <![CDATA[ */
					var google_conversion_id = 995444632;
					var google_conversion_language = "en";
					var google_conversion_format = "2";
					var google_conversion_color = "ffffff";
					var google_conversion_label = "a8jQCOjD6QQQmI_V2gM";
					var google_conversion_value = 0;
					/* ]]> */
					</script>
					<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
					</script>
					<noscript>
						<div style="display:inline;">
						<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/995444632/?value=0&amp;label=a8jQCOjD6QQQmI_V2gM&amp;guid=ON&amp;script=0"/>
						</div>
					</noscript>
					<?php else:?>
						<form name="frm1" id="frm1" method="post">
					<div class="dl">
						<!--<label>Your Name</label>-->
						<label>&nbsp;</label>
						<div class="inp"><span class="txt"><input type="text" id="name" name="name" class="frm_placeholder" placeholder="Your Name"/></span></div>
					</div>
					<div class="dl">
						<!--<label>Business Name</label>-->
						<label>&nbsp;</label>
						<div class="inp"><span class="txt"><input type="text" id="busi_name" name="busi_name" class="frm_placeholder" placeholder="Business Name"/></span></div>
					</div>
					<div class="dl">
						<!--<label>Phone Number</label>-->
						<label>&nbsp;</label>
						<div class="inp"><span class="txt"><input type="text" id="phone" name="phone"  class="frm_placeholder" placeholder="Phone Number" /></span></div>
					</div>
					<div class="dl">
						<label>&nbsp;</label>
						<input type="button" class="btn-getstarted" id="form_sub" value="Get Started!" />
					</div>
					</form>
					<?php endif;?>

					
				</div>
			</div>		
		</div>
	</div>
	<div id="content">
		<div class="container">	
			<div class="serve clearfix">
				<div class="item">
					<span class="img"><img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm_images/serve-img-1.png" /></span>
					<h2>More Customers!</h2>
					<span class="desc"><p>We help you to get more customers by working to improve your online reputation, and helping you to increase your star rating.</p></span>
				</div>
				<div class="item">
					<span class="img"><img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm_images/serve-img-2.png" /></span>
					<h2>More Visibility!</h2>
					<span class="desc"><p>Our team will optimize your presence on social media sites such as Yelp, to help your business appear before competitors!</p></span>
				</div>
				<div class="item last">
					<span class="img"><img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm_images/serve-img-3.png" /></span>
					<h2>More Loyalty!</h2>
					<span class="desc"><p>We create more customer loyalty by communicating with your customers online, <br />and creating rewards to show appreciation for their support.</p></span>
				</div>
			</div>
			<p class="space-3"></p>
			<center><img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm_images/img-ss1.jpg" alt=""/></center>
			<p class="space-3"></p>
		</div>
	</div>
		
	<div id="footer">
		<div class="container">
			<div class="left"><p class="copy">&copy; 2013 QualityMedia</p></div>
			<div class="right">
				<div class="connect">
					<span>Connect with us</span>
					<a href="https://www.facebook.com/QualityMedia1"><img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm_images/ico-fb.png" alt=""/></a>
					<a href="https://twitter.com/Quality_Media1"><img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm_images/ico-tw.png" alt=""/></a>
					<a href="#"><img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm_images/ico-rss.png" alt=""/></a>
				</div>
				<p class="mail"><a href="mailto:helpdesk@qualitymedia.com">helpdesk@qualitymedia.com</a></p>
				<p class="number">+1 888 435 5518</p>
			</div>
			<p class="clear"></p>
		</div>
	</div>
	
	<script type="text/javascript" src="js/jquery.placeholder.min.js" charset="utf-8"></script>
	<script type="text/javascript">
		$('input[placeholder], textarea[placeholder]').placeholder();
	</script>	
</body>
</html>