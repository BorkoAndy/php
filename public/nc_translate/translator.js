(function () {
  const config = window.translatorConfig || {};
  const scriptKey = config.key || null;
  const displayMode = config.mode || 'labels'; // 'flags' or 'labels'
  const layout = config.layout || 'row';      // 'row' or 'select'
  const langs = Array.isArray(config.langs) ? config.langs : [];
  const icon_set = config.icon_set+'/' || ''; 
  const flag_path = 'https://www.netcontact.at/API/Translate/flags/';

  function initTranslatorWidget(mode) {
  const container = document.getElementById('translator-container');
  if (!container) return;

  container.className = 'translator-widget';

  if (layout === 'select') {
    let currentLang = langs[0]; // Default to first language

    const selected = document.createElement('div');
    selected.className = 'translator-selected';
    selected.innerHTML = mode === 'flags'
      ? `<img src="${flag_path}${icon_set}${currentLang.flag}" alt="${currentLang.label}"> ${currentLang.label}`
      : currentLang.label;

    const optionsList = document.createElement('ul');
    optionsList.className = 'translator-options';

    langs.forEach(lang => {
      const li = document.createElement('li');
      li.dataset.lang = lang.code;
      li.onclick = () => {
        translateWithGoogle(lang.code);
        currentLang = lang;
        selected.innerHTML = mode === 'flags'
          ? `<img src="${flag_path}${icon_set}${lang.flag}" alt="${lang.label}"> ${lang.label}`
          : lang.label;
        optionsList.style.display = 'none';
      };

      if (mode === 'flags') {
        const img = document.createElement('img');
        img.src = `${flag_path}${icon_set}${lang.flag}`;
        img.alt = lang.label;
        li.appendChild(img);
      }

      const label = document.createElement('span');
      label.textContent = lang.label;
      li.appendChild(label);

      optionsList.appendChild(li);
    });

    selected.onclick = () => {
      optionsList.style.display = optionsList.style.display === 'block' ? 'none' : 'block';
    };

    container.appendChild(selected);
    container.appendChild(optionsList);
  } else {
    langs.forEach(lang => {
      const btn = document.createElement('button');
      btn.className = 'lang-btn';
      btn.title = lang.label;
      btn.onclick = () => translateWithGoogle(lang.code);

      if (mode === 'flags') {
        const img = document.createElement('img');
        img.src = `${flag_path}${icon_set}${lang.flag}`;
        img.alt = lang.label;
        btn.appendChild(img);
      } else {
        btn.textContent = lang.label;
      }

      container.appendChild(btn);
    });
  }
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
            .catch(err => console.error('Google Translate failed:', err));
        }
      });
    });
  }

  // Initialize widget
  initTranslatorWidget(displayMode);
})();