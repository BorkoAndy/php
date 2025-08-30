const form = document.getElementById('uploadForm');
form.onsubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    // fetch('upload.php', { method: 'POST', body: formData })
    // .then(res => res.json()) // now safe
    // .then(data => console.log(data))
    // .catch(err => console.error('JSON parse error:', err));


    fetch('upload.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Status:', response.status);
        console.log('Content-Type:', response.headers.get('content-type'));
        return response.text(); // Get as text first, not JSON
    })
    .then(data => {
        console.log('Full response:', data); // This will show you the actual HTML error
        // Try to parse as JSON only if it looks like JSON
        if (data.startsWith('{') || data.startsWith('[')) {
            const jsonData = JSON.parse(data);
            console.log('Parsed JSON:', jsonData);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
    });

};