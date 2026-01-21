@extends('layouts.app')
@section('baslik', 'Hoca Düzenle')

@section('icerik')
    <div class="space-y-6">

        <div class="ui-card p-6">
            <div class="text-xs font-semibold tracking-wider uppercase text-slate-500">Admin • Hoca</div>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Hoca Düzenle</h1>
            <p class="mt-1 text-sm text-slate-600">Bilgileri güncelle, gerekirse şifreyi değiştir.</p>

            <div class="mt-3 flex flex-wrap gap-2 text-xs">
                <span class="ui-badge bg-white/70">ID: {{ $hoca->id }}</span>
                <span class="ui-badge bg-white/70">Rol: {{ strtoupper($hoca->rol) }}</span>
            </div>
        </div>

        <div class="ui-card p-7 max-w-3xl">
            <form method="POST" action="{{ route('admin.hocalar.update', $hoca->id) }}">
                @csrf
                @method('PUT')
                @include('admin.hocalar._form', ['hoca' => $hoca, 'bolumler' => $bolumler])
            </form>
        </div>

    </div>
@endsection
