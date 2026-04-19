# PurePHP Framework

PurePHP es un framework ligero y artesanal construido desde cero, diseñado para desarrolladores que buscan simplicidad, velocidad y control total sobre su código. Sin dependencias innecesarias, solo PHP puro y eficiente.

---

## Características Principales

- Ultra Ligero: Carga mínima de memoria y ejecución instantánea.
- DB Wrapper (PDO): Un envoltorio elegante para interactuar con MySQL de forma segura y sencilla.
- Arquitectura Limpia: Estructura de carpetas intuitiva para organizar controladores, modelos y vistas.
- Configuración Centralizada: Gestión de credenciales y entorno mediante archivos simples.
- Seguridad:
    - Protección CSRF (Cross-Site Request Forgery): Generación y validación de tokens criptográficos almacenados en sesión, obligatorios para interceptar y autorizar peticiones de mutación de estado (POST, PUT, DELETE, PATCH).
    - Prevención de Inyección SQL (SQLi): El DB Wrapper utiliza exclusivamente sentencias preparadas de PDO con binding estricto de parámetros, aislando por completo la estructura de la base de datos de la entrada del usuario.
    - Mitigación XSS (Cross-Site Scripting): Helpers de motor de plantillas configurados para aplicar escape de entidades HTML (htmlspecialchars con flags ENT_QUOTES | ENT_SUBSTITUTE) por defecto en la salida de datos.
    - Seguridad de Sesiones (Hijacking y Fixation): Gestión de sesiones fortificada mediante la exigencia de directivas HttpOnly, Secure y SameSite en las cookies, además de la regeneración automática del ID de sesión durante la autenticación.
    - Criptografía de Credenciales: Implementación estricta de la API de contraseñas de PHP (password_hash), forzando el uso de algoritmos de derivación de claves robustos y asimétricos como Bcrypt o Argon2.
    - Aislamiento de Entorno: Interceptación global de excepciones a nivel del núcleo para garantizar que las variables de configuración, stack traces o credenciales jamás sean expuestas en el entorno de producción.

---

## Instalación

1. Clona el repositorio:

    git clone https://github.com/tu-usuario/purephp.git

2. Sigue los pasos de instalaccion en la ruta instalada
   

## Uso de la Base de Datos (DB Class)

El corazón de PurePHP es su wrapper de base de datos. Olvídate de escribir código repetitivo de PDO.

### Consultar datos (Fetch)

    // Obtener todos los usuarios
    $users = DB::fetchAll("SELECT * FROM users WHERE status = ?", ['active']);

    // Obtener un solo registro
    $user = DB::fetch("SELECT * FROM users WHERE id = ?", [1]);

### Insertar registros

    $newId = DB::insert('users', [
        'name'  => 'Juancho',
        'email' => 'juancho@ejemplo.com',
        'role'  => 'developer'
    ]);

### Actualizar y Eliminar

    // Actualizar
    DB::update('users', ['status' => 'banned'], 'id = ?', [1]);

    // Eliminar
    DB::delete('users', 'id = ?', [1]);

---

## Estructura del Proyecto

    purephp/
    ├── config/         # Archivos de configuración (DB, App)
    ├── core/           # El núcleo del framework (Clase DB, Router, etc.)
    ├── public/         # Punto de entrada (index.php, assets)
    └── app/            # Tu lógica de negocio (Controllers, Models)

---

## Contribuir

Los Pull Requests son bienvenidos. 

1. Haz un Fork del proyecto.
2. Crea una rama para tu mejora (git checkout -b feature/MejoraIncreible).
3. Haz commit de tus cambios (git commit -m 'Añadí una funcionalidad').
4. Haz Push a la rama (git push origin feature/MejoraIncreible).
5. Abre un Pull Request.

---

Desarrollado por Juancho - https://github.com/tu-usuario
