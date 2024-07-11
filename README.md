```bash
X   X  DDDD  I  RRRR
 X X   D   D I  R   R
  X    D   D I  RRRR
 X X   D   D I  R R
X   X  DDDD  I  R  RR
```

# xdir-back-v2
Backend de administración y registro de Usuarios y Roles

# Configuración inicial de xdir-back-v2

Este documento proporciona una guía paso a paso para configurar el entorno de desarrollo del backend de administración y registro de Usuarios y Roles, `xdir-back-v2`. Sigue cuidadosamente las instrucciones para asegurar una correcta configuración del proyecto.
PHP8.2

## Requisitos previos

Antes de comenzar, asegúrate de tener instalado Git y Composer en tu sistema. Estas herramientas son esenciales para clonar el repositorio y gestionar las dependencias de PHP del proyecto.

## Clonar el repositorio

Para obtener el código fuente del proyecto, ejecuta el siguiente comando en tu terminal:
```bash
git clone git@github.com:XIMDEX/xdir-back-v2.git
```

Este comando clona el repositorio en una nueva carpeta llamada xdir-back-v2 en tu directorio actual.
Luego es necesario hacer un cd xdir-back-v2 para acceder al directorio. 

## Cambiar a la rama de desarrollo
Para trabajar con la versión de desarrollo más reciente, cambia a la rama develop:
```bash
git checkout develop
```

## Instalar las dependecias 
```bash
composer install
```
Este comando lee el archivo composer.json, descarga las dependencias requeridas y las instala en el directorio vendor.

## ENV

Copia el archivo .env.example para crear tu .env
```bash
cp .env.example .env
```
Y configurar las variables de entorno, sobretodo las de database. 
Para que todos estos nuevos datos sean cargados es necesario ejecutar el siguiente comando: 
```bash
php artisan optimize
```
Y tras ello podemos verificar las rutas para ver que todo esta correcto:
```bash
php artisan route:list
```
## Migrate
Es necesario hacer el migrate:
```bash
php artisan migrate
```
Ahora probar la ruta de /register, podemos usar los siguientes datos: 
```bash
{
  "email": "testXdir@ximdex.com",
  "password": "test123456",
  "name": "test",
  "surname": "text para surname",
  "birthdate": "2020-10-10"
}
```
En la respuesta tendremos el token que nos servira para no tener que registar un mail real(Recuerda tener la app en modo debug, en el env, para obtener esta respuesta)
## Keys
```bash
php artisan key:generate
```
Tambien hay que generar las keys para passport 
```bash
php artisan passport:keys
```
Y asegurarnos de que tengan los permisos correctamente
```bash
sudo chown www-data:www-data storage/oauth-public.key storage/oauth-private.key
```
Nos quedaria general la clave 
```bash
php artisan passport:client --personal.
```
Al final recibiras dos variables que habra que guardar en el .env junto al nombre que se le haya asignado 
```bash
PASSPORT_PERSONAL_ACCESS_CLIENT_ID="tu_id_de_cliente"
PASSPORT_SECRET="tu_secreto_cliente"
PASSPORT_TOKEN_NAME="nombre_del_cliente"
```
Y acabamos con un php artisan optimize

## Verificación de Email y Login

Verificar el email: Para simular la verificación de email, accede a la ruta /email/verify/{token}, donde {token} es el token de verificación que obtuviste previamente.

Probar el login: Una vez verificado el email, puedes proceder a probar el login mediante la ruta /login utilizando herramientas como Postman o cURL, proporcionando las credenciales de usuario (email y contraseña).