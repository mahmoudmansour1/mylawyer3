<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="description" content="We have served clients of all range, from individuals to big organization. We provide personal solutions based on one’s situation">
<meta name="keywords" content="My Lawyer, clients, Legal, solutions, personal ">
<meta name="author" content="Mawaqaa">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="X-UA-Compatible" content="ie=edge" />
<link rel="icon" href="favicon.ico">
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css?family=Cairo:400,700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="css/owl.carousel.css" rel="stylesheet" />
<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" href="css/slick.css">
<link rel="stylesheet" href="css/select2.css" >
<link rel="stylesheet" href="css/animate.css" >
<link href="css/custom.css" rel="stylesheet" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://kit.fontawesome.com/03a46c431d.js" crossorigin="anonymous"></script><link href="css/media_q.css" rel="stylesheet" />
@if(app()->getLocale() == "ar")
<link href="css/custom_ar.css" rel="stylesheet" />
@endif
<title>My Lawyer</title>
<style>
.pop-msg {
    float: left;
    margin: 0px;
    padding: 0px;
    width: 100%;
    text-align: center;
}
.pop-msg h2 {
    float: left;
    margin: 0px;
    padding: 0px;
    width: 100%;
    color: #5b529b;
    font-weight: 800;
    font-size: 31px;
    text-align: center;
}
.pop-msg p {
    float: left;
    margin: 7px 0 8px 0;
    padding: 0px;
    width: 100%;
    color: #5b529b;
    font-weight: 400;
    font-size: 22px;
    text-align: center;
    line-height: normal;
}
.msg-info-hdr {
    float: left;
    margin: 0px;
    padding: 0px;
    width: 100%;
    border: none;
}
.msg-info-hdr .model-header-general {
    text-align: right;
    width: 100%;
}
.msg-info-hdr .model-header-general .general-close {
    text-align: right;
    color: #5b529b;
    float: right;
    margin: 0px;
}
.pop-msg .general-button {
    background: #5b529b;
    color: #fff;
    border: none;
    padding: 10px 35px 10px 35px;
    margin: 25px 0 25px 0;
}
</style>
</head>
<body>
<div class="wrapper">
  <div class="container-lg">
    <header>
      <div class="row">
        <div class="col-lg-2 col-sm-12 logo-hold"> <a href="#home"  class="logo-link" > <img src="images/logo_my_lawyer.png"> </a> </div>
        <div class="col-lg-10 col-sm-12 nav-hold">
          <nav class="navbar navbar-expand-lg navbar-light ">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
            <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation"> <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"> <img src="images/home_icon.svg"> </a> </li>
                <li class="nav-item" role="presentation"> <a class="nav-link" id="about-tab" data-toggle="tab" href="#about" role="tab" aria-controls="profile" aria-selected="false">@lang('website.about_us')</a> </li>
                <li class="nav-item" role="presentation"> <a class="nav-link" id="privacy-tab" data-toggle="tab" href="#privacy" role="tab" aria-controls="contact" aria-selected="false">@lang('website.privacy_page_label')</a> </li>
				  
				  
					
					
                <li class="nav-item" role="presentation"> <a class="nav-link" id="lawyer-tab" data-toggle="tab" href="#lawyer" role="tab" aria-controls="contact" aria-selected="false">@lang('website.terms_conditionst_page_label')</a> </li>
				  
				  
               
                <li class="nav-item" role="presentation"> <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false"> @lang('website.contact_us_label')</a> </li>
                @if(app()->getLocale() == "ar")
                <li class="nav-item" role="presentation"> <a class="nav-link"    
                href="change_lang/en"  > <i class="fas fa-globe"></i>  English </a> </li>
                @else
                <li class="nav-item" role="presentation"> <a class="nav-link"    
                href="change_lang/en"  > <i class="fas fa-globe"></i>  English </a> </li>
                @endif


              </ul>
              
              <!--  
    <ul class="navbar-nav">
      <li class="nav-item ">
        <a class="nav-link" href="#">Home </a>
      </li>
		  <li class="nav-item">
        <a class="nav-link " href="#">About Us</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">  Contact Us</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Terms and Condition</a>
      </li>
    
    </ul>--> 
            </div>
          </nav>
        </div>
      </div>
    </header>
    <section class="content-sec">
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <div class="row">
            <div class="col-lg-4 col-sm-12">
              <div class="about-hold">
                <!-- <p>We have served clients of all range, from individuals to big organization. We provide personal solutions based on one’s situation </p> -->
                <h2> @lang('website.download_app_from')</h2>
                <div class="store-link-hold">
                <div class="str-link"> <a href="{!!  $setting->google_store_link !!}"> <img src="images/google_play.jpg" > </a> </div>
                <div class="str-link"> <a href="{!!  $setting->app_store_link !!}"> <img src="images/apple_store.jpg" > </a> </div>

                </div>
              </div>
            </div>
            <div class="col-lg-4 col-sm-12">
              <div class="mob-banner">
                <ul class="home-banner-slider">
                  <li> <img src="images/banner_1.png"> </li>
                  <li> <img src="images/banner_2.png"> </li>
                  <li> <img src="images/banner_3.png"> </li>
                </ul>
              </div>
            </div>
            <div class="col-lg-4 col-sm-12">
              <div class="service-sec">
                <h2> @lang('website.download_app_title') <br>
                  <!-- and get all Services Online -->
                </h2>
                <img src="images/Group 5.png"> </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="about" role="tabpanel" aria-labelledby="about-tab">
          <div class="about-us-hold  general-content-hold">
            <div class="page-title">
              <h2> @lang('website.about_us')</h2>
            </div>
            <div class="row">
              <div class="col-lg-8  col-sm-12">
                <div class="about-txt">
                {!!  $setting->about_us_footer !!}
                  <!-- <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum rutrum ligula ac commodo blandit. Cras commodo a nisi eu efficitur. Integer a leo non eros laoreet aliquet interdum non metus. Fusce consectetur ornare facilisis. Donec non nisl ante. Mauris a mi quis felis porta aliquet. In sit amet pretium lacus. Mauris tristique varius lacinia. Nulla nisl diam, venenatis id ipsum vel, molestie suscipit sem. In sollicitudin consequat leo, eu finibus neque malesuada at. <br>
                    <br>
                    Proin quis ipsum ac magna bibendum volutpat. Fusce ac mattis urna. Aliquam quis metus eu mi pretium venenatis. Nunc placerat tortor non sodales facilisis. Praesent ultrices ligula sit amet luctus semper. Sed interdum, justo et finibus porta, quam augue posuere eros, ut consequat erat elit eu ipsum. Integer purus augue, rutrum eu massa non, gravida hendrerit mauris. <br>
                    <br>
                  </p>
                  <h2> Our Values </h2>
                  <p> Nunc ultricies dui ut sapien cursus vulputate. Phasellus pharetra aliquam urna eget luctus. Nunc egestas mauris ut purus consequat placerat. Nulla at lorem nec justo ultrices pellentesque. Sed pharetra orci quis ornare vehicula. In purus neque, luctus vel elementum quis, e </p> -->
                </div>
              </div>
              <div class="col-lg-4 col-sm-12">
                <div class="about-image"> <img src="images/about.jpg"> </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="privacy" role="tabpanel" aria-labelledby="privacy-tab">
          <div class="privacy-hold  general-content-hold">
            <div class="page-title">
              <h2> @lang('website.privacy_page_label')</h2>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="about-txt">
                {!!  $setting->chassis_information !!}

                
                </div>
              </div>
            </div>
          </div>
        </div>
		  
		
		 
		  
		  
		  <div class="tab-pane fade" id="lawyer" role="tabpanel" aria-labelledby="lawyer-tab">
          <div class="privacy-hold  general-content-hold">
            <div class="page-title">
              <h2> @lang('website.terms_conditionst_page_label')</h2>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="about-txt">

                {!!  $setting->our_vision_footer !!}



                </div>
              </div>
            </div>
          </div>
        </div>
		  
		  
		  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
          <div class="privacy-hold  general-content-hold">
            <div class="page-title">
              <h2>@lang('website.contact_us_label')</h2>
			<div class="contact-hold">
				<ul>
				<li>
				<div class="contact-item">
					<img src="images/email.svg">
					<h3> @lang('website.email_req') </h2>
					<a href="#"> {!!  $setting->email_contact_us !!} </a>
				</div>
					</li>
					
					<li>
				<div class="contact-item">
					<img src="images/phone.svg">
					<h3> @lang('website.mobile_req_label') </h2>
					<a href="#"> {!!  $setting->call_phone !!} </a>
				</div>
					</li>
					
							<li>
				<div class="contact-item">
					<img src="images/location.svg">
					<h3> @lang('website.address_label') </h2>
					<a href="#">
                        Kuwait
                    <!-- {!!  $setting->address !!} -->
 </a>
				</div>
					</li>
				
				</ul>	
			</div>
				
            </div>
             
          </div>
        </div>
		  
      </div>
    </section>
  </div>
  <footer class="wow slideInUp fixed-bottom"  data-wow-duration="1s">
	  
    <div class="circle-btm"> </div>
    <p> © Copyright 2022 My Lawyer. All Rights Reserved </p>
	  <div class="social">
	  <ul>
		  <li>
		  <a href="{!!  $setting->instagram !!}"> <img src="images/instagram.svg">  </a>
		  </li>
		  
		  <li>
		  <a href="{!!  $setting->facebook !!}"> <img src="images/facebook.svg">  </a>
		  </li>
		  
		  <li>
		  <a href="{!!  $setting->twitter !!}"> <img src="images/twitter.svg">  </a>
		  </li>
	  </ul>
	  </div>
  </footer>
</div>
<div class="modal fade bd-example-modal-lg" id="info-pop" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header msg-info-hdr">
        <div class="model-header-general "> <a href="#" type="button" class="close general-close" data-dismiss="modal" aria-label="Close"> X </a> </div>
      </div>
      <div class="modal-body ">
        <div class="pop-msg">
          <h2> Thank You </h2>
          <p> Your registration has been successfully completed. </p>
          <button class="general-button" data-dismiss="modal" aria-label="Close"> OK </button>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="js/jquery-3.4.1.js"></script> 
<script src="js/bootstrap.js"></script> 
<script src="js/tilt.jquery.min.js"></script> 
<script src="js/jquery-ui.js"></script> 
<script src="js/custom.js"></script> 
<script src="js/slick.js"></script> 
<script src="js/select2.min.js"></script> 
<script src="js/wow.min.js"></script> 
<script src="js/jquery.ui.touch-punch.js"></script> 

<!-- <script>
	 $(document).ready(function(){
$('.js-tilt').tilt({
    axis: x,
})
});
</script>--> 

<script>
	
	
/* $(document).ready(function () {
 setTimeout(function() {
    $('#info-pop').modal('show');
}, 1000);
});
	*/

	
</script>
	
	<script>
	
		$('.logo-link').click(function() {
    
    $('[href="#home"]').tab('show');
});
	</script>

</body>
</html>