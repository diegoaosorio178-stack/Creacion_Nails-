<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen bg-marfil flex flex-col">
    <!-- Navbar Cliente -->
    <header class="bg-white shadow-sm border-b border-dorado-light">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex justify-between items-center">
            <a href="<?= BASE_URL ?>index.php?url=cliente/dashboard" class="flex items-center gap-2 font-heading font-bold text-2xl text-dorado-dark">
                <i data-lucide="sparkles"></i>
                Creación Nails
            </a>
            <div class="flex items-center gap-6">
                <span class="font-semibold text-grafito hidden sm:inline-block">¡Hola, Cliente VIP!</span>
                <a href="<?= BASE_URL ?>index.php?url=usuario/logout" class="text-sm font-semibold text-gray-500 hover:text-rosa transition flex items-center gap-1">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Salir
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-5xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-heading font-bold text-grafito">Mis Citas</h1>
                <p class="text-grafito-light mt-1">Revisa el historial y estado de tus reservas.</p>
            </div>
            <a href="<?= BASE_URL ?>index.php?url=cliente/agendar" class="bg-dorado hover:bg-dorado-dark text-white px-6 py-3 rounded-full font-bold flex items-center gap-2 transition shadow-md hover:shadow-lg">
                <i data-lucide="calendar-plus" class="w-5 h-5"></i> Agendar Nueva Cita
            </a>
        </div>

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

        <!-- Lista de Citas del Cliente -->
        <div class="bg-white rounded-2xl shadow-sm border border-rosa-light overflow-hidden">
            <div class="divide-y divide-gray-100">
                
                <?php if (empty($citas)): ?>
                    <div class="p-8 text-center text-gray-500">
                        <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                        <p class="font-semibold text-lg">No tienes citas programadas aún.</p>
                        <p class="text-sm mt-1">¡Anímate a agendar tu primer servicio con nosotros!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($citas as $cita): ?>
                        <?php
                            $bgClass = 'hover:bg-gray-50';
                            $badgeClass = '';
                            $badgeText = '';
                            switch ($cita['estado']) {
                                case 'pendiente':
                                    $badgeClass = 'bg-yellow-100 text-yellow-800 border border-yellow-200';
                                    $badgeText = 'Pendiente de Validación';
                                    break;
                                case 'confirmada':
                                    $badgeClass = 'bg-green-100 text-green-800 border border-green-200';
                                    $badgeText = 'Aprobada';
                                    break;
                                case 'rechazada':
                                    $bgClass = 'bg-red-50/30 hover:bg-red-50/50';
                                    $badgeClass = 'bg-red-100 text-red-800 border border-red-200';
                                    $badgeText = 'Rechazada';
                                    break;
                                case 'completada':
                                    $badgeClass = 'bg-blue-100 text-blue-800 border border-blue-200';
                                    $badgeText = 'Completada';
                                    break;
                            }
                        ?>
                        
                        <div class="p-6 sm:p-8 <?= $bgClass ?> transition flex flex-col sm:flex-row gap-6 items-start sm:items-center justify-between">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-3 py-1 rounded-full <?= $badgeClass ?> text-xs font-bold uppercase tracking-wider"><?= $badgeText ?></span>
                                </div>
                                <h3 class="text-xl font-heading font-bold text-grafito mb-1"><?= htmlspecialchars($cita['servicio'] ?? 'Servicio Personalizado') ?></h3>
                                <p class="text-gray-600 text-sm flex items-center gap-1">
                                    <i data-lucide="calendar" class="w-4 h-4"></i> 
                                    <?= date('l, d M Y', strtotime($cita['fecha_cita'])) ?> - <?= date('h:i A', strtotime($cita['hora_inicio'])) ?>
                                </p>
                                <p class="text-gray-500 text-sm mt-1">Con: <?= htmlspecialchars($cita['manicurista'] ?? 'Manicurista Asignada') ?></p>
                                
                                <?php if ($cita['estado'] === 'rechazada' && !empty($cita['motivo_rechazo'])): ?>
                                    <div class="mt-3 p-3 bg-white border border-red-200 rounded-lg text-sm text-red-700 flex items-start gap-2 max-w-xl">
                                        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                                        <p><strong>Motivo del rechazo:</strong> <?= htmlspecialchars($cita['motivo_rechazo']) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($cita['estado'] === 'completada' && empty($cita['calificacion'])): ?>
                                <!-- Botón para Calificar si está completada y aún no se ha calificado (ejemplo) -->
                                <a href="<?= BASE_URL ?>index.php?url=cliente/calificar&id=<?= $cita['id_cita'] ?>" class="w-full sm:w-auto text-center border-2 border-dorado text-dorado-dark hover:bg-dorado hover:text-white px-5 py-2.5 rounded-lg font-bold transition whitespace-nowrap">
                                    Calificar Servicio &starf;
                                </a>
                            <?php endif; ?>
                            
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>

    </main>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
