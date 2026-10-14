<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProdutoResource;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProdutoResource::collection(Produto::latest()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'], 
            'descricao' => 'nullable|string',
            'preco' => ['required', 'numeric', 'min:0'],
            'estoque' => 'sometimes|integer|min:0',
        ]);

        $produto = Produto::create($dados);

        // Código 201 : Recurso Criado
        return new ProdutoResource($produto);
    }

    /**
     * Display the specified resource.
     */
    public function show(Produto $produto)
    {
        return new ProdutoResource($produto);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produto $produto)
    {
        $dados = $request->validate([
            'nome' => ['sometimes', 'string', 'max:255'], 
            'descricao' => 'nullable|string',
            'preco' => ['sometimes', 'numeric', 'min:0'],
            'estoque' => 'sometimes|integer|min:0',
        ]);

        $produto->update($dados);

        return new ProdutoResource($produto);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produto $produto)
    {
        $produto->delete();
        return response()->noContent(); // 204
    }
}
