<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creación Nails</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Tailwind Custom Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        marfil: '#FCFCFB',
                        blanco: '#FFFFFF',
                        rosa: {
                            light: '#FBEFF0',
                            DEFAULT: '#E8A5AC',
                            dark: '#D97D87'
                        },
                        dorado: {
                            DEFAULT: '#D4AF37',
                            dark: '#C59B27'
                        },
                        grafito: {
                            light: '#78716C',
                            DEFAULT: '#1C1917'
                        }
                    },
                    fontFamily: {
                        heading: ['"Playfair Display"', 'serif'],
                        body: ['"Plus Jakarta Sans"', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS (Si es necesario) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FCFCFB; color: #1C1917; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-marfil text-grafito antialiased min-h-screen">
