

function parseWords()
{
    var eLetters = FUSION.get.node("letters");
    var oMinLen  = FUSION.get.node("minlength");

    var sLetters = eLetters.value;
    var nMinLen  = oMinLen.value;

    var div = FUSION.get.node("wordlist");
    div.innerHTML = "";

    if( !FUSION.lib.isBlank(sLetters) )
    {
        $.ajax({
			type: "POST",
			beforeSend: function(xhr) {
				xhr.setRequestHeader("X-CSRF-Token", $('meta[name="csrf-token"]').attr('content'));
				xhr.setRequestHeader("Accept", "text/html");
			},
			url: "php/getwordlist.php",
			data: { "sLetters": sLetters, "nMinLength": nMinLen },
			success: function(result) {
                var response = JSON.parse(result);
                if(response['status'] == "success")
                {
                    var oResultDiv = FUSION.get.node("wordlist");
                    var aWords = response['content'];
                    var sWords = "<ul>";

                    for(var i = 0; i < aWords.length; i++)
                    {
                        sWords += "<li>" + aWords[i] + "</li>";
                    }

                    sWords += "</ul>";

                    oResultDiv.innerHTML = sWords;
                    document.getElementById("numresults").innerHTML = aWords.length;
                }
                FUSION.set.overlayMouseNormal();
                return false;
            },
            error: function(){
                FUSION.set.overlayMouseNormal();
                FUSION.error.showError("There was a problem retrieving the timesheet data");
    		}
		});
		return false;
    }
}