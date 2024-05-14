#!/bin/bash

# Stop Docker container
echo "Stopping Minesweeper container..."
docker stop minesweeper_container

# Check if container was stopped successfully
if [ $? -eq 0 ]; then
    echo "Minesweeper container stopped successfully."
else
    echo "Failed to stop Minesweeper container."
fi
