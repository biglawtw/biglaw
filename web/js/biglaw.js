	//Add smooth effect to scrollspy
	$("#nav ul li a[href^='#']").on('click', function(e) {

       // prevent default anchor click behavior
       e.preventDefault();

       // animate
       $('html, body').animate({
           scrollTop: $(this.hash).offset().top
         }, 5000, function(){
   
           // when done, add hash to url
           // (default click behaviour)
           window.location.hash = this.hash;
         });

    });
	
	$(function(){
		$('#navbar').scrollspy();
	})
	
	// ADD SLIDEDOWN ANIMATION TO DROPDOWN //
    $('.dropdown').on('show.bs.dropdown', function(e){
      $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
    });

    // ADD SLIDEUP ANIMATION TO DROPDOWN //
    $('.dropdown').on('hide.bs.dropdown', function(e){
      $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
    });
	
	$(document).on('click', '.accordion-toggle', function(event) {
        event.stopPropagation();
        var $this = $(this);
        var parent = $this.data('parent');
		var actives = $(parent).find('.collapse.in');

        // From bootstrap itself
        if (actives && actives.length) {
            hasData = actives.data('collapse');
            //if (hasData && hasData.transitioning) return;
            actives.collapse('hide');
        }
        var target = $this.attr('data-target') || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, ''); //strip for ie7
		$(target).collapse('toggle');
	});
	/*
	$(document).focusout('click', function(event) {
        var $this = $(this);
		console.log(event.target.id);
		if(!$this.parent().data('collapse') && !$this.data('collapse'))
		{
			event.stopPropagation();
			var actives = $(document).find('.collapse.in');
			if (actives && actives.length && actives.is('div')) {
				hasData = actives.data('collapse');
				//if (hasData && hasData.transitioning) return;
				actives.collapse('hide');
			}
		}
	});
	*/
	
	$(document).ready(function(){
		 $(window).scroll(function () {
				if ($(this).scrollTop() > 100) {
					$('#back-to-top').fadeIn();
				} else {
					$('#back-to-top').fadeOut();
				}
			});
			// scroll body to 0px on click
			$('#back-to-top').click(function () {
				$('#back-to-top').tooltip('hide');
				$('body,html').animate({
					scrollTop: 0
				}, 800);
				return false;
			});
			
			$('#back-to-top').tooltip('show');

	});