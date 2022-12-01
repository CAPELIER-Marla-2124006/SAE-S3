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
var executeButton = document.querySelector("#execute");				// query button
var resetButton = document.querySelector("#restart");				// reset button
var levelNumberHTML = document.querySelector("#levelNumber");		// level number stored in page
var instructionsDiv = document.querySelector("#instructions");		// instructions div for exercice
var resultsDiv = document.querySelector("#results");				// results div
var colorSlider = document.querySelector("#colorSlider");			// slider to choose color from
var levelSelector = document.querySelector("#levels");				// level selector to trigger when changed
var notesTextarea = document.querySelector("#notes");				// textarea where the user can type notes
var cssRoot = document.querySelector(":root");						// root of the page
var closePopup = document.querySelectorAll(".exitButton")			// select all closePopup buttons
var popupBackground = document.querySelector(".popupBackground")	// background of popups to remove them

colorSlider.oninput = function() {
	cssRoot.style.setProperty('--hue', this.value);
}


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
	resultsDiv.innerHTML = "";
}


// when user press the execute button
executeButton.addEventListener("click", () => {
	// CLEAN THE TEXT FROM EDITOR //
	let query = editor.getValue();				// get editor text
	query = query.replaceAll(['\n','\r\n'], '\n ');	// add spaces after each line
	query = query.replace(/ *[Ss][Ee][Ll][Ee][Cc][Tt]/i, 'SELECT');	// put every select in start of line and uppercase
	query = query.replaceAll(/\-\-.*['\n','\r\n']/ig, '');	// remove all comment (start with '--' and have end of line)
	//console.log(query);

	// we call sendRequest whith a func that sendRequest will call that will edit the html in responseDiv
	sendRequest("sql.php?idLevel=" + levelNumberHTML.innerHTML + "&type=ex&request=" + query, (resp) => {
		resultsDiv.innerHTML = resp;
	});

})

// reset content in code editor
resetButton.addEventListener("click", () => {
	resetCodeEditor(levelNumberHTML.innerHTML);
});

// when the user change level
levelSelector.addEventListener("change", ()=>{
	resetCodeEditor(levelSelector.value);
});

// when the user exit notes textarea
notesTextarea.addEventListener("focusout", ()=>{
	sendRequest("accounts.php?type=note&text="+notesTextarea.innerHTML, ()=>{})
});


/* change level selected, reset code editor & edit buttons */
function changeLevel(id) {
	// change level number
	levelNumberHTML.innerHTML = id + 1;
	// change default code
	resetCodeEditor(levelNumberHTML.innerHTML);
	// clear result div
	resultsDiv.innerHTML = "";
	// get all buttons
	let buttons = document.querySelector("level");
	// remove all selected classes
	for (let i = 0; i < buttons.length; i++) {
		buttons[i].classList.remove("selected");
	}
	// edit button selected
	buttons[id].classList.add("selected");
}

/* this part of code run itslef every refresh of the page, at the beginning */
(
	function start() {

		/// SET COLOR VALUE ///
		cssRoot.style.setProperty('--hue', 165);
		colorSlider.value = "165";

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

		/// CLOSE POPUP TRIGGER ///
		// for each popup close button
		closePopup.forEach(b => {
			// when clicked
			b.addEventListener("click", ()=>{
				// get all children of popupBackground
				for(const p of popupBackground.children) {
					// remove their class display
					p.classList.remove("display");
				}
			});
		});
	}
)();
