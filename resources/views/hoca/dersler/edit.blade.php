@extends('layouts.app')

@section('baslik', 'Ders Düzenle')

@section('icerik')
    <div class="mx-auto space-y-4">
        <form method="POST" action="{{ route('hoca.dersler.update', $ders->id) }}">
            @csrf
            @method('PUT')
            @include('hoca.dersler._form', ['ders' => $ders])
        </form>
    </div>
@endsection
