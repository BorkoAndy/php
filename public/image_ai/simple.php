<!-- upload.html -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Image Analyzer</title>
</head>
<body>
  <h2>Upload an Image for Analysis</h2>
  <form id="uploadForm">
    <input type="file" name="file" id="fileInput" accept="image/*" required>
    <button type="submit">Upload & Analyze</button>
  </form>

  <div id="result"></div>

  <script>
    document.getElementById("uploadForm").addEventListener("submit", async (e) => {
      e.preventDefault();

      const fileInput = document.getElementById("fileInput");
      if (!fileInput.files.length) {
        alert("Please select an image.");
        return;
      }

      const formData = new FormData();
      formData.append("file", fileInput.files[0]);

      try {
        const response = await fetch("https://<your-app>.azurewebsites.net/imageanalyzer/analyze", {
          method: "POST",
          body: formData
        });

        if (!response.ok) {
          throw new Error("HTTP error " + response.status);
        }

        const result = await response.json();
        document.getElementById("result").innerText = JSON.stringify(result, null, 2);
      } catch (err) {
        document.getElementById("result").innerText = "Error: " + err.message;
      }
    });
  </script>
</body>
</html>
