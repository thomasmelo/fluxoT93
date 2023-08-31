@extends('layouts.base')
@section('content')
<h1>
    <i class="bi bi-list-check"></i>
    Centro de Custo: {!!  $centro->centro_custo  !!}
</h1>
<h2>
    Listas de Lancamentos -
    {{ $centro->lancamentos()->count() }}
     itens
</h2>
<div class="table-responsive">
    <table class="table table-striped  table-hover ">
        <thead>
            <caption>Listas de Lancamentos - {{ $centro->lancamentos()->count() }}</caption>
            <tr>
                <th class="col-md-1">#</th>
                <th class="col-md-1">ID</th>
                <th>Descrição</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @foreach ($centro->lancamentos()->get() as $lancamento)
            <tr>
                <td scope="row">{{ $loop->iteration }}</td>
                <td scope="row">{{ $lancamento->id_lancamento }}</td>
                <td>{{ $lancamento->descricao }}</td>
                <td>{{ $lancamento->valor }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

@endsection
@section('scripts')
@parent

@endsection
