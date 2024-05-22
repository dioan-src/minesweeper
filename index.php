<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minesweeper</title>
    <link rel="icon" href="mine.svg" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=<?=time()?>">
</head>
<body>

<div id="mother-div" class="hero is-fullheight has-background-black is-bold">
    <div class="container has-text-centered is-centered">
        <div class="columns is-centered m-3">
            <div id="start-container" class="column is-half">
                <div id="lets-play-container" class="box has-box-shadow">
                    <h2 id="welcoming-title" class="title has-text-primary">Welcome to Minesweeper!</h2>
                    <div class="buttons is-centered">
                        <button id="start-button" class="button is-primary is-dark">Let's Play</button>
                    </div>
                </div>
            </div>
            <div id="level-container" class="column is-half" style="display: none;">
                <div id="select-level-container" class="box has-box-shadow">
                    <h2 id="level-title"  class="title has-text-primary">Select Difficulty Level</h2>
                    <div class="buttons is-centered">
                        <button id="level-8x8" class="button is-primary is-dark">8x8</button>
                        <button id="level-16x16" class="button is-primary is-dark">16x16</button>
                        <button id="level-16x30" class="button is-primary is-dark">16x30</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="columns is-centered contain-grid">
            <div class="column">
                <div id="grid-container" class="box has-box-shadow" style="display: none;">
                    <div is="board-info" class="columns is-multiline is-mobile board-info">
                        <div class="column is-one-quarter has-text-left">
                            <div class="tooltip">
                                <i class="fas fa-info-circle"></i>
                                <span class="tooltiptext">
                                    <ul>
                                        <li>->Left-click to open a closed square.</li>
                                        <li>->Right-click a closed square to flag/unflag it.</li>
                                        <li>->Left-click an opened square once you've flagged all the correct neighbors to open its un-mined neighbors.</li>
                                    </ul></span>
                            </div>
                        </div>
                        <div class="column is-half">
                            <p id="timer" class="has-text-white"></p>
                        </div>
                        <div class="column is-one-quarter has-text-right">
                            <p id="mines-revealed" class="has-text-white">
                                ⚑<span id="mines-found"></span>/<span id="total-mines"></span>
                            </p>
                        </div>
                    </div>
                    <table id="minesweeper-table" class="table is-bordered is-fullwidth">
                        <tbody>
                            <!-- Table content will be dynamically generated here -->
                        </tbody>
                    </table>
                </div>
                <div id="new-game-container" class="buttons is-centered" style="display: none;">
                    <button id="new-game-button" class="button is-primary is-dark is-medium has-box-shadow">New Game</button>
                </div>
            </div>
        </div>
        <div id="back-to-level" class="buttons is-centered" style="display: none;">
            <button id="back-to-level-button" class="button is-primary is-dark is-medium has-box-shadow">Back to Level Selection</button>
        </div>
        <div>
            <button id="mode-button" class="button is-primary is-dark has-box-shadow">Light Mode</button>
        </div>
    </div>
</div>

<div id="game-result-modal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-content">
        <div id="modal-content-box" class="box centered-flex has-text-black">
            <p id="game-result-message"></p>
            <button id="game-result-modal-close" class="delete" aria-label="close"></button>
        </div>
    </div>
</div>

<script src="js/main.js?v=<?=time()?>"></script>


</body>
</html>
