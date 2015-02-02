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
			document.getElementById('searchlist').innerHTML = ('<div>' + gistresponse[1].url + '</div>');
		}
	};

	var url = 'https://api.github.com/gists/public';
	var geturl = (url + '?page=' + parameter);
	request.open('GET', geturl);
	request.send();

	return gistresponse; 
};



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
	ajaxRequest('POST', 1);
};