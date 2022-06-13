// JavaScript Document

$(document).ready(function(){
  $('.home-banner-slider').slick({
  dots: true,
  infinite: true,
  speed: 500,
  fade: true,
  cssEase: 'linear',
  autoplay: true,
  arrows: false,
  });
});

$(document).ready(function(){
  $('.vac-details-slider-image').slick({
  dots: true,
  infinite: true,
  arrows: true,
  speed: 500,
  autoplay: false,
  slidesToShow: 1,
  });
});



$(document).ready(function(){
  $('.sub-banner-slider').slick({
  dots: true,
  infinite: true,
  speed: 500,
  fade: true,
  cssEase: 'linear',
  autoplay: true,
  arrows: true,
  });
});




$(document).ready(function(){
  $('.home-vacancy-slider').slick({
  dots: false,
  infinite: true,
  arrows: true,
  speed: 500,
  centerMode: true,
  centerPadding: '200px',
  autoplay: true,
  slidesToShow: 3,
	  responsive: [
		  {
      breakpoint: 1800,
      settings: {
        slidesToShow: 5,
      }
    },
		  
		  {
      breakpoint: 1550,
      settings: {
        slidesToShow: 3,
      }
    },
		  
		   {
      breakpoint: 1150,
      settings: {
        slidesToShow: 2,
      }
    },
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 2,
		centerPadding: '150px',
      }
    },
    {
      breakpoint: 769,
      settings: {
        slidesToShow: 2,
		  centerPadding: '50px',
      }
    },
    {
      breakpoint: 500,
      settings: {
       slidesToShow: 1,
	  centerPadding: '50px',
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
   
 
  });
});


$(document).ready(function(){
  $('.home-project-slider').slick({
  dots: true,
  infinite: true,
  speed: 500,
  fade: true,
  cssEase: 'linear',
  autoplay: true,
  arrows: false,
  autoplay: true,
  slidesToShow: 1,
  });
});


$(document).ready(function(){
   new WOW().init();
});



// go top button
 
             
	var btn = $('#myBtn');

$(window).scroll(function() {
  if ($(window).scrollTop() > 300) {
    btn.addClass('show');
  } else {
    btn.removeClass('show');
  }
});

btn.on('click', function(e) {
  e.preventDefault();
  $('html, body').animate({scrollTop:0}, '300');
});
         
 

// select2 listbox starts here
 


	$(function() {
  $('.normal-list').select2();
});

 

$( function() {
    $( "#slider-range" ).slider({
      range: true,
      min: 500,
      max: 5000,
      values: [ 1500, 4000 ],
      slide: function( event, ui ) {
        $( "#amount" ).val( "KD" + ui.values[ 0 ] + " - KD" + ui.values[ 1 ] );
      }
    });
    $( "#amount" ).val( "KD" + $( "#slider-range" ).slider( "values", 0 ) +
      " - KD" + $( "#slider-range" ).slider( "values", 1 ) );
  } );

 

// select2 listbox ends here


// upload file

$('.upload').on('change',function(event){
        var files = event.originalEvent.target.files;    
        $(this).parent().siblings('#uploadFile').val(files[0].name);
		$(this).parent().siblings('#uploadFile1').val(files[0].name);
  });



$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	     e.target
     e.relatedTarget
  $('.home-banner-slider').slick('setPosition');
})
