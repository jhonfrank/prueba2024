# API RESTful

## Requerimientos
1. PHP 8.2
2. MySQL 8.0
3. Docker
4. Composer

## Ejecución
1. Descargar el proyecto: `https://github.com/jhonfrank/prueba2024.gite`
2. Ejecutar compose: `composer install`
3. Ejecutar docker: `docker-compose up -d`

## Estructura de ficheros
- **app:** Contiene la aplicación que responde a las request HTTP.
- **docker:** Contiene la configuración de contenedores para php, apache y mysql.
- **files:** Contiene los archivos para poder ejecutar los endpoints de la API en postman.
- **public:** Carpeta publica, contiene el archivo index.php.
- **src:** Contiene la logica del negocio utilizando DDD.
- **test:** Contiene los test unitarios de la logica de negocios.

Dentro de la carpeta **src** se implemento la siguiente estructura:
```scala
src
|-- User
|   |-- Application
|   |-- Domain
|   |   |-- ValueObject // Contiene todos los VO de user
|   |   |-- Interfaces
|   |   `-- User.php // Entidad
|   `-- Infrastructure
|-- Transaction
|   |-- Application
|   |-- Domain
|   |   |-- ValueObject // Contiene todos los VO de transaction
|   |   |-- Interfaces
|   |   `-- Transaction.php // Entidad
|   `-- Infrastructure
`-- Shared
    |-- Domain // Contiene VO primitivos y clases de excepciones para el manejo de errores.
    `-- Infrastructure // Contiene el helper de conexión a BD.
```

## Docker
- **MYSQL:** Se ejecuta en el puerto 1420. La configuración del acceso se encuentra en el archivo .env.
- **PHP y APACHE:** Se ejecuta en el puerto 1410.

## API
Puede revisar la carpeta **files** donde se tiene las plantillas de las colleciones para usarlo en postman.

### USER
* Mostrar todos los usuarios : `GET /api/v1/users`
* Mostrar un usuario por id : `GET /api/v1/users/:id`
* Crear un usuario : `POST /api/v1/users`
    ```scala
    Body request example
    {
        "fullName": "Juan Perez",
        "documentNumber": "25478689",
        "email": "juan@dominio.com",
        "password": "password",
        "isMerchant": false,
        "walletAmount": 100
    }
    ```
    **"documentNumber"** y **"email"** deben ser unicos, sino se obtiene una respuesta BadRequest.
* Mostrar las transacciones de un usuario : `GET /api/v1/users/:id/transactions`
### TRANSACTIONS
* Mostrar todas las transaciones : `GET /api/v1/transactions`
* Mostrar una transacción por id : `GET /api/v1/transactions/:id`
* Crear transacción : `POST /api/v1/transaction`
    ```scala
    Body request example
    {
        "amount": 3,
        "payerUserId": 1,
        "payeeUserId": 3
    }
    ```
    En este proceso se realiza lo siguiente:
    * Se valida el saldo del usuario que envía el pago.
    * Se registra el movimiento.
    * Se realiza el ajuste de los saldos de las billeteres de los usuarios.
    * Se valida la autorización del pago con un servicio externo.
    (url: https://run.mocky.io/v3/1f94933c-353c-4ad1-a6a5-a1a5ce2a7abe)
    * Se envía una alerta al usuario usando un servicio de notificación.
    (url: https://run.mocky.io/v3/6839223e-cd6c-4615-817a-60e06d2b9c82)