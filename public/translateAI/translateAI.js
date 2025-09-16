const targetLang = 'en'; // ISO language code for German

// DeepL Proxy
const proxyUrl = 'https://www.netcontact.at/API/Translate/deepl-proxy.php';

// Microsoft Translator
const msEndpoint = 'https://api.cognitive.microsofttranslator.com/translate?api-version=3.0&to='+ targetLang;

// Translate using DeepL
function translateWithDeepL() {
  const elements = document.querySelectorAll('p, h1, h2, h3, h4, h5, h6, span, strong, li, a, div');
  elements.forEach(el => {
    el.childNodes.forEach(node => {
      if (node.nodeType === Node.TEXT_NODE && node.textContent.trim()) {
        const originalText = node.textContent.trim();
        const body = `text=${encodeURIComponent(originalText)}&target_lang=${targetLang}`;

        fetch(proxyUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: body
        })
        .then(res => res.text())
        .then(raw => {
          try {
            const data = JSON.parse(raw);
            if (data.translations && data.translations[0]) {
              node.textContent = data.translations[0].text;
            }
          } catch (err) {
            console.error('DeepL JSON error:', raw);
          }
        })
        .catch(err => console.error('DeepL failed:', err));
      }
    });
  });
}

// Translate using Google Translate (unofficial endpoint)
function translateWithGoogle() {
  const elements = document.querySelectorAll('p, h1, h2, h3, h4, h5, h6, span, strong, li, a, div');
  elements.forEach(el => {
    el.childNodes.forEach(node => {
      if (node.nodeType === Node.TEXT_NODE && node.textContent.trim()) {
        const originalText = node.textContent.trim();
        const googleUrl = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=${targetLang}&dt=t&q=${encodeURIComponent(originalText)}`;

        fetch(googleUrl)
        .then(res => res.json())
        .then(data => {
          if (Array.isArray(data) && data[0] && data[0][0]) {
            node.textContent = data[0][0][0];
          }
        })
        .catch(err => console.error('Google failed:', err));
      }
    });
  });
}

// Translate using Microsoft Translator
function translateWithMicrosoft() {
  const elements = document.querySelectorAll('p, h1, h2, h3, h4, h5, h6, span, strong, li, a, div');
  elements.forEach(el => {
    el.childNodes.forEach(node => {
      if (node.nodeType === Node.TEXT_NODE && node.textContent.trim()) {
        const originalText = node.textContent.trim();
        const body = JSON.stringify([{ Text: originalText }]);

        fetch(msEndpoint, {
          method: 'POST',
          headers: {
            'Ocp-Apim-Subscription-Key': msKey,
            'Content-Type': 'application/json'
          },
          body: body
        })
        .then(res => res.json())
        .then(data => {
          if (data[0] && data[0].translations && data[0].translations[0]) {
            node.textContent = data[0].translations[0].text;
          }
        })
        .catch(err => console.error('Microsoft failed:', err));
      }
    });
  });
}

// Add buttons to the page
document.addEventListener('DOMContentLoaded', () => {
  const container = document.createElement('div');
  container.style.position = 'fixed';
  container.style.bottom = '20px';
  container.style.right = '20px';
  container.style.zIndex = '9999';
  container.style.display = 'flex';
  container.style.flexDirection = 'column';
  container.style.gap = '10px';

  const deepLBtn = document.createElement('button');
  deepLBtn.innerText = 'DeepL Translate';
  deepLBtn.style.padding = '10px';
  deepLBtn.style.backgroundColor = '#0078D4';
  deepLBtn.style.color = '#fff';
  deepLBtn.style.border = 'none';
  deepLBtn.style.borderRadius = '5px';
  deepLBtn.onclick = translateWithDeepL;

  const googleBtn = document.createElement('button');
  googleBtn.innerText = 'Google Translate';
  googleBtn.style.padding = '10px';
  googleBtn.style.backgroundColor = '#34A853';
  googleBtn.style.color = '#fff';
  googleBtn.style.border = 'none';
  googleBtn.style.borderRadius = '5px';
  googleBtn.onclick = translateWithGoogle;

  const msBtn = document.createElement('button');
  msBtn.innerText = 'Microsoft Translate';
  msBtn.style.padding = '10px';
  msBtn.style.backgroundColor = '#F25022';
  msBtn.style.color = '#fff';
  msBtn.style.border = 'none';
  msBtn.style.borderRadius = '5px';
  msBtn.onclick = translateWithMicrosoft;

  container.appendChild(deepLBtn);
  container.appendChild(googleBtn);
  container.appendChild(msBtn);
  document.body.appendChild(container);
});