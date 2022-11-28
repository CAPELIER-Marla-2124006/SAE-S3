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
var defaultCode = ["--insérer du code ici\nSELECT * FROM TEST;",
				   "SELECT * FROM TEST;",
				   "SELECT * FROM TEST;",
				   "SELECT * FROM TEST;",
				   "SELECT * FROM TEST;",
				   "SELECT * FROM TEST;",
				   "SELECT * FROM TEST;",
				   "SELECT * FROM TEST;",
				   "SELECT * FROM TEST;",
				   "SELECT * FROM TEST;",
				  ];
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

	// create request
	let request = "sql.php?level=" + levelNumber.innerHTML + "&request=" + query;
	console.log(request);
	// send request with query in GET
	xmlhttp.open("GET", request, true);
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

/* change level selected, reset code editor & edit buttons */
function changeLevel(id) {
	// change level number
	levelNumber.innerHTML = id+1;
	// change default code
	editor.setValue(defaultCode[id]);
	// reset result
	results.innerHTML = "";
	// get all buttons
	let buttons = document.getElementsByClassName("level");
	// remove all selected classes
	for (let i = 0; i < buttons.length; i++) {
		buttons[i].classList.remove("selected");
	}
	// edit button selected
	buttons[id].classList.add("selected");
}

/* this part of code run itslef every refresh of the page, at the beginning */
(function addChangeLevelListeners() {
	/* get all levels */
	let buttons = document.getElementsByClassName("level");
	/* add event listeneer for every buttons in levels */
	for(let i = 0; i < buttons.length; i++) {
		buttons[i].addEventListener("click", ()=>{
			changeLevel(i);
		})
	}
})();
