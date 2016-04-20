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
			var newli = createLi(hash);
			ul = (hash['ptype'] == "project") ? FUSION.get.node(hash['pstat'] + "ul") : FUSION.get.node("toolul");
			ul.appendChild(newli);
		}
		else
		{
			if(hash['ptype'] != hash['prvtp'] || (hash['pstat'] != hash['prvst'] && !FUSION.lib.isBlank(hash['pstat']) && !FUSION.lib.isBlank(hash['prvst'])))
			{
				//move li from old to new ul
				FUSION.remove.node("li_" + iid);
				var newli = createLi(hash);
				ul = (hash['ptype'] == "project") ? FUSION.get.node(hash['pstat'] + "ul") : FUSION.get.node("toolul");
				ul.appendChild(newli);
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

		//sort the three ULs to get them in correct order (by ID)
		sortUl("toolul");
		sortUl("completeul");
		sortUl("developmentul");
		return false;
	}
	catch(err){
		FUSION.error.logError(err);
		FUSION.lib.alert("Error during item update: " + err.toString());
		return false;
	}
}


function sortUl(id)
{
	//send in the id of UL you would like sorted
	//this case is fairly specific, requiring li elements with ids
	//in the format "string_integer", such as "li_4"
	if(FUSION.lib.isBlank(id))
	{
		FUSION.lib.alert("Can not sort list - does not exist");
		return false;
	}
	var lst = FUSION.get.node(id);
	var ary = [];
	var chl = lst.children
	var len = chl.length
    for(var i = 0; i < len; i++)
	{
		ary[i] = chl[i]; //store the NodeList in an array
	}

    ary.sort(function(a, b) {
		//need to split and parseInt because the sort function
		//was placing ids like "li_10" above "li_7" because it compared 1 to 7, not 10 to 7
		var atmp = a.id.split("_");
		var btmp = b.id.split("_");
		var ai = parseInt(atmp[1]);
		var bi = parseInt(btmp[1]);
		return ai < bi ? -1 : 1;
	});

	for(var j = 0; j < len; j++)
	{
		lst.appendChild(ary[j]);
	}
}


function createLi(hash)
{
	var h = hash || {};
	if(FUSION.get.objSize(h) == 0)
	{
		FUSION.lib.alert("Unable to create list element with no parameters!");
		return false;
	}
	var iid = h['pageid'];
	var nam = h['pname'];
	var lnk = h['plink'];
	var typ = h['ptype'];
	var dsc = h['pdesc'];
	var stt = h['pstat'];

	var newli = FUSION.lib.createHtmlElement({"type":"li","attributes":{"id":"li_" + iid, "class":"linav"}});
	var plink = FUSION.lib.createHtmlElement({"type":"a",
											  "attributes":{"id":"link_" + iid, "target":"_blank", "href":lnk},
												"text":nam});
	var elink = FUSION.lib.createHtmlElement({"type":"a",
											  "attributes":{"title":"Edit " + nam, "class":"editlnk glyphicon glyphicon-pencil", "id":"editlnk_" + iid}});
	var rlink = FUSION.lib.createHtmlElement({"type":"a",
											  "attributes":{"title":"Remove " + nam, "class":"remlnk glyphicon glyphicon-remove", "id":"remlnk_" + iid}});
	if(typ == "project")
	{
		var cls = "";
		var ttl = "";
		if(stt == "development")
		{
			cls = "glyphicon glyphicon-exclamation-sign navspan nswarning";
			ttl = "Currently under development";
		}
		else
		{
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

	newli.appendChild(plink);
	newli.appendChild(rlink);
	newli.appendChild(elink);

	return newli;
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
	if(FUSION.lib.isBlank(i))
	{
		FUSION.lib.alert("Invalid ID - please refresh the page and try again");
		return false;
	}
	else
	{
		var yn = confirm("Are you sure you would like to remove this item?");
		if(yn)
		{
			var tmp = i.split("_");
			var iid = tmp[1];

			var info = {
				"type": "POST",
				"path": "php/indexlib.php",
				"data": {
					"method":	"removeItem",
					"libcheck":	true,
					"itemid":	iid
				},
				"func": removeItemResponse
			};
			FUSION.lib.ajaxCall(info);
		}
	}
}


function removeItemResponse(h)
{
	var hash = h || {};
	FUSION.remove.node("li_" + hash['pageid']);
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