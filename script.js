var editor = ace.edit("editor");
        editor.setTheme("ace/theme/dracula");
        editor.session.setMode("ace/mode/sql");
        editor.setOptions({
            fontFamily: "JetBrains Mono, monospace",
            fontSize: "14pt"
        })



var btn = document.getElementById("btn");
var query = "SELECT * FROM test;";
var reponse;

btn.addEventListener("click", ()=>{
    console.log("btn clicked");

    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = ()=>{
        reponse = this.responseText;
        console.log(reponse);
    }
    xmlhttp.open("POST", "sql.php", true);
    xmlhttp.send("request="+query);
})
