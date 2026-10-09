<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebaradmin.php'; ?>

<main class="ml-64 flex-1 bg-marfil min-h-screen flex flex-col">
    <header class="bg-white shadow-sm px-8 py-5 flex justify-between items-center border-b border-rosa-light">
        <h1 class="text-2xl font-heading font-bold text-grafito">Gestión de Personal & Horarios (HU-011)</h1>
        <button onclick="document.getElementById('modalHorario').classList.remove('hidden')" class="bg-dorado hover:bg-dorado-dark text-white px-4 py-2 rounded-lg font-semibold flex items-center gap-2 transition shadow-sm">
            <i data-lucide="clock" class="w-5 h-5"></i>
            Asignar Horario
        </button>
    </header>

    <div class="p-8 flex-1">
        
        <div class="bg-white rounded-xl shadow-sm border border-rosa-light overflow-hidden">
            <div class="px-6 py-5 border-b border-rosa-light">
                <h2 class="text-xl font-heading font-bold text-dorado-dark">Nómina de Manicuristas</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-marfil text-grafito-light text-sm uppercase tracking-wide">
                            <th class="px-6 py-4 font-semibold">Manicurista</th>
                            <th class="px-6 py-4 font-semibold">Contacto</th>
                            <th class="px-6 py-4 font-semibold">Estado Laboral</th>
                            <th class="px-6 py-4 font-semibold text-center">Horario Asignado</th>
                            <th class="px-6 py-4 font-semibold text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($manicuristas)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 font-semibold">No hay personal registrado.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($manicuristas as $manicurista): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-rosa-light text-rosa-dark flex items-center justify-center font-bold"><?= strtoupper(substr($manicurista['nombre'], 0, 1)) ?></div>
                                        <p class="font-bold text-grafito"><?= htmlspecialchars($manicurista['nombre']) ?></p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-grafito"><?= htmlspecialchars($manicurista['email']) ?></p>
                                        <p class="text-xs text-gray-500"><?= htmlspecialchars($manicurista['telefono'] ?? 'Sin teléfono') ?></p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($manicurista['estado_laboral'] === 'activa'): ?>
                                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-bold border border-green-200">ACTIVA</span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-bold border border-red-200">INACTIVA</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-sm text-gray-600 font-semibold bg-gray-100 px-3 py-1 rounded">Asignado</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form method="POST" action="<?= BASE_URL ?>index.php?url=admin/toggleManicurista">
                                            <input type="hidden" name="id_manicurista" value="<?= $manicurista['id_manicurista'] ?>">
                                            <?php if ($manicurista['estado_laboral'] === 'activa'): ?>
                                                <input type="hidden" name="estado_nuevo" value="inactiva">
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold transition flex items-center justify-end gap-1 w-full">
                                                    <i data-lucide="power" class="w-4 h-4"></i> Desactivar
                                                </button>
                                            <?php else: ?>
                                                <input type="hidden" name="estado_nuevo" value="activa">
                                                <button type="submit" class="text-green-500 hover:text-green-700 text-sm font-bold transition flex items-center justify-end gap-1 w-full">
                                                    <i data-lucide="power" class="w-4 h-4"></i> Activar
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal Horario -->
    <div id="modalHorario" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6">
            <h3 class="text-xl font-heading font-bold text-grafito mb-4">Asignar Bloque Horario</h3>
            
            <form method="POST" action="<?= BASE_URL ?>index.php?url=admin/gusuarios/asignar_horario">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-grafito mb-2">Manicurista *</label>
                    <select name="id_manicurista" required class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-dorado bg-white">
                        <option value="">Seleccione una colaboradora...</option>
                        <?php if(!empty($manicuristas)): ?>
                            <?php foreach ($manicuristas as $m): ?>
                                <option value="<?= $m['id_manicurista'] ?>"><?= htmlspecialchars($m['nombre']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-bold text-grafito mb-2">Día de la semana *</label>
                    <select name="dia_semana" required class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-dorado bg-white">
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miercoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                        <option value="Sabado">Sábado</option>
                        <option value="Domingo">Domingo</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-grafito mb-2">Hora Entrada *</label>
                        <input type="time" name="hora_inicio" required class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-dorado">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-grafito mb-2">Hora Salida *</label>
                        <input type="time" name="hora_fin" required class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-dorado">
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalHorario').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-dorado hover:bg-dorado-dark text-white rounded-lg font-semibold transition">Guardar Horario</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
