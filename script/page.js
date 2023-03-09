///--------///
/// EDITOR ///
///--------///
// get editor from ace (for syntaxic colors etc)
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



///------------------///
/// GLOBAL FUNCTIONS ///
///------------------///
/* send a request from url, with a lambda in params (almost like the return) */
function sendApiRequests(what, level, callback, post='') {
    let xmlhttp = new XMLHttpRequest();
    // send request to the api
    xmlhttp.open("GET", 'api/v1/' + what + '/' + level, true);
    xmlhttp.send(post??'');

    // when result comes back, we call the function given
    xmlhttp.onload = function () {
        // get response
        let response = this.responseText;
        // callback is a func declared when call this func, to do whatever we want with the response
        callback(response);
    }
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
