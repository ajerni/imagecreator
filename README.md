# Image Creator

A simple web application that generates images based on text prompts using an n8n webhook with Flux schnell model via HuggingFace.

## Features

- Simple, clean interface built with Bootstrap
- Text input for image generation prompts
- Real-time image generation display
- Loading indicator for feedback during generation
- Secure credentials storage in separate PHP file

## Usage

1. Upload both `index.html` and `credentials.php` to your PHP-enabled web server
2. Access the `index.html` file through your web browser
3. Enter a text prompt describing the image you want to create
4. Click the "Create Image" button
5. Wait for the image to be generated and displayed

## Technical Details

- Uses Bootstrap from CDN for styling
- Makes authenticated POST requests to n8n webhook
- Handles base64 encoded images or image URLs in the response
- Securely stores authentication credentials in a separate PHP file
- Requires a web server with PHP support

## Setup

1. Upload both files to a web server with PHP support:
   - `index.html` - The main application interface
   - `credentials.php` - Contains authentication credentials

2. Make sure the credentials.php file is only accessible from your server (not directly by users)

3. Access the application by navigating to index.html in your web browser

## Security Notes

- The credentials.php file contains sensitive authentication information
- Consider implementing additional security measures in a production environment:
  - Add .htaccess rules to restrict direct access to credentials.php
  - Use environment variables for credentials on the server
  - Implement proper user authentication if needed 