main();

// Func main()
function main() {
    const canvas = document.querySelector("#game");
    // init le contexte de webgl
    const gl = canvas.getContext("webgl");

    // arreter si webgl n'est pas disponible
    if(!gl) {
        alert("Impossible d'initialiser WebGL.")
        return;
    }

    // def la couleur d'effacement en noir
    gl.clearColor(0.0, 0.0, 0.0, 1.0);

    // efface le tampon avec la couleur d'effacement
    gl.clear(gl.COLOR_BUFFER_BIT|gl.DEPTH_BUFFER_BIT);
}
