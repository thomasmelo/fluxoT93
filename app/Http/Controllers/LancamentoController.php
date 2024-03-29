<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\{
    CentroCusto,
    Lancamento,
    Tipo,
    User
};



class LancamentoController extends Controller
{
    /**
     * Listar todos os lançamentos
     * @date 04-09-2023
     * @update 15-09-2023 - inclusão da pesquisa
     *
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $dt_inicial = $request->get('dt_inicial') ?? null;
        $dt_final = $request->get('dt_final') ?? null;
        $id_tipo = $request->get('id_tipo') ?? null;
        $id_centro_custo = $request->get('id_centro_custo') ?? null;

        //where('id_user',Auth::user()->id)
        $lancamentos = Lancamento::with(['usuario', 'centroCusto', 'tipo'])
        ->where(function ($query) use (
            $search,
            $dt_inicial,
            $dt_final,
            $id_tipo,
            $id_centro_custo
        ) {
            if ($search) {
                $query->where('descricao', 'like', "%$search%");
            }

            if ($dt_inicial) {
                $query->where('vencimento', '>=', $dt_inicial);
            }

            if ($dt_final) {
                $query->where('vencimento', '<=', $dt_final);
            }
            if ($id_tipo) {
                $query->where('id_tipo', $id_tipo);
            }
            if ($id_centro_custo) {
                $query->where('id_centro_custo', $id_centro_custo);
            }
        })->orderBy('id_lancamento', 'desc')
            ->paginate(40);

        $centrosDeCusto = CentroCusto::class;
        $tipos = Tipo::class;

        return view('lancamento.index')
            ->with(compact(
                'lancamentos',
                'centrosDeCusto',
                'tipos'
            ));
    }

    /**
     * Formulário de novo lançamento
     * @date 11-09-2023
     */
    public function create()
    {
        $lancamento = null;
        $centrosDeCusto = CentroCusto::class;
        $tipos = Tipo::class;

        return view('lancamento.form')
            ->with(
                compact(
                    'lancamento',
                    'centrosDeCusto',
                    'tipos'
                )
            );
    }

    /**
     * Cadastrar um novo lançamento
     * @date 13-09-2023
     */
    public function store(Request $request)
    {
        $lancamento = new Lancamento();
        $lancamento->fill($request->all());
        // capturar o id do usuario logado
        $lancamento->id_user = Auth::user()->id;
        // subir o anexo
        if ($request->anexo) {
            $extension = $request->anexo->getClientOriginalExtension();
            $nomeAnexo = date('YmdHis') . '.' . $extension;
            $request->anexo->storeAs('anexos', $nomeAnexo);
            $lancamento->anexo = $nomeAnexo;
            // $lancamento->anexo = $request->anexo->store('anexos');
        }
        $lancamento->save();
        return redirect()
            ->route('lancamento.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lancamento $lancamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lancamento $lancamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lancamento $lancamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lancamento $lancamento)
    {
        //
    }
}
