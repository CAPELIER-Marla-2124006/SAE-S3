// get editor from ace (for syntaxic colors etc)
var editor = ace.edit("code-editor");
// change theme
editor.setTheme("ace/theme/perso");
//set sql language
editor.session.setMode("ace/mode/sql");
// set font
editor.setOptions({
	fontFamily: "JetBrains Mono, monospace",
	fontSize: "14pt"
})

// defaults codes for every level
var defaultCode = ["--insérer du code ici\nSELECT * FROM TEST;"]
// get button for query
var exe = document.getElementById("execute");
// get button for reset
var rst = document.getElementById("restart");
// get level number
var levelNumber = document.getElementById("levelNumber");
//get result place
var results = document.getElementById("results");
// deflaut query
var query = "SELECT * FROM TEST;";

exe.addEventListener("click", ()=>{
	query = editor.getValue();
	// add spaces after each line
	query = query.replaceAll('\n', '\n ')
	// put every select in start of line and upper
	query = query.replace(/ *[Ss][Ee][Ll][Ee][Cc][Tt]/i, 'SELECT')
	// remove all comment (start with '--' and have end of line)
	query = query.replaceAll(/\-\-.*\n/ig, '')
	//console.log(query);

	let xmlhttp = new XMLHttpRequest();

	// create and send request with query in GET
	xmlhttp.open("GET", "sql.php?request=" + query, true);
	xmlhttp.send();

	// when result come back
	xmlhttp.onload = function() {
		// get response
		let response = this.responseText;
		//console.log(response);
		// edit result div to show response
		results.innerHTML = response;
	}
})


rst.addEventListener("click", ()=>{
	// reset editor
	editor.setValue(defaultCode[levelNumber.innerHTML -1]);
})
