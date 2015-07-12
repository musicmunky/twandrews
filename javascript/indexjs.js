jQuery(document).ready(function() {
	$('.remlnk').click(function(){
		removeItem(this.id);
	});
	$('.editlnk').click(function(){
		showAddItem(this.id);
	});
	$("#addlnk").click(function(){
		showAddItem("");
	});
});

function showAddItem(i)
{
	var id = i || "";
	var ttl = "Edit this Item"
	var pid = 0;
	if(FUSION.lib.isBlank(id)){
		ttl = "Add New Item";
	}
	else
	{
		var tmp = id.split("_");
		pid = tmp[1];
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
	FUSION.get.node("new_item_title").innerHTML = "";
}


function removeItem(i)
{
	if(FUSION.lib.isBlank(i)){
		FUSION.lib.alert("Invalid ID - please refresh the page and try again")
	}
	else{
		alert("I WAS CALLED");
	}
}