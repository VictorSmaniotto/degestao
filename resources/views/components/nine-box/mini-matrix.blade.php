@props(['position'])

@php
    // Position expects { performance: 1-3, potential: 1-3 }
    // Rows (Potential): 3 (High), 2 (Mid), 1 (Low)
    // Cols (Performance): 1 (Low), 2 (Mid), 3 (High)
@endphp

<div class="flex flex-col items-center">
    <div class="grid grid-cols-3 gap-1 p-1 bg-gray-900 rounded border border-gray-800 w-[50px] h-[50px] shrink-0">
        @for ($pot = 3; $pot >= 1; $pot--)
            @for ($perf = 1; $perf <= 3; $perf++)
                @php
                    $isActive = ($position['potential'] == $pot && $position['performance'] == $perf);

                    // Dynamic color based on position (similar to reference)
                    $activeColor = match (true) {
                        $pot == 3 && $perf == 3 => 'bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.5)]', // Star
                        $pot == 3 => 'bg-blue-400 shadow-[0_0_10px_rgba(96,165,250,0.5)]', // High Pot
                        $perf == 3 => 'bg-indigo-400', // High Perf
                        $pot == 1 && $perf == 1 => 'bg-red-400', // Low/Low
                        default => 'bg-amber-400', // Mid
                    };
                @endphp

                <div
                    class="w-3 h-3 rounded-[2px] transition-all duration-300 {{ $isActive ? $activeColor : 'bg-gray-600 dark:bg-gray-700 border border-transparent' }}">
                </div>
            @endfor
        @endfor
    </div>
</div>