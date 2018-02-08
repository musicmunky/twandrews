$(document).ready(function(){

    var oStartTime   = Date.parse('22 Jan 2018 00:00:00 EST');
    var oCurrentTime = Date.parse(new Date());
    var nWeekNumber  = Math.ceil( ((oCurrentTime - oStartTime) / 1000) / (7 * 86400) );
    var sWeekNumber  = FUSION.lib.padZero(nWeekNumber, 2);

    $('html, body').animate({
        scrollTop: $('#week_' + sWeekNumber).offset().top - 100
    }, 'slow');

});

