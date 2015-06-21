function validateForm()
{
	var user = document.getElementById("txtusername").value;
	var pass = document.getElementById("txtpassword").value;
	if(FUSION.lib.isBlank(user))
	{
		FUSION.lib.alert({"message":"<p>Please enter a username!</p>", "height":100, "width":250, "text-align":"left"});
		return false;
	}
	if(FUSION.lib.isBlank(pass))
	{
		FUSION.lib.alert({"message":"<p>Please enter a password!</p>", "height":100, "width":250, "text-align":"left"});
		return false;
	}
	return true;
}
