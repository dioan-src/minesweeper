const FETCH_BOARD_URL = '/api/FetchBoard.php';
const OPEN_SQUARE_URL = '/api/OpenSquare.php';
const TOUCH_SQUARE_URL = '/api/TouchSquare.php';
const FLAG_SQUARE_URL = '/api/FlagSquare.php';
const KILL_BOARD_URL = '/api/KillBoard.php';
const LEFT_CLICK = 0;
const RIGHT_CLICK = 2;
const FLAG_ICON = '⚑';
const SVG_PATH = "mine.svg";
const WINNER_BANNER = 'I have now idea how you did it but you WON. Congrats I gess?';
const LOSER_BANNER = "Lol.. I knew you'd LOSE";


document.addEventListener("DOMContentLoaded", function() {

    var motherDiv = document.getElementById("mother-div");
    var welcomingTitle = document.getElementById("welcoming-title");
    var letsPlayContainer = document.getElementById("lets-play-container");
    var startButton = document.getElementById("start-button");
    var selectLevelContainer = document.getElementById("select-level-container");
    var levelTitle = document.getElementById("level-title");
    var levelContainer = document.getElementById("level-container");
    var gridContainer = document.getElementById("grid-container");
    var level8x8Button = document.getElementById("level-8x8");
    var level16x16Button = document.getElementById("level-16x16");
    var level16x30Button = document.getElementById("level-16x30");
    var backToLevelButton = document.getElementById("back-to-level");
    var backButton = document.getElementById("back-to-level-button");
    var newGameButton = document.getElementById("new-game-button");
    var modeButton = document.getElementById("mode-button");

    modeButton.addEventListener("click", function() {
        toggleMode();
    });

    function toggleMode() {
        document.body.classList.toggle("light-mode");
        var buttonText = modeButton.textContent.trim();
        modeButton.textContent = buttonText === "Light Mode" ? "Dark Mode" : "Light Mode";
        updateElementClasses();
    }

    function updateElementClasses() {
        motherDiv.classList.toggle('has-background-black');
        motherDiv.classList.toggle('has-background-light');
        letsPlayContainer.classList.toggle('has-background-light');
        welcomingTitle.classList.toggle('has-text-primary');
        welcomingTitle.classList.toggle('has-text-link');
        startButton.classList.toggle('is-primary');
        startButton.classList.toggle('is-link');
        selectLevelContainer.classList.toggle('has-background-light');
        startButton.classList.toggle('is-inverted');
        levelTitle.classList.toggle('has-text-primary');
        levelTitle.classList.toggle('has-text-link');
        level8x8Button.classList.toggle('is-primary');
        level8x8Button.classList.toggle('is-link');
        level8x8Button.classList.toggle('is-inverted');
        level16x16Button.classList.toggle('is-primary');
        level16x16Button.classList.toggle('is-link');
        level16x16Button.classList.toggle('is-inverted');
        level16x30Button.classList.toggle('is-primary');
        level16x30Button.classList.toggle('is-link');
        level16x30Button.classList.toggle('is-inverted');
        backButton.classList.toggle('is-primary');
        backButton.classList.toggle('is-link');
        backButton.classList.toggle('is-inverted');
        newGameButton.classList.toggle('is-primary');
        newGameButton.classList.toggle('is-link');
        newGameButton.classList.toggle('is-inverted');
        modeButton.classList.toggle('is-primary');
        modeButton.classList.toggle('is-link');
        modeButton.classList.toggle('is-inverted');
    }

    startButton.addEventListener("click", function() {
        document.getElementById("start-container").style.display = "none";
        levelContainer.style.display = "block";
    });

    level8x8Button.addEventListener("click", function () {
        levelContainer.style.display = "none";
        fetchBoard(8, 8);
        document.getElementById("new-game-container").style.display = "block";
        gridContainer.style.display = "block";
        showBackButton();
    });

    level16x16Button.addEventListener("click", function () {
        levelContainer.style.display = "none";
        fetchBoard(16, 16);
        document.getElementById("new-game-container").style.display = "block";
        gridContainer.style.display = "block";
        showBackButton();
    });

    level16x30Button.addEventListener("click", function () {
        levelContainer.style.display = "none";
        fetchBoard(16, 30);
        document.getElementById("new-game-container").style.display = "block";
        gridContainer.style.display = "block";
        showBackButton();
    });

    backToLevelButton.addEventListener("click", function () {
        levelContainer.style.display = "block";
        gridContainer.style.display = "none";
        backToLevelButton.style.display = "none";
        document.getElementById("new-game-container").style.display = "none";
    });
    
    newGameButton.addEventListener("click", function () {
        // Get the dimensions of the existing board
        const board = document.getElementById('minesweeper-table');
        const numRows = board.rows.length;
        const numColumns = board.rows[0].cells.length;
        killBoard(numRows, numColumns);
        generateGrid(numRows, numColumns);
    });

    document.getElementById('game-result-modal-close').addEventListener('click', function() {
        hideGameResultModal();
    });

    function showGameResultModal(message, gameOutcome) {
        document.getElementById('game-result-message').innerText = message;
        var modalContentBox = document.getElementById('modal-content-box');
        modalContentBox.classList.add(gameOutcome ? 'has-background-success' : 'has-background-danger');
        var gameResultModal = document.getElementById('game-result-modal');
        gameResultModal.classList.add('is-active');
    }

    function hideGameResultModal() {
        var modalContentBox = document.getElementById('modal-content-box');
        modalContentBox.classList.remove('has-background-success', 'has-background-danger');
        var gameResultModal = document.getElementById('game-result-modal');
        gameResultModal.classList.remove('is-active');
    }

    function generateGrid(rows, columns, board =null, isGameActive = true) {
        const tableBody = document.querySelector('#minesweeper-table tbody');
        tableBody.innerHTML = "";
    
        for (let i = 0; i < rows; i++) {
            const tr = document.createElement("tr");
            for (let j = 0; j < columns; j++) {
                const td = document.createElement("td");
                td.classList.add("square-cell", "has-background-text-30", "has-text-centered");

                if (isGameActive) {
                    td.addEventListener("click", cellClicked);
                    td.addEventListener("contextmenu", handleContextMenu);
                }

                if (board?.[i]?.[j] !== undefined) {
                    const cellContent = board[i][j];
                    if (cellContent === '*') {
                        // Create and append the SVG element
                        const svg = document.createElement("img");
                        svg.src = SVG_PATH;
                        svg.classList.add("svg-icon");
                        td.appendChild(svg);
                    } else {
                        td.textContent = cellContent;
                    }
                    assignAdditionalClasses(td, board[i][j]);
                }
                
                tr.appendChild(td);
            }
            tableBody.appendChild(tr);
        }
    }

    function assignAdditionalClasses(element, value) {
        // Example logic: Add classes based on the value of the cell
        switch (value) {
            case 0:
                element.classList.add("has-background-text-80", "has-text-text-80");
                break;
            case 1:
                element.classList.add("has-background-primary-85", "has-text-black");
                break;
            case 2:
                element.classList.add("has-background-warning-90", "has-text-black");
                break;
            case 3:
                element.classList.add("has-background-warning-80", "has-text-black");
                break;
            case 4:
                element.classList.add("has-background-warning-65", "has-text-black");
                break;
            case 5:
                element.classList.add("has-background-danger-80", "has-text-black");
                break;
            case 6:
                element.classList.add("has-background-danger-70", "has-text-black");
                break;
            case 7:
                element.classList.add("has-background-danger-60", "has-text-black");
                break;
            case 8:
                element.classList.add("has-background-danger-dark", "has-text-black");
                break;
            case FLAG_ICON:
                element.classList.add("has-text-danger");
                break;
            case '*':
                element.classList.add("has-background-danger");
                break;
        }
    }

    function handleContextMenu(event) {
        event.preventDefault();
        cellClicked(event);
    }

    function showBackButton() {
        backToLevelButton.style.display = "block";
    }

    function cellClicked(event) {
        const cell = event.target;
        const cellContent = cell.textContent; // Get the content of the clicked cell
        const row = cell.parentElement.rowIndex;
        const column = cell.cellIndex;
        // Get the dimensions of the existing board
        const board = document.getElementById('minesweeper-table');
        const numRows = board.rows.length;
        const numColumns = board.rows[0].cells.length;
        const numericContent = parseInt(cellContent);
        
        if (event.button === LEFT_CLICK) {
            //request touch only on open squares
            if (!isNaN(numericContent) && numericContent >= 1 && numericContent <= 8){
                touchCell(row, column, numRows, numColumns);
            }
            //request open only on hidden squares
            if (cellContent == '') {
                openCell(row, column, numRows, numColumns);
            }
        }else if(event.button === RIGHT_CLICK) {
            //request flag only on hidden/flagged squares
            if (cellContent == '' || cellContent == FLAG_ICON) {
                flagCell(row, column, numRows, numColumns);
            }
        }
    }

    function openCell(row, column, numRows, numColumns) {
        sendAjaxRequest(
            OPEN_SQUARE_URL,
            'POST',
            {row: row, column: column, boardRows: numRows, boardColumns: numColumns},
            function(response) {
                // Handle successful response from the backend
                const responseObject = JSON.parse(response);
                board = responseObject.board;
                gameStatus = responseObject.game_status;
                nonMinedCellsRevealed = responseObject.nonMinedCellsRevealed;
                
                if (board  !== undefined && gameStatus  !== undefined) {
                    generateGrid(numRows, numColumns, board, gameStatus);
                    if (!gameStatus){ //game is over
                        gameOutcomeBanner = nonMinedCellsRevealed ? WINNER_BANNER : LOSER_BANNER ;
                        showGameResultModal(gameOutcomeBanner, nonMinedCellsRevealed);
                    }
                }
            },
            function(error) {
                // Handle error if AJAX request fails
                console.error('Error sending move to the backend:', error);
            }
        );
    }

    function flagCell(row, column, numRows, numColumns) {
        sendAjaxRequest(
            FLAG_SQUARE_URL,
            'POST',
            {row: row, column: column, boardRows: numRows, boardColumns: numColumns},
            function(response) {
                // Handle successful response from the backend
                const responseObject = JSON.parse(response);
                board = responseObject.board;
                gameStatus = responseObject.game_status;
                
                if (board  !== undefined && gameStatus  !== undefined) {
                    generateGrid(numRows, numColumns, board, gameStatus);
                }
            },
            function(error) {
                // Handle error if AJAX request fails
                console.error('Error sending move to the backend:', error);
            }
        );
    }

    function touchCell(row, column, numRows, numColumns) {
        sendAjaxRequest(
            TOUCH_SQUARE_URL,
            'POST',
            {row: row, column: column, boardRows: numRows, boardColumns: numColumns},
            function(response) {
                // Handle successful response from the backend
                const responseObject = JSON.parse(response);
                board = responseObject.board;
                gameStatus = responseObject.game_status;
                nonMinedCellsRevealed = responseObject.nonMinedCellsRevealed;
                
                if (board  !== undefined && gameStatus  !== undefined) {
                    generateGrid(numRows, numColumns, board, gameStatus);
                    if (!gameStatus){ //game is over
                        gameOutcomeBanner = nonMinedCellsRevealed ? WINNER_BANNER : LOSER_BANNER ;
                        showGameResultModal(gameOutcomeBanner, nonMinedCellsRevealed);
                    }
                }
            },
            function(error) {
                // Handle error if AJAX request fails
                console.error('Error sending move to the backend:', error);
            }
        );
    }

    function killBoard(numRows, numColumns) {
        sendAjaxRequest(
            KILL_BOARD_URL,
            'POST',
            {boardRows: numRows, boardColumns: numColumns},
            function(response) {
                // Handle successful response from the backend
            },
            function(error) {
                // Handle error if AJAX request fails
                console.error('Error sending move to the backend:', error);
            }
        );
    }

    function fetchBoard(numRows, numColumns) {
        const url = `${FETCH_BOARD_URL}?boardRows=${numRows}&boardColumns=${numColumns}`;
        sendAjaxRequest(
            url,
            'GET',
            null,
            function(response) {
                // Handle successful response from the backend
                const responseObject = JSON.parse(response);
                board = responseObject.board;
                gameStatus = responseObject.game_status;
                
                if (board  !== undefined && gameStatus  !== undefined) {
                    generateGrid(numRows, numColumns, board, gameStatus);
                }else{
                    generateGrid(numRows, numColumns);
                }
            },
            function(error) {
                // Handle error if AJAX request fails
                console.error('Error sending move to the backend:', error);
            }
        );
    }

    function sendAjaxRequest(url, method, data, successCallback, errorCallback) {
        var xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    successCallback(xhr.responseText);
                } else {
                    errorCallback(xhr.statusText);
                }
            }
        };
        xhr.send(JSON.stringify(data));
    }
});
