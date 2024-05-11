<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minesweeper</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>


<div class="hero is-fullheight is-primary is-bold">
    <div class="hero-body">
        <div class="container has-text-centered">
            <div class="columns is-centered">
                <div class="column is-half">
                    <div id="start-container" class="box">
                        <h2 class="title has-text-primary">Welcome to Minesweeper!</h2>
                        <div class="buttons is-centered">
                            <button id="start-button" class="button is-primary">Let's Play</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="columns is-centered">
                <div class="column is-half">
                    <div id="level-container" class="box" style="display: none;">
                        <h2 class="title has-text-primary">Select Difficulty Level</h2>
                        <div class="buttons is-centered">
                            <button id="level-8x8" class="button is-primary">8x8</button>
                            <button id="level-16x16" class="button is-primary">16x16</button>
                            <button id="level-16x30" class="button is-primary">16x30</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="columns is-centered">
                <div class="column">
                    <div id="grid-container" class="box" style="display: none;">
                        <!-- Grid will be dynamically generated here -->                    
                    </div>
                </div>
            </div>
            <div id="back-to-level" class="buttons is-centered" style="display: none;">
                <button id="back-to-level-button" class="button is-black has-text-primary is-medium">Back to Level Selection</button>
            </div>
        </div>
    </div>
</div>

<script src="js/main.js"></script>

</body>
</html>
