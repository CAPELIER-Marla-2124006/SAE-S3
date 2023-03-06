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
const popupTheEnd		= document.querySelector("#popupTheEnd");		// the final popup of the game, when the user finished everything
const hintDiv 			= document.querySelector("#popupHint");			// where the hint will be displayed
const endDiv 			= document.querySelector("#popupEnd");			// div with win message
const closePopupButton 	= document.querySelectorAll(".exitButton");		// select all closePopup buttons
const nextPopupButton 	= document.querySelector(".nextButton");		// next button in end popup
const popupBackground 	= document.querySelector(".popupBackground");	// background of popups to remove them
const cssRoot 			= document.querySelector(":root");				// root of the page
const connexionButton	= document.querySelector("#connexionButton");	// button to show connexion form
const connexionForm		= document.querySelector("#connexionForm");		// connexion form
const registerButton	= document.querySelector("#registerButton");	// button to show register form
const registerForm		= document.querySelector("#registerForm");		// register form
const accountButton		= document.querySelector("#accountButton");		// button to disconect
const accountForm		= document.querySelector("#accountForm");		// form to disconect
const userPoints		= document.querySelector("#userPoints");		// where the points are displayed


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
	sendRequest("php/sql.php?idLevel=" + levelNumber + "&type=codeInit", (code) => {
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
	//editor.setValue("");

	// change level number
	levelNumberHTML.innerHTML = id;
	// change default code
	//resetCodeEditor(levelNumberHTML.innerHTML);
	// edit the level selector to have the right level selected
	levelSelector.value=id;
	sendRequest("php/sql.php?idLevel="+id+"&type=instructions", (i)=>{
		//console.log(i);
		instructionsDiv.innerHTML = i;
	});
	// edit lesson div to put new text
	sendRequest("php/sql.php?idLevel="+levelNumberHTML.innerHTML+"&type=lesson", (lesson)=>{
		// put lesson in the text div
		lessonDiv.querySelector(".text").innerHTML = lesson;
	});
	// edit hint div to put new text
	sendRequest("php/sql.php?idLevel="+levelNumberHTML.innerHTML+"&type=hint", (hint)=>{
		// put hint in the text div
		hintDiv.querySelector(".text").innerHTML = hint;
	});
	// edit end popup to display the success message
	sendRequest("php/sql.php?idLevel="+levelNumberHTML.innerHTML+"&type=success", (success)=>{
		// put html code in success popup
		endDiv.querySelector(".text").innerHTML = success;
	})
	//console.log(levelSelector.children);
	for (let i = 0; i < id; i++) {
		levelSelector.children[i].disabled = false;
	}
}

/* select next level */
function nextLevel() {
	if(parseInt(levelNumberHTML.innerHTML) < 8) {
		changeLevel(parseInt(levelNumberHTML.innerHTML)+1);
		displayLesson();
	}
	else
		popupTheEnd.classList.add("display");
		popupBackground.classList.add("display");
}

/* display the lesson in the popup */
function displayLesson() {
	// display lesson div
	lessonDiv.classList.add("display");
	popupBackground.classList.add("display");
}

/* display the hint in the popup */
function displayHint() {
	// display hint div
	hintDiv.classList.add("display");
	popupBackground.classList.add("display");
}

/* display win message in the popup */
function displayWin() {
	endDiv.classList.add("display");
	popupBackground.classList.add("display");
}

/* display the connexion form and set the button to hide it */
function displayConnexionForm() {
	connexionForm.style.display = "flex";
	connexionButton.removeEventListener('click', displayConnexionForm);
	connexionButton.addEventListener('click', hideConnexionForm);
	hideRegisterForm();
}

// ↑ != ↓

/* hide the connexion form and set the button to display it */
function hideConnexionForm() {
	connexionForm.style.display = "none";
	connexionButton.removeEventListener('click', hideConnexionForm);
	connexionButton.addEventListener('click', displayConnexionForm);
}

/* display the register form and set the button to hide it */
function displayRegisterForm() {
	registerForm.style.display = "flex";
	registerButton.removeEventListener('click', displayRegisterForm);
	registerButton.addEventListener('click', hideRegisterForm);
	hideConnexionForm();
}

// ↑ != ↓

/* hide the register form and set the button to display it */
function hideRegisterForm() {
	registerForm.style.display = "none";
	registerButton.removeEventListener('click', hideRegisterForm);
	registerButton.addEventListener('click', displayRegisterForm);
}

/* display the account form and set the button to hide it */
function displayAccountForm() {
	accountForm.style.display = "flex";
	accountButton.removeEventListener('click', displayAccountForm);
	accountButton.addEventListener('click', hideAccountForm);
}
// ↑ != ↓

/* display the account form and set the button to hide it */
function hideAccountForm() {
	accountForm.style.display = "none";
	accountButton.removeEventListener('click', hideAccountForm);
	accountButton.addEventListener('click', displayAccountForm);
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
	//console.log(query);

	// we call sendRequest whith a func that sendRequest will call that will edit the html in responseDiv
	sendRequest("php/sql.php?idLevel=" + levelNumberHTML.innerHTML + "&type=ex&request=" + query, (resp) => {
		//console.log(resp);
		let win = resp.split('\n')[0];
		let points = resp.split('\n')[1];
		let table = resp.split('\n')[2];
		resultsDiv.innerHTML = table;
		if(win == "true") {
			displayWin();
		}
		userPoints.innerHTML = "Points : " + points;
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
	sendRequest("php/update.php?column=notes&value="+notesTextarea.value, (e)=>{});
});

// when the user change slider value
colorSlider.oninput = function() {
	cssRoot.style.setProperty('--hue', this.value);
};

// when the user slected the value, it's updated in DB
colorSlider.addEventListener("change", ()=> {
	sendRequest("php/update.php?column=colorHue&value="+colorSlider.value, (e)=>{});
});

/* close end popup and get next level */
nextPopupButton.addEventListener("click", ()=>{
	// get all popupBackground children and remove their class display
	popupBackground.classList.remove("display");
	for(const p of popupBackground.children) {
		// remove their class display
		p.classList.remove("display");
	}

	nextLevel();
});

/* display lesson when clicking lesson */
lessonButton.addEventListener("click", ()=>{
	displayLesson();
});

/* display hint when clicking hint */
hintButton.addEventListener("click", ()=>{
	displayHint();
});

/* CLOSE POPUP TRIGGER */
closePopupButton.forEach(b => {
	// when clicked
	b.addEventListener("click", ()=>{
		// get all children of popupBackground
		popupBackground.classList.remove("display");
		for(const p of popupBackground.children) {
			// remove their class display
			p.classList.remove("display");
		}
	});
});

// add listeners for account buttons only if displayed

if(connexionButton != null) {
	connexionButton.addEventListener("click", displayConnexionForm);
}

if(registerButton != null) {
	registerButton.addEventListener("click", displayRegisterForm);
}

if(accountButton != null) {
	accountButton.addEventListener("click", displayAccountForm);
}



///----------------///
/// RESIZABLE DIVS ///
///----------------///
// GET DIVS //
const horizontalResizer 	= document.querySelector("#horizontalResizer");		// drag to resize horizontaly
const verticalResizerLeft 	= document.querySelector("#verticalResizerLeft");	// left vertical resizer
const verticalResizerRight 	= document.querySelector("#verticalResizerRight");	// right vertical resizer

// where is the mouse?
var mouseX = 0;
var mouseY = 0;

// sides of the resizer
var prevSide;
var nextSide;

var direction;
var resizer;

var prevHeight;
var prevWidth;

// when the user start clicking on one resizer
function mouseDownHandler(e) {
	// get the position of the mouse in global vars
	mouseX = e.clientX;
	mouseY = e.clientY;
	//console.log(resizer);
	//console.log(resizer.previousElementSibling);
	// set the global vars to the element that called the func and its previous element
	resizer = this;
	prevSide = resizer.previousElementSibling;
	direction = resizer.getAttribute('class').split(" ")[0];

	// add listeners
	document.addEventListener('mousemove', mouseMoveHandler);
	document.addEventListener('mouseup', mouseUpHandler);

	// prevent mouse from selecting anything on the page
	document.body.style.userSelect = 'none';
    document.body.style.pointerEvents = 'none';

	// store height and width of previous side in gloabl vars
	prevHeight = prevSide.getBoundingClientRect().height;
	prevWidth = prevSide.getBoundingClientRect().width;
}

// this funct is called everytime the mouse move in the page, to resize the divs
function mouseMoveHandler(e) {
	// calc distance by mouse
	const dx = e.clientX - mouseX;
	const dy = e.clientY - mouseY;
	//console.log(resizer);

	switch (direction) {
		// if the slider has class "verticalResizer"
		case 'verticalResizer':
			const h = ((prevHeight + dy) * 100) / resizer.parentNode.getBoundingClientRect().height;
			prevSide.style.height = h + '%';
			document.body.style.cursor = 'row-resize';
			break;
		// if the slider has class "horizontalResizer"
		case 'horizontalResizer':
			const w = ((prevWidth + dx) * 100) / resizer.parentNode.getBoundingClientRect().width;
			prevSide.style.width = w + '%';
			document.body.style.cursor = 'col-resize';
		default:
			break;
	}

}

// when the user release mouse, we remove listeners
function mouseUpHandler(e) {
	// remove cursor style
	horizontalResizer.style.removeProperty('cursor');
	document.body.style.removeProperty('cursor');

	// remove preventing selection
	document.body.style.removeProperty('user-select');
    document.body.style.removeProperty('pointer-events');

	// remove handlers
	document.removeEventListener('mousemove', mouseMoveHandler);
    document.removeEventListener('mouseup', mouseUpHandler);
}

// add all listeners to sliders
horizontalResizer.addEventListener('mousedown', mouseDownHandler);
verticalResizerLeft.addEventListener('mousedown', mouseDownHandler);
verticalResizerRight.addEventListener('mousedown', mouseDownHandler);






///--------------///
/// SORT OF MAIN ///
///--------------///
/* initialize web page */
/// SET COLOR VALUE ///
cssRoot.style.setProperty('--hue', colorSlider.value);

/// SET LEVEL SELECTOR ///
levelSelector.value = levelNumberHTML.innerHTML;

/// GET INSTRUCTIONS ///
sendRequest("php/sql.php?idLevel="+levelNumberHTML.innerHTML+"&type=instructions", (inst)=>{
	instructionsDiv.innerHTML = inst;
});

/// DISPLAY LESSON ///
sendRequest("php/sql.php?idLevel="+levelNumberHTML.innerHTML+"&type=lesson", (lesson)=>{
	// put lesson in the text div
	lessonDiv.querySelector(".text").innerHTML = lesson;
	displayLesson();
});

/// GET HINT ///
sendRequest("php/sql.php?idLevel="+levelNumberHTML.innerHTML+"&type=hint", (hint)=>{
	// put hint in the text div
	hintDiv.querySelector(".text").innerHTML = hint;
});

/// GET WIN MESSAGE ///
sendRequest("php/sql.php?idLevel="+levelNumberHTML.innerHTML+"&type=success", (msg)=> {
	endDiv.querySelector(".text").innerHTML = msg;
})