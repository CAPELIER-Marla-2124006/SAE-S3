const config = {
    // game window size
    width: 720,
    height: 480,
    backgroundColor: "ffffff",
    // game type auto
    type: Phaser.AUTO,
    // game physics (platforms, fall speed...)
    physics: {
        default: 'arcade',
        arcade: {
            gravity: {y: 1000}
        }
    },
    // init game order
    scene: {
        preload: preload,
        create: create,
        update: update
    }
}

var game = new Phaser.Game(config)
let player          // playable object
let cursors         // keyboard cursors to move
let platforms       // platforms where player can move

function preload() {
    this.load.image('player', 'assets/images/player.png')
}

function create() {
    player = this.physics.add.image(100, 100, 'player')
    player.body.collideWorldBounds = true

    cursors = this.input.keyboard.createCursorKeys()

    //platforms = this.physics.add.staticGroup()
    //platforms.create(0, 0, 'platform')
}

function update() {
    player.setVelocityX(0)
    if(cursors.up.isDown && (player.body.onFloor() || player.body.touchingDown))
        player.setVelocityY(-600)
    if(cursors.left.isDown)
        player.setVelocityX(-200)
    if(cursors.right.isDown)
        player.setVelocityX(200)
}