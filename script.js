// get editor from ace (for syntaxic colors etc)
var editor = ace.edit("editor");
// change theme
editor.setTheme("ace/theme/dracula");
//set sql language
editor.session.setMode("ace/mode/sql");
// set font
editor.setOptions({
	fontFamily: "JetBrains Mono, monospace",
	fontSize: "14pt"
})


// get button for query
var btn = document.getElementById("btn");
//get result place
var results = document.getElementById("results");
// deflaut query
var query = "SELECT * FROM TEST;";

btn.addEventListener("click", ()=>{
	//query = editor.getValue();
	//console.log("btn clicked");

	let xmlhttp = new XMLHttpRequest();

	// create and send request with query in GET
	xmlhttp.open("GET", "sql.php?request=" + query, true);
	xmlhttp.send();

	// when result come back
	xmlhttp.onload = function() {
		// get response
		let response = this.responseText;
		console.log(response);
		// edit result div to show response
		results.style = "display: flex";
		results.innerHTML = response;
	}
})
