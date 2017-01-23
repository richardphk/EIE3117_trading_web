function check_search(){
	var value = document.forms["form"]["keyword"].value;
	if(value == ""){
		alert("the keyword cannot be blank" + value);
			return false;
	}
	var value = str.replace(/--/, "");
	var value = str.replace(/#/, "");
	var value = str.replace(/ /, "");
	var value = str.replace(/&nbsp/, "");
	return true;
}
