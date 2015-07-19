jQuery(document).ready(function() {

	jQuery('.ulnav').on('click', 'a.remlnk', function (event) {
		removeItem(this.id);
	});

	jQuery('.ulnav').on('click', "a.editlnk", function (event) {
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
		var ul  = {};
		if(hash['n_or_e'] == "new")
		{
			//add new li to correct ul
			var newli = FUSION.lib.createHtmlElement({"type":"li","attributes":{"id":"li_" + iid, "class":"linav"}});
			var plink = FUSION.lib.createHtmlElement({"type":"a","attributes":{
																	"id":"link_" + iid,
																	"target":"_blank",
																	"href":hash['plink']},
													  	"text":hash['pname']});
			var elink = FUSION.lib.createHtmlElement({"type":"a","attributes":{
													 				"title":"Edit " + hash['pname'],
													 				"class":"editlnk glyphicon glyphicon-pencil",
													 				"id":"editlnk_" + iid}});
			var rlink = FUSION.lib.createHtmlElement({"type":"a","attributes":{
													 				"title":"Remove " + hash['pname'],
													 				"class":"remlnk glyphicon glyphicon-remove",
													 				"id":"remlnk_" + iid}});
			if(hash['ptype'] == "project")
			{
				var cls = "";
				var ttl = "";
				if(hash['pstat'] == "development")
				{
					ul  = FUSION.get.node("developmentul");
					cls = "glyphicon glyphicon-exclamation-sign navspan nswarning";
					ttl = "Currently under development";
				}
				else
				{
					ul  = FUSION.get.node("completeul");
					cls = "glyphicon glyphicon-ok-sign navspan nsokay";
					ttl = "Primary development complete";
				}

				var gispan = FUSION.lib.createHtmlElement({"type":"span",
														   "attributes":{
															   "id":"gispan_" + iid,
															   "aria-hidden":"true",
															   "class":cls}});
				newli.setAttribute("title", ttl);
				plink.insertBefore(gispan, plink.firstChild);
			}
			else
			{
				ul = FUSION.get.node("toolul");
			}

			newli.appendChild(plink);
			newli.appendChild(elink);
			newli.appendChild(rlink);
			ul.appendChild(newli);
		}
		else
		{
			if(hash['ptype'] != hash['prvtp'])
			{
				//move li from old to new ul
				var li = FUSION.get.node("li_" + iid);
				var licopy = li;
				if(hash['ptype'] == "tool")
				{
					licopy.removeChild(FUSION.get.node("gispan_" + iid));
					licopy.setAttribute("title", "");
				}
				else
				{
					var cls = "";
					var ttl = "";
					if(hash['pstat'] == "development")
					{
						ul  = FUSION.get.node("developmentul");
						cls = "glyphicon glyphicon-exclamation-sign navspan nswarning";
						ttl = "Currently under development";
					}
					else
					{
						ul  = FUSION.get.node("completeul");
						cls = "glyphicon glyphicon-ok-sign navspan nsokay";
						ttl = "Primary development complete";
					}

					var gispan = FUSION.lib.createHtmlElement({"type":"span",
															   "attributes":{
																   "id":"gispan_" + iid,
																   "aria-hidden":"true",
																   "class":cls}});

					licopy.insertBefore(gispan, FUSION.get.node("link_" + iid).firstChild);
					licopy.setAttribute("title", ttl);
				}
				FUSION.remove.node(li);
				ul.appendChild(licopy);
			}
			else
			{
				//update current li with new info (name and link are only display elements to update)
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
			}
		}
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