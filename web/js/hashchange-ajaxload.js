var currenthash, currentpara;
var lasthash, lastpara;
var scrolltopid;

$( document ).ready(function() {
	updatehash();
	loadpage();
	
	$(window).hashchange( function() {
		//console.log("hashchange");
        
		var temphash = window.location.hash;
		temphash = ( temphash.replace( /^#/, '' ) || 'blank' );
        
		var temppara = temphash.split('@')[0].split('&');
		
		if(temppara[0] != currentpara[0]) {
			updatehash();
			loadpage();
		} else {
			updatehash();
			if(typeof loadelse != 'undefined')
				loadelse();
            else
                loadpage();
		}
	});
});

$( document ).ajaxStart(function() {
	$( "#error" ).animate({opacity: 0}, animatePeriod, function() {
		$( "#error" ).addClass("hidden");
	});
});

$( document ).ajaxError(function( event, jqxhr, settings, exception ) {
	var msg = "<h1>Sorry :(</h1><br>Error Occured:<br>";
	$( "#error" ).html( msg + jqxhr.status + " " + jqxhr.statusText + "<br>" + exception);
	
	$( "#error" ).removeClass("hidden");
	$( "#error" ).animate({opacity: 1}, animatePeriod);
});

function updatehash() {
	//console.log("updatehash");
	gasendpageview();
	
	if(typeof window.location.hash == 'undefined' || window.location.hash == '')
		window.location.hash = defualtpage;
		
	lasthash = currenthash;
	lastpara = currentpara;
	
	currenthash = window.location.hash;
	currenthash = ( currenthash.replace( /^#/, '' ) || 'blank' );
	currentpara = currenthash.split('@')[0].split('&');
    
    if(currenthash && typeof unloadpage != 'undefined' && currentpara[0] != lastpara[0])
        unloadpage();
    
    if(currenthash.split('@').length == 2)
        scrolltopid = currenthash.split('@')[1];
    else
        scrolltopid = "";
}

function loadpage(completeEvent) {
	//console.log("loadpage");
	var page = currentpara[0];
	$( "#error" ).html('');
	$( "#context" ).html('');
	ajaxload("#context", "ajax/" + page + ".html", function() {
		if(typeof loadelse != 'undefined') 
			loadelse();
        else
            loadpage();
	});
}

function scrolltophashid() {
    if(scrolltopid && $("#" + scrolltopid)[0]) {
        var scrolltop = $("#" + scrolltopid).offset().top - scrolltopoffset;
        $("html, body").animate({ scrollTop: scrolltop }, animatePeriod);
        //console.log(scrolltop);
    } else {
        $("html, body").animate({ scrollTop: 0 }, animatePeriod);
    }
}

function ajaxload( id, path, completeEvent )
{
	//console.log("ajaxload:" + id);
	$( id ).clearQueue();
    $( id ).stop();

	$( "#loading" ).removeClass("hidden"); //show loading animation
	$( "#loading" ).animate({opacity: 1}, animatePeriod, function() {
		$( id ).animate({opacity: 0}, animatePeriod, function() {
			// Animation complete.
			$( id ).load( path, function( response, status, xhr ) {
				if ( status == "success" ) {
					$( id ).animate({opacity: 1}, animatePeriod);
				}
				$( "#loading" ).animate({opacity: 0}, animatePeriod, function() {
					$( "#loading" ).addClass("hidden"); //hide loading animation
					if(typeof completeEvent != 'undefined')
						completeEvent();
				});
			});
		});
	});
}