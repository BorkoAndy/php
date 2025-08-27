const form = document.getElementById('uploadForm');
form.onsubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    fetch('upload.php', { method: 'POST', body: formData })
    .then(res => res.json()) // now safe
    .then(data => console.log(data))
    .catch(err => console.error('JSON parse error:', err));
};