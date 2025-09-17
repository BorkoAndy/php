(function () {
const params = new URLSearchParams(window.location.search);
const scriptKey = window.translatorConfig?.key || new URLSearchParams(window.location.search).get('key') || null;
const displayMode = window.translatorConfig?.mode || new URLSearchParams(window.location.search).get('mode') || 'flags';
//   const currentDomain = window.location.hostname;
// console.log(`🌐 Current domain: ${currentDomain}`);
// console.log(`🔑 Script key: ${scriptKey}`);
  // 🔒 Hardcoded auth data for testing
  const link = document.createElement('link');
  link.rel = 'stylesheet';
  link.type = 'text/css';
  link.href = "https://www.netcontact.at/API/Translate/translator.css";
  document.head.appendChild(link);

// fetch('https://www.netcontact.at/API/Translate/auth.json')
//     .then(res => res.json())
//     .then(auth => {
//       let authorized = false;
//       for (const entry of Object.values(auth)) {
//         if (entry.name === currentDomain && entry.key === scriptKey) {
//           console.log(`✅ Authorized: ${entry.name}`);
//           authorized = true;
//           break;
//         }
//       }

//       if (true) {
//         initTranslatorWidget(displayMode);
//       } else {
//         console.warn('❌ Unauthorized domain or invalid key');
//       }
//     })
//     .catch(err => console.error('Auth check failed:', err));
initTranslatorWidget(displayMode);
  function initTranslatorWidget(mode) {
    const container = document.createElement('div');
    container.className = 'translator-widget';

    const langs = [
      { code: 'en', label: 'EN', flag: 'gb.png' },
      { code: 'de', label: 'DE', flag: 'de.png' },
      { code: 'nl', label: 'NL', flag: 'nl.png' },
      { code: 'fr', label: 'FR', flag: 'fr.png' },
      { code: 'it', label: 'IT', flag: 'it.png' },
      { code: 'uk', label: 'UA', flag: 'ua.png' }
    ];

    langs.forEach(lang => {
      const btn = document.createElement('button');
      btn.className = 'lang-btn';
      btn.title = lang.label;
      btn.onclick = () => translateWithGoogle(lang.code);

      if (mode === 'flags') {
        const img = document.createElement('img');
        img.src = "https://www.netcontact.at/API/Translate/flags/w40/"+lang.flag;
        img.alt = lang.label;
        btn.appendChild(img);
      } else {
        btn.textContent = lang.label;
      }

      container.appendChild(btn);
    });

    document.body.appendChild(container);
  }

  function translateWithGoogle(targetLang) {
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
})();