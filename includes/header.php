<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($judul) ? htmlspecialchars($judul) : 'Sudut Temu Cafe'; ?></title>

    <link rel="icon" type="image/png" href="<?= BASE_URL; ?>assets/logo-brand.png">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#C85274',
                        'primary-dark': '#A83F5E',
                        'primary-soft': '#F8DDE4',
                        secondary: '#F4B5C8',
                        accent: '#B88B7D',
                        'accent-dark': '#8F6B5E',
                        cream: '#FFF8F9',
                        blush: '#FDEEF2',
                        charcoal: '#3D2B2E',
                        'charcoal-soft': '#5A4548',
                    },
                    fontFamily: {
                        display: ['"Playfair Display"', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        soft: '0 4px 20px -4px rgba(200, 82, 116, 0.12)',
                        'soft-lg': '0 10px 40px -8px rgba(200, 82, 116, 0.18)',
                        card: '0 1px 3px rgba(61, 43, 46, 0.06), 0 4px 16px -4px rgba(61, 43, 46, 0.08)',
                    }
                }
            }
        }
    </script>

    <style>
        html { scroll-behavior: smooth; }
        body {
            background-color: #FFF8F9;
            color: #3D2B2E;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .font-display { font-family: 'Playfair Display', serif; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #FDEEF2; }
        ::-webkit-scrollbar-thumb { background: #F4B5C8; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #C85274; }
        input:focus, select:focus, textarea:focus, button:focus-visible { outline: none; }
        input[type="file"]::file-selector-button {
            margin-right: 0.75rem;
            padding: 0.4rem 0.85rem;
            border-radius: 0.5rem;
            border: none;
            background: #C85274;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        input[type="file"]::file-selector-button:hover { background: #A83F5E; }
        img.brand-logo { filter: drop-shadow(0 1px 2px rgba(200, 82, 116, 0.08)); }
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">