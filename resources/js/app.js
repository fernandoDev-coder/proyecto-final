import './bootstrap';

// Función para generar códigos QR
export function generateQR(data, elementId, size = 5) {
    const qr = qrcode(0, 'M');
    qr.addData(data);
    qr.make();
    document.getElementById(elementId).innerHTML = qr.createImgTag(size);
}

// Función para actualizar el resumen de asientos
export function updateSeatsSelection(asientos, selectedSeatsElement, totalPriceElement, precioAsiento = 8.00) {
    const asientosSeleccionados = Array.from(asientos)
        .filter(asiento => asiento.checked)
        .map(asiento => asiento.value);
    
    selectedSeatsElement.textContent = asientosSeleccionados.length > 0 
        ? asientosSeleccionados.join(', ') 
        : '-';
    
    const total = asientosSeleccionados.length * precioAsiento;
    totalPriceElement.textContent = `${total.toFixed(2)} €`;
}

// Inicialización de elementos cuando el DOM está listo
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar tooltips de Bootstrap si existen
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Inicializar selección de asientos si estamos en la página de reservas
    const asientos = document.querySelectorAll('.seat-checkbox');
    const selectedSeatsElement = document.getElementById('selected-seats');
    const totalPriceElement = document.getElementById('total-price');
    
    if (asientos.length && selectedSeatsElement && totalPriceElement) {
        asientos.forEach(asiento => {
            asiento.addEventListener('change', () => {
                updateSeatsSelection(asientos, selectedSeatsElement, totalPriceElement);
            });
        });
    }

    // Inicializar QR si estamos en la página de confirmación
    const qrcodeElement = document.getElementById('qrcode');
    const reservaCodigo = qrcodeElement?.dataset.codigo;
    
    if (qrcodeElement && reservaCodigo) {
        generateQR(reservaCodigo, 'qrcode');
    }
});
