let isVATValid = false;

async function checkVAT() {
  const vatInput = document.getElementById('vatInput');
  const vat = vatInput.value.trim();
  const resultDiv = document.getElementById('vat_result');

  if (!vat) {
    resultDiv.textContent = "Bitte geben Sie eine Umsatzsteuer-Identifikationsnummer ein.";
    resultDiv.style.color = 'red';
    vatInput.classList.add('input-error');
    isVATValid = false;
    return;
  }

  try {
    const response = await fetch(`https://api.vatcomply.com/vat?vat_number=${vat}`);
    const data = await response.json();

    if (data.valid) {
      resultDiv.innerHTML = `
        <strong>Gültige Umsatzsteuer-Identifikationsnummer</strong><br>
        Land: ${data.country_code}<br>
        Unternehmen: ${data.name || 'N/A'}<br>
        Adresse: ${data.address || 'N/A'}
      `;
      resultDiv.style.color = 'green';
      vatInput.classList.remove('input-error');
      isVATValid = true;
    } else {
      resultDiv.textContent = "Ungültige Umsatzsteuer-Identifikationsnummer.";
      resultDiv.style.color = 'red';
      vatInput.classList.add('input-error');
      isVATValid = false;
    }
  } catch (error) {
    resultDiv.textContent = "Fehler bei der Überprüfung der Umsatzsteuer-Identifikationsnummer.";
    resultDiv.style.color = 'red';
    vatInput.classList.add('input-error');
    isVATValid = false;
    console.error(error);
  }
}

function validatePassword() {
  const password = document.getElementById('password').value;
  const feedback = document.getElementById('feedback');

  // Regex: min 8 chars, 1 uppercase, 1 lowercase, 1 digit, 1 special char
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

  if (!regex.test(password)) {
    feedback.innerHTML = `
      ❌ Password must contain:<br>
      • Mindestens 8 Zeichen<br>
      • Ein Großbuchstabe<br>
      • Ein Kleinbuchstabe<br>
      • Eine Zahl<br>
      • Ein besonderes Symbol (z. B. !@#$%^&*)<br>
    `;
  } else {
    feedback.innerHTML = "✅ Starkes Passwort!";
  }
}


document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('password').addEventListener('keyup', function () {
    const password = this.value;
    const feedback = document.getElementById('password_feedback');
    const errors = [];

    // Rule checks
    if (password.length < 8) {
      errors.push("• Mindestens 8 Zeichen");
    }
    if (!/[A-Z]/.test(password)) {
      errors.push("• Ein Großbuchstabe");
    }
    if (!/[a-z]/.test(password)) {
      errors.push("• Ein Kleinbuchstabe");
    }
    if (!/\d/.test(password)) {
      errors.push("• Eine Zahl");
    }
    if (!/[\W_]/.test(password)) {
      errors.push("• Ein besonderes Symbol (e.g. !@#$%^&*)");
    }
    if (/\s/.test(password)) {
      errors.push("• Keine Leerzeichen erlaubt");
    }

    // Output
    if (errors.length > 0) {
      feedback.innerHTML = `❌ Passwort fehlt:<br>${errors.join('<br>')}`;
      feedback.style.color = 'red';
    } else {
      feedback.textContent = "✅ Starkes Passwort!";
      feedback.style.color = 'green';
    }
  });

  const passwordField = document.getElementById('password');
  const repeatField = document.getElementById('password_repeat');
  const matchFeedback = document.getElementById('matchFeedback');

  function checkPasswordMatch() {
    const password = passwordField.value;
    const repeat = repeatField.value;

    if (repeat === '') {
      matchFeedback.textContent = '';
      return;
    }

    if (password === repeat) {
      matchFeedback.textContent = "✅ Die Passwörter stimmen überein!";
      matchFeedback.style.color = "green";
    } else {
      matchFeedback.textContent = "❌ Die Passwörter stimmen nicht überein.";
      matchFeedback.style.color = "red";
    }
  }

  passwordField.addEventListener('keyup', checkPasswordMatch);
  repeatField.addEventListener('keyup', checkPasswordMatch);

  const vat_field = document.getElementById('vatInput');
  vat_field.addEventListener('blur', () => {
    const vat = vat_field.value.trim();
    if (vat) {
      checkVAT();
    }
  });


  document.getElementById('email').addEventListener('keyup', function () {
    const email = this.value;
    const feedback = document.getElementById('emailFeedback');

    // Simple but effective email regex
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (regex.test(email)) {
      feedback.textContent = "✅ Gültige E-Mail-Adresse!";
      feedback.style.color = "green";
    } else {
      feedback.textContent = "❌ Ungültiges E-Mail-Format.";
      feedback.style.color = "red";
    }
  });

  const tradeLicense = document.getElementById('tradeLicense');
  const fileLabel = document.querySelector('.file-upload');

  tradeLicense.addEventListener('change', () => {
    const fileName = tradeLicense.files[0]?.name || 'Gewerbeschein auswählen';
    fileLabel.textContent = fileName;
  });


  // function togglePassword(id) {
  //   const input = document.getElementById(id);
  //   input.type = input.type === 'password' ? 'text' : 'password';
  // }

});

const form = document.getElementById('signupForm');

form.addEventListener('submit', async function (e) {
  e.preventDefault(); // Stop form for now
  await checkVAT();   // Wait for VAT check

  if (!isVATValid) {
    // Do nothing — error message and red border are already shown
    return;
  }

  form.submit(); // Proceed if valid
});


