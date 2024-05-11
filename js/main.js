const START_GAME_URL = '/api/Start.php';
const OPEN_SQUARE_URL = '/api/OpenSquare';
const TOUCH_SQUARE_URL = '/api/TouchSquare.php';
const FLAG_SQUARE_URL = '/api/FlagSquare.php';

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

    function generateGrid(rows, columns) {
        const tableBody = document.querySelector('#minesweeper-table tbody');
        tableBody.innerHTML = "";
    
        for (let i = 0; i < rows; i++) {
            const tr = document.createElement("tr");
            for (let j = 0; j < columns; j++) {
                const td = document.createElement("td");
                td.classList.add("square-cell", "has-background-black", "has-text-centered");
                td.addEventListener("click", cellClicked);
                td.addEventListener("contextmenu", function(event) {
                    event.preventDefault(); // Prevent the default context menu
                    cellClicked(event); // Trigger cellClicked function on right-click
                });
                tr.appendChild(td);
            }
            tableBody.appendChild(tr);
        }
        gridContainer.style.display = "block";

    }

    function showBackButton() {
        backToLevelButton.style.display = "block";
    }

    function cellClicked(event) {
        const cell = event.target;
        const row = cell.parentElement.rowIndex;
        const column = cell.cellIndex;
        // Get the dimensions of the existing board
        const board = document.getElementById('minesweeper-table');
        const numRows = board.rows.length;
        const numColumns = board.rows[0].cells.length;
        console.log(row, column, numRows, numColumns);
    
        // Create an object with the row and column indices
        const moveData = {
            row: row,
            column: column,
            boardRows: numRows,
            boardColumns: numColumns
        };
    
        // Call the sendAjaxRequest function with appropriate parameters
        sendAjaxRequest(
            START_GAME_URL,
            'POST',
            moveData,
            function(response) {
                // Handle successful response from the backend
                const responseObject = JSON.parse(response);
                console.log(responseObject);
                
                // Call the recreateBoard function with the response data
                // recreateBoard(response);
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
