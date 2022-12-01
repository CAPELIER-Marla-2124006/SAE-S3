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
const executeButton 	= document.querySelector("#execute");			// query button
const resetButton 		= document.querySelector("#restart");			// reset button
const levelNumberHTML 	= document.querySelector("#levelNumber");		// level number stored in page
const instructionsDiv 	= document.querySelector("#instructions");		// instructions div for exercice
const lessonButton 		= document.querySelector("#lesson");			// button to display the lesson
const hintButton 		= document.querySelector("#hint");				// button to display the hint
const resultsDiv 		= document.querySelector("#results");			// results div
const colorSlider 		= document.querySelector("#colorSlider");		// slider to choose color from
const levelSelector 	= document.querySelector("#levels");			// level selector to trigger when changed
const notesTextarea 	= document.querySelector("#notes");				// textarea where the user can type notes
const lessonDiv 		= document.querySelector("#popupLesson");		// where the lesson will be displayed
const hintDiv 			= document.querySelector("#popupHint");			// where the hint will be displayed
const endDiv 			= document.querySelector("#popupEnd");			// div with win message
const closePopupButton 	= document.querySelectorAll(".exitButton");		// select all closePopup buttons
const nextPopupButton 	= document.querySelector(".nextButton");		// next button in end popup
const popupBackground 	= document.querySelector(".popupBackground");	// background of popups to remove them
const cssRoot 			= document.querySelector(":root");				// root of the page




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
	// hide everything
	resultsDiv.innerHTML = "";
	lessonDiv.querySelector(".text").innerHTML = "";
	hintDiv.querySelector(".text").innerHTML = "";
	instructionsDiv.innerHTML = "";
	editor.setValue("");

	// change level number
	levelNumberHTML.innerHTML = id;
	// change default code
	resetCodeEditor(levelNumberHTML.innerHTML);
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

/* display the lesson in the popup */
function displayLesson() {
	// display lesson div
	lessonDiv.classList.add("display");
}

/* display the hint in the popup */
function displayHint() {
	// display hint div
	hintDiv.classList.add("display");
}

/* display win message in the popup */
function displayWin() {
	endDiv.classList.add("display");
}




///-----------------///
/// EVENT LISTENERS ///
///-----------------///

// when user press the execute button
executeButton.addEventListener("click", () => {
	// CLEAN THE TEXT FROM EDITOR //
	let query = editor.getValue();					// get editor text
	query = query.replace(/\-\-.*\n?/ig, '');		// remove all comment (start with '--' and have end of line)
	query = query.replace(/\n|\r\n/gm, ' ');		// replace new lines by space
	query = query.replace(/select/gmi, 'SELECT');	// put every select in start of line and uppercase
	console.log(query);

	// we call sendRequest whith a func that sendRequest will call that will edit the html in responseDiv
	sendRequest("sql.php?idLevel=" + levelNumberHTML.innerHTML + "&type=ex&request=" + query, (resp) => {
		let win = resp.split('\n')[0];
		let table = resp.split('\n')[1];
		if(win == "true") {
			displayWin();
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



///----------------///
/// RESIZABLE DIVS ///
///----------------///
// GET DIVS //
const horizontalResizer = document.querySelector("#horizontalResizer");		// drag to resize horizontaly
const leftSide 	= horizontalResizer.previousElementSibling;					// div on the left of drager
const rightSide = horizontalResizer.nextElementSibling;						// div on the right of drager

// where is the mouse?
var mouseX = 0;
var mouseY = 0;

// width of the left side
var leftWidth = 0;

function mouseDownHandler(e) {
	// updates global vars
	mouseX = e.clientX;
	mouseY = e.clientY;
	leftWidth = leftSide.getBoundingClientRect().width;

	// add listeners
	document.addEventListener('mousemove', mouseMoveHandler);
	document.addEventListener('mouseup', mouseUpHandler);
}

function mouseMoveHandler(e) {
	// calc distance by mouse
	const dx = e.clientX - mouseX;
	const dy = e.clientY - mouseY;

	// edit width of the left
	const newLeftWidth = ((leftWidth + dx) * 100) / horizontalResizer.parentNode.getBoundingClientRect().width;
	leftSide.style.width = newLeftWidth+'%';

	// show mouse cursor everywhere on the page (to prevent flickering)
	document.body.style.cursor = 'col-resize';

	// prevent mouse from selecting anything on the page
	leftSide.style.userSelect = 'none';
    leftSide.style.pointerEvents = 'none';
    rightSide.style.userSelect = 'none';
    rightSide.style.pointerEvents = 'none';
}

function mouseUpHandler(e) {
	// remove cursor style
	horizontalResizer.style.removeProperty('cursor');
	document.body.style.removeProperty('cursor');

	// remove preventing selection
	leftSide.style.removeProperty('user-select');
    leftSide.style.removeProperty('pointer-events');
    rightSide.style.removeProperty('user-select');
    rightSide.style.removeProperty('pointer-events');

	// remove handlers
	document.removeEventListener('mousemove', mouseMoveHandler);
    document.removeEventListener('mouseup', mouseUpHandler);
}

horizontalResizer.addEventListener('mousedown', mouseDownHandler);














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

	/// GET WIN MESSAGE ///
	sendRequest("sql.php?idLevel="+levelNumberHTML.innerHTML+"&type=success", (msg)=> {
		endDiv.querySelector(".text").innerHTML = msg;
	})

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
