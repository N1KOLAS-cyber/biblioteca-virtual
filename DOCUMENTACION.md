# 📚 BIBLIOTECA VIRTUAL - DOCUMENTACIÓN COMPLETA

> **Proyecto:** Sistema de Biblioteca Virtual con membresías, roles y gestión de libros
> 
> **Fecha de inicio:** Noviembre 2025
> 
> **Versión:** 1.0.0
> 
> **Última actualización:** 25 de Noviembre, 2025

---

## 📑 TABLA DE CONTENIDOS

1. [Resumen Ejecutivo](#resumen-ejecutivo)
2. [Stack Tecnológico](#stack-tecnológico)
3. [Arquitectura del Sistema](#arquitectura-del-sistema)
4. [Roles y Permisos](#roles-y-permisos)
5. [Sistema de Membresías](#sistema-de-membresías)
6. [Reglas de Negocio](#reglas-de-negocio)
7. [Estructura de Base de Datos](#estructura-de-base-de-datos)
8. [Configuración del Proyecto](#configuración-del-proyecto)
9. [Decisiones Técnicas](#decisiones-técnicas)
10. [Flujos de Trabajo](#flujos-de-trabajo)
11. [Seguridad y Autorización](#seguridad-y-autorización)
12. [Guía de Desarrollo](#guía-de-desarrollo)

---

## 🎯 RESUMEN EJECUTIVO

### ¿Qué es Biblioteca Virtual?

**Biblioteca Virtual** es una plataforma web que permite a los usuarios leer libros digitales mediante un sistema de membresías. La plataforma incluye:

- **Sistema de membresías** con diferentes niveles de acceso
- **Gestión de roles** (Admin, Staff, Escritor, Usuario)
- **Panel de administración** personalizado
- **Sistema de publicación** para escritores
- **Gestión de autores** y catálogo de libros
- **Sistema de notificaciones** y soporte

### Objetivos del Proyecto

1. ✅ Proporcionar acceso a libros digitales mediante membresías
2. ✅ Permitir a escritores publicar sus obras (previa aprobación)
3. ✅ Facilitar la gestión de usuarios y membresías al staff
4. ✅ Ofrecer una experiencia de lectura personalizada según el tipo de membresía
5. ✅ Generar estadísticas para autores y administradores

### Usuarios del Sistema

- **Administradores:** Control total del sistema
- **Staff/Bibliotecarios:** Gestión de usuarios y membresías
- **Escritores:** Publican libros y ven sus estadísticas
- **Usuarios lectores:** Acceden a libros según su membresía
- **Invitados:** Pueden registrarse y ver catálogo limitado

---

## 🛠️ STACK TECNOLÓGICO

### Backend Framework

```
Laravel 12.39.0
├── PHP 8.3.25
├── MySQL 8.0.43
└── Composer 2.x
```

**¿Por qué Laravel?**
- Framework PHP robusto y maduro
- Excelente ecosistema de paquetes
- Integración perfecta con Livewire
- Gran comunidad y documentación

### Frontend Stack

```
Frontend
├── Livewire 3.x (componentes reactivos)
├── Tailwind CSS 3.x (estilos)
├── Flowbite 4.0.1 (componentes UI)
├── Wire-UI (componentes Livewire adicionales)
├── Alpine.js 3.x (interactividad)
└── Font Awesome 6.x (iconos)
```

**¿Por qué esta combinación?**
- **Livewire:** Desarrollo fullstack con PHP (sin necesidad de API)
- **Tailwind:** Utility-first CSS, rápido y moderno
- **Flowbite:** Componentes pre-diseñados con Tailwind
- **Wire-UI:** Componentes avanzados (modales, notificaciones)
- **Alpine.js:** JavaScript minimalista para interactividad

### Autenticación y Autorización

```
Auth
├── Laravel Jetstream 5.x (auth scaffolding)
├── Laravel Sanctum (API tokens)
├── Spatie Laravel Permission (roles y permisos)
└── Laravel Fortify (backend auth)
```

**Flujo de autenticación:**
1. Usuario se registra/inicia sesión con Jetstream
2. Sistema asigna rol según tipo de registro
3. Spatie Permission valida permisos en cada acción
4. Middleware protege rutas según rol

### Paquetes Adicionales

| Paquete | Versión | Propósito |
|---------|---------|-----------|
| `laravel-lang/common` | 6.7.1 | Traducciones al español |
| `rappasoft/laravel-livewire-tables` | Latest | Tablas dinámicas con Livewire |

### Herramientas de Desarrollo

```
Development Tools
├── PHPStorm / VS Code (IDE)
├── Git (control de versiones)
├── npm 10.x (gestión de assets)
├── Node.js 20.x (compilación de assets)
└── MySQL Workbench (gestión de BD)
```

---

## 🏗️ ARQUITECTURA DEL SISTEMA

### Estructura MVC + Livewire

```
app/
├── Http/
│   ├── Controllers/          # Controladores tradicionales (mínimos)
│   ├── Livewire/            # Componentes Livewire (CRUD principal)
│   └── Middleware/          # Middleware personalizado
├── Models/                  # Modelos Eloquent
├── Policies/               # Políticas de autorización
├── View/
│   └── Components/         # Componentes Blade
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
├── api.php              # Rutas API (futuro)
└── console.php          # Comandos Artisan

database/
├── migrations/          # Migraciones de BD
├── seeders/            # Datos iniciales
└── factories/          # Factories para testing
```

### Patrón de diseño: Repository + Service

**Aunque Laravel usa Eloquent directamente, para lógica compleja seguiremos:**

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

**¿Por qué?**
- Separación de responsabilidades
- Código reutilizable
- Fácil testing
- Lógica desacoplada

---

## 👥 ROLES Y PERMISOS

### Sistema de Roles (Spatie Permission)

El sistema utiliza **Spatie Laravel Permission** para gestionar roles y permisos de forma granular.

#### Configuración de Spatie

```php
// config/permission.php
'models' => [
    'permission' => Spatie\Permission\Models\Permission::class,
    'role' => Spatie\Permission\Models\Role::class,
],

'table_names' => [
    'roles' => 'roles',
    'permissions' => 'permissions',
    'model_has_permissions' => 'model_has_permissions',
    'model_has_roles' => 'model_has_roles',
    'role_has_permissions' => 'role_has_permissions',
],
```

#### Tablas que crea Spatie:

```sql
roles                    -- Almacena roles (admin, staff, writer, user)
permissions              -- Almacena permisos (create_book, edit_user, etc.)
model_has_roles          -- Relación: usuarios ↔ roles
model_has_permissions    -- Relación: usuarios ↔ permisos directos
role_has_permissions     -- Relación: roles ↔ permisos
```

---

### Roles Definidos

#### 🔴 1. ADMIN (Administrador)

**Descripción:** Control total del sistema. Puede realizar cualquier acción.

**Permisos:**

```php
// Gestión de usuarios
'users.view'         → Ver listado de usuarios
'users.create'       → Crear usuarios
'users.edit'         → Editar usuarios
'users.delete'       → Eliminar usuarios
'users.export'       → Exportar usuarios

// Gestión de libros
'books.view'         → Ver catálogo completo
'books.create'       → Crear libros
'books.edit'         → Editar libros
'books.delete'       → Eliminar libros
'books.approve'      → Aprobar/rechazar libros de escritores
'books.export'       → Exportar catálogo

// Gestión de autores
'authors.view'       → Ver autores
'authors.create'     → Crear autores
'authors.edit'       → Editar autores
'authors.delete'     → Eliminar autores

// Gestión de membresías
'memberships.view'   → Ver membresías
'memberships.edit'   → Editar membresías
'memberships.manage' → Gestionar planes

// Gestión de roles y permisos
'roles.view'         → Ver roles
'roles.create'       → Crear roles
'roles.edit'         → Editar roles
'roles.delete'       → Eliminar roles

// Categorías y géneros
'categories.manage'  → Gestionar categorías

// Reportes y estadísticas
'reports.view'       → Ver reportes completos
'reports.export'     → Exportar reportes

// Configuración del sistema
'settings.manage'    → Configurar sistema

// Tickets de soporte
'tickets.view'       → Ver todos los tickets
'tickets.respond'    → Responder tickets
'tickets.close'      → Cerrar tickets
```

**Dashboard Admin incluye:**
- Estadísticas generales (usuarios, libros, membresías activas)
- Gráficos de actividad
- Libros pendientes de aprobación
- Alertas del sistema
- Accesos rápidos a CRUD

---

#### 🟡 2. STAFF / BIBLIOTECARIO

**Descripción:** Gestiona usuarios, membresías y soporte. No puede modificar libros ni configuración.

**Permisos:**

```php
// Gestión de usuarios (limitada)
'users.view'              → Ver usuarios
'users.edit'              → Editar usuarios (sin eliminar)

// Gestión de membresías
'memberships.view'        → Ver membresías
'memberships.edit'        → Cambiar/cancelar membresías
'memberships.disable'     → Deshabilitar por falta de pago
'memberships.reminders'   → Enviar recordatorios de pago

// Notificaciones
'notifications.send'      → Enviar notificaciones masivas

// Reseñas y comentarios
'reviews.view'            → Ver reseñas
'reviews.moderate'        → Moderar/eliminar reseñas inapropiadas

// Tickets de soporte
'tickets.view'            → Ver tickets asignados
'tickets.respond'         → Responder tickets
'tickets.close'           → Cerrar tickets

// Reportes (limitados)
'reports.view'            → Ver reportes de actividad
```

**Dashboard Staff incluye:**
- Usuarios activos vs inactivos
- Membresías por vencer
- Tickets pendientes
- Alertas de pagos vencidos
- Recordatorios pendientes

**Restricciones:**
- ❌ No puede eliminar usuarios
- ❌ No puede crear/editar/eliminar libros
- ❌ No puede cambiar roles
- ❌ No puede acceder a configuración del sistema

---

#### 🟢 3. ESCRITOR (Writer)

**Descripción:** Usuario que puede publicar libros y ver estadísticas. Hereda todos los permisos de Usuario Ilimitado + permisos especiales.

**Permisos (además de Usuario Ilimitado):**

```php
// Gestión de sus libros
'own_books.create'       → Crear/publicar libros
'own_books.edit'         → Editar sus libros (solo antes de aprobación o rechazados)
'own_books.delete'       → Eliminar sus libros (solo si no están aprobados)
'own_books.view_status'  → Ver estado (pendiente/aprobado/rechazado)

// Estadísticas
'own_books.stats'        → Ver estadísticas de sus libros
'own_books.readers'      → Ver quiénes leyeron sus libros
'own_books.reviews'      → Ver reseñas de sus libros

// Comisiones
'commissions.view'       → Ver comisiones generadas (futuro)

// Perfil de autor
'author_profile.edit'    → Editar su perfil de autor (bio, foto, redes)
```

**Dashboard Escritor incluye:**
- Total de libros publicados
- Total de lecturas
- Libro más leído
- Gráfico de lecturas por mes
- Últimas reseñas recibidas
- Comisiones generadas (futuro)
- Estado de libros pendientes de aprobación

**Flujo de publicación:**
1. Escritor crea libro con editor
2. Libro queda en estado "pendiente"
3. Admin revisa y aprueba/rechaza
4. Si se aprueba → libro disponible en catálogo
5. Si se rechaza → escritor puede editar y reenviar

---

#### 🔵 4. USUARIO (User / Reader)

**Descripción:** Usuario lector con membresía. Acceso a libros según su plan.

**Permisos (todas las membresías):**

```php
// Lectura
'books.read'             → Leer libros (según su membresía)
'books.view'             → Ver catálogo completo

// Interacción
'reviews.create'         → Dejar reseñas
'reviews.edit'           → Editar sus reseñas
'reviews.delete'         → Eliminar sus reseñas

// Listas y favoritos
'lists.create'           → Crear listas de lectura
'lists.edit'             → Editar sus listas
'lists.delete'           → Eliminar sus listas
'favorites.manage'       → Agregar/quitar favoritos

// Historial
'reading_history.view'   → Ver su historial de lectura

// Membresía
'membership.view'        → Ver su membresía actual
'membership.change'      → Cambiar de plan
'membership.cancel'      → Cancelar membresía

// Perfil
'profile.edit'           → Editar su perfil
```

**Permisos adicionales según membresía:**

```php
// Solo Ilimitada
'recommendations.view'   → Ver recomendaciones personalizadas
'books.share'            → Compartir libros con otros usuarios
'authors.follow'         → Seguir autores favoritos
```

**Dashboard Usuario incluye:**
- Libros actualmente leyendo
- Recomendaciones (si tiene Ilimitada)
- Historial reciente
- Libros favoritos
- Autores seguidos (si tiene Ilimitada)
- Estado de membresía

---

#### ⚪ 5. INVITADO (Guest)

**Descripción:** Usuario no autenticado o con cuenta gratuita sin membresía activa.

**Permisos:**

```php
'books.browse'           → Ver catálogo
'books.details'          → Ver detalles de libros
'books.read_free'        → Leer solo libros marcados como gratuitos
'auth.register'          → Registrarse
'auth.login'             → Iniciar sesión
```

**Restricciones:**
- ❌ No puede leer libros de pago
- ❌ No puede dejar reseñas
- ❌ No puede crear listas
- ✅ Puede ver el catálogo para decidir si comprar membresía

---

### Asignación de Roles

#### Al registrarse:

```php
// app/Actions/Fortify/CreateNewUser.php

public function create(array $input)
{
    $user = User::create([
        'name' => $input['name'],
        'email' => $input['email'],
        'password' => Hash::make($input['password']),
    ]);
    
    // Asignar rol según tipo de registro
    if ($input['register_as'] === 'writer') {
        $user->assignRole('escritor');
        
        // Crear perfil de autor automáticamente
        Author::create([
            'nombre' => $user->name,
            'user_id' => $user->id,
        ]);
    } else {
        $user->assignRole('usuario');
    }
    
    return $user;
}
```

#### Cambio manual (Admin):

```php
// Admin puede cambiar rol desde panel
$user->syncRoles(['admin']); // Reemplaza todos los roles
// o
$user->assignRole('staff'); // Agrega rol adicional
```

---

### Verificación de Permisos

#### En Controladores/Livewire:

```php
// Verificar permiso específico
if (auth()->user()->can('books.create')) {
    // Permitir acción
}

// Lanzar excepción si no tiene permiso
$this->authorize('books.edit', $book);

// Verificar rol
if (auth()->user()->hasRole('admin')) {
    // Es admin
}

// Verificar múltiples roles
if (auth()->user()->hasAnyRole(['admin', 'staff'])) {
    // Es admin o staff
}
```

#### En Blade:

```blade
@can('books.create')
    <button>Crear Libro</button>
@endcan

@role('admin')
    <a href="{{ route('admin.settings') }}">Configuración</a>
@endrole

@hasanyrole('admin|staff')
    <p>Panel de gestión</p>
@endhasanyrole
```

#### En Rutas:

```php
// routes/admin.php

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index']);
});

Route::middleware(['auth', 'permission:books.create'])->group(function () {
    Route::get('/books/create', [BookController::class, 'create']);
});
```

---

## 🎫 SISTEMA DE MEMBRESÍAS

### Tipos de Membresías

#### 1. GRATUITA (Free)

```php
'name' => 'Gratuita',
'slug' => 'free',
'price' => 0.00,
'duration_days' => null, // Permanente
'trial_days' => 0,
```

**Características:**
- ✅ Registrarse en la plataforma
- ✅ Ver catálogo completo
- ✅ Leer libros marcados como "gratuitos"
- ✅ Crear listas de lectura
- ✅ Dejar reseñas en libros leídos
- ✅ Ver historial de lectura
- ❌ No puede leer libros de pago
- ❌ No recibe recomendaciones personalizadas
- ❌ No puede compartir libros

**Reglas:**
- Se asigna automáticamente al registrarse
- No vence nunca
- Puede actualizar a cualquier plan pagado

---

#### 2. BÁSICA (Basic)

```php
'name' => 'Básica',
'slug' => 'basic',
'price' => 9.99, // Mensual (ejemplo)
'duration_days' => 30,
'trial_days' => 30, // 1 mes de prueba
```

**Características:**
- ✅ Todo lo de Gratuita +
- ✅ Acceso a catálogo limitado (ej: 50 libros simultáneos)
- ✅ Acceso a nuevos lanzamientos **después de 1 mes** de su publicación
- ✅ Todas las funcionalidades sociales (reseñas, listas, favoritos)
- ✅ Historial completo
- ❌ No acceso inmediato a nuevos lanzamientos
- ❌ No recomendaciones personalizadas
- ❌ No puede compartir libros

**Reglas:**
- 1 mes de prueba gratis al suscribirse
- El acceso a nuevos lanzamientos es **1 mes después de la publicación del libro**
  - Ejemplo: Libro publicado el 1 de enero → usuario Básico puede leerlo desde el 1 de febrero
- Si cancela, pierde acceso inmediato
- Puede actualizar a Ilimitada en cualquier momento

**Restricción técnica:**

```php
// Al intentar leer un libro
public function canReadBook(User $user, Book $book): bool
{
    if ($book->is_free) return true;
    
    if ($user->membership->slug === 'basic') {
        // Verificar si han pasado 30 días desde publicación
        $publishedDaysAgo = $book->published_at->diffInDays(now());
        
        if ($publishedDaysAgo < 30) {
            return false; // No puede leer aún
        }
    }
    
    return true;
}
```

---

#### 3. ILIMITADA (Unlimited / Premium)

```php
'name' => 'Ilimitada',
'slug' => 'unlimited',
'price' => 19.99, // Mensual (ejemplo)
'duration_days' => 30,
'trial_days' => 30,
```

**Características:**
- ✅ Todo lo de Básica +
- ✅ **Acceso completo a TODO el catálogo**
- ✅ **Acceso inmediato a nuevos lanzamientos**
- ✅ **Recomendaciones personalizadas** basadas en historial
- ✅ **Compartir libros** con otros usuarios
- ✅ **Seguir autores favoritos** → notificaciones de nuevos libros
- ✅ Descuentos en productos físicos (futuro)

**Reglas:**
- 1 mes de prueba gratis al suscribirse
- Puede degradar a Básica en cualquier momento
- Al degradar, pierde acceso a funciones premium inmediatamente

---

#### 4. ESCRITOR (Writer)

```php
'name' => 'Escritor',
'slug' => 'writer',
'price' => 29.99, // Mensual (ejemplo - puede ser 0 si ganan por comisiones)
'duration_days' => 30,
'trial_days' => 30,
```

**Características:**
- ✅ **Todos los beneficios de Ilimitada** +
- ✅ **Editor de libros** integrado en la plataforma
- ✅ **Publicar libros** (requiere aprobación de Admin)
- ✅ **Ver estadísticas** de sus libros (lecturas, reseñas, rating)
- ✅ **Ganar comisiones** por lectura de sus libros (futuro)
- ✅ **Perfil de autor** personalizado
- ✅ **Notificaciones** cuando sus libros son aprobados/rechazados

**Reglas:**
- Cualquiera puede registrarse como escritor
- 1 mes de prueba gratis
- Libros publicados requieren aprobación de Admin
- Puede tener múltiples libros en diferentes estados:
  - Borrador (draft)
  - Pendiente de aprobación (pending)
  - Aprobado (approved)
  - Rechazado (rejected)
- Si cancela membresía, sus libros aprobados siguen publicados pero no puede crear nuevos

---

### Tabla de Comparación Rápida

| Funcionalidad | Gratuita | Básica | Ilimitada | Escritor |
|--------------|----------|--------|-----------|----------|
| Precio | $0 | $9.99/mes | $19.99/mes | $29.99/mes |
| Prueba gratis | - | 1 mes | 1 mes | 1 mes |
| Ver catálogo | ✅ | ✅ | ✅ | ✅ |
| Leer libros gratis | ✅ | ✅ | ✅ | ✅ |
| Leer libros de pago | ❌ | Limitado | ✅ Todo | ✅ Todo |
| Nuevos lanzamientos | ❌ | Después 1 mes | ✅ Inmediato | ✅ Inmediato |
| Reseñas y listas | ✅ | ✅ | ✅ | ✅ |
| Recomendaciones | ❌ | ❌ | ✅ | ✅ |
| Compartir libros | ❌ | ❌ | ✅ | ✅ |
| Seguir autores | ❌ | ❌ | ✅ | ✅ |
| Publicar libros | ❌ | ❌ | ❌ | ✅ |
| Ver estadísticas | ❌ | ❌ | ❌ | ✅ (propias) |
| Editor integrado | ❌ | ❌ | ❌ | ✅ |
| Comisiones | ❌ | ❌ | ❌ | ✅ |

---

### Gestión de Membresías

#### Ciclo de vida:

```
1. Usuario se registra → Gratuita (automática)
2. Usuario compra membresía → Trial (si aplica)
3. Trial vence → Activa (si pagó) o Gratuita (si canceló)
4. Usuario cancela → Vence al final del periodo pagado
5. Membresía vence → Gratuita (automática)
```

#### Estados de membresía:

```php
'active'   → Membresía activa y vigente
'trial'    → En periodo de prueba
'expired'  → Venció y no renovó
'canceled' → Cancelada por usuario (activa hasta vencimiento)
'suspended' → Suspendida por staff (falta de pago, etc.)
```

#### Cambio de plan:

```php
// Usuario quiere cambiar de Básica → Ilimitada
public function upgradeMembership(User $user, string $newPlan)
{
    $currentMembership = $user->currentMembership;
    $remainingDays = $currentMembership->expires_at->diffInDays(now());
    
    // Prorratea el pago
    $proratedAmount = calculateProrate($remainingDays, $newPlan);
    
    // Actualizar membresía
    $user->memberships()->update([
        'plan' => $newPlan,
        'expires_at' => now()->addDays(30), // Nuevo ciclo
    ]);
}
```

---

## 📐 REGLAS DE NEGOCIO

### 1. Acceso a Libros

#### Regla General:
"Un usuario puede leer un libro si su membresía lo permite"

```php
// app/Services/BookAccessService.php

public function canAccess(User $user, Book $book): bool
{
    // 1. Libro gratuito → todos pueden leer
    if ($book->is_free) {
        return true;
    }
    
    // 2. Usuario sin membresía activa → solo gratis
    if (!$user->hasActiveMembership()) {
        return false;
    }
    
    $membership = $user->currentMembership;
    
    // 3. Membresía Ilimitada o Escritor → todo el catálogo
    if (in_array($membership->plan->slug, ['unlimited', 'writer'])) {
        return true;
    }
    
    // 4. Membresía Básica → restricciones
    if ($membership->plan->slug === 'basic') {
        // Verificar si el libro tiene más de 30 días de publicado
        if ($book->published_at->diffInDays(now()) < 30) {
            return false; // Libro muy nuevo
        }
        
        // Verificar límite de libros simultáneos (si aplica)
        $currentlyReading = $user->currentlyReadingBooks()->count();
        if ($currentlyReading >= 50) {
            return false; // Límite alcanzado
        }
    }
    
    return true;
}
```

---

### 2. Publicación de Libros por Escritores

#### Flujo de aprobación:

```
Escritor crea libro → estado: 'draft'
  ↓
Escritor publica → estado: 'pending'
  ↓
Admin revisa
  ↓
  ├─→ Aprueba → estado: 'approved' → visible en catálogo
  └─→ Rechaza → estado: 'rejected' → escritor puede editar y reenviar
```

#### Reglas:

```php
// Solo escritores pueden publicar
if (!auth()->user()->hasRole('escritor')) {
    abort(403, 'Solo escritores pueden publicar libros');
}

// No puede publicar si tiene membresía vencida
if (!auth()->user()->hasActiveMembership()) {
    return redirect()->back()->with('error', 'Renueva tu membresía para publicar');
}

// No puede editar libro aprobado (debe contactar admin)
if ($book->status === 'approved' && !auth()->user()->hasRole('admin')) {
    return redirect()->back()->with('error', 'No puedes editar un libro aprobado');
}

// Puede eliminar solo si está en borrador o rechazado
if (!in_array($book->status, ['draft', 'rejected'])) {
    return redirect()->back()->with('error', 'No puedes eliminar este libro');
}
```

---

### 3. Gestión de Membresías (Staff)

#### Staff puede:

```php
// ✅ Cambiar plan de usuario
$user->currentMembership->update([
    'plan_id' => $newPlanId,
]);

// ✅ Suspender por falta de pago
$user->currentMembership->update([
    'status' => 'suspended',
    'suspended_reason' => 'Falta de pago',
    'suspended_at' => now(),
]);

// ✅ Reactivar membresía
$user->currentMembership->update([
    'status' => 'active',
    'expires_at' => now()->addDays(30),
]);

// ✅ Enviar recordatorio de pago
Notification::send($user, new PaymentReminderNotification());
```

#### Staff NO puede:

```php
// ❌ Eliminar usuario
if (auth()->user()->hasRole('staff')) {
    abort(403, 'Solo Admin puede eliminar usuarios');
}

// ❌ Cambiar roles
if (auth()->user()->hasRole('staff')) {
    abort(403, 'Solo Admin puede cambiar roles');
}

// ❌ Crear/editar libros
if (auth()->user()->hasRole('staff') && $action === 'books.edit') {
    abort(403, 'Solo Admin puede gestionar libros');
}
```

---

### 4. Cancelación de Membresía

#### Reglas:

```php
// Usuario cancela membresía
public function cancelMembership(User $user)
{
    $membership = $user->currentMembership;
    
    // Marcar como cancelada
    $membership->update([
        'status' => 'canceled',
        'canceled_at' => now(),
    ]);
    
    // Pierde acceso INMEDIATAMENTE
    // (Decisión de negocio confirmada)
    $membership->update([
        'expires_at' => now(),
    ]);
    
    // Asignar membresía gratuita
    $user->memberships()->create([
        'plan_id' => Plan::where('slug', 'free')->first()->id,
        'status' => 'active',
        'started_at' => now(),
        'expires_at' => null, // Sin vencimiento
    ]);
    
    // Notificar
    $user->notify(new MembershipCanceledNotification());
}
```

**Importante:** Usuario pierde acceso **inmediatamente** al cancelar, no al final del periodo.

---

### 5. Sistema de Notificaciones

#### Tipos de notificaciones:

```php
// 1. Recordatorios de pago (Staff → Usuario)
PaymentReminderNotification::class

// 2. Nuevos libros de autor favorito (Sistema → Usuario Ilimitado/Escritor)
NewBookFromFavoriteAuthorNotification::class

// 3. Libro aprobado/rechazado (Admin → Escritor)
BookApprovedNotification::class
BookRejectedNotification::class

// 4. Nueva reseña en tu libro (Usuario → Escritor)
NewReviewNotification::class

// 5. Respuesta a ticket (Staff → Usuario)
TicketResponseNotification::class

// 6. Membresía por vencer (Sistema → Usuario)
MembershipExpiringNotification::class
```

#### Almacenamiento:

```php
// Tabla: notifications (Laravel incluida)
- id
- type (clase de notificación)
- notifiable_type (App\Models\User)
- notifiable_id (ID del usuario)
- data (JSON con info de la notificación)
- read_at (null si no leída)
- created_at
```

#### Buzón de notificaciones:

```blade
<!-- Mostrar notificaciones no leídas -->
@foreach(auth()->user()->unreadNotifications as $notification)
    <div class="notification">
        {{ $notification->data['message'] }}
        <a href="{{ route('notifications.read', $notification) }}">Marcar como leída</a>
    </div>
@endforeach
```

---

### 6. Sistema de Reseñas

#### Reglas:

```php
// Solo puede reseñar si ha leído el libro
if (!$user->hasRead($book)) {
    return back()->with('error', 'Debes leer el libro antes de reseñarlo');
}

// Una reseña por usuario por libro
if ($user->reviews()->where('book_id', $book->id)->exists()) {
    return back()->with('error', 'Ya has reseñado este libro');
}

// Puede editar su propia reseña
if ($review->user_id !== auth()->id()) {
    abort(403, 'No puedes editar reseñas de otros');
}

// Staff puede eliminar reseñas inapropiadas
if (auth()->user()->can('reviews.moderate')) {
    $review->delete();
    Notification::send($review->user, new ReviewDeletedNotification());
}
```

---

### 7. Estadísticas para Escritores

#### Métricas disponibles:

```php
// Total de lecturas
$totalReads = $author->books()->sum('reads_count');

// Libro más leído
$mostReadBook = $author->books()->orderBy('reads_count', 'desc')->first();

// Promedio de rating
$avgRating = $author->books()->avg('average_rating');

// Lecturas por mes (últimos 12 meses)
$readsByMonth = BookRead::where('author_id', $author->id)
    ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as reads')
    ->groupBy('month')
    ->orderBy('month', 'desc')
    ->limit(12)
    ->get();

// Comisiones generadas (futuro)
$commissions = $author->commissions()->sum('amount');
```

---

## 🗄️ ESTRUCTURA DE BASE DE DATOS

### Diagrama de Relaciones

```
users
  ├─→ model_has_roles (Spatie)
  ├─→ memberships (1:N - historial)
  ├─→ authors (1:1 si es escritor)
  ├─→ books (1:N si es escritor - publicados por él)
  ├─→ reviews (1:N)
  ├─→ reading_lists (1:N)
  ├─→ favorites (N:M con books)
  ├─→ reading_history (N:M con books)
  ├─→ tickets (1:N)
  └─→ notifications (1:N)

books
  ├─→ authors (N:1)
  ├─→ categories (N:M)
  ├─→ reviews (1:N)
  ├─→ favorites (N:M con users)
  └─→ reading_history (N:M con users)

authors
  ├─→ users (1:1 opcional)
  └─→ books (1:N)

memberships
  ├─→ users (N:1)
  └─→ plans (N:1)

plans
  └─→ memberships (1:N)
```

---

### Migraciones Detalladas

#### 1. Tabla: users (Jetstream)

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->foreignId('current_team_id')->nullable();
    $table->string('profile_photo_path', 2048)->nullable();
    $table->timestamps();
});
```

**Campos adicionales que agregaremos:**

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('phone')->nullable()->after('email');
    $table->date('birth_date')->nullable();
    $table->string('country')->nullable();
    $table->text('bio')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamp('last_login_at')->nullable();
});
```

**Relaciones en el modelo:**

```php
class User extends Authenticatable
{
    use HasRoles; // Spatie
    
    // Membresía actual
    public function currentMembership()
    {
        return $this->hasOne(Membership::class)
            ->where('status', 'active')
            ->latest();
    }
    
    // Historial de membresías
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
    
    // Si es escritor, tiene perfil de autor
    public function author()
    {
        return $this->hasOne(Author::class);
    }
    
    // Libros publicados (si es escritor)
    public function publishedBooks()
    {
        return $this->hasMany(Book::class, 'published_by_user_id');
    }
    
    // Reseñas escritas
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    // Libros favoritos
    public function favorites()
    {
        return $this->belongsToMany(Book::class, 'favorites')
            ->withTimestamps();
    }
    
    // Historial de lectura
    public function readingHistory()
    {
        return $this->belongsToMany(Book::class, 'reading_history')
            ->withPivot('progress', 'completed_at', 'last_read_at')
            ->withTimestamps();
    }
    
    // Listas de lectura
    public function readingLists()
    {
        return $this->hasMany(ReadingList::class);
    }
    
    // Tickets de soporte
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    // Helpers
    public function hasActiveMembership(): bool
    {
        return $this->currentMembership()
            ->where('expires_at', '>', now())
            ->exists();
    }
    
    public function isWriter(): bool
    {
        return $this->hasRole('escritor');
    }
    
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}
```

---

#### 2. Tabla: plans (Planes de membresía)

```php
Schema::create('plans', function (Blueprint $table) {
    $table->id();
    $table->string('name'); // Gratuita, Básica, Ilimitada, Escritor
    $table->string('slug')->unique(); // free, basic, unlimited, writer
    $table->text('description')->nullable();
    $table->decimal('price', 8, 2)->default(0); // Precio mensual
    $table->integer('duration_days')->nullable(); // null = permanente
    $table->integer('trial_days')->default(0); // Días de prueba gratis
    $table->json('features')->nullable(); // JSON con características
    $table->boolean('is_active')->default(true);
    $table->integer('order')->default(0); // Orden de visualización
    $table->timestamps();
});
```

**Ejemplo de datos:**

```php
// Seeder
Plan::create([
    'name' => 'Gratuita',
    'slug' => 'free',
    'description' => 'Acceso limitado a libros gratuitos',
    'price' => 0.00,
    'duration_days' => null, // Sin vencimiento
    'trial_days' => 0,
    'features' => json_encode([
        'Acceso a libros gratuitos',
        'Ver catálogo completo',
        'Crear listas de lectura',
        'Dejar reseñas',
    ]),
    'is_active' => true,
    'order' => 1,
]);

Plan::create([
    'name' => 'Básica',
    'slug' => 'basic',
    'description' => 'Acceso a catálogo limitado',
    'price' => 9.99,
    'duration_days' => 30,
    'trial_days' => 30,
    'features' => json_encode([
        'Todo lo de Gratuita',
        'Acceso a 50 libros simultáneos',
        'Nuevos lanzamientos tras 1 mes',
        'Sin anuncios',
    ]),
    'is_active' => true,
    'order' => 2,
]);

// ... Ilimitada, Escritor
```

---

#### 3. Tabla: memberships (Membresías de usuarios)

```php
Schema::create('memberships', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('plan_id')->constrained()->onDelete('restrict');
    $table->enum('status', ['active', 'trial', 'expired', 'canceled', 'suspended'])->default('active');
    $table->timestamp('started_at');
    $table->timestamp('expires_at')->nullable(); // null = sin vencimiento (gratuita)
    $table->timestamp('trial_ends_at')->nullable();
    $table->timestamp('canceled_at')->nullable();
    $table->string('canceled_reason')->nullable();
    $table->timestamp('suspended_at')->nullable();
    $table->string('suspended_reason')->nullable();
    $table->boolean('auto_renew')->default(true);
    $table->timestamps();
    
    // Índices
    $table->index(['user_id', 'status']);
    $table->index('expires_at');
});
```

**Relaciones:**

```php
class Membership extends Model
{
    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'canceled_at' => 'datetime',
        'suspended_at' => 'datetime',
        'auto_renew' => 'boolean',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
    
    // Helpers
    public function isActive(): bool
    {
        return $this->status === 'active' 
            && ($this->expires_at === null || $this->expires_at > now());
    }
    
    public function isInTrial(): bool
    {
        return $this->status === 'trial' 
            && $this->trial_ends_at > now();
    }
    
    public function daysRemaining(): int
    {
        if ($this->expires_at === null) return 0;
        return max(0, $this->expires_at->diffInDays(now()));
    }
}
```

---

#### 4. Tabla: authors (Autores)

```php
Schema::create('authors', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
    $table->string('slug')->unique(); // Para URLs amigables
    $table->text('biografia')->nullable();
    $table->string('foto')->nullable();
    $table->date('fecha_nacimiento')->nullable();
    $table->string('nacionalidad')->nullable();
    $table->json('redes_sociales')->nullable(); // {twitter: '', instagram: '', web: ''}
    $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Si es escritor del sistema
    $table->boolean('is_verified')->default(false); // Para autores verificados
    $table->integer('books_count')->default(0); // Cache de total de libros
    $table->integer('followers_count')->default(0); // Total de seguidores
    $table->timestamps();
    
    $table->index('slug');
    $table->index('user_id');
});
```

**Relaciones:**

```php
class Author extends Model
{
    protected $casts = [
        'fecha_nacimiento' => 'date',
        'redes_sociales' => 'array',
        'is_verified' => 'boolean',
    ];
    
    // Si es escritor del sistema
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Libros del autor
    public function books()
    {
        return $this->hasMany(Book::class);
    }
    
    // Seguidores
    public function followers()
    {
        return $this->belongsToMany(User::class, 'author_followers')
            ->withTimestamps();
    }
    
    // Helpers
    public function isSystemWriter(): bool
    {
        return $this->user_id !== null;
    }
    
    public function getFullNameAttribute(): string
    {
        return $this->nombre;
    }
}
```

---

#### 5. Tabla: books (Libros)

```php
Schema::create('books', function (Blueprint $table) {
    $table->id();
    $table->string('titulo');
    $table->string('slug')->unique();
    $table->text('descripcion')->nullable();
    $table->text('sinopsis')->nullable();
    $table->foreignId('author_id')->constrained()->onDelete('restrict');
    $table->foreignId('published_by_user_id')->nullable()->constrained('users')->onDelete('set null'); // Escritor que lo subió
    $table->string('isbn')->nullable();
    $table->integer('paginas')->nullable();
    $table->string('idioma')->default('es');
    $table->year('año_publicacion')->nullable();
    $table->string('editorial')->nullable();
    
    // Archivos
    $table->string('portada')->nullable();
    $table->string('archivo_pdf')->nullable(); // Ruta del PDF
    
    // Estado
    $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
    $table->text('rejection_reason')->nullable(); // Si fue rechazado
    $table->timestamp('published_at')->nullable(); // Fecha de publicación/aprobación
    
    // Acceso
    $table->boolean('is_free')->default(false); // Libro gratuito
    $table->boolean('is_featured')->default(false); // Destacado
    
    // Estadísticas (cache)
    $table->integer('reads_count')->default(0);
    $table->integer('downloads_count')->default(0);
    $table->integer('favorites_count')->default(0);
    $table->integer('reviews_count')->default(0);
    $table->decimal('average_rating', 3, 2)->default(0); // 0.00 - 5.00
    
    $table->timestamps();
    $table->softDeletes(); // Eliminación suave
    
    // Índices
    $table->index('slug');
    $table->index('author_id');
    $table->index(['status', 'published_at']);
    $table->index('is_free');
});
```

**Relaciones:**

```php
class Book extends Model
{
    use SoftDeletes;
    
    protected $casts = [
        'published_at' => 'datetime',
        'is_free' => 'boolean',
        'is_featured' => 'boolean',
    ];
    
    // Autor del libro
    public function author()
    {
        return $this->belongsTo(Author::class);
    }
    
    // Usuario que lo publicó (si es escritor del sistema)
    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by_user_id');
    }
    
    // Categorías
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'book_category')
            ->withTimestamps();
    }
    
    // Reseñas
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    // Usuarios que lo han marcado como favorito
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')
            ->withTimestamps();
    }
    
    // Historial de lectura
    public function readBy()
    {
        return $this->belongsToMany(User::class, 'reading_history')
            ->withPivot('progress', 'completed_at', 'last_read_at')
            ->withTimestamps();
    }
    
    // Helpers
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
    
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    
    public function canBeEditedBy(User $user): bool
    {
        if ($user->hasRole('admin')) return true;
        
        if ($this->published_by_user_id === $user->id) {
            return in_array($this->status, ['draft', 'rejected']);
        }
        
        return false;
    }
    
    public function daysPublished(): int
    {
        if (!$this->published_at) return 0;
        return $this->published_at->diffInDays(now());
    }
}
```

---

#### 6. Tabla: categories (Categorías/Géneros)

```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
    $table->string('slug')->unique();
    $table->text('descripcion')->nullable();
    $table->string('icono')->nullable(); // Font Awesome class
    $table->string('color')->nullable(); // Color hex para UI
    $table->integer('books_count')->default(0); // Cache
    $table->boolean('is_active')->default(true);
    $table->integer('order')->default(0);
    $table->timestamps();
});

Schema::create('book_category', function (Blueprint $table) {
    $table->id();
    $table->foreignId('book_id')->constrained()->onDelete('cascade');
    $table->foreignId('category_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    
    $table->unique(['book_id', 'category_id']);
});
```

**Ejemplos de categorías:**

```php
// Seeder
$categories = [
    ['nombre' => 'Ficción', 'slug' => 'ficcion', 'icono' => 'fa-book-open', 'color' => '#3b82f6'],
    ['nombre' => 'No Ficción', 'slug' => 'no-ficcion', 'icono' => 'fa-book', 'color' => '#10b981'],
    ['nombre' => 'Ciencia Ficción', 'slug' => 'ciencia-ficcion', 'icono' => 'fa-rocket', 'color' => '#8b5cf6'],
    ['nombre' => 'Romance', 'slug' => 'romance', 'icono' => 'fa-heart', 'color' => '#ec4899'],
    ['nombre' => 'Misterio', 'slug' => 'misterio', 'icono' => 'fa-magnifying-glass', 'color' => '#6366f1'],
    ['nombre' => 'Fantasía', 'slug' => 'fantasia', 'icono' => 'fa-dragon', 'color' => '#f59e0b'],
    ['nombre' => 'Biografía', 'slug' => 'biografia', 'icono' => 'fa-user', 'color' => '#14b8a6'],
    ['nombre' => 'Historia', 'slug' => 'historia', 'icono' => 'fa-landmark', 'color' => '#a855f7'],
];

foreach ($categories as $category) {
    Category::create($category);
}
```

---

#### 7. Tabla: reviews (Reseñas)

```php
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('book_id')->constrained()->onDelete('cascade');
    $table->integer('rating')->unsigned(); // 1-5 estrellas
    $table->text('comment')->nullable();
    $table->boolean('is_spoiler')->default(false);
    $table->boolean('is_verified_purchase')->default(true); // Si leyó el libro
    $table->integer('likes_count')->default(0);
    $table->timestamps();
    
    // Una reseña por usuario por libro
    $table->unique(['user_id', 'book_id']);
    $table->index('book_id');
});
```

**Relaciones:**

```php
class Review extends Model
{
    protected $casts = [
        'is_spoiler' => 'boolean',
        'is_verified_purchase' => 'boolean',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    
    // Usuarios que dieron like a la reseña
    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'review_likes')
            ->withTimestamps();
    }
}
```

---

#### 8. Tabla: favorites (Favoritos)

```php
Schema::create('favorites', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('book_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    
    $table->unique(['user_id', 'book_id']);
});
```

---

#### 9. Tabla: reading_history (Historial de lectura)

```php
Schema::create('reading_history', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('book_id')->constrained()->onDelete('cascade');
    $table->integer('progress')->default(0); // 0-100 (porcentaje)
    $table->timestamp('completed_at')->nullable(); // Cuando terminó el libro
    $table->timestamp('last_read_at'); // Última vez que lo leyó
    $table->timestamps();
    
    $table->unique(['user_id', 'book_id']);
    $table->index(['user_id', 'last_read_at']);
});
```

---

#### 10. Tabla: reading_lists (Listas de lectura)

```php
Schema::create('reading_lists', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('nombre');
    $table->text('descripcion')->nullable();
    $table->boolean('is_public')->default(false);
    $table->integer('books_count')->default(0);
    $table->timestamps();
    
    $table->index('user_id');
});

Schema::create('reading_list_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('reading_list_id')->constrained()->onDelete('cascade');
    $table->foreignId('book_id')->constrained()->onDelete('cascade');
    $table->integer('order')->default(0);
    $table->timestamps();
    
    $table->unique(['reading_list_id', 'book_id']);
});
```

---

#### 11. Tabla: author_followers (Seguir autores)

```php
Schema::create('author_followers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('author_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    
    $table->unique(['user_id', 'author_id']);
});
```

---

#### 12. Tabla: tickets (Soporte)

```php
Schema::create('tickets', function (Blueprint $table) {
    $table->id();
    $table->string('subject');
    $table->text('message');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // Staff asignado
    $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
    $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
    $table->timestamp('resolved_at')->nullable();
    $table->timestamps();
    
    $table->index(['user_id', 'status']);
    $table->index('assigned_to');
});

Schema::create('ticket_responses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quien respondió
    $table->text('message');
    $table->boolean('is_staff_response')->default(false);
    $table->timestamps();
    
    $table->index('ticket_id');
});
```

---

### Resumen de Tablas

| Tabla | Propósito | Relaciones |
|-------|-----------|------------|
| `users` | Usuarios del sistema | → roles, memberships, authors, books |
| `plans` | Planes de membresía | → memberships |
| `memberships` | Membresías activas/históricas | → users, plans |
| `authors` | Autores (externos + escritores) | → users, books |
| `books` | Catálogo de libros | → authors, categories, reviews |
| `categories` | Géneros literarios | → books (N:M) |
| `reviews` | Reseñas de libros | → users, books |
| `favorites` | Libros favoritos | → users, books (N:M) |
| `reading_history` | Historial de lectura | → users, books (N:M) |
| `reading_lists` | Listas personalizadas | → users, books (N:M) |
| `author_followers` | Seguir autores | → users, authors (N:M) |
| `tickets` | Soporte técnico | → users |
| `ticket_responses` | Respuestas a tickets | → tickets, users |

**Total:** ~13 tablas principales + Spatie (5 tablas) = **18 tablas**

---

## ⚙️ CONFIGURACIÓN DEL PROYECTO

### Variables de Entorno (.env)

```env
# Aplicación
APP_NAME="Biblioteca Virtual"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# Base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=biblioteca_virtual
DB_USERNAME=laravelphp
DB_PASSWORD=laravel1234

# Idioma
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_ES

# Sesión
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_DRIVER=file
QUEUE_CONNECTION=database

# Filesystems
FILESYSTEM_DISK=local

# Jetstream
JETSTREAM_STACK=livewire
JETSTREAM_FEATURES=profile-photos,api,account-deletion
```

### Estructura de Rutas

#### routes/web.php (Rutas públicas y usuario)

```php
<?php

use Illuminate\Support\Facades\Route;

// Página de inicio
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Catálogo público
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/libros/{book:slug}', [BookController::class, 'show'])->name('books.show');
Route::get('/autores/{author:slug}', [AuthorController::class, 'show'])->name('authors.show');

// Rutas autenticadas (usuario)
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    
    // Redirigir dashboard según rol
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        
        if (auth()->user()->hasRole('staff')) {
            return redirect()->route('admin.dashboard');
        }
        
        if (auth()->user()->hasRole('escritor')) {
            return redirect()->route('writer.dashboard');
        }
        
        return view('dashboard');
    })->name('dashboard');
    
    // Lectura de libros
    Route::get('/leer/{book:slug}', [ReaderController::class, 'read'])->name('books.read');
    
    // Membresías
    Route::get('/membresias', [MembershipController::class, 'index'])->name('memberships.index');
    Route::post('/membresias/subscribe/{plan}', [MembershipController::class, 'subscribe'])->name('memberships.subscribe');
    Route::post('/membresias/cancel', [MembershipController::class, 'cancel'])->name('memberships.cancel');
    
    // Favoritos
    Route::post('/favoritos/{book}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    
    // Listas de lectura
    Route::resource('listas', ReadingListController::class);
    
    // Reseñas
    Route::post('/libros/{book}/review', [ReviewController::class, 'store'])->name('reviews.store');
    
    // Tickets
    Route::resource('tickets', TicketController::class);
});
```

#### routes/admin.php (Panel de administración)

```php
<?php

use Illuminate\Support\Facades\Route;

// Todas las rutas requieren autenticación + rol admin o staff
// Configurado en bootstrap/app.php

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

// Gestión de usuarios (solo admin)
Route::middleware(['permission:users.view'])->group(function () {
    Route::get('/users', App\Http\Livewire\Admin\UserTable::class)->name('users.index');
});

// Gestión de libros (solo admin)
Route::middleware(['permission:books.view'])->group(function () {
    Route::get('/books', App\Http\Livewire\Admin\BookTable::class)->name('books.index');
    Route::get('/books/pending', [BookController::class, 'pending'])->name('books.pending');
});

// Gestión de autores (solo admin)
Route::middleware(['permission:authors.view'])->group(function () {
    Route::get('/authors', App\Http\Livewire\Admin\AuthorTable::class)->name('authors.index');
});

// Gestión de membresías (admin y staff)
Route::middleware(['permission:memberships.view'])->group(function () {
    Route::get('/memberships', [MembershipController::class, 'manage'])->name('memberships.manage');
});

// Tickets (admin y staff)
Route::middleware(['permission:tickets.view'])->group(function () {
    Route::get('/tickets', App\Http\Livewire\Admin\TicketTable::class)->name('tickets.index');
});

// Configuración (solo admin)
Route::middleware(['role:admin'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
});
```

#### bootstrap/app.php (Configuración de rutas admin)

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
    then: function () {
        Route::middleware(['web', 'auth'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('routes/admin.php'));
    },
)
```

---

## 🔒 SEGURIDAD Y AUTORIZACIÓN

### Middleware Personalizado

#### CheckMembershipMiddleware

```php
// app/Http/Middleware/CheckMembershipMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckMembershipMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        // Si no tiene membresía activa, redirigir a planes
        if (!$user->hasActiveMembership()) {
            return redirect()
                ->route('memberships.index')
                ->with('warning', 'Necesitas una membresía activa para acceder a este contenido');
        }
        
        return $next($request);
    }
}
```

#### VerifyBookAccessMiddleware

```php
// app/Http/Middleware/VerifyBookAccessMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\BookAccessService;

class VerifyBookAccessMiddleware
{
    public function __construct(
        protected BookAccessService $bookAccessService
    ) {}
    
    public function handle(Request $request, Closure $next)
    {
        $book = $request->route('book');
        $user = auth()->user();
        
        if (!$this->bookAccessService->canAccess($user, $book)) {
            abort(403, 'No tienes acceso a este libro con tu membresía actual');
        }
        
        return $next($request);
    }
}
```

### Políticas (Policies)

#### BookPolicy

```php
// app/Policies/BookPolicy.php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    // Ver libro
    public function view(User $user, Book $book): bool
    {
        // Todos pueden ver detalles
        return true;
    }
    
    // Leer libro
    public function read(User $user, Book $book): bool
    {
        // Usar servicio de acceso
        return app(BookAccessService::class)->canAccess($user, $book);
    }
    
    // Crear libro
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'escritor']);
    }
    
    // Editar libro
    public function update(User $user, Book $book): bool
    {
        // Admin puede editar cualquier libro
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // Escritor solo puede editar sus libros en draft o rejected
        if ($user->hasRole('escritor') && $book->published_by_user_id === $user->id) {
            return in_array($book->status, ['draft', 'rejected']);
        }
        
        return false;
    }
    
    // Eliminar libro
    public function delete(User $user, Book $book): bool
    {
        // Solo admin puede eliminar
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // Escritor solo puede eliminar borradores
        if ($user->hasRole('escritor') && $book->published_by_user_id === $user->id) {
            return $book->status === 'draft';
        }
        
        return false;
    }
    
    // Aprobar/rechazar libro
    public function approve(User $user, Book $book): bool
    {
        return $user->hasRole('admin');
    }
}
```

### Protección de Archivos PDF

```php
// app/Http/Controllers/ReaderController.php

public function read(Book $book)
{
    // Autorizar acceso
    $this->authorize('read', $book);
    
    // Registrar en historial
    auth()->user()->readingHistory()->syncWithoutDetaching([
        $book->id => [
            'last_read_at' => now(),
        ]
    ]);
    
    // Incrementar contador de lecturas
    $book->increment('reads_count');
    
    // Devolver vista con PDF protegido
    return view('books.read', [
        'book' => $book,
        'pdfUrl' => route('books.pdf', ['book' => $book, 'token' => encrypt($book->id)]),
    ]);
}

// Ruta protegida para servir PDF
public function pdf(Book $book, Request $request)
{
    // Verificar token
    if (decrypt($request->token) !== $book->id) {
        abort(403);
    }
    
    // Autorizar
    $this->authorize('read', $book);
    
    // Servir archivo
    return response()->file(storage_path('app/books/' . $book->archivo_pdf), [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $book->titulo . '.pdf"',
    ]);
}
```

---

## 🚀 GUÍA DE DESARROLLO

### Instalación del Proyecto

```bash
# Clonar repositorio (cuando esté en Git)
git clone [URL]
cd biblioteca_virtual

# Instalar dependencias PHP
composer install

# Instalar dependencias Node
npm install

# Copiar .env
cp .env.example .env

# Generar key
php artisan key:generate

# Configurar base de datos en .env

# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders
php artisan db:seed

# Crear link simbólico storage
php artisan storage:link

# Compilar assets
npm run build

# Levantar servidor
php artisan serve
```

### Comandos Útiles

```bash
# Limpiar caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Ver rutas
php artisan route:list

# Crear componente Livewire
php artisan make:livewire Admin/BookTable

# Crear migración
php artisan make:migration create_books_table

# Crear modelo con todo
php artisan make:model Book -mfsc
# -m: migration, -f: factory, -s: seeder, -c: controller

# Crear policy
php artisan make:policy BookPolicy --model=Book

# Ejecutar tests
php artisan test

# Compilar assets en desarrollo
npm run dev

# Compilar assets para producción
npm run build
```

### Convenciones de Código

#### Nombres de archivos y clases:

```
Modelos: Book.php (singular, PascalCase)
Controladores: BookController.php (singular + Controller)
Livewire: BookTable.php (PascalCase)
Migraciones: 2025_11_25_000000_create_books_table.php
Vistas Blade: book-table.blade.php (kebab-case)
```

#### Nombres de variables:

```php
// Eloquent
$book (singular)
$books (plural)

// Relaciones
$book->author (singular)
$book->categories (plural)

// Métodos
public function getBooks() // camelCase
public function canReadBook() // camelCase con can/is/has
```

#### Nombres de rutas:

```php
Route::get('/books', ...)->name('books.index');
Route::get('/books/{book}', ...)->name('books.show');
Route::post('/books', ...)->name('books.store');
Route::get('/admin/books', ...)->name('admin.books.index');
```

---

## 📊 FLUJOS DE TRABAJO

### Flujo: Usuario se registra y lee un libro

```
1. Usuario visita home
2. Click en "Registrarse"
3. Completa formulario (Jetstream)
4. Sistema asigna rol "usuario"
5. Sistema crea membresía "Gratuita" automática
6. Redirige a dashboard
7. Usuario navega a catálogo
8. Usuario selecciona libro gratuito
9. Sistema verifica acceso (BookAccessService)
10. Usuario lee libro
11. Sistema registra en reading_history
12. Usuario deja reseña
13. Sistema notifica al autor (si es escritor del sistema)
```

### Flujo: Escritor publica un libro

```
1. Escritor se registra con rol "escritor"
2. Sistema crea perfil en tabla authors
3. Escritor va a "Mis libros"
4. Click en "Crear libro"
5. Completa formulario (título, sinopsis, categorías, PDF)
6. Guarda como borrador (status = 'draft')
7. Puede editar/previsualizar
8. Click en "Publicar"
9. Libro cambia a status = 'pending'
10. Admin recibe notificación
11. Admin revisa libro
12. Admin aprueba → status = 'approved', published_at = now()
13. Libro aparece en catálogo
14. Escritor recibe notificación de aprobación
```

### Flujo: Staff gestiona membresía vencida

```
1. Staff ve dashboard con alertas
2. Ve usuario con membresía vencida hace 5 días
3. Click en "Enviar recordatorio"
4. Sistema envía notificación al usuario
5. Sistema guarda en tabla notifications
6. Usuario ve notificación en su buzón
7. Usuario ignora (no paga)
8. Staff puede suspender membresía
9. Status cambia a 'suspended'
10. Usuario pierde acceso a libros de pago
11. Usuario solo puede leer libros gratuitos
```

---

## 📝 PRÓXIMOS PASOS

### Fase 1: Base de Datos y Autenticación ✅ (Completado)
- [x] Instalación de Laravel
- [x] Configuración de base de datos
- [x] Instalación de Jetstream
- [x] Instalación de Spatie Permissions
- [x] Configuración de roles básicos

### Fase 2: Diseño de Base de Datos (Siguiente)
- [ ] Crear migraciones de todas las tablas
- [ ] Crear modelos con relaciones
- [ ] Crear seeders (roles, permisos, planes, categorías)
- [ ] Crear factories para testing
- [ ] Ejecutar migraciones y seeders

### Fase 3: Sistema de Roles y Permisos
- [ ] Definir todos los permisos en seeder
- [ ] Asignar permisos a roles
- [ ] Crear policies para cada modelo
- [ ] Crear middleware personalizados
- [ ] Testing de autorización

### Fase 4: Panel de Administración
- [ ] Layout admin completo (sidebar, nav)
- [ ] Dashboard con estadísticas
- [ ] CRUD de usuarios (Rappasoft table)
- [ ] CRUD de libros (Rappasoft table)
- [ ] CRUD de autores (Rappasoft table)
- [ ] Gestión de categorías
- [ ] Aprobación de libros
- [ ] Gestión de membresías

### Fase 5: Panel de Escritor
- [ ] Dashboard con estadísticas propias
- [ ] CRUD de libros propios
- [ ] Editor de libros (CKEditor/TinyMCE)
- [ ] Ver reseñas de sus libros
- [ ] Ver comisiones (futuro)

### Fase 6: Experiencia de Usuario
- [ ] Catálogo de libros (filtros, búsqueda)
- [ ] Página de detalle de libro
- [ ] Lector de PDF integrado
- [ ] Sistema de favoritos
- [ ] Listas de lectura
- [ ] Sistema de reseñas
- [ ] Historial de lectura
- [ ] Recomendaciones (Ilimitada)

### Fase 7: Sistema de Membresías
- [ ] Página de planes
- [ ] Suscripción a planes
- [ ] Gestión de membresía (cambiar, cancelar)
- [ ] Lógica de acceso según plan
- [ ] Sistema de trials
- [ ] Notificaciones de vencimiento

### Fase 8: Notificaciones y Soporte
- [ ] Sistema de notificaciones
- [ ] Buzón de notificaciones
- [ ] Sistema de tickets
- [ ] Panel de tickets para staff
- [ ] Emails de notificación

### Fase 9: Búsqueda y Filtros
- [ ] Buscador avanzado
- [ ] Filtros por categoría
- [ ] Filtros por autor
- [ ] Ordenamiento
- [ ] Paginación optimizada

### Fase 10: Testing y Optimización
- [ ] Unit tests
- [ ] Feature tests
- [ ] Optimización de consultas (N+1)
- [ ] Cache de estadísticas
- [ ] Índices de base de datos
- [ ] SEO básico

---

## 📞 CONTACTO Y SOPORTE

**Desarrollador:** [Tu Nombre]  
**Email:** [Tu Email]  
**Repositorio:** [URL Git]

---

**Última actualización:** 25 de Noviembre, 2025

---

## 🎉 CONCLUSIÓN

Este documento es la **guía completa** del proyecto Biblioteca Virtual. Contiene:

✅ Toda la arquitectura del sistema  
✅ Todos los roles y permisos definidos  
✅ Sistema completo de membresías  
✅ Estructura de base de datos detallada  
✅ Reglas de negocio implementadas  
✅ Convenciones de código  
✅ Flujos de trabajo  
✅ Guía de desarrollo  

**Este documento se actualizará** conforme avancemos en el desarrollo del proyecto.

---

*Generado con ❤️ para el proyecto Biblioteca Virtual*

