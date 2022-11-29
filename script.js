/// EDITOR ///
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


/// GET AND STORE DIVS IN PAGE ///
var executeButton = document.getElementById("execute");			// query button
var resetButton = document.getElementById("restart");			// reset button
var levelNumberHTML = document.getElementById("levelNumber");	// level number stored in page
var instructionsDiv = document.getElementById("instructions");	// instructions div for exercice
var resultsDiv = document.getElementById("results");			// results div


function sendRequest(req, callback) {
	let xmlhttp = new XMLHttpRequest();
	// console.log(request);
	// send request with query in GET
	xmlhttp.open("GET", req, true);
	xmlhttp.send();

	// when result comes back
	xmlhttp.onload = function () {
		// get response)
		let response = this.responseText;
		// console.log(response);
		// callback is a func declared when call this func, it's almost like return but we call a func that will do what we want
		callback(response);
	}
}

// change code editor content with db request
function resetCodeEditor(levelNumber) {
	sendRequest("sql.php?idLevel=" + levelNumber + "&type=codeInit", (code) => {
		editor.setValue(code);
	})
}


// when user press the execute button
executeButton.addEventListener("click", () => {
	// CLEAN THE TEXT FROM EDITOR //
	let query = editor.getValue();				// get editor text
	query = query.replaceAll('\n', '\n ');	// add spaces after each line
	query = query.replace(/ *[Ss][Ee][Ll][Ee][Cc][Tt]/i, 'SELECT');	// put every select in start of line and uppercase
	query = query.replaceAll(/\-\-.*\n/ig, '');	// remove all comment (start with '--' and have end of line)
	//console.log(query);

	// we call sendRequest whith a func that sendRequest will call that will edit the html in responseDiv
	sendRequest("sql.php?idLevel=" + levelNumberHTML.innerHTML + "&type=ex&request=" + query, (resp) => {
		resultsDiv.innerHTML = resp;
	});

})

// reset content in code editor
resetButton.addEventListener("click", () => {
	resetCodeEditor(levelNumberHTML.innerHTML);
})

/* change level selected, reset code editor & edit buttons */
function changeLevel(id) {
	// change level number
	levelNumberHTML.innerHTML = id + 1;
	// change default code
	resetCodeEditor(levelNumberHTML.innerHTML);
	// clear result div
	resultsDiv.innerHTML = "";
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
(
	function main() {

		/// ADD LISTENERS FOR LEVEL BUTTONS ///
		/* get all levels */
		let buttons = document.getElementsByClassName("level");
		/* add event listeneer for every buttons in levels */
		for (let i = 0; i < buttons.length; i++) {
			buttons[i].addEventListener("click", () => {
				changeLevel(i);
			})
		}

		/// PUT INSTRUCTIONS ///
		sendRequest("sql.php?idLevel=1&type=instructions", (inst)=>{
			instructionsDiv.innerHTML = inst;
		});
	}
)();
