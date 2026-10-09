<aside class="w-64 bg-grafito text-white flex flex-col h-screen fixed left-0 top-0 shadow-xl z-20">
    <div class="p-6 border-b border-grafito-light/20">
        <a href="<?= BASE_URL ?>index.php?url=admin/dashboard" class="flex items-center gap-3 text-dorado font-heading text-xl font-bold">
            <i data-lucide="sparkles"></i>
            CREACIÓN NAILS
        </a>
    </div>
    
    <nav class="flex-1 py-6 flex flex-col gap-2 px-4 overflow-y-auto">
        <a href="<?= BASE_URL ?>index.php?url=admin/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition-colors">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="<?= BASE_URL ?>index.php?url=admin/auditar_citas" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition-colors">
            <i data-lucide="calendar-check" class="w-5 h-5"></i>
            <span>Auditar Citas</span>
        </a>
        
        <a href="<?= BASE_URL ?>index.php?url=admin/servicios" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition-colors">
            <i data-lucide="scissors" class="w-5 h-5"></i>
            <span>Catálogo de Servicios</span>
        </a>
        
        <a href="<?= BASE_URL ?>index.php?url=admin/gusuarios" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition-colors">
            <i data-lucide="users" class="w-5 h-5"></i>
            <span>Personal & Horarios</span>
        </a>
        
        <a href="<?= BASE_URL ?>index.php?url=admin/liquidacion" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition-colors">
            <i data-lucide="wallet" class="w-5 h-5"></i>
            <span>Liquidación 50/50</span>
        </a>
    </nav>
    
    <div class="p-6 border-t border-grafito-light/20">
        <a href="<?= BASE_URL ?>index.php?url=usuario/logout" class="flex items-center gap-3 px-6 py-3 text-sm font-semibold text-gray-500 hover:text-red-500 transition mt-8 border-t border-gray-100 pt-6">
            <i data-lucide="log-out" class="w-5 h-5"></i>
            <span>Cerrar Sesión</span>
        </a>
    </div>
</aside>
