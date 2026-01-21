@extends('layouts.app')
@section('baslik', 'Bölüm Düzenle')

@section('icerik')
    <div class="mx-auto max-w-2xl ui-card p-7">
        <h1 class="text-2xl font-extrabold tracking-tight">Bölüm Düzenle</h1>

        <form method="POST" action="{{ route('admin.bolumler.update', $bolum->id) }}">
            @csrf
            @method('PUT')
            @include('admin.bolumler._form', ['bolum' => $bolum])
        </form>
    </div>
@endsection
