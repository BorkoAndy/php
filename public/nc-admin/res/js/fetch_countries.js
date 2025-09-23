 fetch('https://restcountries.com/v3.1/all?fields=name,translations,cca2')
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('countrySelect');

                // Filter out Austria so we don't duplicate it
                const filtered = data.filter(c => c.cca2 !== 'AT');

                // Sort by German name
                filtered.sort((a, b) => {
                    const nameA = a.translations?.de?.common || a.name.common;
                    const nameB = b.translations?.de?.common || b.name.common;
                    return nameA.localeCompare(nameB);
                });

                // Add countries to select
                filtered.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.cca2;
                    option.textContent = country.translations?.de?.common || country.name.common;
                    select.appendChild(option);
                });
            })
            .catch(error => console.error('Fehler beim Laden der Länder:', error));