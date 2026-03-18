<?php

namespace Tests\Feature;

use App\Models\Medicament;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_create_negative_stock_movement()
    {
        $user = User::factory()->create(['role' => 'pharmacie']);
        $medicament = Medicament::factory()->create(['stock' => 5, 'stock_min' => 10]);

        $response = $this->actingAs($user)->post(route('pharmacie.mouvements.store'), [
            'medicament_id' => $medicament->id,
            'type' => 'sortie',
            'quantite' => 10,
            'motif' => 'Test sortie excessive',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_can_create_valid_stock_entry()
    {
        $user = User::factory()->create(['role' => 'pharmacie']);
        $medicament = Medicament::factory()->create(['stock' => 5]);

        $response = $this->actingAs($user)->post(route('pharmacie.mouvements.store'), [
            'medicament_id' => $medicament->id,
            'type' => 'entree',
            'quantite' => 10,
            'motif' => 'Réapprovisionnement',
        ]);
        $response->assertRedirect();
        $this->assertEquals(15, $medicament->fresh()->stock);
    }
}
