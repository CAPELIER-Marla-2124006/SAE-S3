///--------///
/// EDITOR ///
///--------///
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




///----------------------------///
/// GET AND STORE DIVS IN PAGE ///
///----------------------------///
var executeButton = document.querySelector("#execute");				// query button
var resetButton = document.querySelector("#restart");				// reset button
var levelNumberHTML = document.querySelector("#levelNumber");		// level number stored in page
var instructionsDiv = document.querySelector("#instructions");		// instructions div for exercice
var lessonButton = document.querySelector("#lesson");				// button to display the lesson
var hintButton = document.querySelector("#hint");					// button to display the hint
var resultsDiv = document.querySelector("#results");				// results div
var colorSlider = document.querySelector("#colorSlider");			// slider to choose color from
var levelSelector = document.querySelector("#levels");				// level selector to trigger when changed
var notesTextarea = document.querySelector("#notes");				// textarea where the user can type notes
var lessonDiv = document.querySelector("#popupLesson");				// where the lesson will be displayed
var hintDiv = document.querySelector("#popupHint");					// where the hint will be displayed
var closePopupButton = document.querySelectorAll(".exitButton")		// select all closePopup buttons
var nextPopupButton = document.querySelector(".nextButton")			// next button in end popup
var popupBackground = document.querySelector(".popupBackground")	// background of popups to remove them
var cssRoot = document.querySelector(":root");						// root of the page




///------------------///
/// GLOBAL FUNCTIONS ///
///------------------///
/* send a request from url, with a lambda in params (almost like the return) */
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

/* change code editor content with db request */
function resetCodeEditor(levelNumber) {
	sendRequest("sql.php?idLevel=" + levelNumber + "&type=codeInit", (code) => {
		editor.setValue(code);
	})
}

/* change level selected, reset code editor & edit buttons */
function changeLevel(id) {
	// change level number
	levelNumberHTML.innerHTML = id;
	// change default code
	resetCodeEditor(levelNumberHTML.innerHTML);
	// clear result div
	resultsDiv.innerHTML = "";
	// edit the level selector to have the right level selected
	levelSelector.value=id;
	sendRequest("sql.php?idLevel="+id+"&type=instructions", (i)=>{
		instructionsDiv.innerHTML = i;
	});
	// edit lesson div to put new text
	sendRequest("sql.php?idLevel="+levelNumberHTML.innerHTML+"&type=lesson", (lesson)=>{
		// put lesson in the text div
		lessonDiv.querySelector(".text").innerHTML = lesson;
	});
	// edit hint div to put new text
	sendRequest("sql.php?idLevel="+levelNumberHTML.innerHTML+"&type=hint", (hint)=>{
		// put hint in the text div
		hintDiv.querySelector(".text").innerHTML = hint;
	});
}

/* select next level */
function nextLevel() {
	changeLevel(parseInt(levelNumberHTML.innerHTML)+1);
	displayLesson();
}

/* display the lesson in the popup from db */
function displayLesson() {
	// display lesson div
	lessonDiv.classList.add("display");
}

/* display the hint in the popup from db */
function displayHint() {
	// display hint div
	hintDiv.classList.add("display");
}




///-----------------///
/// EVENT LISTENERS ///
///-----------------///

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
		let win = resp.split('\n')[0];
		let table = resp.split('\n')[1];
		if(win == "true") {
			console.log("reussi");
		}
		resultsDiv.innerHTML = table;
	});

})

// reset content in code editor
resetButton.addEventListener("click", () => {
	resetCodeEditor(levelNumberHTML.innerHTML);
});

// when the user change level from selector, load level
levelSelector.addEventListener("change", ()=>{
	changeLevel(levelSelector.value);
});

// when the user exit notes textarea, store its data in db
notesTextarea.addEventListener("focusout", ()=>{
	sendRequest("accounts.php?type=note&text="+notesTextarea.innerHTML, ()=>{})
});

// when the user change slider value
colorSlider.oninput = function() {
	cssRoot.style.setProperty('--hue', this.value);
}

/* close end popup and get next level */
nextPopupButton.addEventListener("click", ()=>{
	// get all popupBackground children and remove their class display
	for(const p of popupBackground.children) {
		// remove their class display
		p.classList.remove("display");
	}

	nextLevel();
});

/* display lesson when clicking lesson */
lessonButton.addEventListener("click", ()=>{
	displayLesson();
})

/* display hint when clicking hint */
hintButton.addEventListener("click", ()=>{
	displayHint();
});



///------///
/// MAIN ///
///------///
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

	/// GET INSTRUCTIONS ///
	sendRequest("sql.php?idLevel="+levelNumberHTML.innerHTML+"&type=instructions", (inst)=>{
		instructionsDiv.innerHTML = inst;
	});

	/// DISPLAY LESSON ///
	sendRequest("sql.php?idLevel="+levelNumberHTML.innerHTML+"&type=lesson", (lesson)=>{
		// put lesson in the text div
		lessonDiv.querySelector(".text").innerHTML = lesson;
		displayLesson();
	});

	/// GET HINT ///
	sendRequest("sql.php?idLevel="+levelNumberHTML.innerHTML+"&type=hint", (hint)=>{
		// put hint in the text div
		hintDiv.querySelector(".text").innerHTML = hint;
	});

	/// CLOSE POPUP TRIGGER ///
	// for each popup close button
	closePopupButton.forEach(b => {
		// when clicked
		b.addEventListener("click", ()=>{
			// get all children of popupBackground
			for(const p of popupBackground.children) {
				// remove their class display
				p.classList.remove("display");
			}
		});
	});
})();
