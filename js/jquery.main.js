$(document).ready(function() { $('#slides').slides({ preload: true, preloadImage: '../images/ajax-loader.gif', play: 6000, pause: 1, hoverPause: true });
$('.password').pstrength(); $('.password_regUserPanel').pstrength(); });

function mapa(kolik) { if(kolik < 8) { document.getElementById('ma').style.backgroundPosition = "0px -"+(kolik*160)+"px";} else { document.getElementById('ma').style.backgroundPosition = "-200px -"+((kolik-7)*160)+"px"; } }