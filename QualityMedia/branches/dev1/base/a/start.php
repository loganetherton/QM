<?php
$opt = @$_REQUEST['opt'];
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>QualityMedia</title>
	<link rel="stylesheet" href="css/reset.css" type="text/css" charset="utf-8"/>
	<link rel="stylesheet" href="css/style_v2.css" type="text/css" charset="utf-8"/>
	
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
				url: 'send_mail_v2.php',
				data: dataString,
				success: function(data) {
					if(data){
						window.document.location = "start.php?opt=thankyou";
						/*
						$("#business_div").html('<h2 class="thanks">Someone will contact you shortly!<br /> We\'re looking forward to <br />working with you!</h2>');
						$("#conversionScript").attr("src", "http://www.googleadservices.com/pagead/conversion.js");
						$("#GACode img").attr("src", "http://www.googleadservices.com/pagead/conversion/995444632/?value= 0&;label=_yFxCKCt6wQQmI_V2gM&guid=ON&script=0");
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
		  if (name==null || name=="")
		  {
		  alert("Please Enter Name");
		  document.forms["frm1"]["name"].focus();
		  return false;
		  }
		  if (busi_name==null || busi_name=="")
		  {
		  alert("Please Enter Business Name");
		  document.forms["frm1"]["busi_name"].focus();
		  return false;
		  }
		  if (phone==null || phone=="")
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
	<div class="headtop">
		<div class="container clearfix">
			<div class="left"><!--<a href="#">--><h1 class="logo"><img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm2_images/logo.png" alt=""/></h1><!--</a>--></div>
			<div class="right"><div class="call">888-435-5518</div></div>
		</div>
	</div>
	<div class="signwrap">
		<div class="tagline">
			<h2>Online Reviews Affect <strong>YOUR</strong> Business! <br /> Start Getting More Customers And Better Reviews Now!</h2>
			<!--<h4>We Engage With Your Customers, And Build Your Business</h4>-->
		</div>
		<div class="signbox">
			<div class="business-boxed">
				<div class="business-form" id="business_div">
				
				<?php if($opt == "thankyou"):?>
					<h2 class="thanks">Someone will contact you shortly!<br /> We're looking forward to <br />working with you!</h2>
							<!-- Google Code for Start Conversion Page -->
							<script type="text/javascript">
							/* <![CDATA[ */
							var google_conversion_id = 995444632;
							var google_conversion_language = "en";
							var google_conversion_format = "2";
							var google_conversion_color = "ffffff";
							var google_conversion_label = "_yFxCKCt6wQQmI_V2gM";
							var google_conversion_value = 0;
							/* ]]> */
							</script>
							<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
							</script>
							<noscript>
							<div style="display:inline;">
							<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/995444632/?value=0&amp;label=_yFxCKCt6wQQmI_V2gM&amp;guid=ON&amp;script=0"/>
							</div>
							</noscript>
				
				<?php else:?>
					<form name="frm1" id="frm1" method="post">
					<div class="dl">
						<label>Your Name</label>
						<div class="inp"><span class="txt"><input type="text" id="name" name="name" /></span></div>
					</div>
					<div class="dl">
						<label>Business Name</label>
						<div class="inp"><span class="txt"><input type="text" id="busi_name" name="busi_name" /></span></div>
					</div>
					<div class="dl">
						<label>Phone Number</label>
						<div class="inp"><span class="txt"><input type="text" id="phone" name="phone"  /></span></div>
					</div>
					<div class="dl">
						<label>&nbsp;</label>
						<input type="button" class="btn-getstarted" id="form_sub" value="" />
					</div>
					</form>
				<?php endif;?>	
				</div>
			</div>		
		</div>
				
		<div class="serve clearfix">
			<div class="item">
				<span class="img"><img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm2_images/serve-img-1.png" /></span>
				<span class="desc"><p><span class="blue">7 out of 10</span> consumers look<br /> online first for local business<br /> information</p></span>
			</div>
			<div class="item">
				<span class="img"><img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm2_images/serve-img-2.png" /></span>
				<span class="desc"><p>We will help you to achieve<br /> a higher rating on online<br /> review sites!</p></span>
			</div>
			<div class="item last">
				<span class="img"><img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm2_images/serve-img-3.png" /></span>
				<span class="desc"><p>Get new business from<br /> mobile users, and reward<br /> your most loyal customers!</p></span>
			</div>
		</div>
	</div>
	
	<div class="qwrap">
		<div class="container">
			<div class="quote-cnt clearfix">
				<div class="col3">
					<div class="col-ct">
						<div class="quote">
							<p>Quality Media has helped manage our review sites, which gives us time to focus on the core of our business. Since they have taken over, we have seen a HUGE increase in traffic and positive reviews. The investment has already paid tenfold!</p>
						</div>
						<div class="sender">
							<img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm2_images/img-quote1.png" alt="" align="left"/>
							<p>Peddy, PIzza Rustica<span>West Hollywood, CA</span></p>
						</div>
					</div>
				</div>
				<div class="col3">
					<div class="col-ct">
						<div class="quote">
							<p>You guys have engaged with all of our online reviews, and helped increase the frequency of visits because of it! Thanks for managing my account with such care, you guys have done terrific Job!</p>
						</div>
						<div class="sender">
							<img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm2_images/img-quote2.png" alt="" align="left"/>
							<p>Cedric T., Sofic Greek Resto<span>Los Angeles, CA</span></p>
						</div>
					</div>
				</div>
				<div class="col3">
					<div class="col-ct">
						<div class="quote">
							<p>TableCard has helped me realize how important my online reviews are.  My account rep has reached out to all of our reviews, optimized my account, and gotten us more business! This has been an A+ experience so far, thanks!</p>
						</div>
						<div class="sender">
							<img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm2_images/img-quote3.png" alt="" align="left"/>
							<p>Tom D., West Coast Tires<span>Los Angeles, CA</span></p>
						</div>
					</div>
				</div>
			</div>		
		</div>
	</div>
	
	<div id="content">
		<div class="container">
			<p class="space-3"></p>
			<center><img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm2_images/img-ss1.jpg" alt=""/></center>
			<p class="space-3"></p>
		</div>
	</div>
		
	<div id="footer">
		<div class="container">
			<div class="left"><p class="copy">&copy; 2013 QualityMedia</p></div>
			<div class="right">
				<div class="connect">
					<span>Connect with us</span>
					<a href="https://www.facebook.com/QualityMedia1"><img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm2_images/ico-fb.png" alt=""/></a>
					<a href="https://twitter.com/Quality_Media1"><img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm2_images/ico-tw.png" alt=""/></a>
					<a href="#"><img src="http://newclusterb2.s3.amazonaws.com/mintmob/qm2_images/ico-rss.png" alt=""/></a>
				</div>
				<p class="mail"><a href="mailto:helpdesk@qualitymedia.com">helpdesk@qualitymedia.com</a></p>
				<p class="number">+1 888 435 5518</p>
			</div>
			<p class="clear"></p>
		</div>
	</div>	
	
</body>
</html>