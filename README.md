# Pilox — Sistema de Gestión para Centro Deportivo

Aplicación web desarrollada en **PHP (CodeIgniter 4)**, **MySQL** y **Bootstrap 5** para la gestión integral del centro deportivo **Pilox** (Pilates, Yoga, GAP y Pilates AFA).

Permite administrar sucursales, disciplinas, grilla de turnos (individuales y por período), abonos mensuales, tarifas, métricas de gestión, reservas de clases de alumnos y control de asistencia por parte de los docentes.

---

## 🛠️ Requisitos Previos

Para ejecutar el proyecto en un entorno local (como **WAMP**, **XAMPP**, **Laragon** o similar) necesitás:

- **PHP 8.2** o superior (con las extensiones `intl`, `mbstring`, `mysqli` y `json` habilitadas en `php.ini`).
- **MySQL / MariaDB**.
- **Servidor Web Apache** (con `mod_rewrite` activado) o el servidor integrado de CodeIgniter (`php spark serve`).
- **Composer** (opcional si la carpeta `vendor/` ya está incluida; necesario para instalar dependencias desde cero).

---

## 🚀 Instalación Paso a Paso

### 1. Clonar o descargar el proyecto
Ubicá la carpeta del proyecto dentro del directorio público de tu servidor local:
- En **WAMP**: `C:\wamp64\www\pilox`
- En **XAMPP**: `C:\xampp\htdocs\pilox`

```bash
git clone <URL_DEL_REPOSITORIO> pilox
cd pilox
```

### 2. Instalar dependencias (si no existe la carpeta `vendor/`)
Ejecutá en la terminal desde la raíz del proyecto:
```bash
composer install
```

### 3. Crear e importar la Base de Datos
1. Abrí **phpMyAdmin** (o tu cliente MySQL) y creá una base de datos llamada `pilox` (cotejamiento recomendado: `utf8mb4_unicode_ci`).
2. Importá el archivo [`database/pilox_db.sql`](database/pilox_db.sql) dentro de la base de datos recién creada.

### 4. Configurar el archivo `.env`
1. En la raíz del proyecto encontrarás el archivo `.env.example`.
2. Hacé una copia de ese archivo y renombrala a **`.env`**.
3. Abrí el archivo `.env` y ajustá los datos de tu entorno local (URL base y conexión a MySQL):

```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost/pilox/public/'

database.default.hostname = localhost
database.default.database = pilox
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port = 3306
```
> **Nota para usuarios de WAMP:** verificá qué puerto utiliza tu servicio MySQL (suele ser `3306` o `3308`) y colocalo en `database.default.port`.

### 5. Iniciar la aplicación
Tenés dos formas de abrir el sistema en el navegador:

- **Opción A — Con Apache (WAMP / XAMPP):**
  Ingresá desde el navegador a:
  `http://localhost/pilox/public/`

- **Opción B — Con el servidor interno de CodeIgniter:**
  Si ejecutás `php spark serve` en la terminal, recordá cambiar `app.baseURL = 'http://localhost:8080/'` en tu `.env` e ingresá a `http://localhost:8080/`.

---

## 👥 Roles del Sistema

El sistema cuenta con tres paneles diferenciados según el rol del usuario:

- **Administrador (`admin`):**
  - Dashboard general y panel de **Información General** (métricas de ingresos, deudas, ocupación por día/franja/actividad/sucursal, asistencia y alumnos).
  - ABM de Sucursales, Actividades, Docentes y Alumnos.
  - Configuración de Tarifas y asignación/cobro de Abonos mensuales (4, 8 y 12 clases).
  - Gestión de Turnos individuales y **Generador Masivo de Turnos por período**.
- **Alumno (`alumno`):**
  - Consulta del estado y vigencia de su abono.
  - Buscador de turnos disponibles con filtros (actividad, sucursal, fecha y franja horaria).
  - Reserva y cancelación de clases con control automático de cupos y clases disponibles.
- **Docente (`docente`):**
  - Panel con resumen del día, agenda de clases de hoy y alertas de asistencias pendientes.
  - Consulta de clases próximas e historial de clases dictadas.
  - Registro de asistencia de alumnos inscriptos.
- **Perfil de Usuario (todos los roles):**
  - Edición de datos personales y cambio de contraseña desde `/perfil`.
