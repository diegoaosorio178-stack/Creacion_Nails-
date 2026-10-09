<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen bg-marfil flex flex-col">
    <!-- Navbar Cliente -->
    <header class="bg-white shadow-sm border-b border-dorado-light">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex justify-between items-center">
            <a href="<?= BASE_URL ?>index.php?url=cliente/dashboard" class="flex items-center gap-2 font-heading font-bold text-xl text-dorado-dark">
                &larr; Volver
            </a>
            <h1 class="font-heading font-bold text-2xl text-grafito">Agendar Nueva Cita</h1>
        </div>
    </header>

    <main class="flex-1 max-w-3xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-50 text-red-800 border border-red-200 p-4 rounded-xl mb-6 flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                <p class="font-semibold"><?= htmlspecialchars($_SESSION['error']) ?></p>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-50 text-green-800 border border-green-200 p-4 rounded-xl mb-6 flex items-start gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                <p class="font-semibold"><?= htmlspecialchars($_SESSION['success']) ?></p>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>index.php?url=cliente/agendar/guardar" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-xl border border-rosa-light overflow-hidden">
            
            <div class="p-8 space-y-8">
                <!-- Paso 1 -->
                <div>
                    <h2 class="text-xl font-heading font-bold text-dorado-dark border-b border-gray-100 pb-2 mb-4 flex items-center gap-2">
                        <span class="bg-dorado text-white w-6 h-6 rounded-full inline-flex items-center justify-center text-sm">1</span> 
                        Servicio y Profesional
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-grafito mb-2">Selecciona el Servicio *</label>
                            <select name="id_servicio" required class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-dorado bg-white">
                                <option value="">Elige un servicio...</option>
                                <?php if (!empty($serviciosActivos)): ?>
                                    <?php foreach ($serviciosActivos as $srv): ?>
                                        <option value="<?= $srv['id_servicio'] ?>">
                                            <?= htmlspecialchars($srv['nombre_servicio']) ?> ($ <?= number_format($srv['precio'], 0, ',', '.') ?>) - <?= $srv['duracion_minutos'] ?> min
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-grafito mb-2">Manicurista de preferencia *</label>
                            <select name="id_manicurista" required class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-dorado bg-white">
                                <option value="0">Cualquier Manicurista (Asignación Automática)</option>
                                <?php if (!empty($manicuristasActivas)): ?>
                                    <?php foreach ($manicuristasActivas as $manicurista): ?>
                                        <option value="<?= $manicurista['id_manicurista'] ?>">
                                            <?= htmlspecialchars($manicurista['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Paso 2 -->
                <div>
                    <h2 class="text-xl font-heading font-bold text-dorado-dark border-b border-gray-100 pb-2 mb-4 flex items-center gap-2">
                        <span class="bg-dorado text-white w-6 h-6 rounded-full inline-flex items-center justify-center text-sm">2</span> 
                        Fecha y Hora
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-grafito mb-2">Fecha deseada *</label>
                            <input type="date" name="fecha_cita" required class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-dorado">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-grafito mb-2">Hora deseada *</label>
                            <input type="time" name="hora_cita" required class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-dorado bg-white">
                        </div>
                    </div>
                </div>

                <!-- Paso 3 -->
                <div>
                    <h2 class="text-xl font-heading font-bold text-dorado-dark border-b border-gray-100 pb-2 mb-4 flex items-center gap-2">
                        <span class="bg-dorado text-white w-6 h-6 rounded-full inline-flex items-center justify-center text-sm">3</span> 
                        Confirmación y Abono
                    </h2>
                    
                    <div class="bg-rosa-light/30 rounded-xl p-5 mb-6 border border-rosa-light">
                        <p class="text-grafito-light text-sm mb-3">Para fijar tu cita, debes realizar una transferencia a cualquiera de nuestros números oficiales y subir el comprobante aquí.</p>
                        <div class="flex gap-4">
                            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100 flex-1 text-center">
                                <p class="text-xs font-bold uppercase text-gray-500">Nequi</p>
                                <p class="font-heading font-bold text-lg text-grafito">321 456 7890</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100 flex-1 text-center">
                                <p class="text-xs font-bold uppercase text-gray-500">Daviplata</p>
                                <p class="font-heading font-bold text-lg text-grafito">300 987 6543</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-grafito mb-2">Monto Transferido (COP) *</label>
                            <input type="number" name="monto_abono" required class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-dorado" placeholder="Ej. 15000">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-grafito mb-2">Foto del Comprobante *</label>
                            <input type="file" name="comprobante" accept="image/*,.pdf" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-dorado bg-gray-50 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-dorado file:text-white hover:file:bg-dorado-dark cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-6 sm:px-8 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-grafito hover:bg-grafito-light text-white px-8 py-4 rounded-xl font-bold text-lg flex items-center gap-2 transition shadow-lg w-full sm:w-auto justify-center">
                    Enviar Solicitud <i data-lucide="send" class="w-5 h-5"></i>
                </button>
            </div>
            
        </form>

    </main>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
