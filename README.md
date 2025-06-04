# TakeYourSeat - Sistema de Reserva de Entradas de Cine

## Índice
- [Descripción del Proyecto](#descripción-del-proyecto)
- [Objetivos](#objetivos)
- [Tecnologías Utilizadas](#tecnologías-utilizadas)
- [Funcionalidades Principales](#funcionalidades-principales)
- [Ejemplos de Código](#ejemplos-de-código)
- [Instalación](#instalación)
- [Conclusión](#conclusión)

## Descripción del Proyecto
### Justificación y Relevancia
TakeYourSeat surge como respuesta a las limitaciones existentes en las plataformas actuales de reserva de entradas de cine. Muchas páginas web presentan problemas de usabilidad, están restringidas a una única cadena de cines y no ofrecen una experiencia verdaderamente unificada. Los usuarios se ven obligados a navegar entre diferentes plataformas según el cine que deseen visitar, lo que fragmenta significativamente su experiencia.

### Visión y Propósito
Nuestra plataforma se concibe como una solución moderna, centralizada e interactiva que permite gestionar múltiples cines desde una única interfaz. La arquitectura del sistema está diseñada para escalar sin restricciones, facilitando la incorporación de nuevos cines sin afectar a los ya existentes ni requerir modificaciones estructurales.

## Objetivos

### Objetivos Generales
- Desarrollar una plataforma web interactiva que permita a los usuarios reservar entradas de cine de forma rápida, cómoda y segura.
- Centralizar la gestión de distintos cines en un único sistema, sin limitaciones en cuanto a la expansión del número de salas o funciones.
- Optimizar la experiencia del usuario mediante una interfaz clara que facilite la navegación, la búsqueda de películas y la selección de asientos.

### Objetivos Específicos
- Implementar un sistema de autenticación y gestión de roles (usuarios y administradores) con protección de rutas mediante middleware.
- Diseñar una base de datos relacional eficiente para manejar usuarios, películas, horarios y reservas sin conflictos ni redundancia.
- Crear una interfaz dinámica y responsive utilizando Bootstrap 5 y JavaScript para la selección de asientos y validaciones en tiempo real.
- Garantizar la correcta gestión de funciones sin solapamiento de horarios.
- Desarrollar funcionalidades administrativas para la creación, edición y eliminación de películas y horarios.
- Incluir la generación de informes sobre ingresos, ocupación de salas y número de reservas, con métricas claras para facilitar la toma de decisiones.

## Tecnologías Utilizadas

### Backend
- **Laravel 10.x**: 
- **PHP 8.x**
- **MySQL**: 

### Frontend
- **Blade Templates**
- **Bootstrap 5**
- **JavaScript (ES6+)**
- **Font Awesome**
- **SimpleSoftwareIO/QrCode**

## Funcionalidades Principales

### 1. Sistema de Autenticación y Roles
```php
// Middleware para administradores
public function handle($request, Closure $next)
{
    if (!auth()->check() || !auth()->user()->is_admin) {
        return redirect('/')->with('error', 'No tienes permiso para acceder a esta página');
    }
    return $next($request);
}
```

### 2. Selección Visual de Asientos
```javascript
document.addEventListener('DOMContentLoaded', function() {
    const asientos = document.querySelectorAll('.seat');
    let asientosSeleccionados = [];

    asientos.forEach(seat => {
        seat.addEventListener('click', function() {
            if (this.classList.contains('occupied')) return;

            const asiento = this.dataset.asiento;
            const index = asientosSeleccionados.indexOf(asiento);

            if (index > -1) {
                asientosSeleccionados.splice(index, 1);
                this.classList.remove('selected');
            } else {
                if (asientosSeleccionados.length < 10) {
                    asientosSeleccionados.push(asiento);
                    this.classList.add('selected');
                } else {
                    alert('No puedes seleccionar más de 10 asientos por reserva.');
                }
            }
            actualizarContadores();
        });
    });
});
```

### 3. Gestión de Reservas
```php
// Modelo de Reserva
class Reserva extends Model
{
    protected $fillable = [
        'user_id',
        'horario_id',
        'asientos',
        'cantidad_asientos',
        'estado',
        'codigo_entrada',
        'precio_total'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }
}
```

### 4. Panel de Administración
```php
// Rutas protegidas para administradores
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('peliculas', PeliculaController::class);
    Route::resource('horarios', HorarioController::class);
});
```

## Instalación

1. Clonar el repositorio:
```bash
git clone https://github.com/tu-usuario/TakeYourSeat.git
cd TakeYourSeat
```

2. Instalar dependencias:
```bash
composer install
npm install
```

3. Configurar el entorno:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configurar la base de datos en el archivo .env:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=takeyourseat
DB_USERNAME=root
DB_PASSWORD=
```

5. Ejecutar migraciones:
```bash
php artisan migrate
```

6. Iniciar el servidor:
```bash
php artisan serve
```

## Conclusión
TakeYourSeat demuestra una implementación robusta de un sistema de reservas de cine con características modernas como:
- Separación clara de roles (admin/usuario)
- Interfaz intuitiva para selección de asientos
- Sistema de códigos QR para entradas
- Gestión eficiente de reservas
- Panel de administración completo

El proyecto utiliza las mejores prácticas de Laravel y proporciona una base sólida para futuras mejoras y expansiones.
