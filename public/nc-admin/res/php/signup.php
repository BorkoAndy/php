<?php
session_start();
$warning = $_SESSION['signup_error'] ?? '';
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['signup_error'], $_SESSION['form_data']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Anmelden | E-Shop</title>
    <link rel="stylesheet" href="../css/signup.css" />
</head>

<body>
    <div class="container">
        <form id="signupForm" action="signup_logic.php" method="POST" enctype="multipart/form-data">
            <h2>Anmelden</h2>

            <input type="hidden" name="user_role" value="regular" />

            <input type="text" name="company_name" placeholder="Firmenname" required
                value="<?= htmlspecialchars($formData['company_name'] ?? '') ?>" />

            <label>Ansprechpartner</label>
            <input type="text" name="first_name" placeholder="Vorname" required value="<?= htmlspecialchars($formData['first_name'] ?? '') ?>" />
            <input type="text" name="last_name" placeholder="Nachname" required value="<?= htmlspecialchars($formData['last_name'] ?? '') ?>" />
            <input type="text" name="phone_number" placeholder="Telefonnummer" required value="<?= htmlspecialchars($formData['phone_number'] ?? '') ?>" />
            <input type="email" name="email" placeholder="Email" id="email" required
                value="<?= htmlspecialchars($formData['email'] ?? '') ?>"
                class="<?= $warning ? 'input-error' : '' ?>" />
            <?php if ($warning): ?>
                <div class="form-warning"><?= htmlspecialchars($warning) ?></div>
            <?php endif; ?>
            <div id="emailFeedback"></div>
            <label>Adresse</label>
            <input type="text" name="street" placeholder="Straße" required value="<?= htmlspecialchars($formData['street'] ?? '') ?>" />
            <input type="text" name="postal_code" id="postal_code" placeholder="Postleitzahl" required value="<?= htmlspecialchars($formData['postal_code'] ?? '') ?>" />
            <input type="text" name="city" id="city" placeholder="Stadt" required value="<?= htmlspecialchars($formData['city'] ?? '') ?>" />
            <!-- <input type="text" name="country" placeholder="Country" required /> -->
            <select id="countrySelect" name="country" value="<?= htmlspecialchars($formData['country'] ?? '') ?>">
                <option value="AT" selected>Österreich</option>
            </select>

            <label>Umsatzsteuer-Identifikationsnummer (VAT)</label>
            <input type="text" id="vatInput" name="vat_number" placeholder="e.g. ATU12345678" value="<?= htmlspecialchars($formData['vat_number'] ?? '') ?>" />
            <div id="vat_result"></div>

            <label>Passwort</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="Passwort" required />
                <span class="toggle-password" onclick="togglePassword('password')">Show</span>
            </div>

            <div class="password-wrapper">
                <input type="password" id="password_repeat" name="password_repeat" placeholder="Passwort wiederholen" required />
                <span class="toggle-password" onclick="togglePassword('password_repeat')">Show</span>
            </div>
            <div id="password_feedback"></div>
            <div id="matchFeedback"></div>

            <fieldset>
                <legend>Optionale Informationen</legend>
                <p>Ich möchte Informationen erhalten:</p>
                <label><input type="checkbox" name="optional[]" value="common" />Allgemeine Informationen</label>
                <label><input type="checkbox" name="optional[]" value="sales" />Verkaufsinformationen</label>
                <label><input type="checkbox" name="optional[]" value="technical" />Technische Informationen</label>
                <label>
                    Sonstiges:
                    <textarea name="other_info" placeholder="Beschreiben Sie weitere relevante Informationen..."><?= htmlspecialchars($formData['other_info'] ?? '') ?></textarea>
                    <!-- <textarea name="other_info" placeholder="Beschreiben Sie weitere relevante Informationen..." value="<?= htmlspecialchars($formData['other_info'] ?? '') ?>" ></textarea> -->
                </label>
            </fieldset>

            <fieldset>
                <legend>Erforderliche Unterlagen</legend>
                <label for="tradeLicense" class="file-upload">
                    Gewerbeschein auswählen
                </label>
                <input type="file" name="trade_license" id="tradeLicense" accept="image/*" value="<?= htmlspecialchars($formData['trade_license'] ?? '') ?>" />
            </fieldset>

            <fieldset>
                <legend>Rechtlich</legend>
                <label class="required">
                    <input type="checkbox" name="terms" id="terms" required />
                    Ich habe gelesen und akzeptiere die <a href="#" target="_blank">Allgemeine Geschäftsbedingungen</a> und <a href="#" target="_blank">Datenschutzerklärung</a>.
                </label>
            </fieldset>

            <button type="submit">Anmelden</button>
        </form>


    </div>

    <script src="../js/validation.js"></script>
    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const toggle = input.nextElementSibling;

            if (input.type === 'password') {
                input.type = 'text';
                toggle.textContent = 'Hide';
            } else {
                input.type = 'password';
                toggle.textContent = 'Show';
            }
        }
    </script>
    <script src="../js/fetch_countries.js"></script>   
</body>

</html>