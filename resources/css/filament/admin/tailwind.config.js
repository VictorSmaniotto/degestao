import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        '../../../../app/Filament/**/*.php',
        '../../../../resources/views/filament/**/*.blade.php',
        '../../../../vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                // Aliases para variáveis CSS do tema
                dg: {
                    bg: 'var(--dg-card-bg)', // Usando var para consistência
                }
            },
        },
    },
}
