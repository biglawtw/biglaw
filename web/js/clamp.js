/**
 * Clamps a number. Based on Zevan's idea: http://actionsnippet.com/?p=475
 * params: val, min, max
 * Author: Jakub Korzeniowski
 * Agency: Softhis
 * http://www.softhis.com
 */
(function(){Math.clamp=function(a,b,c){return Math.max(b,Math.min(c,a));}})();