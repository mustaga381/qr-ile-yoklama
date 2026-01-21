<?php

if (!function_exists('yoklamaBadgeClass')) {
    function yoklamaBadgeClass(string $durum): string
    {
        return match ($durum) {
            'katildi'   => 'bg-emerald-50 text-emerald-700 ring-emerald-200/70',
            'gec_kaldi' => 'bg-indigo-50 text-indigo-700 ring-indigo-200/70',
            'manuel'    => 'bg-slate-50 text-slate-700 ring-slate-200/70',
            'supheli'   => 'bg-amber-50 text-amber-700 ring-amber-200/70',
            'reddedildi' => 'bg-rose-50 text-rose-700 ring-rose-200/70',
            default     => 'bg-white/70 text-slate-700 ring-slate-900/10',
        };
    }
}
