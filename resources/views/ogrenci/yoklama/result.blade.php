@extends('layouts.app')
@section('baslik', 'Yoklama')

@section('icerik')
    <div class="max-w-md mx-auto px-4 py-10">
        <div class="ui-card p-6 text-center">

            @if ($durum === 'katildi')
                <div class="text-2xl font-extrabold text-emerald-600">✔ Katılım Alındı</div>
            @elseif($durum === 'supheli')
                <div class="text-2xl font-extrabold text-amber-500">⚠ Şüpheli Katılım</div>
            @else
                <div class="text-2xl font-extrabold text-rose-600">✖ Katılım Alınamadı</div>
            @endif

            <div class="mt-3 text-slate-700">{{ $mesaj }}</div>

            <div class="mt-6">
                <a href="/" class="ui-btn w-full">Ana Sayfa</a>
            </div>

        </div>
    </div>
@endsection
