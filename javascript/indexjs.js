jQuery(document).ready(function() {
	$('.remlnk').click(function(){
		removeItem(this.id);
	});
	$('.editlnk').click(function(){
		getItemInfo(this.id);
	});
	$("#addlnk").click(function(){
		showAddItem({"pageid":0});
	});
});


function updateItem()
{
	try {
		var id = parseInt(FUSION.get.node("ni_pageid").value);
		var pname = FUSION.get.node("ni_pagename").value;
		var ptype = FUSION.get.node("ni_pagetype").value;
		var plink = FUSION.get.node("ni_pagelink").value;
		var pstat = FUSION.get.node("ni_pagestat").value;
		var pdesc = FUSION.get.node("ni_pagedesc").value;

		var errstr = "";
		var errcnt = 80;

		if(FUSION.lib.isBlank(pname)) {
			errstr += "<br>Project Name";
			errcnt += 20;
		}
		if(FUSION.lib.isBlank(ptype)) {
			errstr += "<br>Project Type";
			errcnt += 20;
		}
		if(FUSION.lib.isBlank(plink)) {
			errstr += "<br>Project URL / Link";
			errcnt += 20;
		}
		if(ptype == "project" && FUSION.lib.isBlank(pstat)) {
			errstr += "<br>Project Status";
			errcnt += 20;
		}
		if(!FUSION.lib.isBlank(errstr)) {
			FUSION.lib.alert({"message":"Please make sure the following fields are not blank:" + errstr,
							  "color":"#F00",
							  "height": errcnt,
							  "text-align":"center"});
			return false;
		}
	}
	catch(err){
		FUSION.lib.alert("Error saving item - please refresh the page and try again");
		return false;
	}
}


function updateItemResponse(h)
{
	var hash = h || {};
}


function getItemInfo(i)
{
	clearItemForm();
	var id = i || "";
	if(FUSION.lib.isBlank(id))
	{
		FUSION.lib.alert("Invalid ID - please refresh the page and try again");
		return false;
	}

	var tmp = id.split("_");
	var iid = tmp[1];
	var info = {
		"type": "POST",
		"path": "php/indexlib.php",
		"data": {
			"method":	"getItemInfo",
			"libcheck":	true,
			"itemid":	iid
		},
		"func": showAddItem
	};
	FUSION.lib.ajaxCall(info);
}


function showAddItem(h)
{
	var hash  = h || {};
	try {
		if(typeof hash === "string")
		{
			hash = JSON.parse(hash);
		}
	}
	catch(err){
		FUSION.lib.alert("Error attempting to parse JSON - please refresh the page and try again");
		return false;
	}
	var ttl = "Edit this Item"
	var pid = parseInt(hash['pageid']);
	if(pid == 0)
	{
		ttl = "Add New Item";
	}
	else
	{
		FUSION.get.node("ni_pagename").value = hash['pagename'];
		FUSION.get.node("ni_pagelink").value = hash['pagelink'];
		FUSION.get.node("ni_pagetype").value = hash['pagetype'];
		FUSION.get.node("ni_pagestat").value = hash['pagestat'];
		FUSION.get.node("ni_pagedesc").value = hash['pagedesc'];
		FUSION.get.node("ni_pagestat").disabled = (hash['pagetype'] == "tool") ? true : false;
		FUSION.get.node("pagestatdiv").style.visibility = (hash['pagetype'] == "tool") ? "hidden" : "visible";
	}

	FUSION.get.node("ni_pageid").value = pid;
	FUSION.get.node("new_item_title").innerHTML = ttl;
	FUSION.get.node("new_item_overlay").style.height = FUSION.get.pageHeight() + "px";
	FUSION.get.node("new_item_overlay").style.display = "block";
	FUSION.lib.dragable("new_item_header", "new_item_wrapper");
}


function hideNewItem()
{
	FUSION.get.node("new_item_overlay").style.display = "none";
	clearItemForm();
}


function removeItem(i)
{
	if(FUSION.lib.isBlank(i)){
		FUSION.lib.alert("Invalid ID - please refresh the page and try again");
	}
	else{
		alert("I WAS CALLED");
	}
}


function clearItemForm()
{
	FUSION.get.node("ni_pageid").value = "";
	FUSION.get.node("ni_pagename").value = "";
	FUSION.get.node("ni_pagelink").value = "";
	FUSION.get.node("ni_pagedesc").value = "";
	FUSION.get.node("ni_pagetype").value = "";
	FUSION.get.node("ni_pagestat").value = "";
	FUSION.get.node("new_item_title").innerHTML = "";
	FUSION.get.node("ni_pagestat").disabled = false;
	FUSION.get.node("pagestatdiv").style.visibility = "visible";
}


function enDisStat(v)
{
	FUSION.get.node("ni_pagestat").disabled = (v == "tool") ? true : false;
	FUSION.get.node("pagestatdiv").style.visibility = (v == "tool") ? "hidden" : "visible";
}