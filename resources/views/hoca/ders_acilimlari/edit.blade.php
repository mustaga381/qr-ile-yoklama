@extends('layouts.app')
@section('baslik', 'Ders Açılışı Düzenle')
@section('icerik')
    <div class="mx-auto space-y-4">
        <form method="POST" action="{{ route('hoca.ders_acilimlari.update', $dersAcilim) }}">
            @csrf
            @method('PUT')
            @include('hoca.ders_acilimlari._form', $dersAcilim)
        </form>
    </div>
@endsection
