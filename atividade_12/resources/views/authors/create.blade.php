@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Adicionar Autor</h1>

    <form action="{{ route('authors.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="birth_date" class="form-label">Data de Nascimento</label>
            <input type="date" class="form-control @error('birth_date') is-invalid @enderror" name="birth_date" id="birth_date" value="{{ old('birth_date') }}">
            @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Salvar</button>
        <a href="{{ route('authors.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
    </form>
</div>
@endsection
