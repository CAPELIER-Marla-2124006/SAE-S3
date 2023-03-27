///--------///
/// EDITOR ///
///--------///
// get editor from ace (for syntax colors etc.)
const editor = ace.edit("code-editor");
// change theme
editor.setTheme("ace/theme/perso");
// set sql language
editor.session.setMode("ace/mode/sql");
// set font
editor.setOptions({
    fontFamily: "JetBrains Mono, monospace",
    fontSize: "14pt"
})


///----------------------------///
/// GET AND STORE DIVS IN PAGE ///
///----------------------------///
const cssRoot 			= document.querySelector(":root");				// root of the page
const connexionButton	= document.querySelector("#connexionButton");	// button to show connexion form
const connexionForm		= document.querySelector("#connexionForm");		// connexion form
const registerButton	= document.querySelector("#registerButton");	// button to show register form
const registerForm		= document.querySelector("#registerForm");		// register form
const accountButton		= document.querySelector("#accountButton");		// button to disconnect
const accountForm		= document.querySelector("#accountForm");		// form to disconnect
const colorSlider 		= document.querySelector("#colorSlider");		// slider to choose color from


/* initialize web color value */
cssRoot.style.setProperty('--hue', colorSlider.value);


///------------------///
/// GLOBAL FUNCTIONS ///
///------------------///
/* send a request from url, with a lambda in params (almost like the return) */
function sendApiRequests(what, level, callback, post='') {
    //console.log("[Api Request] /api/v1/"+ what + '/' + level)
    //console.log("[Api Request] data : " + post)
    fetch('api/v1/' + what + '/' + level, {method: 'POST', headers: {'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded'}, body: post})
        .then(response => {
            //console.log("[Api Response] response :" + response);
            response.text().then(text =>{
                //console.log("[Api Response] text :" + text);
                callback(text);
            });
        });
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

////////////////////
/// SORT OF MAIN ///
////////////////////
// when the user exit notes textarea, store its data in db
notesTextarea.addEventListener("focusout", ()=>{
    sendApiRequests("saveUser", levelNumber, ()=>{}, 'notes='+notesTextarea.value);
});

// when the user change slider value
colorSlider.oninput = function() {
    cssRoot.style.setProperty('--hue', this.value);
};

// when the user selected the value, it's updated in DB
colorSlider.addEventListener("change", ()=> {
    sendApiRequests("saveUser", levelNumber, ()=>{}, 'colorHue='+colorSlider.value);
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
            break;
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
