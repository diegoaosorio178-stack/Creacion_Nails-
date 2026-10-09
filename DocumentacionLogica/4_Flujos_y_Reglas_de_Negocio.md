# Flujos Críticos y Reglas de Negocio

## Flujo de Agendamiento
1. Cliente navega a `index.php?url=cliente/agendar`.
2. Completa formulario (selecciona servicio, adjunta captura bancaria).
3. `CitaController` verifica formato/peso de captura y la guarda físicamente.
4. `CitaModel` inicia Transacción.
5. `CitaModel` inserta Cita (estado = 'pendiente').
6. `CitaModel` inserta Pago (estado = 'pendiente_validacion').
7. Transacción Commit.
8. La cita es visible solo en "Auditoría de Citas" del Admin.

## Flujo de Liquidación de Comisiones
- **Regla:** El spa retiene el 50%, la manicurista gana el 50% de cada servicio *completado*.
- Solo se cuentan citas cuyo `estado` sea `'completada'`. (No se pagan citas confirmadas que no se realizaron ni rechazadas).
- El administrador saca cortes Quincenales. El sistema genera dinámicamente los cortes del 1-15 y del 16-30/31 basado en la función de fechas nativa de PHP.

## Flujo de Autenticación "Cascada"
Debido a que el sistema usa 3 tablas diferentes (`clientes`, `manicuristas`, `administradores`) pero un solo formulario de Login:
- El `AuthModel` recibe el correo y pregunta secuencialmente: ¿Eres Admin? -> No. ¿Eres Manicurista? -> No. ¿Eres Cliente? -> Sí.
- Si en cualquier paso la validación de `password_verify` es correcta, se le asigna un string literal de 'rol' y el controlador orquesta la redirección:
  - `administrador` -> `admin/dashboard`
  - `manicurista` -> `manicurista/dashboard`
  - `cliente` -> `cliente/dashboard`
