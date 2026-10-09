<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen bg-marfil flex flex-col items-center justify-center py-10 px-4">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl border border-rosa-light overflow-hidden">
        
        <div class="bg-dorado/10 p-8 text-center border-b border-dorado-light">
            <div class="w-16 h-16 bg-dorado rounded-full flex items-center justify-center mx-auto mb-4 text-white shadow-md">
                <i data-lucide="star" class="w-8 h-8 fill-current"></i>
            </div>
            <h1 class="font-heading font-bold text-2xl text-grafito mb-2">¡Califica tu Experiencia!</h1>
            <p class="text-sm text-gray-600">Ayúdanos a mejorar contándonos cómo te fue con tu servicio de <strong>Manicura Rusa</strong> con <strong>Laura</strong>.</p>
        </div>

        <form method="POST" action="<?= BASE_URL ?>index.php?url=cliente/calificar/guardar" class="p-8">
            <input type="hidden" name="id_cita" value="<?= isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '' ?>">
            
            <!-- Estrellas (Mock Interactivo CSS) -->
            <div class="mb-8 text-center">
                <label class="block text-sm font-bold text-grafito mb-4">¿Cuántas estrellas nos das? *</label>
                <div class="flex flex-row-reverse justify-center gap-2 group">
                    <!-- Técnica clásica de rating con CSS usando flex-row-reverse y peer -->
                    <input type="radio" name="estrellas" id="star5" value="5" class="peer hidden" required>
                    <label for="star5" class="cursor-pointer text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-400 transition">
                        <i data-lucide="star" class="w-10 h-10 fill-current"></i>
                    </label>
                    
                    <input type="radio" name="estrellas" id="star4" value="4" class="peer hidden">
                    <label for="star4" class="cursor-pointer text-gray-300 peer-checked:text-yellow-400 peer-checked:peer-hover:text-yellow-400 hover:text-yellow-400 transition">
                        <i data-lucide="star" class="w-10 h-10 fill-current"></i>
                    </label>
                    
                    <input type="radio" name="estrellas" id="star3" value="3" class="peer hidden">
                    <label for="star3" class="cursor-pointer text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-400 transition">
                        <i data-lucide="star" class="w-10 h-10 fill-current"></i>
                    </label>
                    
                    <input type="radio" name="estrellas" id="star2" value="2" class="peer hidden">
                    <label for="star2" class="cursor-pointer text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-400 transition">
                        <i data-lucide="star" class="w-10 h-10 fill-current"></i>
                    </label>
                    
                    <input type="radio" name="estrellas" id="star1" value="1" class="peer hidden">
                    <label for="star1" class="cursor-pointer text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-400 transition">
                        <i data-lucide="star" class="w-10 h-10 fill-current"></i>
                    </label>
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-grafito mb-2">Déjanos un comentario (Opcional)</label>
                <textarea name="comentario" rows="4" class="w-full border border-gray-300 rounded-lg p-4 focus:outline-none focus:ring-2 focus:ring-dorado resize-none" placeholder="Me encantó el detalle en el diseño..."></textarea>
            </div>

            <div class="flex gap-4">
                <a href="<?= BASE_URL ?>index.php?url=cliente/dashboard" class="flex-1 text-center py-3 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                    Cancelar
                </a>
                <button type="submit" class="flex-1 bg-dorado hover:bg-dorado-dark text-white py-3 rounded-xl font-bold transition shadow-md hover:shadow-lg">
                    Enviar Reseña
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
