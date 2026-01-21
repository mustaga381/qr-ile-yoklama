@extends('layouts.app')
@section('baslik', 'Yeni Bölüm')

@section('icerik')
    <div class="mx-auto max-w-2xl ui-card p-7">
        <h1 class="text-2xl font-extrabold tracking-tight">Yeni Bölüm</h1>
        <p class="text-sm text-slate-600 mt-1">Yeni bölüm kaydı oluştur.</p>

        <form method="POST" action="{{ route('admin.bolumler.store') }}">
            @csrf
            @include('admin.bolumler._form')
        </form>
    </div>
@endsection
