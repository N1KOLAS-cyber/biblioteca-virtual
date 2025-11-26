# NubeLectora - Biblioteca Virtual

Sistema de biblioteca virtual desarrollado con Laravel que permite gestionar libros, autores, categorías, usuarios y membresías. Incluye panel de administración con roles y permisos, catálogo público de libros, y sistema de lectura.

## Instalación Local

### 1. Clonar el repositorio

```bash
git clone <url-del-repositorio>
cd biblioteca_virtual
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar entorno

Copia el archivo `.env.example` a `.env`:

```bash
cp .env.example .env
```

Genera la clave de la aplicación:

```bash
php artisan key:generate
```

Configura las variables de entorno en el archivo `.env`, especialmente:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 4. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

Este comando creará:
- Las tablas de la base de datos
- Los roles del sistema (admin, staff, escritor, usuario)
- Un usuario administrador de prueba
- Planes de membresía (Gratuita, Básica, Premium)

### 5. Iniciar el servidor

```bash
php artisan serve
```

El sistema estará disponible en `http://localhost:8000`

## Usuarios de Prueba

### Administrador
- **Correo:** `prueba0@gmail.com`
- **Contraseña:** `prueba1230`
- **Rol:** Admin
- **Acceso:** Panel completo de administración con todos los permisos

### Staff / Bibliotecario
Para crear un usuario staff, puedes:
1. Iniciar sesión como admin
2. Ir a "Usuarios" > "Nuevo"
3. Crear un usuario y asignarle el rol "staff"

**Permisos Staff:**
- Ver y editar usuarios (sin eliminar)
- Gestionar membresías (ver, editar, cancelar)
- Ver reportes de actividad
- No puede: eliminar usuarios, gestionar libros/autores/categorías, cambiar roles, acceder a configuración

### Escritor
Para crear un usuario escritor:
1. Iniciar sesión como admin
2. Ir a "Usuarios" > "Nuevo"
3. Crear un usuario y asignarle el rol "escritor"

**Permisos Escritor:**
- Crear y publicar libros propios
- Editar sus libros (antes de aprobación o si fueron rechazados)
- Ver estadísticas de sus libros
- Ver reseñas de sus libros
- Acceso completo al catálogo como lector

**Flujo de publicación:**
1. Escritor crea libro con editor
2. Libro queda en estado "pendiente"
3. Admin revisa y aprueba/rechaza
4. Si se aprueba → libro disponible en catálogo
5. Si se rechaza → escritor puede editar y reenviar

### Usuario / Lector
Los usuarios se registran automáticamente con el rol "usuario" al crear una cuenta.

**Permisos Usuario:**
- Ver catálogo completo
- Leer libros según su membresía
- Agregar/quitar favoritos
- Ver historial de lectura
- Dejar reseñas
- Crear listas de lectura
- Gestionar su membresía (ver, cambiar, cancelar)
- Editar su perfil

**Acceso según membresía:**
- **Gratuita:** Solo libros marcados como gratuitos
- **Básica:** Acceso limitado a catálogo
- **Premium:** Acceso completo al catálogo

## Sistema de Roles y Permisos

El sistema utiliza **Spatie Laravel Permission** para gestionar roles y permisos de forma granular.

### Roles Disponibles

1. **Admin** - Control total del sistema
2. **Staff** - Gestión de usuarios y membresías
3. **Escritor** - Publicación de libros propios
4. **Usuario** - Lectura y gestión personal

### Permisos por Rol

#### Admin
- Gestión completa de usuarios (crear, editar, eliminar)
- Gestión completa de libros (crear, editar, eliminar, aprobar)
- Gestión completa de autores (crear, editar, eliminar)
- Gestión de categorías
- Gestión de roles y permisos
- Gestión de planes de membresía
- Acceso a configuración del sistema
- Ver reportes completos

#### Staff
- Ver y editar usuarios (sin eliminar)
- Gestionar membresías (ver, editar, cancelar, deshabilitar)
- Enviar recordatorios de pago
- Moderar reseñas
- Ver tickets de soporte
- Ver reportes de actividad
- **Restricciones:** No puede eliminar usuarios, gestionar libros/autores/categorías, cambiar roles, acceder a configuración

#### Escritor
- Crear y publicar libros propios
- Editar sus libros (solo antes de aprobación o si fueron rechazados)
- Eliminar sus libros (solo si no están aprobados)
- Ver estadísticas de sus libros
- Ver reseñas de sus libros
- Editar su perfil de autor
- Acceso completo como lector

#### Usuario
- Ver catálogo completo
- Leer libros según su membresía
- Agregar/quitar favoritos
- Ver historial de lectura
- Dejar reseñas
- Crear listas de lectura
- Gestionar su membresía
- Editar su perfil

## Flujo del Sistema

### Flujo de Registro
1. Usuario se registra en la plataforma
2. Se asigna automáticamente el rol "usuario"
3. Se crea membresía "Gratuita" por defecto
4. Usuario puede ver catálogo y leer libros gratuitos

### Flujo de Publicación (Escritor)
1. Escritor inicia sesión
2. Crea un nuevo libro desde su panel
3. Libro queda en estado "pendiente"
4. Admin recibe notificación
5. Admin revisa el libro
6. Admin aprueba o rechaza
7. Si se aprueba: libro disponible en catálogo
8. Si se rechaza: escritor puede editar y reenviar

### Flujo de Lectura
1. Usuario navega por el catálogo
2. Selecciona un libro
3. Sistema verifica membresía del usuario
4. Si tiene acceso: muestra botón "Leer"
5. Si no tiene acceso: muestra opción de suscribirse
6. Al leer, se registra en historial de lectura

### Flujo de Membresía
1. Usuario selecciona un plan
2. Se crea membresía con estado "trial" (si aplica)
3. Al finalizar trial: se activa membresía pagada
4. Usuario puede cambiar o cancelar su plan
5. Al cancelar: membresía activa hasta fin del período pagado

## Requisitos

- PHP >= 8.2
- Composer
- MySQL 8.0 o superior
- Node.js y NPM (para assets)

## Arquitectura del Proyecto

### Modelo MVC + Livewire

El proyecto sigue una arquitectura híbrida que combina el patrón MVC tradicional de Laravel con componentes Livewire para interfaces reactivas.

**Estructura de Directorios:**

```
app/
├── Http/
│   ├── Controllers/          # Controladores tradicionales (mínimos)
│   ├── Livewire/            # Componentes Livewire (CRUD principal)
│   └── Middleware/          # Middleware personalizado
├── Models/                  # Modelos Eloquent
├── Policies/               # Políticas de autorización
└── Services/               # Lógica de negocio compleja

resources/
├── views/
│   ├── layouts/           # Layouts principales
│   │   ├── app.blade.php      # Layout usuario
│   │   ├── admin.blade.php    # Layout admin
│   │   └── includes/
│   │       └── admin/
│   │           ├── navigation.blade.php
│   │           ├── sidebar.blade.php
│   │           └── breadcrumb.blade.php
│   ├── admin/            # Vistas panel admin
│   ├── livewire/         # Vistas componentes Livewire
│   └── auth/             # Vistas autenticación (Jetstream)
└── js/
    └── app.js           # JavaScript principal

routes/
├── web.php              # Rutas usuario
├── admin.php            # Rutas admin (protegidas)
└── api.php              # Rutas API (futuro)

database/
├── migrations/          # Migraciones de BD
├── seeders/            # Datos iniciales
└── factories/          # Factories para testing
```

### Patrón de Diseño

El proyecto utiliza una combinación de patrones:

**1. MVC Tradicional (Laravel)**
- **Modelos:** Representan entidades de negocio (User, Book, Author, etc.)
- **Vistas:** Templates Blade para presentación
- **Controladores:** Manejan peticiones HTTP y coordinan entre modelos y vistas

**2. Componentes Livewire**
- Para interfaces reactivas sin escribir JavaScript
- Tablas dinámicas con Rappasoft Livewire Tables
- Formularios interactivos con validación en tiempo real

**3. Service Layer (Para lógica compleja)**
- Servicios encapsulan lógica de negocio compleja
- Separación de responsabilidades
- Código reutilizable y fácil de testear

**Ejemplo de flujo:**

```php
// Modelo
app/Models/Book.php

// Servicio (lógica de negocio)
app/Services/BookService.php
  → Aprobación de libros
  → Cálculo de comisiones
  → Lógica de acceso según membresía

// Componente Livewire (interacción)
app/Http/Livewire/Admin/BookTable.php
  → Listado con Rappasoft
  → Acciones CRUD
```

### Separación de Responsabilidades

- **Controladores:** Mínimos, solo coordinan peticiones
- **Livewire Components:** Manejan interacción y estado de UI
- **Models:** Acceso a datos y relaciones
- **Services:** Lógica de negocio compleja
- **Policies:** Autorización y permisos
- **Middleware:** Protección de rutas y validaciones

### Ventajas de esta Arquitectura

- **Separación de responsabilidades:** Cada componente tiene una función clara
- **Código reutilizable:** Servicios y componentes pueden reutilizarse
- **Fácil testing:** Cada capa puede testearse independientemente
- **Lógica desacoplada:** Cambios en una capa no afectan otras
- **Interfaz reactiva:** Livewire permite interactividad sin JavaScript complejo

## Tecnologías Utilizadas

- Laravel 12
- Livewire 3
- Jetstream
- Spatie Laravel Permission
- Rappasoft Laravel Livewire Tables
- WireUI
- Tailwind CSS
- Flowbite

---

Desarrollado con Laravel para NubeLectora
