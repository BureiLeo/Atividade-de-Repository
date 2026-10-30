<?php

namespace App\Repositories;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Collection;

interface ProdutoRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Produto;

    public function create(array $dados): Produto;

    public function search(array $filtros): Collection;

    public function update(int $id, array $dados): ?Produto;

    public function delete(int $id): bool;
}
