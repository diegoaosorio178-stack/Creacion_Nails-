<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebaradmin.php'; ?>

<main class="ml-64 flex-1 bg-marfil min-h-screen flex flex-col">
    <header class="bg-white shadow-sm px-8 py-5 flex justify-between items-center border-b border-rosa-light">
        <h1 class="text-2xl font-heading font-bold text-grafito">Panel Principal</h1>
        <div class="flex items-center gap-4">
            <span class="font-semibold text-grafito flex items-center gap-2">
                <i data-lucide="user-check" class="w-5 h-5 text-dorado"></i>
                Admin General
            </span>
        </div>
    </header>

    <div class="p-8 flex-1">
        <!-- 4 Tarjetas de Métricas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <!-- Card 1 -->
            <div class="bg-white rounded-xl p-6 border border-dorado-dark/20 shadow-sm border-l-4 border-l-rosa flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-grafito-light uppercase tracking-wide">Citas por Auditar</p>
                    <p class="text-3xl font-heading font-bold text-grafito mt-2"><?= htmlspecialchars($pendientesCount ?? 0) ?></p>
                </div>
                <div class="bg-rosa-light text-rosa-dark p-3 rounded-full">
                    <i data-lucide="bell" class="w-6 h-6"></i>
                </div>
            </div>
            
            <!-- Card 2 -->
            <div class="bg-white rounded-xl p-6 border border-dorado-dark/20 shadow-sm border-l-4 border-l-dorado flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-grafito-light uppercase tracking-wide">Citas Hoy</p>
                    <p class="text-3xl font-heading font-bold text-grafito mt-2"><?= htmlspecialchars($citasHoyCount ?? 0) ?></p>
                </div>
                <div class="bg-dorado/20 text-dorado-dark p-3 rounded-full">
                    <i data-lucide="calendar" class="w-6 h-6"></i>
                </div>
            </div>
            
            <!-- Card 3 -->
            <div class="bg-white rounded-xl p-6 border border-dorado-dark/20 shadow-sm border-l-4 border-l-grafito flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-grafito-light uppercase tracking-wide">Manicuristas Activas</p>
                    <p class="text-3xl font-heading font-bold text-grafito mt-2"><?= htmlspecialchars($manicuristasCount ?? 0) ?></p>
                </div>
                <div class="bg-gray-100 text-grafito p-3 rounded-full">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
            </div>
            
            <!-- Card 4 -->
            <div class="bg-white rounded-xl p-6 border border-dorado-dark/20 shadow-sm border-l-4 border-l-dorado-dark flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-grafito-light uppercase tracking-wide">Facturado (Mes)</p>
                    <p class="text-3xl font-heading font-bold text-grafito mt-2">$<?= number_format($facturadoMes ?? 0, 0, ',', '.') ?></p>
                </div>
                <div class="bg-green-100 text-green-700 p-3 rounded-full">
                    <i data-lucide="trending-up" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <!-- Tabla Últimas 5 citas -->
        <div class="bg-white rounded-xl shadow-sm border border-rosa-light overflow-hidden">
            <div class="px-6 py-5 border-b border-rosa-light flex justify-between items-center">
                <h2 class="text-xl font-heading font-bold text-dorado-dark">Últimas Solicitudes Recibidas</h2>
                <a href="<?= BASE_URL ?>index.php?url=admin/auditar_citas" class="text-sm font-semibold text-dorado hover:text-dorado-dark transition">Ver Todas &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-marfil text-grafito-light text-sm uppercase tracking-wide">
                            <th class="px-6 py-4 font-semibold">Clienta</th>
                            <th class="px-6 py-4 font-semibold">Servicio</th>
                            <th class="px-6 py-4 font-semibold">Fecha / Hora</th>
                            <th class="px-6 py-4 font-semibold">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($ultimasCitas)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 font-semibold">No hay solicitudes recientes.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ultimasCitas as $cita): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-semibold text-grafito"><?= htmlspecialchars($cita['nombre_cliente']) ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($cita['nombre_servicio']) ?></td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <?= date('d M Y', strtotime($cita['fecha_cita'])) ?> - 
                                        <?= date('h:i A', strtotime($cita['hora_inicio'])) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($cita['estado'] === 'pendiente'): ?>
                                            <div class="flex items-center gap-2">
                                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-bold uppercase hidden md:inline-block mr-2">Pendiente</span>
                                                <form method="POST" action="<?= BASE_URL ?>index.php?url=admin/aprobarCita" class="inline">
                                                    <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">
                                                    <button type="submit" class="bg-green-100 hover:bg-green-200 text-green-700 p-1.5 rounded-lg transition" title="Aprobar Pago y Cita">
                                                        <i data-lucide="check" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                                <button onclick="document.getElementById('id_cita_rechazo').value = '<?= $cita['id_cita'] ?>'; document.getElementById('modalRechazo').classList.remove('hidden')" class="bg-red-100 hover:bg-red-200 text-red-700 p-1.5 rounded-lg transition" title="Rechazar y Notificar">
                                                    <i data-lucide="x" class="w-4 h-4"></i>
                                                </button>
                                            </div>
                                        <?php elseif ($cita['estado'] === 'confirmada'): ?>
                                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-bold uppercase">Confirmada</span>
                                        <?php elseif ($cita['estado'] === 'rechazada'): ?>
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-bold uppercase">Rechazada</span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-bold uppercase"><?= htmlspecialchars($cita['estado']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
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
