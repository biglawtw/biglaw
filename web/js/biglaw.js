var projectname = 'BigLaw';
var version = '201407171353';
var serverURL = "http://server/";

var caseperrequest = 10;
var requestPeriod = 100;

var defualtpage = "search";
var animatePeriod = 100;

var scrolltopoffset = 100;

$( document ).ready(function() {
	ajaxload("#header", "ajax/header.html");
	ajaxload("#footer", "ajax/footer.html");
	var spinner = new Spinner().spin();
	$("#loading")[0].appendChild(spinner.el);
});

function gasendpageview() {
	ga('send', 'pageview', {
		'page': window.location.protocol +
				'//' + window.location.hostname +
				window.location.pathname +
				window.location.search + 
				window.location.hash
	});
}