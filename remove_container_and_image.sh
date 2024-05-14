# Remove Docker container
echo "Removing Minesweeper container..."
docker rm minesweeper_container

# Check if container was removed successfully
if [ $? -eq 0 ]; then
    echo "Minesweeper container removed successfully."
else
    echo "Failed to remove Minesweeper container."
fi

# Remove Docker image
echo "Removing Minesweeper image..."
docker rmi minesweeper

# Check if image was removed successfully
if [ $? -eq 0 ]; then
    echo "Minesweeper image removed successfully."
else
    echo "Failed to remove Minesweeper image."
fi