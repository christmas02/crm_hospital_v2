<?php

namespace Tests\Feature;

use App\Models\Consultation;
use App\Models\Patient;
use App\Models\Medecin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultationTest extends TestCase
{
    use RefreshDatabase;

    private function setup_data()
    {
        $user = User::factory()->create(['role' => 'reception']);
        $patient = Patient::factory()->create();
        $medecin = Medecin::factory()->create();
        return [$user, $patient, $medecin];
    }

    public function test_can_create_consultation()
    {
        [$user, $patient, $medecin] = $this->setup_data();

        $response = $this->actingAs($user)->post(route('reception.consultations.store'), [
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'heure' => '10:00',
            'motif' => 'Consultation de routine',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('consultations', ['patient_id' => $patient->id]);
    }

    public function test_cannot_create_consultation_with_past_date()
    {
        [$user, $patient, $medecin] = $this->setup_data();

        $response = $this->actingAs($user)->post(route('reception.consultations.store'), [
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'date' => now()->subDay()->format('Y-m-d'),
            'heure' => '10:00',
            'motif' => 'Test',
        ]);
        $response->assertSessionHasErrors('date');
    }

    public function test_can_delete_pending_consultation()
    {
        [$user, $patient, $medecin] = $this->setup_data();
        $consultation = Consultation::factory()->create([
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'statut' => 'en_attente',
        ]);

        $response = $this->actingAs($user)->delete(route('reception.consultations.destroy', $consultation));
        $response->assertRedirect();
        $this->assertSoftDeleted('consultations', ['id' => $consultation->id]);
    }
}
