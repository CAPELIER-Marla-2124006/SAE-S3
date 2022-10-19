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
let groundList = {}
let platforms
let platformList = {}// platforms where we can pas thru
let colliders = {}  // array of colliders

function preload() {
    this.load.image('player', 'assets/images/player.png')
    this.load.image('ground', 'assets/images/ground.png')
    this.load.image('platform', 'assets/images/platform.png')
}

function create() {
    player = this.physics.add.image(100, 100, 'player')
    player.setCollideWorldBounds(true)

    cursors = this.input.keyboard.createCursorKeys()

    // create the ground
    grounds = this.physics.add.staticGroup()
    groundList["bottom"] = grounds.create(config.width/2, config.height-(32/2*1.7), 'ground').setScale(1.7).refreshBody()
    groundList["top"] = grounds.create(config.width*(4/5), config.height*(2/5), 'ground').setScale(0.8).refreshBody()
    colliders["player-ground"] = this.physics.add.collider(player, grounds)

    // create platforms
    platformList["first-platform"] = this.physics.add.staticSprite(config.width*(3/4), config.height*(2/3), 'platform').refreshBody()
    // create collisions with the platforms and player
    for (const key of Object.keys(platformList)) {
        platformList[key].body.checkCollision.down = false
        platformList[key].body.checkCollision.left = false
        platformList[key].body.checkCollision.right = false
        colliders[key] = this.physics.add.collider(player, platformList[key])
    }

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