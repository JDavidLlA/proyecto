# Gestor - Proyecto Final INF560

Gestor es una aplicación web desarrollada con Laravel, Blade y PostgreSQL para la gestión colaborativa de proyectos, tareas y comentarios.

El sistema incluye autenticación manual, roles y permisos, administración de usuarios, CRUD de proyectos, CRUD de tareas, CRUD de comentarios, dashboard con estadísticas, diseño mejorado, búsqueda, filtros y paginación.

---

## Tecnologías utilizadas

* Laravel
* PHP
* Blade
* PostgreSQL
* Spatie Laravel Permission
* HTML
* CSS
* Git y GitHub

---

## Repositorio

Repositorio del proyecto:

```txt
https://github.com/JDavidLlA/proyecto.git
```

---

## Funcionalidades principales

* Login manual.
* Registro de usuarios.
* Logout.
* Dashboard privado.
* Middleware de autenticación.
* Roles y permisos con Spatie Laravel Permission.
* Policies para proyectos, tareas y comentarios.
* Administración de usuarios.
* CRUD de proyectos.
* CRUD de tareas relacionadas a proyectos.
* CRUD de comentarios relacionados a tareas.
* Dashboard con estadísticas reales.
* Diseño general mejorado con layout principal.
* Mensajes de éxito y error.
* Confirmación antes de eliminar.
* Búsqueda y filtros en proyectos.
* Búsqueda y filtros en tareas.
* Filtro por estado.
* Paginación.
* Control de acceso con `@can`, `@role` y Gates.

---

## Roles del sistema

El sistema trabaja con cuatro roles principales:

| Rol         | Descripción                                   |
| ----------- | --------------------------------------------- |
| admin       | Administrador general del sistema             |
| lider       | Líder de proyecto                             |
| colaborador | Usuario que participa en tareas y comentarios |
| invitado    | Usuario con permisos de visualización         |

---

## Permisos del sistema

| Permiso            | Descripción               |
| ------------------ | ------------------------- |
| users.view         | Ver usuarios              |
| users.update_roles | Cambiar roles de usuarios |
| projects.view      | Ver proyectos             |
| projects.create    | Crear proyectos           |
| projects.update    | Editar proyectos          |
| projects.delete    | Eliminar proyectos        |
| tasks.view         | Ver tareas                |
| tasks.create       | Crear tareas              |
| tasks.update       | Editar tareas             |
| tasks.delete       | Eliminar tareas           |
| comments.view      | Ver comentarios           |
| comments.create    | Crear comentarios         |
| comments.update    | Editar comentarios        |
| comments.delete    | Eliminar comentarios      |

---

## Permisos por rol

### Admin

El administrador tiene todos los permisos del sistema:

* Administración de usuarios.
* Gestión completa de proyectos.
* Gestión completa de tareas.
* Gestión completa de comentarios.

### Líder

El líder puede:

* Ver proyectos.
* Crear proyectos.
* Editar proyectos.
* Ver tareas.
* Crear tareas.
* Editar tareas.
* Eliminar tareas.
* Ver comentarios.
* Crear comentarios.
* Editar comentarios.
* Eliminar comentarios.

### Colaborador

El colaborador puede:

* Ver proyectos.
* Ver tareas.
* Editar tareas.
* Ver comentarios.
* Crear comentarios.
* Editar comentarios.
* Eliminar comentarios.

### Invitado

El invitado puede:

* Ver proyectos.
* Ver tareas.
* Ver comentarios.

---

## Usuarios de prueba

Los usuarios base se crean mediante seeders.

| Rol         | Correo                                                  | Contraseña |
| ----------- | ------------------------------------------------------- | ---------- |
| admin       | [admin@gestor.com](mailto:admin@gestor.com)             | password   |
| lider       | [lider@gestor.com](mailto:lider@gestor.com)             | password   |
| colaborador | [colaborador@gestor.com](mailto:colaborador@gestor.com) | password   |
| invitado    | [invitado@gestor.com](mailto:invitado@gestor.com)       | password   |

---

## Instalación del proyecto

### 1. Clonar el repositorio

```bash
git clone https://github.com/JDavidLlA/proyecto.git
```

Entrar a la carpeta del proyecto:

```bash
cd proyecto
```

---

### 2. Instalar dependencias de PHP

```bash
composer install
```

---

### 3. Instalar dependencias de Node

```bash
npm install
```

---

### 4. Copiar archivo de entorno

```bash
copy .env.example .env
```

En Linux o Mac:

```bash
cp .env.example .env
```

---

### 5. Generar clave de aplicación

```bash
php artisan key:generate
```

---

### 6. Configurar PostgreSQL en `.env`

Editar el archivo `.env` y configurar la conexión a PostgreSQL.

Ejemplo:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=gestor
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña
```

---

### 7. Ejecutar migraciones y seeders

Este comando crea las tablas y carga usuarios, roles, permisos, proyectos, tareas y comentarios de prueba.

```bash
php artisan migrate:fresh --seed
```

---

### 8. Limpiar caché

```bash
php artisan optimize:clear
```

---

### 9. Levantar el servidor

```bash
php artisan serve
```

Luego abrir en el navegador:

```txt
http://127.0.0.1:8000
```

---

## Rutas principales

| Ruta                               | Descripción                |
| ---------------------------------- | -------------------------- |
| `/login`                           | Inicio de sesión           |
| `/register`                        | Registro de usuario        |
| `/dashboard`                       | Dashboard privado          |
| `/projects`                        | Listado de proyectos       |
| `/projects/create`                 | Crear proyecto             |
| `/projects/{project}`              | Ver proyecto               |
| `/projects/{project}/edit`         | Editar proyecto            |
| `/projects/{project}/tasks`        | Tareas de un proyecto      |
| `/projects/{project}/tasks/create` | Crear tarea                |
| `/projects/{project}/tasks/{task}` | Ver tarea                  |
| `/admin/usuarios`                  | Administración de usuarios |

---

## Búsqueda y filtros

### Proyectos

La búsqueda de proyectos permite filtrar por nombre o descripción.

Ejemplo:

```txt
/projects?buscar=sistema
```

También permite filtrar por estado.

Ejemplo:

```txt
/projects?estado=en_proceso
```

También se pueden combinar ambos filtros.

```txt
/projects?buscar=sistema&estado=en_proceso
```

### Tareas

La búsqueda de tareas permite filtrar por título o descripción.

Ejemplo:

```txt
/projects/1/tasks?buscar=base
```

También permite filtrar por estado.

```txt
/projects/1/tasks?estado=pendiente
```

También se pueden combinar ambos filtros.

```txt
/projects/1/tasks?buscar=base&estado=pendiente
```

---

## Estados utilizados

### Proyectos

* pendiente
* en_proceso
* completado
* cancelado

### Tareas

* pendiente
* en_proceso
* completada
* cancelada

---

## Fases del proyecto

| Fase    | Versión | Descripción                                                              |
| ------- | ------- | ------------------------------------------------------------------------ |
| Fase 1  | v0.1    | Migraciones, modelos, relaciones, factories, seeders y PostgreSQL        |
| Fase 2  | v0.2    | Login manual, registro, logout, dashboard privado y middleware auth      |
| Fase 3  | v0.3    | Roles, permisos, policies, administración de usuarios y directivas Blade |
| Fase 4  | v0.4    | CRUD de proyectos con Blade                                              |
| Fase 5  | v0.5    | CRUD de tareas relacionadas a proyectos                                  |
| Fase 6  | v0.6    | CRUD de comentarios relacionados a tareas                                |
| Fase 7  | v0.7    | Dashboard mejorado con estadísticas reales                               |
| Fase 8  | v0.8    | Diseño general mejorado                                                  |
| Fase 9  | v0.9    | Búsqueda, filtros y paginación                                           |
| Fase 10 | v1.0    | Documentación, pruebas finales y cierre del proyecto                     |

---

## Pruebas recomendadas desde navegador

### Autenticación

* Verificar login con cada usuario base.
* Verificar registro de usuario.
* Verificar logout.
* Verificar que `/dashboard` no sea accesible sin iniciar sesión.

### Roles y permisos

* Verificar que admin pueda administrar usuarios.
* Verificar que lider pueda crear y editar proyectos.
* Verificar que colaborador no tenga permisos de administración.
* Verificar que invitado solo pueda visualizar lo permitido.
* Verificar que aparezcan u oculten botones según `@can` y `@role`.

### Proyectos

* Crear proyecto.
* Ver proyecto.
* Editar proyecto.
* Eliminar proyecto.
* Buscar proyecto.
* Filtrar proyecto por estado.
* Probar paginación.

### Tareas

* Crear tarea dentro de un proyecto.
* Ver tarea.
* Editar tarea.
* Cambiar estado de tarea.
* Eliminar tarea.
* Buscar tarea.
* Filtrar tarea por estado.
* Probar paginación.

### Comentarios

* Crear comentario en una tarea.
* Ver comentarios.
* Editar comentario.
* Eliminar comentario.
* Verificar permisos por rol.

---

## Comandos útiles

Limpiar caché:

```bash
php artisan optimize:clear
```

Ver rutas:

```bash
php artisan route:list
```

Ejecutar migraciones y seeders desde cero:

```bash
php artisan migrate:fresh --seed
```

Levantar servidor:

```bash
php artisan serve
```

Compilar assets:

```bash
npm run dev
```

---

## Git y versiones

Ver estado de cambios:

```bash
git status
```

Agregar cambios:

```bash
git add .
```

Crear commit final:

```bash
git commit -m "Fase 10 v1.0 documentacion y cierre final"
```

Crear tag final:

```bash
git tag v1.0
```

Subir cambios:

```bash
git push origin main
```

Subir tag:

```bash
git push origin v1.0
```

Si la rama principal se llama `master`, usar:

```bash
git push origin master
```
