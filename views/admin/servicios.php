<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebaradmin.php'; ?>

<main class="ml-64 flex-1 bg-marfil min-h-screen flex flex-col">
    <header class="bg-white shadow-sm px-8 py-5 flex justify-between items-center border-b border-rosa-light">
        <h1 class="text-2xl font-heading font-bold text-grafito">Catálogo de Servicios (HU-010)</h1>
        <button onclick="document.getElementById('modalServicio').classList.remove('hidden')" class="bg-dorado hover:bg-dorado-dark text-white px-4 py-2 rounded-lg font-semibold flex items-center gap-2 transition shadow-sm">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Nuevo Servicio
        </button>
    </header>

    <div class="p-8 flex-1">
        
        <div class="bg-white rounded-xl shadow-sm border border-rosa-light overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-marfil text-grafito-light text-sm uppercase tracking-wide">
                            <th class="px-6 py-4 font-semibold">Servicio</th>
                            <th class="px-6 py-4 font-semibold">Descripción</th>
                            <th class="px-6 py-4 font-semibold text-center">Duración (min)</th>
                            <th class="px-6 py-4 font-semibold text-right">Precio COP</th>
                            <th class="px-6 py-4 font-semibold text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($servicios)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 font-semibold">No hay servicios registrados en el catálogo.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($servicios as $servicio): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-bold text-grafito"><?= htmlspecialchars($servicio['nombre_servicio']) ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate"><?= htmlspecialchars($servicio['descripcion'] ?? '') ?></td>
                                    <td class="px-6 py-4 text-center font-semibold text-gray-700"><?= htmlspecialchars($servicio['duracion_minutos']) ?></td>
                                    <td class="px-6 py-4 text-right font-bold text-dorado-dark flex items-center justify-end gap-2">
                                        $ <?= number_format($servicio['precio'], 0, ',', '.') ?>
                                        <button onclick="openEditPriceModal(<?= $servicio['id_servicio'] ?>, '<?= number_format($servicio['precio'], 0, ',', '.') ?>', '<?= htmlspecialchars($servicio['nombre_servicio'], ENT_QUOTES) ?>')" class="text-gray-400 hover:text-dorado transition" title="Editar Precio">
                                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form method="POST" action="<?= BASE_URL ?>index.php?url=admin/toggleServicio" class="inline">
                                            <input type="hidden" name="id_servicio" value="<?= $servicio['id_servicio'] ?>">
                                            <?php if ($servicio['activo'] == 1): ?>
                                                <input type="hidden" name="estado_nuevo" value="0">
                                                <button type="submit" class="px-3 py-1 rounded-full bg-green-100 text-green-800 hover:bg-green-200 text-xs font-bold border border-green-200 transition" title="Desactivar">ACTIVO</button>
                                            <?php else: ?>
                                                <input type="hidden" name="estado_nuevo" value="1">
                                                <button type="submit" class="px-3 py-1 rounded-full bg-red-100 text-red-800 hover:bg-red-200 text-xs font-bold border border-red-200 transition" title="Activar">INACTIVO</button>
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

    <!-- Modal Servicio -->
    <div id="modalServicio" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-xl p-6">
            <h3 class="text-xl font-heading font-bold text-grafito mb-4">Agregar Nuevo Servicio</h3>
            
            <form method="POST" action="<?= BASE_URL ?>index.php?url=admin/crearServicio">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-grafito mb-2">Nombre del Servicio *</label>
                    <input type="text" name="nombre_servicio" required class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-dorado" placeholder="Ej. Spa Tradicional">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-bold text-grafito mb-2">Descripción Corta</label>
                    <textarea name="descripcion" rows="2" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-dorado"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-bold text-grafito mb-2">Precio Total (COP) *</label>
                        <input type="text" name="precio" required class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-dorado" placeholder="Ej. 30.000 o 30000">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-grafito mb-2">Duración (Minutos) *</label>
                        <input type="number" name="duracion_minutos" required class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-dorado" placeholder="Ej. 60">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-grafito mb-2">URL de Imagen (Opcional)</label>
                    <input type="url" name="imagen_url" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-dorado" placeholder="https://...">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalServicio').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-dorado hover:bg-dorado-dark text-white rounded-lg font-semibold transition">Guardar Servicio</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Precio -->
    <div id="modalEditPrecio" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-6">
            <h3 class="text-xl font-heading font-bold text-grafito mb-2">Editar Precio</h3>
            <p class="text-sm text-gray-500 mb-4" id="edit_nombre_servicio"></p>
            
            <form method="POST" action="<?= BASE_URL ?>index.php?url=admin/actualizarPrecioServicio">
                <input type="hidden" name="id_servicio" id="edit_id_servicio">
                <div class="mb-6">
                    <label class="block text-sm font-bold text-grafito mb-2">Nuevo Precio (COP) *</label>
                    <input type="text" name="precio_nuevo" id="edit_precio_nuevo" required class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-dorado" placeholder="Ej. 30.000">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalEditPrecio').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-dorado hover:bg-dorado-dark text-white rounded-lg font-semibold transition">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
function openEditPriceModal(id, currentPrice, name) {
    document.getElementById('edit_id_servicio').value = id;
    document.getElementById('edit_precio_nuevo').value = currentPrice;
    document.getElementById('edit_nombre_servicio').innerText = name;
    document.getElementById('modalEditPrecio').classList.remove('hidden');
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
