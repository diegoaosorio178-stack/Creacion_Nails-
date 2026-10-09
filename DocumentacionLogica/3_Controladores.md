# Documentación Lógica: Controladores

Los controladores filtran la solicitud, validan permisos, ejecutan acciones en el Modelo, y redirigen a la Vista correspondiente. Cada controlador suele tener un método `verificarAcceso('rol')` que restringe el acceso al área protegida.

## AuthController.php
- `login()`: Recibe credenciales, usa `AuthModel->loginCascada()`, y genera las variables de sesión `$_SESSION['user']`. Redirige al dashboard correcto según el rol.
- `logout()`: Destruye sesión y redirige al inicio de sesión.

## RegisterController.php
- Valida entradas del formulario (correo no vacío, contraseñas coinciden).
- Verifica usando `AuthModel->emailExiste()`.
- Genera al usuario usando `AuthModel->registrarCliente()`.

## CitaController.php (Cliente)
- `agendar()`: Lógica compleja.
  - Verifica que el archivo (comprobante) sea de formato correcto (JPG/PNG/PDF) y no exceda 5MB.
  - Sube el archivo a `public/uploads/comprobantes/`.
  - Si el cliente no elige manicurista explícitamente, llama a asignación automática.
  - Calcula la `hora_fin` usando `DateTime` sumando la duración del servicio a la `hora_inicio`.
  - Llama a `crearCitaConPago()`.

## AdminController.php
El controlador más masivo. Coordina la gerencia de la plataforma.
- `dashboard()`: Agrupa estadísticas (facturación del mes, citas de hoy).
- `auditarCitas()`: Carga vista con pagos y comprobantes por verificar.
- `aprobarCita()` / `rechazarCita()`: Cambia el estado de cita a 'confirmada' o 'rechazada'.
- `servicios()` y variaciones: CRUD del catálogo (cambios de precio, activar/desactivar).
- `manicuristas()`: Habilitar personal y asignar horarios laborales.
- `liquidacion()`: Genera reporte quincenal calculando rangos de fechas (días 1 al 15 vs 16 al último del mes usando `Y-m-t`).

## ManicuristaController.php
- `dashboard()`: Muestra citas futuras.
- `misComisiones()`: Filtra citas completadas.
- `finalizarServicio()`: Actualiza la cita a `completada` para gatillar el cálculo de comisiones.
