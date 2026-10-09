<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebaradmin.php'; ?>

<main class="ml-64 flex-1 bg-marfil min-h-screen flex flex-col">
    <header class="bg-white shadow-sm px-8 py-5 flex justify-between items-center border-b border-rosa-light">
        <h1 class="text-2xl font-heading font-bold text-grafito">Liquidación de Comisiones 50/50 (HU-013)</h1>
        
        <form method="GET" action="<?= BASE_URL ?>index.php" class="flex gap-2 items-center">
            <input type="hidden" name="url" value="admin/liquidacion">
            <select name="quincena" class="border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-dorado text-sm font-semibold text-grafito bg-white">
                <option value="1" <?= $quincena === 1 ? 'selected' : '' ?>>1ra Quincena <?= $nombreMes . ' ' . $anioActual ?></option>
                <option value="2" <?= $quincena === 2 ? 'selected' : '' ?>>2da Quincena <?= $nombreMes . ' ' . $anioActual ?></option>
            </select>
            <button type="submit" class="bg-grafito hover:bg-grafito-light text-white px-3 py-2 rounded-lg font-semibold text-sm transition">
                Filtrar
            </button>
        </form>
    </header>

    <div class="p-8 flex-1">
        
        <div class="bg-white rounded-xl shadow-sm border border-rosa-light overflow-hidden">
            <div class="px-6 py-5 border-b border-rosa-light flex justify-between items-center">
                <h2 class="text-xl font-heading font-bold text-dorado-dark">Reporte de Dispersión</h2>
                <button class="flex items-center gap-2 text-sm font-bold text-dorado hover:text-dorado-dark transition">
                    <i data-lucide="download" class="w-4 h-4"></i> Exportar Reporte
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-marfil text-grafito-light text-sm uppercase tracking-wide">
                            <th class="px-6 py-4 font-semibold">Manicurista</th>
                            <th class="px-6 py-4 font-semibold text-center">Citas Completadas</th>
                            <th class="px-6 py-4 font-semibold text-right">Facturado ($)</th>
                            <th class="px-6 py-4 font-semibold text-right">Comisión Manicurista (50%)</th>
                            <th class="px-6 py-4 font-semibold text-right">Retención Spa (50%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php 
                        $total_citas = 0;
                        $total_facturado = 0;
                        $total_comision = 0;
                        $total_retencion = 0;
                        ?>

                        <?php if (empty($reporte)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 font-semibold">No hay citas completadas en el periodo seleccionado.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reporte as $fila): ?>
                                <?php 
                                $total_citas += $fila['citas_completadas'];
                                $total_facturado += $fila['facturado'];
                                $total_comision += $fila['pago_manicurista'];
                                $total_retencion += $fila['retencion_spa'];
                                ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-bold text-grafito"><?= htmlspecialchars($fila['manicurista']) ?></td>
                                    <td class="px-6 py-4 text-center font-semibold text-gray-700"><?= htmlspecialchars($fila['citas_completadas']) ?></td>
                                    <td class="px-6 py-4 text-right text-gray-500">$ <?= number_format($fila['facturado'], 0, ',', '.') ?></td>
                                    <td class="px-6 py-4 text-right font-bold text-green-700 bg-green-50">$ <?= number_format($fila['pago_manicurista'], 0, ',', '.') ?></td>
                                    <td class="px-6 py-4 text-right font-bold text-dorado-dark bg-yellow-50">$ <?= number_format($fila['retencion_spa'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <!-- Totales -->
                        <tr class="bg-grafito text-white">
                            <td class="px-6 py-4 font-bold">TOTALES</td>
                            <td class="px-6 py-4 text-center font-bold"><?= $total_citas ?></td>
                            <td class="px-6 py-4 text-right font-bold">$ <?= number_format($total_facturado, 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-right font-bold text-green-300">$ <?= number_format($total_comision, 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-right font-bold text-dorado">$ <?= number_format($total_retencion, 0, ',', '.') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
