<?php

namespace Tests\Feature\Repositories;

use App\Models\Produto;
use App\Repositories\EloquentProdutoRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentProdutoRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_filters_by_stock_and_preserves_existing_filters(): void
    {
        $minimumStock = $this->createProduct('Cadeira ergonômica', 50, 3);
        $maximumStock = $this->createProduct('Cadeira de escritório', 80, 10);
        $this->createProduct('Cadeira com estoque baixo', 50, 2);
        $this->createProduct('Cadeira com estoque alto', 50, 11);
        $this->createProduct('Cadeira barata', 10, 5);
        $this->createProduct('Cadeira cara', 100, 5);
        $this->createProduct('Mesa', 50, 5);

        $results = (new EloquentProdutoRepository)->search([
            'nome' => 'Cadeira',
            'preco_min' => 20,
            'preco_max' => 80,
            'estoque_min' => 3,
            'estoque_max' => 10,
        ]);

        $expectedIds = [$minimumStock->id, $maximumStock->id];
        $actualIds = $results->modelKeys();
        sort($expectedIds);
        sort($actualIds);

        $this->assertSame($expectedIds, $actualIds);
    }

    public function test_update_persists_product_changes_and_returns_null_for_missing_product(): void
    {
        $produto = $this->createProduct('Cadeira', 50, 3);
        $repository = new EloquentProdutoRepository;

        $updatedProduto = $repository->update($produto->id, [
            'nome' => 'Cadeira ergonômica',
            'estoque' => 8,
        ]);

        $this->assertInstanceOf(Produto::class, $updatedProduto);
        $this->assertSame('Cadeira ergonômica', $updatedProduto->nome);
        $this->assertDatabaseHas('produtos', [
            'id' => $produto->id,
            'nome' => 'Cadeira ergonômica',
            'estoque' => 8,
        ]);
        $this->assertNull($repository->update(999, ['nome' => 'Inexistente']));
    }

    public function test_delete_removes_existing_product_and_returns_false_for_missing_product(): void
    {
        $produto = $this->createProduct('Cadeira', 50, 3);
        $repository = new EloquentProdutoRepository;

        $this->assertTrue($repository->delete($produto->id));
        $this->assertDatabaseMissing('produtos', ['id' => $produto->id]);
        $this->assertFalse($repository->delete(999));
    }

    private function createProduct(string $name, int $price, int $stock): Produto
    {
        return Produto::create([
            'nome' => $name,
            'preco_custo' => 10,
            'preco' => $price,
            'estoque' => $stock,
        ]);
    }
}
