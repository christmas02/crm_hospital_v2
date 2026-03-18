<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Personnel;
use App\Helpers\AuditHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PersonnelController extends Controller
{
    public function index(Request $request)
    {
        $query = Personnel::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nom', 'like', "%$s%")->orWhere('prenom', 'like', "%$s%")->orWhere('matricule', 'like', "%$s%")->orWhere('telephone', 'like', "%$s%"));
        }

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('service')) {
            $query->where('service', $request->service);
        }

        $personnel = $query->orderBy('nom')->paginate(20)->appends($request->query());

        $stats = [
            'total' => Personnel::count(),
            'actifs' => Personnel::where('statut', 'actif')->count(),
            'categories' => Personnel::selectRaw('categorie, count(*) as total')->groupBy('categorie')->pluck('total', 'categorie'),
        ];

        $categories = Personnel::select('categorie')->distinct()->pluck('categorie');
        $services = Personnel::select('service')->distinct()->whereNotNull('service')->pluck('service');

        return view('admin.personnel.index', compact('personnel', 'stats', 'categories', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:personnel,email',
            'adresse' => 'nullable|string|max:255',
            'categorie' => 'required|in:infirmier,sage_femme,technicien_labo,technicien_radio,aide_soignant,agent_accueil,agent_entretien,securite,administratif,autre',
            'poste' => 'required|string|max:100',
            'service' => 'nullable|string|max:100',
            'date_embauche' => 'required|date',
            'date_fin_contrat' => 'nullable|date|after:date_embauche',
            'type_contrat' => 'required|in:CDI,CDD,Stage,Vacation',
            'salaire' => 'nullable|integer|min:0',
            'contact_urgence' => 'nullable|string|max:255',
            'telephone_urgence' => 'nullable|string|max:20',
            'qualifications' => 'nullable|string',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Generate matricule
        $validated['matricule'] = 'EMP-' . str_pad(Personnel::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);
        $validated['statut'] = 'actif';

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('personnel', 'public');
        }

        $emp = Personnel::create($validated);
        AuditHelper::log('create', 'Personnel ajouté: ' . $emp->prenom . ' ' . $emp->nom . ' (' . $emp->matricule . ')');

        return redirect()->route('admin.personnel.index')->with('success', 'Employé ' . $emp->matricule . ' ajouté avec succès');
    }

    public function show(Personnel $personnel)
    {
        return response()->json([
            'id' => $personnel->id,
            'matricule' => $personnel->matricule,
            'nom' => $personnel->nom,
            'prenom' => $personnel->prenom,
            'date_naissance' => $personnel->date_naissance->format('Y-m-d'),
            'date_naissance_display' => $personnel->date_naissance->format('d/m/Y'),
            'age' => $personnel->date_naissance->age,
            'sexe' => $personnel->sexe,
            'telephone' => $personnel->telephone,
            'email' => $personnel->email ?? '',
            'adresse' => $personnel->adresse ?? '',
            'photo' => $personnel->photo ? asset('storage/' . $personnel->photo) : null,
            'categorie' => $personnel->categorie,
            'poste' => $personnel->poste,
            'service' => $personnel->service ?? '',
            'date_embauche' => $personnel->date_embauche->format('Y-m-d'),
            'date_embauche_display' => $personnel->date_embauche->format('d/m/Y'),
            'anciennete' => $personnel->anciennete,
            'date_fin_contrat' => $personnel->date_fin_contrat?->format('Y-m-d'),
            'type_contrat' => $personnel->type_contrat,
            'salaire' => $personnel->salaire,
            'statut' => $personnel->statut,
            'contact_urgence' => $personnel->contact_urgence ?? '',
            'telephone_urgence' => $personnel->telephone_urgence ?? '',
            'qualifications' => $personnel->qualifications ?? '',
            'notes' => $personnel->notes ?? '',
            'initiales' => strtoupper(substr($personnel->prenom, 0, 1) . substr($personnel->nom, 0, 1)),
        ]);
    }

    public function update(Request $request, Personnel $personnel)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:personnel,email,' . $personnel->id,
            'adresse' => 'nullable|string|max:255',
            'categorie' => 'required|in:infirmier,sage_femme,technicien_labo,technicien_radio,aide_soignant,agent_accueil,agent_entretien,securite,administratif,autre',
            'poste' => 'required|string|max:100',
            'service' => 'nullable|string|max:100',
            'date_embauche' => 'required|date',
            'date_fin_contrat' => 'nullable|date|after:date_embauche',
            'type_contrat' => 'required|in:CDI,CDD,Stage,Vacation',
            'salaire' => 'nullable|integer|min:0',
            'statut' => 'required|in:actif,conge,suspendu,demission,licencie',
            'contact_urgence' => 'nullable|string|max:255',
            'telephone_urgence' => 'nullable|string|max:20',
            'qualifications' => 'nullable|string',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($personnel->photo) Storage::disk('public')->delete($personnel->photo);
            $validated['photo'] = $request->file('photo')->store('personnel', 'public');
        }

        $personnel->update($validated);
        return redirect()->route('admin.personnel.index')->with('success', 'Employé mis à jour');
    }

    public function destroy(Personnel $personnel)
    {
        if ($personnel->photo) Storage::disk('public')->delete($personnel->photo);
        AuditHelper::log('delete', 'Personnel supprimé: ' . $personnel->prenom . ' ' . $personnel->nom . ' (' . $personnel->matricule . ')');
        $personnel->delete();
        return redirect()->route('admin.personnel.index')->with('success', 'Employé supprimé');
    }
}
