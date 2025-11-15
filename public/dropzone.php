<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Image Upload</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 900px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
            font-size: 2rem;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        .dropzone {
            border: 3px dashed #667eea;
            border-radius: 15px;
            background: #f8f9ff;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dropzone:hover {
            border-color: #764ba2;
            background: #f0f1ff;
        }

        .dropzone.dz-drag-hover {
            border-color: #764ba2;
            background: #e8e9ff;
            transform: scale(1.02);
        }

        .dropzone .dz-message {
            margin: 0;
            font-size: 1.1rem;
            color: #667eea;
        }

        .dropzone .dz-message .icon {
            font-size: 3rem;
            display: block;
            margin-bottom: 15px;
        }

        .dropzone .dz-preview {
            margin: 15px;
        }

        .dropzone .dz-preview .dz-image {
            border-radius: 10px;
            overflow: hidden;
        }

        .dropzone .dz-preview .dz-details {
            background: rgba(102, 126, 234, 0.8);
            border-radius: 10px;
        }

        .stats {
            margin-top: 30px;
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 30px;
            border-radius: 10px;
            text-align: center;
            min-width: 150px;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .controls {
            margin-top: 20px;
            text-align: center;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .btn-clear {
            background: #e74c3c;
            color: white;
        }

        .btn-clear:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .btn-upload {
            background: #27ae60;
            color: white;
        }

        .btn-upload:hover {
            background: #229954;
            transform: translateY(-2px);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 1.5rem;
            }

            .dropzone {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📸 Multi-Image Upload</h1>
        <p class="subtitle">Drag and drop images or click to browse</p>
        
        <form action="/upload" class="dropzone" id="imageDropzone">
            <div class="dz-message">
                <span class="icon">☁️</span>
                <span>Drop images here or click to upload</span>
                <div style="margin-top: 10px; font-size: 0.9rem; color: #999;">
                    Supports: JPG, PNG, GIF (Max 5MB per file)
                </div>
            </div>
        </form>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-number" id="totalFiles">0</div>
                <div class="stat-label">Total Files</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="totalSize">0 MB</div>
                <div class="stat-label">Total Size</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="uploadedFiles">0</div>
                <div class="stat-label">Uploaded</div>
            </div>
        </div>

        <div class="controls">
            <button class="btn btn-clear" id="clearBtn" disabled>Clear All</button>
            <button class="btn btn-upload" id="uploadBtn" disabled>Upload All</button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
    <script>
        // Disable auto discover
        Dropzone.autoDiscover = false;

        // Initialize Dropzone
        const myDropzone = new Dropzone("#imageDropzone", {
            url: "/send.php", // Change this to your server endpoint
            autoProcessQueue: false, // Don't upload automatically
            uploadMultiple: true,
            parallelUploads: 10,
            maxFiles: 50,
            maxFilesize: 5, // MB
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            dictDefaultMessage: "Drop images here or click to upload",
            
            init: function() {
                const dz = this;
                
                // Upload button
                document.getElementById("uploadBtn").addEventListener("click", function(e) {
                    e.preventDefault();
                    if (dz.getQueuedFiles().length > 0) {
                        dz.processQueue();
                    }
                });

                // Clear button
                document.getElementById("clearBtn").addEventListener("click", function(e) {
                    e.preventDefault();
                    dz.removeAllFiles(true);
                    updateStats();
                });

                // Update stats when files are added
                this.on("addedfile", function(file) {
                    updateStats();
                    updateButtons();
                });

                // Update stats when files are removed
                this.on("removedfile", function(file) {
                    updateStats();
                    updateButtons();
                });

                // Handle successful upload
                this.on("success", function(file, response) {
                    console.log("File uploaded successfully:", file.name);
                    updateStats();
                });

                // Handle upload error
                this.on("error", function(file, errorMessage) {
                    console.error("Upload error:", errorMessage);
                    alert("Error uploading " + file.name + ": " + errorMessage);
                });

                // When all files are uploaded
                this.on("queuecomplete", function() {
                    alert("All files uploaded successfully!");
                });
            }
        });

        function updateStats() {
            const files = myDropzone.files;
            const totalFiles = files.length;
            let totalSize = 0;
            let uploadedFiles = 0;

            files.forEach(file => {
                totalSize += file.size;
                if (file.status === "success") {
                    uploadedFiles++;
                }
            });

            document.getElementById("totalFiles").textContent = totalFiles;
            document.getElementById("totalSize").textContent = (totalSize / (1024 * 1024)).toFixed(2) + " MB";
            document.getElementById("uploadedFiles").textContent = uploadedFiles;
        }

        function updateButtons() {
            const hasFiles = myDropzone.files.length > 0;
            document.getElementById("clearBtn").disabled = !hasFiles;
            document.getElementById("uploadBtn").disabled = !hasFiles;
        }
    </script>
</body>
</html>