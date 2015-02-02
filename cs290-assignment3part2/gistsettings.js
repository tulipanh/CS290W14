function ajaxRequest(type, parameter){
	var request = new XMLHttpRequest();
	if(!request){
		alert('Http request failed.');
		return false;
	}

	var output = { success: '', code: '', codeDetail: '', response: ''};
	var gistresponse;

	request.onreadystatechange = function(){
		if(this.readyState === 4){
			if(this.status === 200){
				output.success = true;
			}
			else {
				output.success = false;
			}

			output.code = this.status;
			output.codeDetail = this.statusText;
			output.response = this.responseText;
			gistresponse = JSON.parse(this.responseText);

			console.log(parameter);

			for(i = 0; i < 30; i++){
				var gistitem = gistresponse[i];
				for(j in gistitem.files){
					var file = gistitem.files[j];
					for(k in file){
						var idstring = ("\'" + gistresponse[i].id + "\'");
						if(file[k] == parameter[1]) document.getElementById('searchlist').innerHTML += ('<fieldset><li><a href="' + gistresponse[i].html_url + '">' + '(Python)   ' + gistresponse[i].description + '</a><div><input type="button" name="favorite" value="Favorite"></div></li></fieldset>');
						if(file[k] == parameter[2]) document.getElementById('searchlist').innerHTML += ('<fieldset><li><a href="' + gistresponse[i].html_url + '">' + '(JSON)   ' + gistresponse[i].description + '</a><div><input type="button" name="favorite" value="Favorite"></div></li></fieldset>');
						if(file[k] == parameter[3]) document.getElementById('searchlist').innerHTML += ('<fieldset><li><a href="' + gistresponse[i].html_url + '">' + '(JavaScript)   ' + gistresponse[i].description + '</a><div><input type="button" name="favorite" value="Favorite"></div></li></fieldset>');
						if(file[k] == parameter[4]) document.getElementById('searchlist').innerHTML += ('<fieldset><li><a href="' + gistresponse[i].html_url + '">' + '(SQL)   ' + gistresponse[i].description + '</a><div><input type="button" name="favorite" value="Favorite"></div></li></fieldset>');
					}
				}
				if(parameter[1] == 'all') document.getElementById('searchlist').innerHTML += ('<fieldset><li><a href="' + gistresponse[i].html_url + '">' + gistresponse[i].description + '</a><div><input type="button" name="favorite" onclick="addFavorites()" value="Favorite"></div></li></fieldset>');
			}
		}
	};

	
	var url = 'https://api.github.com/gists/public';
	var geturl = (url + '?page=' + parameter[0]);
	request.open('GET', geturl);
	request.send();
	

	

	return gistresponse; 
};

/*function addFavorites(){
	var favList = JSON.parse(localStorage['favList']);
	favList.push('red');
	localStorage['favList'] = JSON.stringify(favList);
}*/

function getSettings(){
	document.getElementsByName('num_pages')[0].value = localStorage.getItem('numPages');
	check('python', 'list_python');
	check('json', 'list_json');
	check('javascript', 'list_javascript');
	check('sql', 'list_sql');
}

function saveSettings(){
	localStorage.clear();
	localStorage.setItem('numPages', document.getElementsByName('num_pages')[0].value);
	localStorage.setItem('python', document.getElementById('list_python').checked);
	localStorage.setItem('json', document.getElementById('list_json').checked);
	localStorage.setItem('javascript', document.getElementById('list_javascript').checked);
	localStorage.setItem('sql', document.getElementById('list_sql').checked);
};

function getGists(){
	saveSettings();
	
	var parameter = [];
	parameter[0] = localStorage.getItem('numPages');
	var notcount = 0;
	if(document.getElementById('list_python').checked) parameter[1] = 'Python';
	else{
		parameter[1] = 'N/A';
		notcount++;
	}
	if(document.getElementById('list_json').checked) parameter[2] = 'JSON';
	else{
		parameter[2] = 'N/A';
		notcount++;
	}
	if(document.getElementById('list_javascript').checked) parameter[3] = 'JavaScript';
	else{
		parameter[3] = 'N/A';
		notcount++;
	}
	if(document.getElementById('list_sql').checked) parameter[4] = 'SQL';
	else{
		parameter[4] = 'N/A';
		notcount++;
	}
	if(notcount >= 4) parameter[1] = 'all';

	document.getElementById('searchlist').innerHTML = "<div></div>";
	for(i = 1; i <= localStorage.getItem('numPages'); i++){
		parameter[0] = i;
		ajaxRequest('POST', parameter);
	}
};

function check(locname, id) {
	if(localStorage.getItem(locname) == 'true') {
		document.getElementById(id).checked = true;
	}
	else {
		document.getElementById(id).checked = false;
	}
};

