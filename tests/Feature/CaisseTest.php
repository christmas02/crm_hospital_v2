<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Facture;
use App\Models\FactureLigne;
use App\Models\CaisseSession;
use App\Models\Paiement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CaisseTest extends TestCase
{
    use RefreshDatabase;

    private function createCaisseUser()
    {
        return User::factory()->create(['role' => 'caisse']);
    }

    private function createFacture($statut = 'en_attente')
    {
        $patient = Patient::factory()->create();
        $facture = Facture::create([
            'numero' => 'FAC-TEST-' . rand(1000, 9999),
            'patient_id' => $patient->id,
            'date' => today(),
            'montant' => 50000,
            'montant_net' => 50000,
            'montant_paye' => 0,
            'montant_restant' => 50000,
            'statut' => $statut,
        ]);
        FactureLigne::create([
            'facture_id' => $facture->id,
            'description' => 'Consultation',
            'quantite' => 1,
            'prix_unitaire' => 50000,
            'total' => 50000,
        ]);
        return $facture;
    }

    public function test_caisse_can_view_dashboard()
    {
        $user = $this->createCaisseUser();
        $response = $this->actingAs($user)->get(route('caisse.index'));
        $response->assertStatus(200);
    }

    public function test_caisse_can_open_session()
    {
        $user = $this->createCaisseUser();
        $response = $this->actingAs($user)->post(route('caisse.session.ouvrir'), [
            'solde_ouverture' => 100000,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('caisse_sessions', ['user_id' => $user->id, 'statut' => 'ouverte']);
    }

    public function test_cannot_open_two_sessions()
    {
        $user = $this->createCaisseUser();
        CaisseSession::create(['user_id' => $user->id, 'ouverture' => now(), 'solde_ouverture' => 50000, 'statut' => 'ouverte']);

        $response = $this->actingAs($user)->post(route('caisse.session.ouvrir'), [
            'solde_ouverture' => 100000,
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_cannot_encaisser_without_session()
    {
        $user = $this->createCaisseUser();
        $facture = $this->createFacture();

        $response = $this->actingAs($user)->post(route('caisse.factures.encaisser', $facture), [
            'montant' => 50000,
            'mode_paiement' => 'especes',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_can_encaisser_with_session()
    {
        $user = $this->createCaisseUser();
        CaisseSession::create(['user_id' => $user->id, 'ouverture' => now(), 'solde_ouverture' => 50000, 'statut' => 'ouverte']);
        $facture = $this->createFacture();

        $response = $this->actingAs($user)->post(route('caisse.factures.encaisser', $facture), [
            'montant' => 50000,
            'mode_paiement' => 'especes',
        ]);
        $response->assertRedirect();
        $this->assertEquals('payee', $facture->fresh()->statut);
    }

    public function test_partial_payment()
    {
        $user = $this->createCaisseUser();
        CaisseSession::create(['user_id' => $user->id, 'ouverture' => now(), 'solde_ouverture' => 50000, 'statut' => 'ouverte']);
        $facture = $this->createFacture();

        $response = $this->actingAs($user)->post(route('caisse.factures.encaisser', $facture), [
            'montant' => 20000,
            'mode_paiement' => 'especes',
        ]);
        $response->assertRedirect();
        $fresh = $facture->fresh();
        $this->assertEquals(20000, $fresh->montant_paye);
        $this->assertEquals(30000, $fresh->montant_restant);
        $this->assertNotEquals('payee', $fresh->statut);
    }

    public function test_cannot_overpay()
    {
        $user = $this->createCaisseUser();
        CaisseSession::create(['user_id' => $user->id, 'ouverture' => now(), 'solde_ouverture' => 50000, 'statut' => 'ouverte']);
        $facture = $this->createFacture();

        $response = $this->actingAs($user)->post(route('caisse.factures.encaisser', $facture), [
            'montant' => 100000,
            'mode_paiement' => 'especes',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_can_close_session()
    {
        $user = $this->createCaisseUser();
        CaisseSession::create(['user_id' => $user->id, 'ouverture' => now(), 'solde_ouverture' => 50000, 'statut' => 'ouverte']);

        $response = $this->actingAs($user)->post(route('caisse.session.fermer'), [
            'solde_fermeture' => 55000,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('caisse_sessions', ['user_id' => $user->id, 'statut' => 'fermee']);
    }

    public function test_can_view_journal()
    {
        $user = $this->createCaisseUser();
        $response = $this->actingAs($user)->get(route('caisse.journal'));
        $response->assertStatus(200);
    }

    public function test_can_view_creances()
    {
        $user = $this->createCaisseUser();
        $response = $this->actingAs($user)->get(route('caisse.creances'));
        $response->assertStatus(200);
    }
}
