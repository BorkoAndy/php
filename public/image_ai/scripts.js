const form = document.getElementById('uploadForm');
form.onsubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    const res = await fetch('upload.php', {
        method: 'POST',
        body: formData
    });

    const data = await res.json();
    if(data.success){
        document.getElementById('result').innerHTML = `
            <p>File uploaded: ${data.file}</p>
            <img src="${data.file}" width="300">
        `;
    } else {
        document.getElementById('result').innerText = data.error;
    }
};