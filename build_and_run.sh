# Build Docker image
echo "Building Docker image..."
docker build -t minesweeper .

# Check if image was built successfully
if [ $? -eq 0 ]; then
    echo "Docker image built successfully."

    # Run Docker container
    echo "Starting Docker container..."
    docker run -d -p 5001:80 --name minesweeper_container minesweeper

    # Check if container was started successfully
    if [ $? -eq 0 ]; then
        echo "Docker container started successfully."
        echo "You can access your Minesweeper application at http://localhost:5001"
    else
        echo "Failed to start Docker container."
    fi
else
    echo "Failed to build Docker image."
fi
