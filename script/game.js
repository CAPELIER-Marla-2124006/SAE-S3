///----------------------------///
/// GET AND STORE DIVS IN PAGE ///
///----------------------------///
const executeButton 	= document.querySelector("#execute");			// query button
const resetButton 		= document.querySelector("#restart");			// reset button
const levelNumberHTML 	= document.querySelector("#levelNumber");		// level number stored in page
let levelNumber			= levelNumberHTML.innerHTML;
const instructionsDiv 	= document.querySelector("#instructions");		// instructions div for exercice
const lessonButton 		= document.querySelector("#lesson");			// button to display the lesson
const hintButton 		= document.querySelector("#hint");				// button to display the hint
const resultsDiv 		= document.querySelector("#results");			// results div
const levelSelector 	= document.querySelector("#levels");			// level selector to trigger when changed
const notesTextarea 	= document.querySelector("#notes");				// textarea where the user can type notes
const lessonDiv 		= document.querySelector("#popupLesson");		// where the lesson will be displayed
const popupTheEnd		= document.querySelector("#popupTheEnd");		// the final popup of the game, when the user finished everything
const hintDiv 			= document.querySelector("#popupHint");			// where the hint will be displayed
const successDiv 		= document.querySelector("#popupEnd");			// div with win message
const closePopupButton 	= document.querySelectorAll(".exitButton");		// select all closePopup buttons
const nextPopupButton 	= document.querySelector(".nextButton");		// next button in end popup
const popupBackground 	= document.querySelector(".popupBackground");	// background of popups to remove them
const userPoints		= document.querySelector("#userPoints");		// where the points are displayed


///------------------///
/// GLOBAL FUNCTIONS ///
///------------------///
/* change code editor content with db request */
function resetCodeEditor(levelNumber) {
	sendApiRequests("exercise", levelNumber, (json) => {
		editor.setValue(JSON.parse(json)['codeInit']);
	})
}

/* change level selected, reset code editor & edit buttons */
function changeLevel(num) {
	// hide everything
	resultsDiv.innerHTML = "";
	lessonDiv.querySelector(".text").innerHTML = "";
	hintDiv.querySelector(".text").innerHTML = "";
	instructionsDiv.innerHTML = "";
	//editor.setValue("");

	// change level number
	levelNumber = num;

	// get json corresponding to the level requested
	sendApiRequests('exercise', levelNumber, (json)=>{
		let levelJSON = JSON.parse(json);
		let lesson = levelJSON['lesson'];
		let instructions = levelJSON['instructions'];
		let hint = levelJSON['hint'];
		let codeInit = levelJSON['codeInit'];
		let success = levelJSON['success'];

		lessonDiv.querySelector(".text").innerHTML = lesson;
		instructionsDiv.innerHTML = instructions;
		hintDiv.querySelector(".text").innerHTML = hint;
		editor.setValue(codeInit);
		successDiv.querySelector(".text").innerHTML = success;
	});

	//console.log(levelSelector.children);
	for (let i = 0; i < levelNumber; i++) {
		levelSelector.children[i].disabled = false;
	}

}

/* select next level */
function nextLevel() {
	if(levelNumber < 8) {
		changeLevel(levelNumber+1);
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
	successDiv.classList.add("display");
	popupBackground.classList.add("display");
}




///-----------------///
/// EVENT LISTENERS ///
///-----------------///
// when user press the execute button
executeButton.addEventListener("click", () => {
	// CLEAN THE TEXT FROM EDITOR //
	let query = editor.getValue();					// get editor text
	query = query.replace(/--.*\n?/ig, '');			// remove all comment (start with '--' and have end of line)
	query = query.replace(/\n|\r\n/gm, ' ');		// replace new lines by space
	query = query.replace(/select/gmi, 'SELECT');	// put every select in start of line and uppercase
	//console.log(query);

	// we call sendRequest with a func that sendRequest will call that will edit the html in responseDiv
	sendApiRequests("submit", levelNumber, (json) => {
		//console.log(json);
		json = JSON.parse(json);
		resultsDiv.innerHTML = json['table'];
		userPoints.innerHTML = "Points : " + json['points'];
		if(json['win'] === "true") {
			displayWin();
		}
	}, query);

})

// reset content in code editor
resetButton.addEventListener("click", () => {
	resetCodeEditor(levelNumber);
});

// when the user change level from selector, load level
levelSelector.addEventListener("change", ()=>{
	changeLevel(levelSelector.value);
});

// when the user exit notes textarea, store its data in db
notesTextarea.addEventListener("focusout", ()=>{
	sendApiRequests("php/update.php?column=notes&value="+notesTextarea.value, (e)=>{});
});

// when the user change slider value
colorSlider.oninput = function() {
	cssRoot.style.setProperty('--hue', this.value);
};

// when the user slected the value, it's updated in DB
colorSlider.addEventListener("change", ()=> {
	sendApiRequests("php/update.php?column=colorHue&value="+colorSlider.value, (e)=>{});
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
sendApiRequests("php/sql.php?idLevel="+levelNumberHTML.innerHTML+"&type=instructions", (inst)=>{
	instructionsDiv.innerHTML = inst;
});

/// DISPLAY LESSON ///
sendApiRequests("php/sql.php?idLevel="+levelNumberHTML.innerHTML+"&type=lesson", (lesson)=>{
	// put lesson in the text div
	lessonDiv.querySelector(".text").innerHTML = lesson;
	displayLesson();
});

/// GET HINT ///
sendApiRequests("php/sql.php?idLevel="+levelNumberHTML.innerHTML+"&type=hint", (hint)=>{
	// put hint in the text div
	hintDiv.querySelector(".text").innerHTML = hint;
});

/// GET WIN MESSAGE ///
sendApiRequests("php/sql.php?idLevel="+levelNumberHTML.innerHTML+"&type=success", (msg)=> {
	successDiv.querySelector(".text").innerHTML = msg;
})
