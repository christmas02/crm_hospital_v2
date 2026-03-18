<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Medecin;
use App\Models\ExamenLabo;
use App\Models\DemandeLabo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaboTest extends TestCase
{
    use RefreshDatabase;

    private function setup_labo()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $patient = Patient::factory()->create();
        $medecin = Medecin::factory()->create();
        $examen1 = ExamenLabo::create(['nom' => 'NFS', 'categorie' => 'Hématologie', 'unite' => '', 'valeur_normale' => '', 'prix' => 5000]);
        $examen2 = ExamenLabo::create(['nom' => 'Glycémie', 'categorie' => 'Biochimie', 'unite' => 'g/L', 'valeur_normale' => '0.70-1.10', 'prix' => 3000]);
        return [$user, $patient, $medecin, $examen1, $examen2];
    }

    public function test_can_view_labo_dashboard()
    {
        [$user] = $this->setup_labo();
        $response = $this->actingAs($user)->get(route('labo.index'));
        $response->assertStatus(200);
    }

    public function test_can_create_lab_demand()
    {
        [$user, $patient, $medecin, $examen1, $examen2] = $this->setup_labo();

        $response = $this->actingAs($user)->post(route('labo.demandes.store'), [
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'examens' => [$examen1->id, $examen2->id],
            'urgence' => 'normal',
            'notes_cliniques' => 'Bilan de routine',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('demandes_labo', ['patient_id' => $patient->id, 'statut' => 'en_attente']);
    }

    public function test_lab_demand_creates_empty_results()
    {
        [$user, $patient, $medecin, $examen1, $examen2] = $this->setup_labo();

        $response = $this->actingAs($user)->post(route('labo.demandes.store'), [
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'examens' => [$examen1->id, $examen2->id],
            'urgence' => 'urgent',
            'notes_cliniques' => 'Test urgent',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $demande = DemandeLabo::first();
        $this->assertNotNull($demande, 'DemandeLabo was not created');
        $this->assertEquals(2, $demande->resultats->count());
    }

    public function test_can_update_demand_status()
    {
        [$user, $patient, $medecin, $examen1] = $this->setup_labo();

        $demande = DemandeLabo::create([
            'numero' => 'LAB-TEST-0001',
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'date_demande' => today(),
            'statut' => 'en_attente',
            'urgence' => 'normal',
        ]);

        $response = $this->actingAs($user)->patch(route('labo.demandes.statut', $demande), [
            'statut' => 'preleve',
        ]);
        $response->assertRedirect();
        $this->assertEquals('preleve', $demande->fresh()->statut);
    }

    public function test_can_view_examens_catalog()
    {
        [$user] = $this->setup_labo();
        $response = $this->actingAs($user)->get(route('labo.examens'));
        $response->assertStatus(200);
    }

    public function test_can_add_examen()
    {
        [$user] = $this->setup_labo();
        $response = $this->actingAs($user)->post(route('labo.examens.store'), [
            'nom' => 'Test VIH',
            'categorie' => 'Sérologie',
            'unite' => '',
            'valeur_normale' => 'Négatif',
            'prix' => 5000,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('examens_labo', ['nom' => 'Test VIH']);
    }
}
