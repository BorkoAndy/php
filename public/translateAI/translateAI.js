const targetLang = 'EN'; // Change to 'EN' or other language codes
// const proxyUrl = 'http://php.local/translateAI/deepl-proxy.php';
const proxyUrl = 'https://www.netcontact.at/API/Translate/deepl-proxy.php';

// Function to translate a single element
function translateElement(el) {
  el.childNodes.forEach(node => {
    if (node.nodeType === Node.TEXT_NODE) {
      const originalText = node.textContent.trim();
      console.log('Translating:', originalText);
      if (!originalText) return;

      const body = `text=${encodeURIComponent(originalText)}&target_lang=${targetLang}`;

      fetch(proxyUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body
      })
        .then(res => res.text()) // First get raw text
        .then(raw => {
          try {
            const data = JSON.parse(raw);
            if (data.translations && data.translations[0]) {
              node.textContent = data.translations[0].text;
            } else {
              console.error('Translation error:', data.error || 'No translation found');
            }
          } catch (err) {
            console.error('Invalid JSON:', raw);
          }
        })
        .catch(err => console.error('Fetch failed:', err));
    }
  });
}

// Scan and translate all paragraphs and headings
function translatePage() {
  const elements = document.querySelectorAll('p, h1, h2, h3, h4, h5, h6, span, strong, li, a, div');
  elements.forEach(el => translateElement(el));
}

// Optional: Add a button or icon to trigger translation
document.addEventListener('DOMContentLoaded', () => {
  const switcher = document.createElement('button');
  switcher.innerText = 'Translate';
  switcher.style.position = 'fixed';
  switcher.style.bottom = '20px';
  switcher.style.right = '20px';
  switcher.style.zIndex = '9999';
  switcher.onclick = translatePage;
  document.body.appendChild(switcher);
});