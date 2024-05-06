// script.js
const accesibilityBtn = document.getElementById('accesibilityBtn');
const accesibilityPanel = document.getElementById('accesibilityPanel');
const textSizeSelect = document.getElementById('textSizeSelect');
const colorSchemeSelect = document.getElementById('colorSchemeSelect');

accesibilityBtn.addEventListener('click', () => {
    accesibilityPanel.style.display = accesibilityPanel.style.display === 'none' ? 'block' : 'none';
});

textSizeSelect.addEventListener('change', () => {
    document.body.style.fontSize = `${textSizeSelect.value}px`;
});

colorSchemeSelect.addEventListener('change', () => {
    const selectedScheme = colorSchemeSelect.value;
    const bodyStyles = document.body.style;

    if (selectedScheme === 'default') {
        bodyStyles.backgroundColor = '#333';
        bodyStyles.color = '#fff';
        // Restablecer otros estilos según sea necesario
    } else if (selectedScheme === 'high-contrast') {
        bodyStyles.backgroundColor = '#000';
        bodyStyles.color = '#fff';
        // Ajustar otros estilos para alto contraste
    } else if (selectedScheme === 'grayscale') {
        bodyStyles.filter = 'grayscale(100%)';
        // Ajustar otros estilos para escala de grises
    }
});