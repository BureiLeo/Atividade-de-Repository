<?php

namespace App\Repositories;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Collection;

class EloquentProdutoRepository implements ProdutoRepositoryInterface
{
    public function all(): Collection
    {
        return Produto::all();
    }

    public function find(int $id): ?Produto
    {
        return Produto::find($id);
    }

    public function create(array $dados): Produto
    {
        return Produto::create($dados);
    }

    public function search(array $filtros): Collection
    {
        $query = Produto::query();

        if (! empty($filtros['nome'])) {
            $query->where('nome', 'like', '%'.$filtros['nome'].'%');
        }

        if (isset($filtros['preco_min'])) {
            $query->where('preco', '>=', $filtros['preco_min']);
        }

        if (isset($filtros['preco_max'])) {
            $query->where('preco', '<=', $filtros['preco_max']);
        }

        if (isset($filtros['estoque_min'])) {
            $query->where('estoque', '>=', $filtros['estoque_min']);
        }

        if (isset($filtros['estoque_max'])) {
            $query->where('estoque', '<=', $filtros['estoque_max']);
        }

        return $query->get();
    }

    public function update(int $id, array $dados): ?Produto
    {
        $produto = Produto::find($id);

        if ($produto) {
            $produto->update($dados);
        }

        return $produto;
    }

    public function delete(int $id): bool
    {
        $produto = Produto::find($id);

        return $produto !== null && $produto->delete();
    }
}
