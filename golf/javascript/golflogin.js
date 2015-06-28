function validateForm()
{
	var user = FUSION.get.node("txtusername").value;
	var pass = FUSION.get.node("txtpassword").value;
	if(FUSION.lib.isBlank(user))
	{
		FUSION.lib.alert({"message":"<p style='color:#FF0000;text-align:center;'>Please enter a username!</p>",
						  "font-weight":"bold",
						  "height":100,
						  "width":250,
						  "text-align":"left"});
		return false;
	}
	if(FUSION.lib.isBlank(pass))
	{
		FUSION.lib.alert({"message":"<p style='color:#FF0000;text-align:center;'>Please enter a password!</p>",
						  "font-weight":"bold",
						  "height":100,
						  "width":250,
						  "text-align":"left"});
		return false;
	}
	return true;
}
