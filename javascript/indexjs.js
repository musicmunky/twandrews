jQuery(document).ready(function() {
	jQuery('.remlnk').click(function(){
		removeItem(this.id);
	});
	jQuery('.editlnk').click(function(){
		getItemInfo(this.id);
	});
	jQuery("#addlnk").click(function(){
		showAddItem({"pageid":0});
	});
});


function updateItem()
{
	try {
		var id = FUSION.get.node("ni_pageid").value ? parseInt(FUSION.get.node("ni_pageid").value) : 0;
		var pname = FUSION.get.node("ni_pagename").value;
		var ptype = FUSION.get.node("ni_pagetype").value;
		var plink = FUSION.get.node("ni_pagelink").value;
		var pstat = FUSION.get.node("ni_pagestat").value;
		var pdesc = FUSION.get.node("ni_pagedesc").value;

		var errstr = "";
		var errcnt = 80;

		if(FUSION.lib.isBlank(id)) {
			FUSION.lib.alert("<p>There was a problem getting the item information - please refresh the page and try again</p>");
			return false;
		}
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

		pname = pname.trim();
		plink = plink.trim();
		pdesc = pdesc.trim();
		pstat = (ptype == "tool") ? "" : pstat;

		var info = {
			"type": "POST",
			"path": "php/indexlib.php",
			"data": {
				"method": "saveItemInfo",
				"libcheck":	true,
				"itemid": id,
				"pname":  pname,
				"ptype":  ptype,
				"plink":  plink,
				"pstat":  pstat,
				"pdesc":  pdesc
			},
			"func": updateItemResponse
		};

	FUSION.lib.ajaxCall(info);
	}
	catch(err){
		FUSION.lib.alert("Error saving item - please refresh the page and try again");
		return false;
	}
}


function updateItemResponse(h)
{
	var hash = h || {};
	try {
		var iid = hash['pageid'];
		var cn = FUSION.get.node("link_" + iid).childNodes;
		for(var i = 0; i < cn.length; i++)
		{
			var nn = cn[i].nodeName;
			if(nn == "#text")
			{
				cn[i].nodeValue = hash['pname'];
				break;
			}
		}
		FUSION.get.node("link_" + iid).href = hash['plink'];
		hideNewItem();
		return false;
	}
	catch(err){
		FUSION.lib.alert("Error during item update: " + err.toString());
		return false;
	}
}


function getItemInfo(i)
{
	clearItemForm();
	var id = i || "";
	var tmp = id.split("_");
	var iid = tmp[1];

	if(FUSION.lib.isBlank(id))
	{
		FUSION.lib.alert("Invalid ID - please refresh the page and try again");
		return false;
	}

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