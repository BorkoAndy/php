document.getElementById('forgotForm').addEventListener('submit', async function (e) {
  e.preventDefault();

  const emailInput = document.getElementById('email');
  const feedback = document.getElementById('feedback');
  const email = emailInput.value.trim();

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (!emailRegex.test(email)) {
    feedback.textContent = 'Bitte geben Sie eine gültige E-Mail-Adresse ein.';
    feedback.style.color = 'red';
    return;
  }

  try {
    const response = await fetch('forgot_password.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `email=${encodeURIComponent(email)}`
    });

    const text = await response.text();
    console.log(text);
    if (text.includes('nicht registriert')) {
      feedback.textContent = 'Diese E-Mail ist nicht registriert.';
      feedback.style.color = 'red';
    } else if (text.includes('gesendet')) {
      feedback.textContent = 'Link zum Zurücksetzen wurde an Ihre E-Mail gesendet!';
      feedback.style.color = 'green';
      emailInput.value = '';
    } else {
      feedback.textContent = 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es erneut.';
      feedback.style.color = 'red';
    }
  } catch (error) {
    feedback.textContent = 'Verbindungsfehler. Bitte versuchen Sie es später erneut.';
    feedback.style.color = 'red';
    console.error(error);
  }
});