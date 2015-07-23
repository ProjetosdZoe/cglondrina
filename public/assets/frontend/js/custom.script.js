(function($) {
    "use strict";
    var revapi;
    var clickFlag = true;
    var shift = $(window).width() > 640 ? 350 : 150;

    /*----------  MOBILE DETECT  ----------*/
    var isMobile = {
	    Android: function() {
	        return navigator.userAgent.match(/Android/i);
	    },
	    BlackBerry: function() {
	        return navigator.userAgent.match(/BlackBerry/i);
	    },
	    iOS: function() {
	        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
	    },
	    Opera: function() {
	        return navigator.userAgent.match(/Opera Mini/i);
	    },
	    Windows: function() {
	        return navigator.userAgent.match(/IEMobile/i);
	    },
	    any: function() {
	        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
	    }
	};
	/*----------  //MOBILE DETECT  ----------*/

	/*----------  PRELOADER  ----------*/
	setTimeout(function(){
		$('#preloader').animate({'opacity' : '0'},300,function(){
			$('#preloader').hide();
			if(0 < $(window).scrollTop()){				
				setTimeout(function(){
					scrolling();
				}, 500)
			}	
		});

		$('.page-wrapper').animate({'opacity' : '1', 'height': '0'},500);
	},3000)
	/*----------  //PRELOADER  ----------*/

	/*----------  NAVIGATION ON PAGE  ----------*/
	$.scrollIt({
        topOffset: -50 // -50px
    });
	/*----------  //NAVIGATION ON PAGE  ----------*/
    
    if($('#clock').length){
            $('#clock').countdown( $('#clock').data("date") , function(event) {
                var $this = $(this).html(event.strftime('<h3>Próximo Evento <small>'+ $('#clock').data("title") +'</small> </h3>'
                + '<span><strong>%D</strong> Dias</span>'
                + '<span><strong>%H</strong> Horas</span>'
                + '<span><strong>%M</strong> Minutos</span>'
                + '<span><strong>%S</strong> Segundos</span>'));
            });
        }
    
    if($('input[name="phone"]').length){
            $('input[name="phone"]').mask("(##) ####-#####",{
                onKeyPress: function(leg){
                    var masks = ['(##) ####-#####', '(##) #####-####'];
                    mask = (leg.length < masks[0].length) ? masks[0] : masks[1];
                    $('input[name="phone"]').mask(mask, this);
                }
            });
        }
    
    $("[data-href]").click(function(){
        window.location = $(this).data("href");
        return false;
    });
    
    $("[data-news-fetch]").click(function(){
        $.post( '/request/FetchNews', { offset: $('[data-news-block]').length }, function(data){
            
            $.each(data, function(){
                $('[data-news-results]').append( this );
            });
            
        },"json");
    });
    
    $("[data-testimonies-fetch]").click(function(){
        $.post( '/request/FetchTestimonies', { offset: $('[data-testimony-block]').length }, function(data){
            
            $.each(data, function(){
                $('[data-testimonies-results]').append( this );
            });
            
        },"json");
    });
    
    $('form[data-comment-article]').submit(function(){
        
        $.post( '/request/PostComment' , { 
            url:     $(this).data('comment-article'), 
            type:    $(this).data('comment-type'), 
            name:    $("#commentName").val(), 
            email:   $("#commentMail").val(), 
            message: $("#commentMessage").val()
        } ,
        function(data){
            
            $(".comments-feed").append( data );
                
        },"json");
        
        return false;
    });
    
    $('#newsletter-send').click(function(){
        
        var flag = true ;
        if(!/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/.test($('#newsletter-mail').val())){
            $('#newsletter-mail').val('').attr('placeholder','E-Mail Inválido').addClass('input-error');
            flag = false;
        }
        if( $('#newsletter-name').val() == "" ){ $('#newsletter-name').addClass('input-error'); flag = false; }
        if( $('#newsletter-mail').val() == "" ){ $('#newsletter-mail').addClass('input-error'); flag = false; }
        
        if(flag){
            $('#newsletter-title').html("Cadastrado Com Sucesso !");
            $.post( '/request/Subscribe' , { 
                name: $('#newsletter-name').val(), 
                email: $('#newsletter-mail').val()
            },
            function(){
                $('#newsletter-name').val('');
                $('#newsletter-mail').val('');
            });
        }else{
        	$('#newsletter-title').html("Preencha Todos os Campos !"); 
        }

        setTimeout(function(){
        	$('#newsletter-title').html("RECEBA NOVIDADES DA CG EM SEU E-MAIL!");
        	$('#newsletter-mail').attr("placeholder","E-Mail");
            $('#newsletter-name').removeClass('input-error');
            $('#newsletter-mail').removeClass('input-error');
        }, 3000)
        
        return false;
    });
    
    $('[data-search]').filtr({
        target : ".blog-block"
    });
    
    $('[data-search-filter]').click(function(){
        $('[data-search]').val( $(this).data("search-filter") ).keyup();
        return false;
    });

	$('.images-bg').each(function(){
		$(this).css({
			'background-image': 'url(' +$('>img', this).hide().attr('src') +')'
		});
	});

	function parallaxInit() {
		if (!isMobile.any())
		{
			$('.parallax').parallax("50%", 0.2);
		}
	}	
	parallaxInit();	

	setTimeout(function(){
		/*----------  SKILLS SLIDER  ----------*/
		$('.skills-slider .flexslider').flexslider({slideshowSpeed: 6000});
		/*----------  //SKILLS SLIDER  ----------*/

		/*----------  SINGLE WORK SLIDER  ----------*/
		$('.single-work .flexslider').flexslider({slideshowSpeed: 6000});
		/*----------  //SINGLE WORK SLIDER  ----------*/

		/*----------  SINGLE WORK SLIDER  ----------*/
		$('.blog-block .flexslider').flexslider({slideshowSpeed: 6000});
		/*----------  //SINGLE WORK SLIDER  ----------*/

		/*----------  CLIENTS FEEDBACK SLIDER  ----------*/
		$('.clients-feedback .flexslider').flexslider({
            slideshowSpeed: 6000,
            minItems: 5,
		    maxItems: 5
        });
		/*----------  //CLIENTS FEEDBACK SLIDER  ----------*/

		/*----------  WORK SLIDER  ----------*/
		if($(window).width() < 361){
		$('.work .flexslider').flexslider({
				prevText: "", 
				nextText: "", 
				slideshow: false,
				animation: "slide",
				itemWidth: 200,
				minItems: 1,
		    	maxItems: 1,
			});
		}else if($(window).width() < 801){
			$('.work .flexslider').flexslider({
				prevText: "", 
				nextText: "", 
				slideshow: false,
				animation: "slide",
				itemWidth: 200,
				minItems: 2,
		    	maxItems: 2,
			});
		}else{
			$('.work .flexslider').flexslider({
				prevText: "", 
				nextText: "", 
				slideshow: false,
				animation: "slide",
				itemWidth: 200,
				minItems: 2,
		    	maxItems: 3
			});
		}
		/*----------  //WORK SLIDER  ----------*/
	}, 2000);
	
	if($('.work-block .zoom').length){
		$('.work-block .zoom').magnificPopup({
			type: 'image',
			gallery: {
				enabled: true
			},
			zoom: {
				enabled: true,
				duration: 300
			}
		});
	}	

	$('.navbar-link-bg').each(function(){
		var width = $(this).width();
		$(this).css({
			'border-left': width/2 + 'px solid transparent',
			'border-right': width/2 + 'px solid transparent'
		})
		$('span', this).width(width).css('left', width/2*-1);
	})
	/*----------  VIDEO  ----------*/
	var min_w = 300; // minimum video width allowed
    var vid_w_orig;  // original video dimensions
    var vid_h_orig;

	setTimeout(function(){	        
        vid_w_orig = parseInt($('#video-container video').attr('width'));
        vid_h_orig = parseInt($('#video-container video').attr('height'));
        
        $(window).resize(function () { resizeToCover(); });
        $(window).trigger('resize');
    }, 2000);

    function resizeToCover() {

	    // set the video viewport to the window size
	    $('#video-container').width($(window).width());
	    $('#video-container').height($(window).height());

	    // use largest scale factor of horizontal/vertical
	    var scale_h = $(window).width() / vid_w_orig;
	    var scale_v = $(window).height() / vid_h_orig;
	    var scale = scale_h > scale_v ? scale_h : scale_v;

	    // don't allow scaled width < minimum video width
	    if (scale * vid_w_orig < min_w) {scale = min_w / vid_w_orig;};

	    // now scale the video
	    $('#video-container video').width(scale * vid_w_orig);
	    $('#video-container video').height(scale * vid_h_orig);
	    // and center it by scrolling the video viewport
	    $('#video-container').scrollLeft(($('#video-container video').width() - $(window).width()) / 2);
	    $('#video-container').scrollTop(($('#video-container video').height() - $(window).height()) / 2);

    }

    /*----------  //VIDEO  ----------*/
    $('#bs-example-navbar-collapse-1 li > a').on('click',function(){
    	clickFlag = false;
    	$('#bs-example-navbar-collapse-1 li').removeClass('active');
    	
		$(this).parent().addClass('active');
		setTimeout(function(){
			clickFlag = true
		}, 600);
    })
	function scrolling(){
		var scroll = $(window).scrollTop() + $(window).height();

		/*----------  HEADER  ----------*/
		if($(window).scrollTop() > 40){
			$('#haeder').addClass('fixed');
		}
        else{
            
			$('#haeder').removeClass('fixed');
			if(clickFlag){
				$('#bs-example-navbar-collapse-1 li').removeClass('active');
				$('#bs-example-navbar-collapse-1 li:eq(0)').addClass('active');
			}
			
		}
		/*----------  //HEADER  ----------*/
        
        if($('[data-scroll-index="2"]').length && parseInt($('[data-scroll-index="2"]').offset().top)+shift < scroll){	
			if(clickFlag){
				$('#bs-example-navbar-collapse-1 li').removeClass('active');
				$('#bs-example-navbar-collapse-1 li:eq(1)').addClass('active');
			}
		}
        
        if($('[data-scroll-index="3"]').length && parseInt($('[data-scroll-index="3"]').offset().top)+shift < scroll){	
			if(clickFlag){
				$('#bs-example-navbar-collapse-1 li').removeClass('active');
				$('#bs-example-navbar-collapse-1 li:eq(2)').addClass('active');
			}
		}
        
        if($('[data-scroll-index="4"]').length && parseInt($('[data-scroll-index="4"]').offset().top)+shift < scroll){	
			if(clickFlag){
				$('#bs-example-navbar-collapse-1 li').removeClass('active');
				$('#bs-example-navbar-collapse-1 li:eq(3)').addClass('active');
			}
		}
        
        if($('[data-scroll-index="5"]').length && parseInt($('[data-scroll-index="5"]').offset().top)+shift < scroll){	
			if(clickFlag){
				$('#bs-example-navbar-collapse-1 li').removeClass('active');
				$('#bs-example-navbar-collapse-1 li:eq(4)').addClass('active');
			}
		}
        
        if($('[data-scroll-index="6"]').length && parseInt($('[data-scroll-index="6"]').offset().top)+shift < scroll){	
			if(clickFlag){
				$('#bs-example-navbar-collapse-1 li').removeClass('active');
				$('#bs-example-navbar-collapse-1 li:eq(5)').addClass('active');
			}
		}
        
        if($('[data-scroll-index="7"]').length && parseInt($('[data-scroll-index="7"]').offset().top)+shift < scroll){	
			if(clickFlag){
				$('#bs-example-navbar-collapse-1 li').removeClass('active');
				$('#bs-example-navbar-collapse-1 li:eq(6)').addClass('active');
			}
		}
        
        if($('[data-scroll-index="8"]').length && parseInt($('[data-scroll-index="8"]').offset().top)+shift < scroll){	
			if(clickFlag){
				$('#bs-example-navbar-collapse-1 li').removeClass('active');
				$('#bs-example-navbar-collapse-1 li:eq(7)').addClass('active');
			}
		}
		
	}




	/*----------  FUNCTION FOR WINDOW SCROLL  ----------*/
	$(window).on('scroll', function(){
		scrolling();
	});
	/*----------  //FUNCTION FOR WINDOW SCROLL  ----------*/

    /*----------  LOGO SLIDER  ----------*/
    setTimeout(function(){
		$('#members_slider').carouFredSel({
			mousewheel: true,
			responsive: true,
            items: $(window).width() > 640 ? 5 : 2,
			scroll: 1,
            height: 280,
            swipe: {
				onMouse: true,
				onTouch: true
			}
		},{
             wrapper: {
				classname: "members_slider"
			}
        });
    }, 2000);    
	/*----------  //LOGO SLIDER  ----------*/

    revapi = jQuery('.tp-banner').revolution(
	{
		delay:9000,
		startwidth:1170,
		startheight:610,
		hideThumbs:10,
		fullWidth:"off",
		fullScreen:"on",
		fullScreenOffsetContainer: "",
		navigationStyle:"preview4" 
	});

    /*----------  WORKS SLIDER  ----------*/
	setTimeout(function(){
		var $container = $('#worksContent');

	    $container.isotope({
	      itemSelector : '.slide',
	      getSortData : {
	        category : function( $elem ) {
	          return $elem.attr('data-category');
	        }
	      }
	    });

	    var $optionSets = $('.works-category'),
	      $optionLinks = $optionSets.find('a');

		$optionLinks.click(function(){
			var $this = $(this);
			// don't proceed if already selected
			if ( $this.hasClass('active') ) {
				return false;
			}
			var $optionSet = $this.parents('.works-category');
			$optionSet.find('.active').removeClass('active');
			$this.addClass('active');

			// make option object dynamically, i.e. { filter: '.my-filter-class' }
			var options = {},
			key = $optionSet.attr('data-option-key'),
			value = $this.attr('data-option-value');
			// parse 'false' as false boolean
			value = value === 'false' ? false : value;
			options[ key ] = value;
			$container.isotope( options );

			return false;
		});
	}, 1000);
	/*----------  //WORKS SLIDER  ----------*/

	/*----------  PEDIDOS FUNCTION  ----------*/
    
    $('#ajax-request-form a.button').on('click', function(){
		var thiz = this;
		var flag = true;
        
        var name = $('#ajax-request-form input[name="name"]').attr('placeholder');
        var email = $('#ajax-request-form input[name="email"]').attr('placeholder');
        var state = $('#ajax-request-form input[name="state"]').attr('placeholder');
        var city = $('#ajax-request-form input[name="city"]').attr('placeholder');
        var message = $('#ajax-request-form textarea[name="message"]').attr('placeholder');
        
        if(!/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/.test($('#ajax-request-form input[name="email"]').val())){
            $('#ajax-request-form input[name="email"]').val('').attr('placeholder','E-Mail Inválido').addClass('input-error');
            flag = false;
        }
        
        if(!$('#ajax-request-form input[name="name"]').val() != "" ){
            $('#ajax-request-form input[name="name"]').val('').attr('placeholder','* Campo Obrigatório').addClass('input-error');
            flag = false;
        }
        
        if(!$('#ajax-request-form input[name="email"]').val() != "" ){
            $('#ajax-request-form input[name="email"]').val('').attr('placeholder','* Campo Obrigatório').addClass('input-error');
            flag = false;
        }
        
        if(!$('#ajax-request-form textarea[name="message"]').val() != "" ){
            $('#ajax-request-form textarea[name="message"]').val('').attr('placeholder','* Campo Obrigatório').addClass('input-error');
            flag = false;
        }
        
        if(!$('#ajax-request-form input[name="state"]').val() != "" ){
            $('#ajax-request-form input[name="state"]').val('').attr('placeholder','* Campo Obrigatório').addClass('input-error');
            flag = false;
        }
        
        if(!$('#ajax-request-form input[name="city"]').val() != "" ){
            $('#ajax-request-form input[name="city"]').val('').attr('placeholder','* Campo Obrigatório').addClass('input-error');
            flag = false;
        }

        if(flag){
            $("#ajax-request-form").submit(); 
            $(thiz).addClass('success').find("span").html('<br>enviado!');
            $('#ajax-request-form input, #ajax-request-form textarea').addClass('input-success');
        }else{
        	$(thiz).addClass('error').find("span").html('<br>Tente Novamente!'); 
        }

        setTimeout(function(){
        	$(thiz).removeClass('error success').find("span").html('envie seu <br> pedido agora!'); 
        	$('#ajax-request-form input, #ajax-request-form textarea').removeClass('input-success input-error');
            $('#ajax-request-form input[name="name"]').attr('placeholder',name);
            $('#ajax-request-form input[name="email"]').attr('placeholder',email);
            $('#ajax-request-form input[name="state"]').attr('placeholder',state);
            $('#ajax-request-form input[name="city"]').attr('placeholder',city);
            $('#ajax-request-form textarea[name="message"]').attr('placeholder',message);
        }, 3000)
        
        return false;
    });
    
    $("#ajax-request-form").submit(function() {
		
        $.post( '/request/Pedidos',{ 
            name : $('#ajax-request-form input[name="name"]').val(),
            email : $('#ajax-request-form input[name="email"]').val(),
            phone : $('#ajax-request-form input[name="phone"]').val(),
            state : $('#ajax-request-form input[name="state"]').val(),
            city : $('#ajax-request-form input[name="city"]').val(),
            message : $('#ajax-request-form textarea[name="message"]').val()
        } , 
        function(data){
            console.log(data);
            
        } ,"json");
        
		return false;
	});
    
	/*----------  //PEDIDOS FUNCTION  ----------*/
    
    /*----------  SUBMIT FUNCTION  ----------*/
    $('.contact form .button').on('click', function(){
		var thiz = this;
		var flag = true;
        
        if(!/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/.test($('.contact input[name="email"]').val())){
            $('.contact input[name="email"]').val('').attr('placeholder','E-Mail Inválido').addClass('input-error');;
            flag = false;
        }
        
        if(!$('.contact input[name="name"]').val() != "" ){
            $('.contact input[name="name"]').val('').attr('placeholder','* Campo Obrigatório').addClass('input-error');;
            flag = false;
        }
        
        if(!$('.contact input[name="email"]').val() != "" ){
            $('.contact input[name="email"]').val('').attr('placeholder','* Campo Obrigatório').addClass('input-error');;
            flag = false;
        }
        
        if(!$('.contact textarea[name="message"]').val() != "" ){
            $('.contact textarea[name="message"]').val('').attr('placeholder','* Campo Obrigatório').addClass('input-error');;
            flag = false;
        }

        if(flag){
            $("#ajax-contact-form").submit(); 
            $(thiz).addClass('success').find("span").html('enviado!');
            $('.contact input, .contact textarea').addClass('input-success');
            $('.contact input').removeClass('input-error');      	
        }else{
        	$(thiz).addClass('error').find("span").html('Tente Novamente!'); 
        }

        setTimeout(function(){
        	$(thiz).removeClass('error success').find("span").html('envie sua<br>mensagem<br>agora'); 
        	$('.contact input, .contact textarea').removeClass('input-success input-error');
        }, 3000)
        
        return false;
    });

	$("#ajax-contact-form").submit(function() {
		
        $.post( '/request/ContactUs',{ 
            name : $('#ajax-contact-form input[name="name"]').val(),
            email : $('#ajax-contact-form input[name="email"]').val(),
            phone : $('#ajax-contact-form input[name="phone"]').val(),
            message : $('#ajax-contact-form textarea[name="message"]').val()
        } , 
        function(data){
            
            
            
        } ,"json");
        
		return false;
	});
	/*----------  //SUBMIT FUNCTION  ----------*/

})(jQuery); 