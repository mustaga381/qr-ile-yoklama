@extends('layouts.app')
@section('baslik', 'Admin Panel')

@section('icerik')
    <div class="space-y-6">

        <div class="ui-card p-6">
            <div class="text-xs font-semibold tracking-wider uppercase text-slate-500">Yönetim</div>
            <div class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Admin Panel</div>
            <div class="mt-1 text-sm text-slate-600">Hocalar, öğrenciler, ders açılışları ve yoklamaları yönet.</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="ui-card p-5">
                <div class="text-xs text-slate-500">Öğrenci</div>
                <div class="text-2xl font-extrabold">{{ $stats['ogrenci'] }}</div>
            </div>
            <div class="ui-card p-5">
                <div class="text-xs text-slate-500">Hoca</div>
                <div class="text-2xl font-extrabold">{{ $stats['hoca'] }}</div>
            </div>
            <div class="ui-card p-5">
                <div class="text-xs text-slate-500">Ders Açılışı</div>
                <div class="text-2xl font-extrabold">{{ $stats['acilim'] }}</div>
            </div>
            <div class="ui-card p-5">
                <div class="text-xs text-slate-500">Yoklama Oturumu</div>
                <div class="text-2xl font-extrabold">{{ $stats['yoklama'] }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a class="ui-card p-6 hover:bg-white transition" href="{{ route('admin.hocalar.index') }}">
                <div class="text-lg font-extrabold">Hocalar</div>
                <div class="text-sm text-slate-600 mt-1">Hoca ekle / düzenle / sil</div>
            </a>
            <a class="ui-card p-6 hover:bg-white transition" href="{{ route('admin.ogrenciler.index') }}">
                <div class="text-lg font-extrabold">Öğrenciler</div>
                <div class="text-sm text-slate-600 mt-1">Liste + detay + yoklama geçmişi</div>
            </a>
        </div>

    </div>
@endsection
