# Documentación Lógica: Modelos

Los modelos son el cerebro de los datos. Se conectan a la base de datos a través de `Database::getConnection()` en el constructor de cada clase.

## AuthModel.php
Encargado de la lógica de autenticación y registro.
- `emailExiste($email)`: Verifica unicidad del correo en tablas `clientes`, `manicuristas`, y `administradores`.
- `registrarCliente($datos)`: Hashea contraseña e inserta nuevo cliente.
- `loginCascada($email, $password)`: Patrón de búsqueda secuencial. Busca las credenciales primero en Administradores, luego en Manicuristas, y finalmente en Clientes. Devuelve el registro con un campo extra `'rol'`.

## CitaModel.php
Maneja el ciclo de vida central de las reservas.
- `crearCitaConPago($cita, $pago)`: Usa transacciones SQL (`beginTransaction`, `commit`, `rollBack`) para insertar simultáneamente la cita y el pago asociado.
- `obtenerPendientesAuditoria()`: Une `citas`, `clientes`, `servicios`, `manicuristas` y `pagos` para el panel de validación del administrador.
- `calcularComisionesManicurista()` / `reporteLiquidacionGeneral()`: Funciones agregadas (`SUM`, `COUNT`) para calcular el 50% de comisión basado en citas completadas.
- `guardarCalificacion()`: Inserta estrellas si el estado de la cita es `completada`.

## ManicuristaModel.php
Gestiona personal.
- `obtenerActivas()`: Filtra manicuristas con `estado_laboral = 'activo'`.
- `obtenerManicuristaMenorCarga($fecha)`: Algoritmo simple para asignación automática. Cuenta las citas por manicurista en una fecha y devuelve el ID de la que tenga menos.

## ServicioModel.php
Gestiona catálogo de servicios.
- `crear()`, `actualizar()`, `obtenerActivos()`, `toggleActivo()`: Funciones estándar de un CRUD.
