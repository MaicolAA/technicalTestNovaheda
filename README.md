# Prueba Técnica Novaheda - Laravel 11 API

## Requisitos Previos

Para ejecutar el proyecto, asegúrate de contar con los siguientes requisitos:

- **PHP** 8.2+
- **Composer** 2.5+
- **MySQL** 8.0+ / **PostgreSQL** 15+
- **Extensiones PHP** requeridas:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML

---

## Instalación

Ejecuta los siguientes comandos para instalar y configurar el proyecto:

```bash
# Clonar repositorio
git clone https://github.com/MaicolAA/technicalTestNovaheda.git novaheda
cd novaheda

# Instalar dependencias
composer install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos (editar .env)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=novaheda
DB_USERNAME=root
DB_PASSWORD=

# Ejecutar migraciones
php artisan migrate 

# Generar clave JWT
php artisan jwt:secret

# Iniciar servidor
php artisan serve
```

---

## Endpoints de la API

### 🔑 Autenticación

| Método | Endpoint  | Descripción |
|--------|----------|-------------|
| **POST** | `/register` | Registrar un nuevo usuario |
| **POST** | `/login` | Iniciar sesión |
| **POST** | `/logout` | Cerrar sesión |
| **GET**  | `/me` | Obtener usuario autenticado |


### 🏢 Compañias

| Método | Endpoint  | Descripción |
|--------|----------|-------------|
| **GET**  | `/api/companies` | Listar compañias |
| **POST** | `/api/companies` | Crear una compañia |
| **GET**  | `/api/companies/{id}` | Obtener detalles de una compañia |
| **PUT**  | `/api/companies/{id}` | Actualizar una compañia |
| **DELETE** | `/api/companies/{id}` | Eliminar una compañia |

### 📇 Contactos

| Método | Endpoint  | Descripción |
|--------|----------|-------------|
| **GET**  | `/api/contacts` | Listar contactos |
| **POST** | `/api/contacts` | Crear un contacto |
| **GET**  | `/api/contacts/{id}` | Obtener detalles de un contacto |
| **PUT**  | `/api/contacts/{id}` | Actualizar un contacto |
| **DELETE** | `/api/contacts/{id}` | Eliminar un contacto |

### 📝 Notas

| Método | Endpoint  | Descripción |
|--------|----------|-------------|
| **GET**  | `/api/notes` | Listar notas |
| **POST** | `/api/notes` | Crear una nota |
| **GET**  | `/api/notes/{id}` | Obtener una nota |
| **PUT**  | `/api/notes/{id}` | Actualizar una nota |
| **DELETE** | `/api/notes/{id}` | Eliminar una nota |
| **GET**  | `/companies/{id}/notes` | Obtener notas de una empresa |
| **GET**  | `/contacts/{id}/notes` | Obtener notas de un contacto |

📌 **Parámetros adicionales:**

- `?noteable_type=contact|company`
- `?noteable_id=1`
- `?include=noteable`

---

## 🧪 Testing

Configura el archivo .env.testing

Ejecuta las pruebas con los siguientes comandos:

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar pruebas específicas
php artisan test tests/Feature/NoteTest.php
```

---

## 🌍 Variables de Entorno Clave

Configura tu archivo `.env` con las siguientes variables clave:

```ini
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=postgres
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=novaheda
DB_USERNAME=novaheda_user
DB_PASSWORD=gatostem123

JWT_SECRET=tu_clave_secreta