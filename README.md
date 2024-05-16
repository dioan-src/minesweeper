# Game Web App

This is a web application for playing a game implemented in PHP 8.1.2 and JavaScript.

## Running the Game

### Terminal Version
To play the game in the terminal, run the following command:
```
php Run.php
```

### Preset Board Version
If you want to play the game with a preset board, run:
```
php RunWithPreset.php
```

### Dockerized Version
For running the game in a Docker container, follow these steps:

1. Execute the script to build and run the Docker image:
```
./build_and_run.sh
```

2. Once the image is built and the container is running, access the game by opening your web browser and navigating to:
```
http://127.0.0.1:5001/
```

## Game Instructions
- Left-click to open a closed square.
- Right-click a closed square to flag/unflag it.
- Left-click an open square once you've flagged all the correct squares to open its in-mined neighbors.
