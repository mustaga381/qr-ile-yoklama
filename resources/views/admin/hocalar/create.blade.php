@extends('layouts.app')
@section('baslik', 'Yeni Hoca')

@section('icerik')
    <div class="space-y-6">

        <div class="ui-card p-6">
            <div class="text-xs font-semibold tracking-wider uppercase text-slate-500">Admin • Hoca</div>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Yeni Hoca Oluştur</h1>
            <p class="mt-1 text-sm text-slate-600">Bölüm, personel no ve ilk şifreyi belirle.</p>
        </div>

        <div class="ui-card p-7 max-w-3xl">
            <form method="POST" action="{{ route('admin.hocalar.store') }}">
                @csrf
                @include('admin.hocalar._form', ['bolumler' => $bolumler])
            </form>
        </div>

    </div>
@endsection
