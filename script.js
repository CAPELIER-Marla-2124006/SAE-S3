var editor = ace.edit("editor");
        editor.setTheme("ace/theme/dracula");
        editor.session.setMode("ace/mode/sql");
        editor.setOptions({
            fontFamily: "JetBrains Mono, monospace",
            fontSize: "14pt"
        })



var btn = document.getElementById("btn");
var query = "SELECT * FROM TEST;";
var reponse;

btn.addEventListener("click", ()=>{
    console.log("btn clicked");

    let xmlhttp = new XMLHttpRequest();

    xmlhttp.onload = function(){
        reponse = this.responseText;
        console.log(reponse);
    }

    xmlhttp.open("GET", "sql.php?request="+query, true);
    xmlhttp.send();
})
