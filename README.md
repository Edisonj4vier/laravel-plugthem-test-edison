# 🚀 Laravel Survey API - Prueba Técnica Plugthem

API RESTful para gestión de encuestas con autenticación mediante Laravel Sanctum, sistema de reportes con estadísticas avanzadas y optimización con Redis Cache.

## 📋 Descripción

Sistema completo de encuestas que permite:
- ✅ Autenticación segura de usuarios con tokens (Laravel Sanctum)
- ✅ Crear y gestionar encuestas con múltiples tipos de preguntas
- ✅ Responder encuestas de manera estructurada con validaciones robustas
- ✅ Generar reportes con estadísticas y promedios en tiempo real
- ✅ Automatización de tareas con comandos Artisan personalizados
- ✅ Sistema de eventos para logging y notificaciones
- ✅ Optimización de performance con Redis Cache (Bonus)

## 🛠️ Stack Tecnológico

- **Framework:** Laravel 11
- **Base de datos:** MySQL 8.0+
- **Autenticación:** Laravel Sanctum
- **Cache:** Redis
- **PHP:** 8.2+
- **Arquitectura:** RESTful API

## ⚙️ Requisitos del Sistema

- PHP >= 8.2
- Composer >= 2.0
- MySQL >= 8.0
- Redis >= 6.0 (para bonus de cache)
- Git

## 📦 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/laravel-plugthem-test-tu-nombre.git
cd laravel-plugthem-test-tu-nombre
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar variables de entorno

```bash
cp .env.example .env
```

Editar `.env` con tus credenciales:

```env
APP_NAME="Laravel Survey API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=plugthem_exam
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

CACHE_DRIVER=redis
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 4. Generar clave de aplicación

```bash
php artisan key:generate
```

### 5. Crear base de datos

Crear manualmente la base de datos `plugthem_exam` en MySQL.

### 6. Instalar API y ejecutar migraciones

```bash
php artisan install:api
php artisan migrate --seed
```

### 7. Iniciar servidor de desarrollo

```bash
php artisan serve
```

La API estará disponible en: **http://localhost:8000**

---

## 📚 Documentación de Endpoints

### 🔐 Autenticación

| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| POST | `/api/register` | Registrar nuevo usuario | No |
| POST | `/api/login` | Iniciar sesión | No |
| POST | `/api/logout` | Cerrar sesión | Sí |

#### Ejemplo: Registro

```bash
POST /api/register
Content-Type: application/json

{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Respuesta exitosa:**
```json
{
    "success": true,
    "message": "Usuario registrado exitosamente",
    "data": {
        "user": {
            "id": 1,
            "name": "Juan Pérez",
            "email": "juan@example.com"
        },
        "token": "1|abcdefghijklmnopqrstuvwxyz..."
    }
}
```

---

### 📊 Encuestas (Surveys)

| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| GET | `/api/surveys` | Listar mis encuestas | Sí |
| POST | `/api/surveys` | Crear encuesta | Sí |
| GET | `/api/surveys/{id}` | Ver encuesta específica | Sí |
| PUT | `/api/surveys/{id}` | Actualizar encuesta | Sí |
| DELETE | `/api/surveys/{id}` | Eliminar encuesta | Sí |

**Restricciones:**
- Solo el creador puede modificar o eliminar sus encuestas
- El campo `status` debe ser: `active` o `inactive`

#### Ejemplo: Crear Encuesta

```bash
POST /api/surveys
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Encuesta de Satisfacción 2024",
    "description": "Queremos conocer tu opinión sobre nuestros servicios",
    "status": "active"
}
```

---

### ❓ Preguntas (Questions)

| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| GET | `/api/surveys/{survey}/questions` | Listar preguntas | Sí |
| POST | `/api/surveys/{survey}/questions` | Crear pregunta | Sí |
| GET | `/api/surveys/{survey}/questions/{question}` | Ver pregunta | Sí |
| PUT | `/api/surveys/{survey}/questions/{question}` | Actualizar pregunta | Sí |
| DELETE | `/api/surveys/{survey}/questions/{question}` | Eliminar pregunta | Sí |

**Tipos de preguntas:**
- `text`: Respuesta de texto libre
- `select`: Opción de selección
- `rating`: Calificación numérica (1-5)

#### Ejemplo: Crear Pregunta

```bash
POST /api/surveys/1/questions
Authorization: Bearer {token}
Content-Type: application/json

{
    "text": "¿Cómo calificarías nuestro servicio?",
    "type": "rating"
}
```

---

### 📝 Respuestas (Answers)

| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| POST | `/api/surveys/{survey}/answers` | Responder encuesta | Sí |
| GET | `/api/surveys/{survey}/answers` | Ver mis respuestas | Sí |

**Reglas importantes:**
- Solo encuestas con estado `active` pueden ser respondidas
- Un usuario solo puede responder una vez cada encuesta
- Todas las preguntas deben pertenecer a la encuesta especificada
- Las respuestas se guardan en una transacción (todo o nada)

#### Ejemplo: Responder Encuesta

```bash
POST /api/surveys/1/answers
Authorization: Bearer {token}
Content-Type: application/json

{
    "answers": [
        {
            "question_id": 1,
            "value": "5"
        },
        {
            "question_id": 2,
            "value": "Excelente atención y rapidez"
        },
        {
            "question_id": 3,
            "value": "Sí"
        }
    ]
}
```

**Respuesta exitosa:**
```json
{
    "success": true,
    "message": "Respuestas guardadas exitosamente",
    "data": {
        "survey_id": 1,
        "total_answers": 3,
        "answers": [...]
    }
}
```

---

### 📈 Reportes

| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| GET | `/api/reports/survey/{survey}` | Obtener estadísticas | Sí |
| DELETE | `/api/reports/survey/{survey}/cache` | Limpiar cache del reporte | Sí |

**Incluye:**
- Total de respuestas
- Total de usuarios únicos
- Estadísticas por pregunta
- Promedios de preguntas tipo `rating`

#### Ejemplo: Obtener Reporte

```bash
GET /api/reports/survey/1
Authorization: Bearer {token}
```

**Respuesta:**
```json
{
    "success": true,
    "message": "Reporte generado exitosamente",
    "data": {
        "survey_id": 1,
        "survey_title": "Encuesta de Satisfacción 2024",
        "survey_status": "active",
        "total_responses": 45,
        "unique_users": 15,
        "questions_stats": [
            {
                "question_id": 1,
                "question_text": "¿Cómo calificarías el servicio?",
                "question_type": "rating",
                "total_answers": 15,
                "average_rating": 4.53,
                "min_rating": 3,
                "max_rating": 5
            },
            {
                "question_id": 2,
                "question_text": "Comentarios adicionales",
                "question_type": "text",
                "total_answers": 12
            }
        ],
        "cached": true,
        "cached_at": "2024-11-01 16:30:45"
    }
}
```

---

## 🤖 Comando Artisan

### Desactivar Encuestas Inactivas

Desactiva automáticamente encuestas que no han recibido respuestas en los últimos 30 días:

```bash
php artisan app:survey-deactivate-inactive
```

**Funcionalidad:**
- Busca encuestas activas sin respuestas durante 30+ días
- Busca encuestas cuya última respuesta fue hace 30+ días
- Cambia el estado a `inactive`
- Registra en el log la cantidad de encuestas desactivadas

**Ver el log:**
```bash
tail -f storage/logs/laravel.log
```

**⚠️ Nota sobre Laravel 11:**
El examen solicita `php artisan survey:deactivate-inactive`, pero en Laravel 11 los comandos personalizados se prefijan con `app:` por convención. Por ello se implementa como `app:survey-deactivate-inactive`.

---

## 📡 Eventos y Listeners

### SurveyAnswered Event

Se dispara automáticamente cuando un usuario completa una encuesta.

**Listener:** `SendSurveyAnsweredNotification`
- Registra en el log: `"Usuario {id} respondió la encuesta {id} ({título}) el {fecha}"`
- Puede extenderse para enviar emails, notificaciones push, etc.

**Ejemplo de log:**
```
[2024-11-01 15:30:45] local.INFO: Usuario 5 respondió la encuesta 1 (Encuesta de Satisfacción 2024) el 2024-11-01 15:30:45
```

---

## 🚀 Bonus: Redis Cache

Este proyecto implementa **Redis Cache** en el endpoint de reportes para optimizar dramáticamente el rendimiento.

### Configuración de Redis

#### 1. Instalar Redis

**Windows:**
- Descargar desde: https://github.com/microsoftarchive/redis/releases
- O usar WSL2: `sudo apt-get install redis-server`

**macOS:**
```bash
brew install redis
brew services start redis
```

**Linux (Ubuntu/Debian):**
```bash
sudo apt-get update
sudo apt-get install redis-server
sudo systemctl start redis
sudo systemctl enable redis
```

#### 2. Verificar instalación

```bash
redis-cli ping
# Debe responder: PONG
```

#### 3. Instalar dependencias PHP

```bash
composer require predis/predis
```

#### 4. Configurar .env

```env
CACHE_DRIVER=redis
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Funcionamiento del Cache

- **Endpoint optimizado:** `GET /api/reports/survey/{id}`
- **Duración del cache:** 1 hora (3600 segundos)
- **Clave de cache:** `survey_report_{survey_id}`
- **Invalidación automática:** Se limpia cuando alguien responde la encuesta
- **Limpieza manual:** `DELETE /api/reports/survey/{id}/cache`

### Beneficios de Performance

| Sin Cache | Con Cache | Mejora |
|-----------|-----------|--------|
| 200-500ms | 5-15ms | **40x más rápido** ⚡ |

### Verificar Cache en Redis

```bash
redis-cli
> KEYS *
1) "laravel_cache:survey_report_1"
2) "laravel_cache:survey_report_2"

> GET "laravel_cache:survey_report_1"
# Muestra el JSON cacheado

> TTL "laravel_cache:survey_report_1"
# Muestra segundos restantes antes de expirar
```

### Service Class (Arquitectura Limpia)

Se implementó `ReportService` para:
- Separar lógica de negocio del controlador
- Facilitar testing unitario
- Reutilización de código
- Mejor mantenibilidad

---

## 🧪 Testing con Postman

### Importar Colección

1. Abre Postman
2. Importa el archivo: `Plugthem_Survey_API.postman_collection.json`
3. Configura el environment con las variables:
   - `base_url`: `http://localhost:8000/api`
   - `token`: (se guarda automáticamente al hacer login)

### Flujo de Prueba Completo

1. **Registrar usuario** → Guarda el token automáticamente
2. **Crear encuesta** → Nota el `survey_id`
3. **Agregar 3-5 preguntas** con diferentes tipos
4. **Login con otro usuario** (usa los seeders)
5. **Responder la encuesta**
6. **Ver reporte** → Primera vez: lento (genera cache)
7. **Ver reporte nuevamente** → Segunda vez: instantáneo (desde cache)
8. **Responder con otro usuario** → Cache se invalida
9. **Ver reporte** → Se regenera con nuevos datos

---

## 🗂️ Estructura del Proyecto

```
laravel-survey-api/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── DeactivateInactiveSurveys.php
│   ├── Events/
│   │   └── SurveyAnswered.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── AuthController.php
│   │   │       ├── SurveyController.php
│   │   │       ├── QuestionController.php
│   │   │       ├── AnswerController.php
│   │   │       └── ReportController.php
│   │   └── Requests/
│   │       ├── StoreSurveyRequest.php
│   │       ├── UpdateSurveyRequest.php
│   │       ├── StoreQuestionRequest.php
│   │       ├── UpdateQuestionRequest.php
│   │       └── StoreAnswerRequest.php
│   ├── Listeners/
│   │   └── SendSurveyAnsweredNotification.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Survey.php
│   │   ├── Question.php
│   │   └── Answer.php
│   ├── Policies/
│   │   └── SurveyPolicy.php
│   ├── Services/
│   │   └── ReportService.php (Bonus)
│   └── Traits/
│       └── ApiResponseTrait.php
├── database/
│   ├── migrations/
│   │   ├── xxxx_create_surveys_table.php
│   │   ├── xxxx_create_questions_table.php
│   │   └── xxxx_create_answers_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       ├── SurveySeeder.php
│       ├── QuestionSeeder.php
│       └── AnswerSeeder.php
├── routes/
│   └── api.php
├── .env.example
├── composer.json
├── Plugthem_Survey_API.postman_collection.json
└── README.md
```

---

## 👥 Usuarios de Prueba (Seeders)

La base de datos viene con usuarios de ejemplo:

| Email | Password | Encuestas |
|-------|----------|-----------|
| admin@plugthem.test | password123 | 2-3 |
| juan@test.com | password123 | 2-3 |
| maria@test.com | password123 | 2-3 |
| carlos@test.com | password123 | 2-3 |
| ana@test.com | password123 | 2-3 |

**Total de datos de prueba:**
- 5 usuarios
- ~12 encuestas
- ~60 preguntas
- ~150 respuestas

---

## 🔍 Comandos Útiles

### Ver todas las rutas
```bash
php artisan route:list
```

### Ver logs en tiempo real
```bash
tail -f storage/logs/laravel.log
```

### Limpiar cache
```bash
php artisan cache:clear
php artisan config:clear
```

### Refrescar base de datos
```bash
php artisan migrate:fresh --seed
```

### Ejecutar comando personalizado
```bash
php artisan app:survey-deactivate-inactive
```

---

## 🏗️ Arquitectura y Patrones

### Patrones Implementados

- **Repository Pattern:** Service classes para lógica de negocio
- **Policy Pattern:** Autorización basada en políticas
- **Trait Pattern:** Reutilización de código (ApiResponseTrait)
- **Observer Pattern:** Eventos y Listeners
- **Dependency Injection:** Controllers y Services
- **Request Validation:** FormRequests dedicados
- **Cache Strategy:** Cache-aside pattern con Redis

### Buenas Prácticas

✅ **Separación de responsabilidades**
- Controllers delgados
- Validaciones en FormRequests
- Lógica de negocio en Services
- Autorización en Policies

✅ **Código limpio**
- Nombres descriptivos
- Comentarios donde es necesario
- PSR-12 code style
- DRY (Don't Repeat Yourself)

✅ **Performance**
- Eager Loading (`with()`) para evitar N+1
- Índices en foreign keys
- Cache en reportes pesados
- Transacciones para integridad

✅ **Seguridad**
- Autenticación con Sanctum
- Validaciones estrictas
- Políticas de autorización
- Hash de contraseñas
- CSRF protection

---

## 🚨 Solución de Problemas

### Error: "Unauthenticated"
- Verifica que el token esté en el header: `Authorization: Bearer {token}`
- Asegúrate de que el token no haya expirado

### Error: "SQLSTATE[42S02]: Base table or view not found"
- Ejecuta: `php artisan migrate`

### Error: "Redis connection refused"
- Verifica que Redis esté corriendo: `redis-cli ping`
- Revisa el puerto en `.env`: `REDIS_PORT=6379`

### Error: "Class 'Predis\Client' not found"
- Instala la dependencia: `composer require predis/predis`

### El comando no se encuentra
- Lista comandos: `php artisan list`
- Verifica el prefijo: `php artisan app:survey-deactivate-inactive`

---

## 📄 Licencia

Este proyecto fue desarrollado como prueba técnica para **Plugthem**.

---

## 🙏 Agradecimientos

Gracias a Plugthem por la oportunidad de demostrar mis habilidades técnicas y pasión por el desarrollo backend con Laravel.

---

<div align="center">

**Desarrollado con ❤️ usando Laravel 11**

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Redis](https://img.shields.io/badge/Redis-6.0-DC382D?style=for-the-badge&logo=redis&logoColor=white)

</div>
