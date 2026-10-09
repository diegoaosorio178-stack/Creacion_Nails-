<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebaradmin.php'; ?>

<main class="ml-64 flex-1 bg-marfil min-h-screen flex flex-col">
    <header class="bg-white shadow-sm px-8 py-5 flex justify-between items-center border-b border-rosa-light">
        <h1 class="text-2xl font-heading font-bold text-grafito">Auditar Citas (HU-012)</h1>
    </header>

    <div class="p-8 flex-1">
        
        <div class="bg-white rounded-xl shadow-sm border border-rosa-light overflow-hidden">
            <div class="px-6 py-5 border-b border-rosa-light">
                <h2 class="text-xl font-heading font-bold text-dorado-dark">Solicitudes Pendientes de Validación</h2>
                <p class="text-sm text-grafito-light mt-1">Verifica los comprobantes de pago (abonos) antes de aprobar las citas.</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-marfil text-grafito-light text-sm uppercase tracking-wide">
                            <th class="px-6 py-4 font-semibold">Fecha / Hora</th>
                            <th class="px-6 py-4 font-semibold">Clienta</th>
                            <th class="px-6 py-4 font-semibold">Servicio / Manicurista</th>
                            <th class="px-6 py-4 font-semibold">Abono</th>
                            <th class="px-6 py-4 font-semibold text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($pendientes)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 font-semibold">No hay solicitudes pendientes de validación.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pendientes as $cita): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-600"><?= date('d M Y', strtotime($cita['fecha_cita'])) ?></p>
                                        <p class="text-xs font-bold text-gray-400"><?= date('h:i A', strtotime($cita['hora_inicio'])) ?></p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-grafito"><?= htmlspecialchars($cita['nombre_cliente']) ?></p>
                                        <p class="text-xs text-gray-500"><?= htmlspecialchars($cita['telefono_cliente']) ?></p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-grafito-light"><?= htmlspecialchars($cita['nombre_servicio']) ?></p>
                                        <p class="text-sm text-gray-500">Con: <?= htmlspecialchars($cita['nombre_manicurista']) ?></p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-green-600">$ <?= number_format($cita['monto_abono'] ?? 0, 0, ',', '.') ?></p>
                                        <?php if (!empty($cita['comprobante_url'])): ?>
                                            <a href="<?= BASE_URL . htmlspecialchars($cita['comprobante_url']) ?>" target="_blank" class="text-dorado hover:text-dorado-dark underline text-xs font-semibold flex items-center gap-1 mt-1">
                                                <i data-lucide="image" class="w-3 h-3"></i> Ver Recibo
                                            </a>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400">Sin recibo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="<?= BASE_URL ?>index.php?url=admin/aprobarCita" class="inline">
                                                <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">
                                                <button type="submit" class="bg-green-100 hover:bg-green-200 text-green-700 p-2 rounded-lg transition" title="Aprobar Pago y Cita">
                                                    <i data-lucide="check" class="w-5 h-5"></i>
                                                </button>
                                            </form>
                                            <button onclick="document.getElementById('id_cita_rechazo').value = '<?= $cita['id_cita'] ?>'; document.getElementById('modalRechazo').classList.remove('hidden')" class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition" title="Rechazar y Notificar">
                                                <i data-lucide="x" class="w-5 h-5"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <!-- Removed endif and endforeach since this is static -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal Rechazo -->
    <div id="modalRechazo" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
            <h3 class="text-xl font-heading font-bold text-grafito mb-4">Rechazar Solicitud</h3>
            <p class="text-sm text-gray-600 mb-4">Ingresa el motivo del rechazo. Este mensaje será visible para la clienta.</p>
            
            <form method="POST" action="<?= BASE_URL ?>index.php?url=admin/rechazarCita">
                <input type="hidden" name="id_cita" id="id_cita_rechazo" value="">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-grafito mb-2">Motivo del rechazo *</label>
                    <textarea name="motivo_rechazo" rows="3" required class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-rosa" placeholder="Ej. El comprobante está borroso o el monto no coincide..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalRechazo').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">Confirmar Rechazo</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
