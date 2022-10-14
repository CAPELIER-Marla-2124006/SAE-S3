const config = {
    width: 720,
    height: 480,
    type: Phaser.AUTO,
    physics: {
        default: 'arcade',
        arcade: {
            gravity: {y: 500}
        }
    },
    scene: {
        preload: preload,
        create: create,
        update: update
    }
}

var game = new Phaser.Game(config)
let dude
let cursors

function preload() {
    this.load.image('perso', 'assets/images/test1/perso.png')
}

function create() {
    dude = this.physics.add.image(100, 100, 'perso')
    dude.body.collideWorldBounds = true;

    cursors = this.input.keyboard.createCursorKeys()
}

function update() {
    dude.setVelocityX(0);
    if(cursors.up.isDown && (dude.body.onFloor() || dude.body.touchingDown)) dude.setVelocityY(-450);
    if(cursors.left.isDown) dude.setVelocityX(-250);
    if(cursors.right.isDown) dude.setVelocityX(250);
}
