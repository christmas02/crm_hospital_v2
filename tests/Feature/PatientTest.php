<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientTest extends TestCase
{
    use RefreshDatabase;

    private function createUser($role = 'reception')
    {
        return User::factory()->create(['role' => $role]);
    }

    public function test_reception_can_view_patients_list()
    {
        $user = $this->createUser('reception');
        $response = $this->actingAs($user)->get(route('reception.patients.index'));
        $response->assertStatus(200);
    }

    public function test_reception_can_create_patient()
    {
        $user = $this->createUser('reception');
        $response = $this->actingAs($user)->post(route('reception.patients.store'), [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'date_naissance' => '1990-01-01',
            'sexe' => 'M',
            'telephone' => '0123456789',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('patients', ['nom' => 'Dupont', 'prenom' => 'Jean']);
    }

    public function test_patient_creation_requires_name()
    {
        $user = $this->createUser('reception');
        $response = $this->actingAs($user)->post(route('reception.patients.store'), [
            'prenom' => 'Jean',
            'date_naissance' => '1990-01-01',
            'sexe' => 'M',
        ]);
        $response->assertSessionHasErrors('nom');
    }

    public function test_patient_email_must_be_unique()
    {
        $user = $this->createUser('reception');
        Patient::factory()->create(['email' => 'test@test.com']);

        $response = $this->actingAs($user)->post(route('reception.patients.store'), [
            'nom' => 'Test',
            'prenom' => 'User',
            'date_naissance' => '1990-01-01',
            'sexe' => 'M',
            'email' => 'test@test.com',
        ]);
        $response->assertSessionHasErrors('email');
    }

    public function test_reception_can_delete_patient()
    {
        $user = $this->createUser('reception');
        $patient = Patient::factory()->create();

        $response = $this->actingAs($user)->delete(route('reception.patients.destroy', $patient));
        $response->assertRedirect();
        $this->assertSoftDeleted('patients', ['id' => $patient->id]);
    }

    public function test_unauthenticated_cannot_access_patients()
    {
        $response = $this->get(route('reception.patients.index'));
        $response->assertRedirect(route('login'));
    }
}
