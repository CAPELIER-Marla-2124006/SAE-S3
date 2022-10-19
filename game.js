const config = {
    // game window size
    width: 720,
    height: 480,
    backgroundColor: "ffffff",
    // game type auto
    type: Phaser.AUTO,
    // game physics (grounds, fall speed...)
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
let grounds         // grounds where player can move
let colliders = {}  // array of colliders
let debugNB = 0

function preload() {
    this.load.image('player', 'assets/images/player.png')
    this.load.image('ground', 'assets/images/ground.png')
}

function create() {
    player = this.physics.add.image(100, 100, 'player')
    player.setCollideWorldBounds(true)
    player.body.checkCollision.up = false

    cursors = this.input.keyboard.createCursorKeys()

    grounds = this.physics.add.staticGroup()
    // create ground
    grounds.create(config.width/2, config.height-(32/2*1.7), 'ground').setScale(1.7).refreshBody()
    // create another ground
    grounds.create(config.width*3/4, config.height*2/3, 'ground')

    colliders["player-ground"] = this.physics.add.collider(player, grounds)

}

function update() {
    player.setVelocityX(0)
    if(cursors.up.isDown && (player.body.onFloor() || player.body.touchingDown)) {
        player.setVelocityY(-600)
    }
    if(cursors.left.isDown)
        player.setVelocityX(-200)
    if(cursors.right.isDown)
        player.setVelocityX(200)
}