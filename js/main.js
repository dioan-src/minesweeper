const FETCH_BOARD_URL = '/api/FetchBoard.php';
const OPEN_SQUARE_URL = '/api/OpenSquare.php';
const TOUCH_SQUARE_URL = '/api/TouchSquare.php';
const FLAG_SQUARE_URL = '/api/FlagSquare.php';
const LEFT_CLICK = 0;
const RIGHT_CLICK = 2;
const FLAG_ICON = '⚑';

document.addEventListener("DOMContentLoaded", function() {
    var startButton = document.getElementById("start-button");
    var levelContainer = document.getElementById("level-container");
    var gridContainer = document.getElementById("grid-container");
    var level8x8Button = document.getElementById("level-8x8");
    var level16x16Button = document.getElementById("level-16x16");
    var level16x30Button = document.getElementById("level-16x30");
    var backToLevelButton = document.getElementById("back-to-level");

    startButton.addEventListener("click", function() {
        document.getElementById("start-container").style.display = "none";
        levelContainer.style.display = "block";
    });

    level8x8Button.addEventListener("click", function () {
        levelContainer.style.display = "none";
        generateGrid(8, 8);
        showBackButton();
    });

    level16x16Button.addEventListener("click", function () {
        levelContainer.style.display = "none";
        generateGrid(16, 16);
        showBackButton();
    });

    level16x30Button.addEventListener("click", function () {
        levelContainer.style.display = "none";
        generateGrid(16, 30);
        showBackButton();
    });

    backToLevelButton.addEventListener("click", function () {
        levelContainer.style.display = "block";
        gridContainer.style.display = "none";
        backToLevelButton.style.display = "none";
    });

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
                    td.textContent = board[i][j];
                    assignAdditionalClasses(td, board[i][j]);
                }
                
                tr.appendChild(td);
            }
            tableBody.appendChild(tr);
        }
        gridContainer.style.display = "block";
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
            default:
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
        //TODO another one to send to cell-touch - havent figured that out yet
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
                
                if (board  !== undefined && gameStatus  !== undefined) {
                    generateGrid(numRows, numColumns, board, gameStatus);
                }

                //TODO show pop-up -> new game
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
