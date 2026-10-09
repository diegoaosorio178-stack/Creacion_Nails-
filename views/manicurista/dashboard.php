<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen bg-marfil flex flex-col">
    <!-- Navbar -->
    <header class="bg-white shadow-sm border-b border-rosa-light">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex justify-between items-center">
            <a href="<?= BASE_URL ?>index.php?url=manicurista/dashboard" class="flex items-center gap-2 font-heading font-bold text-xl text-rosa-dark">
                <i data-lucide="flower-2"></i>
                Creación Nails | Colaboradoras
            </a>
            <div class="flex items-center gap-6">
                <span class="font-semibold text-grafito">¡Hola, <?= htmlspecialchars($_SESSION['user']['nombre'] ?? 'Manicurista') ?>!</span>
                <a href="<?= BASE_URL ?>index.php?url=usuario/logout" class="text-sm font-semibold text-gray-500 hover:text-rosa transition flex items-center gap-1">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Salir
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
        
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Columna Izquierda: Agenda -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex justify-between items-end">
                    <div>
                        <h1 class="text-3xl font-heading font-bold text-grafito">Tu Agenda de Trabajo</h1>
                        <p class="text-grafito-light mt-1">Revisa tus próximas citas confirmadas.</p>
                    </div>
                    <!-- Selector de Vista -->
                    <div class="bg-white rounded-lg p-1 border border-rosa-light shadow-sm flex text-sm font-semibold">
                        <?php $vistaActual = $_GET['vista'] ?? 'todas'; ?>
                        <a href="?url=manicurista/dashboard&vista=diaria" class="px-4 py-2 rounded transition <?= $vistaActual === 'diaria' ? 'bg-rosa-light text-rosa-dark shadow-sm' : 'text-gray-500 hover:text-grafito' ?>">Hoy</a>
                        <a href="?url=manicurista/dashboard&vista=todas" class="px-4 py-2 rounded transition <?= $vistaActual === 'todas' ? 'bg-rosa-light text-rosa-dark shadow-sm' : 'text-gray-500 hover:text-grafito' ?>">Todas las Citas</a>
                    </div>
                </div>

                <!-- Lista de Citas -->
                <div class="space-y-4">
                    <?php if (empty($citas)): ?>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-500">
                            <i data-lucide="calendar-check-2" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                            <p class="font-semibold text-lg">No tienes citas confirmadas para esta vista.</p>
                            <p class="text-sm mt-1">¡Aprovecha para descansar o capacitarte!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($citas as $cita): ?>
                            <?php
                                // Determinar si la cita ya puede finalizarse (si la hora actual es >= a la hora de inicio)
                                $fechaCita = $cita['fecha_cita'];
                                $horaInicio = $cita['hora_inicio'];
                                $fechaHoraCitaStr = $fechaCita . ' ' . $horaInicio;
                                $timestampCita = strtotime($fechaHoraCitaStr);
                                $timestampActual = time();
                                
                                $sePuedeFinalizar = $timestampActual >= $timestampCita;
                                
                                // Color de borde basado en si está lista o no
                                $bordeColor = $sePuedeFinalizar ? 'border-l-dorado-dark' : 'border-l-rosa opacity-75';
                            ?>
                            <div class="bg-white rounded-xl shadow-sm border border-l-4 <?= $bordeColor ?> p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="text-center min-w-[80px]">
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                            <?= $fechaCita === date('Y-m-d') ? 'Hoy' : date('d M', strtotime($fechaCita)) ?>
                                        </p>
                                        <p class="text-2xl font-heading font-bold <?= $sePuedeFinalizar ? 'text-dorado-dark' : 'text-rosa-dark' ?>">
                                            <?= date('h:i', strtotime($horaInicio)) ?>
                                        </p>
                                        <p class="text-sm font-bold <?= $sePuedeFinalizar ? 'text-dorado' : 'text-rosa-dark' ?>">
                                            <?= date('A', strtotime($horaInicio)) ?>
                                        </p>
                                    </div>
                                    <div class="border-l pl-4 border-gray-100">
                                        <h3 class="font-bold text-lg text-grafito"><?= htmlspecialchars($cita['nombre_cliente']) ?></h3>
                                        <p class="text-gray-600 text-sm flex items-center gap-1 mt-1"><i data-lucide="scissors" class="w-3 h-3"></i> <?= htmlspecialchars($cita['nombre_servicio']) ?></p>
                                        <p class="text-gray-500 text-xs mt-1">Contacto: <?= htmlspecialchars($cita['telefono_cliente']) ?></p>
                                    </div>
                                </div>
                                
                                <?php if ($sePuedeFinalizar): ?>
                                    <form method="POST" action="<?= BASE_URL ?>index.php?url=manicurista/finalizarServicio">
                                        <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">
                                        <button type="submit" class="w-full sm:w-auto bg-dorado hover:bg-dorado-dark text-white px-5 py-3 rounded-lg font-bold flex items-center justify-center gap-2 transition shadow-sm">
                                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                                            Finalizar Servicio
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button disabled class="w-full sm:w-auto bg-gray-100 text-gray-400 px-5 py-3 rounded-lg font-bold cursor-not-allowed flex items-center justify-center gap-2">
                                        <i data-lucide="clock" class="w-5 h-5"></i>
                                        Próximamente
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Columna Derecha: Widget Quincenal -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-rosa-light overflow-hidden sticky top-24">
                    <div class="bg-grafito px-6 py-4 text-center">
                        <h2 class="text-white font-heading font-bold text-lg flex items-center justify-center gap-2">
                            <i data-lucide="wallet" class="w-5 h-5 text-dorado"></i> Mi Panel Quincenal
                        </h2>
                        <p class="text-gray-300 text-xs mt-1">
                            <?= date('d') <= 15 ? '1ra' : '2da' ?> Quincena <?= date('M Y') ?>
                        </p>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-sm text-gray-500 uppercase font-semibold tracking-wider mb-2">Comisión Acumulada (50%)</p>
                        <p class="text-4xl font-heading font-bold text-green-600">$ <?= number_format($comisiones['comision'] ?? 0, 0, ',', '.') ?></p>
                        
                        <hr class="my-6 border-gray-100">
                        
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 font-semibold">Servicios Finalizados:</span>
                            <span class="font-bold text-grafito text-lg bg-gray-100 px-3 py-1 rounded-full"><?= $comisiones['citas_completadas'] ?? 0 ?></span>
                        </div>
                    </div>
                    <div class="bg-rosa-light/30 p-4 text-center text-xs text-grafito-light">
                        Las comisiones se liquidan los días 15 y 30 de cada mes.
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
