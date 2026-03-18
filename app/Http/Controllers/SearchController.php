<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Medecin;
use App\Models\Consultation;
use App\Models\Medicament;
use App\Models\Facture;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // Search patients
        $patients = Patient::where('nom', 'like', "%{$q}%")
            ->orWhere('prenom', 'like', "%{$q}%")
            ->orWhere('telephone', 'like', "%{$q}%")
            ->limit(5)->get();

        foreach ($patients as $p) {
            $results[] = [
                'type' => 'Patient',
                'icon' => 'patient',
                'title' => $p->prenom . ' ' . $p->nom,
                'subtitle' => $p->telephone ?? '',
                'url' => route('reception.patients.show', $p),
            ];
        }

        // Search medecins
        $medecins = Medecin::where('nom', 'like', "%{$q}%")
            ->orWhere('prenom', 'like', "%{$q}%")
            ->orWhere('specialite', 'like', "%{$q}%")
            ->limit(5)->get();

        foreach ($medecins as $m) {
            $results[] = [
                'type' => 'Medecin',
                'icon' => 'medecin',
                'title' => 'Dr. ' . $m->prenom . ' ' . $m->nom,
                'subtitle' => $m->specialite,
                'url' => route('admin.medecins'),
            ];
        }

        // Search medicaments
        $medicaments = Medicament::where('nom', 'like', "%{$q}%")
            ->orWhere('categorie', 'like', "%{$q}%")
            ->limit(5)->get();

        foreach ($medicaments as $med) {
            $results[] = [
                'type' => 'Medicament',
                'icon' => 'medicament',
                'title' => $med->nom,
                'subtitle' => ($med->forme ?? '') . ' - Stock: ' . $med->stock,
                'url' => route('pharmacie.index'),
            ];
        }

        return response()->json(['results' => $results]);
    }
}
