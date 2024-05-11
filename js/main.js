document.addEventListener("DOMContentLoaded", function() {
    var startButton = document.getElementById("start-button");
    var levelContainer = document.getElementById("level-container");
    var gridContainer = document.getElementById("grid-container");
    var level8x8Button = document.getElementById("level-8x8");
    var level16x16Button = document.getElementById("level-16x16");
    var level16x30Button = document.getElementById("level-16x30");
    var backToLevelButton = document.getElementById("back-to-level-button");

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
        gridContainer.innerHTML = '';
        gridContainer.style.display = "none";
        document.getElementById("back-to-level").style.display = "none";
    });

    function generateGrid(rows, columns) {
        const gridContainer = document.getElementById("grid-container");
        gridContainer.innerHTML = ""; // Clear previous content
    
        const container = document.createElement("div");
        container.classList.add("container");
    
        const table = document.createElement("table");
        table.classList.add("table", "is-bordered", "is-fullwidth");
    
        const tbody = document.createElement("tbody");
    
        for (let i = 0; i < rows; i++) {
            const tr = document.createElement("tr");
            for (let j = 0; j < columns; j++) {
                const td = document.createElement("td");
                td.classList.add("square-cell", "has-background-black", "has-text-centered");
                td.style.height = "30px"; // Adjust as needed
                td.addEventListener("click", cellClicked);
                td.addEventListener("contextmenu", function(event) {
                    event.preventDefault(); // Prevent the default context menu
                    cellClicked(event); // Trigger cellClicked function on right-click
                });
                tr.appendChild(td);
            }
            tbody.appendChild(tr);
        }
    
        table.appendChild(tbody);
        container.appendChild(table);
        gridContainer.appendChild(container);
        gridContainer.style.display = "block";
    }

    // Cell click event handler
    function cellClicked(event) {
        const cell = event.target;
        const row = cell.parentElement.rowIndex; // Get the row index
        const column = cell.cellIndex; // Get the column index
        console.log("Row:", row, "Column:", column);
        console.log(event.button);
    }

    function showBackButton() {
        document.getElementById("back-to-level").style.display = "block";
    }
});
