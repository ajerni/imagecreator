<?php
// Include authentication check
require_once 'auth.php';

// Handle logout handling was moved to logout.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Creator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        .image-container {
            min-height: 400px;
            max-width: 512px;
            margin: 20px auto;
            border: 1px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            aspect-ratio: 1 / 1;
            position: relative;
            overflow: hidden;
        }
        .loading {
            display: none;
        }
        #generatedImage {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <!-- Main Application -->
    <div class="container">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Andi's Image Creator</h1>
                    <h4>(creatioins visible on <a href="https://imagegallery.andierni.ch" target="_blank" rel="noopener" text-decoration: underline;">imagegallery.andierni.ch</a>)</h4>
                    <a href="logout.php" class="btn btn-outline-danger">Logout</a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form id="imageForm">
                            <div class="mb-3">
                                <label for="promptText" class="form-label">Enter your image prompt:</label>
                                <input type="text" class="form-control" id="promptText" placeholder="Describe the image you want to create..." required>
                            </div>
                            <button type="submit" class="btn btn-primary" id="createBtn">Create Image</button>
                            <div class="spinner-border text-primary mt-3 loading" id="loadingSpinner" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </form>
                        
                        <div class="image-container" id="imageContainer">
                            <p class="text-muted" id="placeholderText">Your generated image will appear here.</p>
                            <img id="generatedImage" style="display: none;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('imageForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const promptText = document.getElementById('promptText').value;
            const createBtn = document.getElementById('createBtn');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const placeholderText = document.getElementById('placeholderText');
            const generatedImage = document.getElementById('generatedImage');
            
            // Show loading state
            createBtn.disabled = true;
            loadingSpinner.style.display = 'inline-block';
            placeholderText.textContent = 'Generating your image...';
            generatedImage.style.display = 'none';
            
            try {
                // Fetch credentials from PHP file
                const credentialsResponse = await fetch('credentials.php');
                
                if (!credentialsResponse.ok) {
                    throw new Error('Failed to load authentication credentials');
                }
                
                const credentialsData = await credentialsResponse.json();
                const credentials = btoa(`${credentialsData.username}:${credentialsData.password}`);
                
                // Make the request to the webhook
                const response = await fetch('https://n8n.ernilabs.com/webhook/e8d9beec-fabb-44be-95ad-48ed75fa26fd', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Basic ${credentials}`
                    },
                    body: JSON.stringify({ text: promptText })
                });
                
                if (!response.ok) {
                    throw new Error(`Error: ${response.status}`);
                }
                
                // Handle the image response
                const contentType = response.headers.get('content-type');
                
                if (contentType && contentType.includes('application/json')) {
                    const result = await response.json();
                    console.log('API result:', result);

                    // Handle case where result is an object with a 'data' property (not an array)
                    if (result.data) {
                        let innerData;
                        try {
                            innerData = JSON.parse(result.data);
                        } catch (e) {
                            placeholderText.textContent = 'Error parsing image data.';
                            return;
                        }
                        if (
                            innerData.data &&
                            Array.isArray(innerData.data) &&
                            innerData.data.length > 0 &&
                            innerData.data[0].imageURL
                        ) {
                            generatedImage.src = innerData.data[0].imageURL;
                            generatedImage.style.display = 'block';
                            placeholderText.style.display = 'none';
                            return;
                        }
                    }

                    // Fallbacks for other possible formats
                    if (result.imageBase64) {
                        generatedImage.src = `data:image/png;base64,${result.imageBase64}`;
                        generatedImage.style.display = 'block';
                        placeholderText.style.display = 'none';
                    } else if (result.imageUrl) {
                        generatedImage.src = result.imageUrl;
                        generatedImage.style.display = 'block';
                        placeholderText.style.display = 'none';
                    } else {
                        placeholderText.textContent = 'Image generation completed, but no image data received';
                        generatedImage.style.display = 'none';
                    }
                } else if (contentType && contentType.includes('image/')) {
                    // Handle direct image response
                    const blob = await response.blob();
                    const imageUrl = URL.createObjectURL(blob);
                    generatedImage.src = imageUrl;
                    generatedImage.style.display = 'block';
                    placeholderText.style.display = 'none';
                } else {
                    // Try to process as text
                    const responseText = await response.text();
                    placeholderText.textContent = 'Received response but could not process as image';
                }
            } catch (error) {
                console.error('Error:', error);
                placeholderText.textContent = `Error generating image: ${error.message}`;
            } finally {
                // Reset UI state
                createBtn.disabled = false;
                loadingSpinner.style.display = 'none';
            }
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 