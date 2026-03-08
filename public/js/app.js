/**
 * MediCare Pro - Application JavaScript
 */

// ==================== UTILITIES ====================
const formatMoney = (n) => new Intl.NumberFormat('fr-FR').format(n) + ' FCFA';
const formatDate = (d) => new Date(d).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' });
const getInitials = (nom, prenom) => (prenom[0] + nom[0]).toUpperCase();
const getAge = (dn) => Math.floor((new Date() - new Date(dn)) / 31557600000);

const statusBadge = (s) => ({
    'en_attente': '<span class="badge badge-warning">En attente</span>',
    'en_cours': '<span class="badge badge-info">En cours</span>',
    'termine': '<span class="badge badge-success">Terminé</span>',
    'paye': '<span class="badge badge-success">Payé</span>',
    'non_paye': '<span class="badge badge-danger">Non payé</span>',
    'actif': '<span class="badge badge-success">Actif</span>',
    'hospitalise': '<span class="badge badge-warning">Hospitalisé</span>',
    'disponible': '<span class="badge badge-success">Disponible</span>',
    'en_consultation': '<span class="badge badge-info">En consultation</span>',
    'en_operation': '<span class="badge badge-warning">En opération</span>',
    'absent': '<span class="badge badge-secondary">Absent</span>',
    'confirme': '<span class="badge badge-success">Confirmé</span>',
    'libre': '<span class="badge badge-success">Libre</span>',
    'occupee': '<span class="badge badge-danger">Occupée</span>',
    'maintenance': '<span class="badge badge-warning">Maintenance</span>'
})[s] || s;

// ==================== MODAL ====================
const openModal = (id) => document.getElementById(id).classList.add('active');
const closeModal = (id) => document.getElementById(id).classList.remove('active');
document.addEventListener('click', (e) => { if (e.target.classList.contains('modal-overlay')) e.target.classList.remove('active'); });

// ==================== SIDEBAR ====================
const toggleSidebar = () => document.getElementById('sidebar').classList.toggle('open');

// ==================== DASHBOARD ====================
function loadDashboard() {
    const stats = DATA.getStats();
    document.getElementById('statPatients').textContent = stats.totalPatients;
    document.getElementById('statHospitalises').textContent = stats.patientsHospitalises + ' hospitalisés';
    document.getElementById('statConsultations').textContent = stats.consultationsJour;
    document.getElementById('statEnAttente').textContent = stats.consultationsEnAttente + ' en attente';
    document.getElementById('statMedecins').textContent = stats.totalMedecins;
    document.getElementById('statDisponibles').textContent = stats.medecinsDisponibles + ' disponibles';
    document.getElementById('statRecettes').textContent = formatMoney(stats.recettesJour);
    document.getElementById('statImpaye').textContent = formatMoney(stats.paiementsEnAttente) + ' impayés';
    document.getElementById('statChambres').textContent = stats.chambresOccupees + '/' + stats.chambresTotal;
    document.getElementById('statOccupation').textContent = stats.tauxOccupation + '% occupation';

    // Table consultations
    const today = "2024-02-20";
    const consults = DATA.consultations.filter(c => c.date === today).slice(0, 5);
    document.getElementById('tableConsultations').innerHTML = consults.map(c => {
        const p = DATA.getPatientById(c.patientId);
        const m = DATA.getMedecinById(c.medecinId);
        return `<tr><td><div class="user-cell"><div class="avatar">${getInitials(p.nom, p.prenom)}</div><span>${p.prenom} ${p.nom}</span></div></td><td>${c.heure}</td><td>Dr. ${m.nom}</td><td>${statusBadge(c.statut)}</td></tr>`;
    }).join('');

    // Alertes stock
    const alertes = DATA.getMedicamentsStockBas();
    document.getElementById('alertesStock').innerHTML = alertes.length ? alertes.map(m =>
        `<div class="alert-item"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg><div class="alert-text"><div class="alert-title">${m.nom}</div><div class="alert-sub">Stock: ${m.stock} / Min: ${m.stockMin}</div></div></div>`
    ).join('') : '<p class="text-muted text-center">Aucune alerte</p>';

    // Charts
    loadCharts();
}

function loadCharts() {
    const graphData = DATA.getStatsGraphiques();

    new Chart(document.getElementById('chartConsultations'), {
        type: 'bar',
        data: { labels: graphData.consultationsParJour.map(d => d.jour), datasets: [{ label: 'Consultations', data: graphData.consultationsParJour.map(d => d.count), backgroundColor: '#0891b2' }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('chartRecettes'), {
        type: 'line',
        data: { labels: graphData.recettesParMois.map(d => d.mois), datasets: [{ label: 'Recettes', data: graphData.recettesParMois.map(d => d.montant), borderColor: '#059669', backgroundColor: 'rgba(5, 150, 105, 0.1)', fill: true, tension: 0.3 }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });
}

// ==================== PATIENTS ====================
function loadPatients() {
    renderPatients(DATA.patients);
}

function renderPatients(patients) {
    document.getElementById('patientCount').textContent = patients.length + ' patients';
    document.getElementById('tablePatients').innerHTML = patients.map(p =>
        `<tr><td><div class="user-cell"><div class="avatar">${getInitials(p.nom, p.prenom)}</div><div><div class="user-name">${p.prenom} ${p.nom}</div><div class="user-sub">${getAge(p.dateNaissance)} ans - ${p.sexe}</div></div></div></td><td>${p.telephone}</td><td>${p.groupeSanguin || '-'}</td><td>${statusBadge(p.statut)}</td><td><button class="btn btn-outline btn-sm" onclick="viewPatient(${p.id})">Voir</button></td></tr>`
    ).join('');
}

function filterPatients() {
    const search = document.getElementById('searchPatient').value.toLowerCase();
    const statut = document.getElementById('filterStatut').value;
    const filtered = DATA.patients.filter(p => (p.nom + ' ' + p.prenom + ' ' + p.telephone).toLowerCase().includes(search) && (!statut || p.statut === statut));
    renderPatients(filtered);
}

function viewPatient(id) {
    const p = DATA.getPatientById(id);
    document.getElementById('patientDetailContent').innerHTML = `
        <div class="text-center mb-4"><div class="avatar lg" style="margin: 0 auto 12px;">${getInitials(p.nom, p.prenom)}</div><h2>${p.prenom} ${p.nom}</h2><p class="text-muted">${getAge(p.dateNaissance)} ans - ${p.sexe === 'M' ? 'Masculin' : 'Féminin'}</p></div>
        <div class="grid-2 mb-4"><div style="background:var(--gray-100);padding:12px;border-radius:8px;"><div class="text-xs text-muted">Téléphone</div><div class="font-medium">${p.telephone}</div></div><div style="background:var(--gray-100);padding:12px;border-radius:8px;"><div class="text-xs text-muted">Email</div><div class="font-medium">${p.email || '-'}</div></div><div style="background:var(--gray-100);padding:12px;border-radius:8px;"><div class="text-xs text-muted">Groupe sanguin</div><div class="font-medium">${p.groupeSanguin || '-'}</div></div><div style="background:var(--gray-100);padding:12px;border-radius:8px;"><div class="text-xs text-muted">Statut</div>${statusBadge(p.statut)}</div></div>
        ${p.allergies.length ? `<div class="mb-4"><strong>Allergies:</strong> <span class="text-danger">${p.allergies.join(', ')}</span></div>` : ''}
        <div><strong>Adresse:</strong> ${p.adresse || '-'}</div>`;
    openModal('modalPatientDetail');
}

function savePatient(e) {
    e.preventDefault();
    DATA.patients.push({
        id: DATA.patients.length + 1,
        nom: document.getElementById('patientNom').value,
        prenom: document.getElementById('patientPrenom').value,
        dateNaissance: document.getElementById('patientDN').value,
        sexe: document.getElementById('patientSexe').value,
        groupeSanguin: document.getElementById('patientGS').value,
        telephone: document.getElementById('patientTel').value,
        email: document.getElementById('patientEmail').value,
        adresse: document.getElementById('patientAdresse').value,
        allergies: document.getElementById('patientAllergies').value.split(',').map(a => a.trim()).filter(a => a),
        dateInscription: new Date().toISOString().split('T')[0],
        statut: 'actif'
    });
    closeModal('modalPatient');
    document.getElementById('formPatient').reset();
    loadPatients();
    alert('Patient enregistré');
}

// ==================== CONSULTATIONS ====================
let currentConsultId = null;

function loadConsultationsPage() {
    renderConsultations(DATA.consultations);
}

function renderConsultations(consults) {
    document.getElementById('tableConsult').innerHTML = consults.map(c => {
        const p = DATA.getPatientById(c.patientId);
        const m = DATA.getMedecinById(c.medecinId);
        return `<tr><td><div class="user-cell"><div class="avatar">${getInitials(p.nom, p.prenom)}</div><span>${p.prenom} ${p.nom}</span></div></td><td>${formatDate(c.date)}</td><td>${c.heure}</td><td>Dr. ${m.nom}</td><td class="truncate" style="max-width:150px;">${c.motif}</td><td>${statusBadge(c.statut)}</td><td><button class="btn btn-outline btn-sm" onclick="viewConsultation(${c.id})">Voir</button></td></tr>`;
    }).join('');
}

function filterConsultations() {
    const search = document.getElementById('searchConsult').value.toLowerCase();
    const statut = document.getElementById('filterStatutC').value;
    const medecin = document.getElementById('filterMedecin').value;
    const filtered = DATA.consultations.filter(c => {
        const p = DATA.getPatientById(c.patientId);
        return (p.nom + ' ' + p.prenom).toLowerCase().includes(search) && (!statut || c.statut === statut) && (!medecin || c.medecinId == medecin);
    });
    renderConsultations(filtered);
}

function viewConsultation(id) {
    currentConsultId = id;
    const c = DATA.consultations.find(x => x.id === id);
    const p = DATA.getPatientById(c.patientId);
    const m = DATA.getMedecinById(c.medecinId);
    document.getElementById('consultDetailContent').innerHTML = `
        <div class="user-cell mb-4"><div class="avatar lg">${getInitials(p.nom, p.prenom)}</div><div><div class="user-name" style="font-size:1.25rem;">${p.prenom} ${p.nom}</div><div class="text-muted">${getAge(p.dateNaissance)} ans</div></div></div>
        <div class="grid-2 mb-4"><div style="background:var(--gray-100);padding:12px;border-radius:8px;"><div class="text-xs text-muted">Date & Heure</div><div class="font-medium">${formatDate(c.date)} à ${c.heure}</div></div><div style="background:var(--gray-100);padding:12px;border-radius:8px;"><div class="text-xs text-muted">Médecin</div><div class="font-medium">Dr. ${m.prenom} ${m.nom}</div></div></div>
        <div style="background:var(--gray-100);padding:12px;border-radius:8px;" class="mb-4"><div class="text-xs text-muted">Motif</div><div>${c.motif}</div></div>
        ${c.diagnostic ? `<div style="background:var(--primary-light);padding:12px;border-radius:8px;" class="mb-4"><div class="text-xs" style="color:var(--primary);">Diagnostic</div><div class="font-medium">${c.diagnostic}</div></div>` : ''}
        ${c.notes ? `<div><strong>Notes:</strong> ${c.notes}</div>` : ''}`;
    openModal('modalConsultDetail');
}

function updateStatut(statut) {
    const c = DATA.consultations.find(x => x.id === currentConsultId);
    if (c) { c.statut = statut; closeModal('modalConsultDetail'); loadConsultationsPage(); }
}

function saveConsultation(e) {
    e.preventDefault();
    DATA.consultations.push({
        id: DATA.consultations.length + 1,
        patientId: parseInt(document.getElementById('consultPatient').value),
        medecinId: parseInt(document.getElementById('consultMedecin').value),
        date: document.getElementById('consultDate').value,
        heure: document.getElementById('consultHeure').value,
        motif: document.getElementById('consultMotif').value,
        diagnostic: '', notes: '', statut: 'en_attente'
    });
    closeModal('modalConsult');
    document.getElementById('formConsult').reset();
    loadConsultationsPage();
    alert('Consultation enregistrée');
}

function loadSelectOptions() {
    const pOpts = DATA.patients.map(p => `<option value="${p.id}">${p.prenom} ${p.nom}</option>`).join('');
    const mOpts = DATA.medecins.map(m => `<option value="${m.id}">Dr. ${m.prenom} ${m.nom} - ${m.specialite}</option>`).join('');
    document.getElementById('consultPatient').innerHTML = '<option value="">Sélectionner</option>' + pOpts;
    document.getElementById('consultMedecin').innerHTML = '<option value="">Sélectionner</option>' + mOpts;
    document.getElementById('filterMedecin').innerHTML = '<option value="">Tous</option>' + mOpts;
}

// ==================== MÉDECINS ====================
function loadMedecins() {
    renderMedecins(DATA.medecins);
}

function renderMedecins(medecins) {
    document.getElementById('medecinsList').innerHTML = medecins.map(m => `
        <div class="card"><div class="card-body text-center">
            <div class="avatar lg" style="margin:0 auto 12px;background:var(--primary);color:#fff;">${getInitials(m.nom, m.prenom)}</div>
            <h3 style="font-size:1rem;">Dr. ${m.prenom} ${m.nom}</h3>
            <p class="text-muted text-sm mb-3">${m.specialite}</p>
            ${statusBadge(m.statut)}
            <div class="mt-4 text-sm"><div class="mb-2"><strong>Bureau:</strong> ${m.bureau}</div><div><strong>Tarif:</strong> ${formatMoney(m.tarifConsultation)}</div></div>
        </div></div>
    `).join('');
}

function filterMedecins() {
    const search = document.getElementById('searchMedecin').value.toLowerCase();
    const spec = document.getElementById('filterSpecialite').value;
    const filtered = DATA.medecins.filter(m => (m.nom + ' ' + m.prenom).toLowerCase().includes(search) && (!spec || m.specialite === spec));
    renderMedecins(filtered);
}

function saveMedecin(e) {
    e.preventDefault();
    DATA.medecins.push({
        id: DATA.medecins.length + 1,
        nom: document.getElementById('medecinNom').value,
        prenom: document.getElementById('medecinPrenom').value,
        specialite: document.getElementById('medecinSpec').value,
        telephone: document.getElementById('medecinTel').value,
        email: document.getElementById('medecinEmail').value,
        bureau: document.getElementById('medecinBureau').value,
        tarifConsultation: parseInt(document.getElementById('medecinTarif').value) || 15000,
        statut: 'disponible'
    });
    closeModal('modalMedecin');
    document.getElementById('formMedecin').reset();
    loadMedecins();
    alert('Médecin enregistré');
}

// ==================== HOSPITALISATION ====================
function loadChambres() {
    document.getElementById('roomGrid').innerHTML = DATA.chambres.map(c => {
        const patient = c.patientId ? DATA.getPatientById(c.patientId) : null;
        return `<div class="room-card ${c.statut}"><div class="room-number">${c.numero}</div><div class="room-type">${c.type} - ${c.capacite} lit(s)</div><div class="room-status">${c.statut === 'occupee' && patient ? patient.prenom + ' ' + patient.nom : c.statut.charAt(0).toUpperCase() + c.statut.slice(1)}</div><div class="text-sm text-muted mt-2">${formatMoney(c.tarifJour)}/jour</div></div>`;
    }).join('');
}

function loadHospitalisations() {
    const hosps = DATA.getHospitalisationsEnCours();
    document.getElementById('tableHosp').innerHTML = hosps.map(h => {
        const p = DATA.getPatientById(h.patientId);
        const c = DATA.getChambreById(h.chambreId);
        const m = DATA.getMedecinById(h.medecinId);
        return `<tr><td><div class="user-cell"><div class="avatar">${getInitials(p.nom, p.prenom)}</div><span>${p.prenom} ${p.nom}</span></div></td><td>${c.numero}</td><td>Dr. ${m.nom}</td><td>${formatDate(h.dateAdmission)}</td><td>${h.motif}</td><td><button class="btn btn-danger btn-sm" onclick="sortiePatient(${h.id})">Sortie</button></td></tr>`;
    }).join('');
}

function loadAdmissionSelects() {
    const pOpts = DATA.patients.filter(p => p.statut === 'actif').map(p => `<option value="${p.id}">${p.prenom} ${p.nom}</option>`).join('');
    const cOpts = DATA.getChambresLibres().map(c => `<option value="${c.id}">${c.numero} - ${c.type}</option>`).join('');
    const mOpts = DATA.medecins.map(m => `<option value="${m.id}">Dr. ${m.nom}</option>`).join('');
    document.getElementById('admPatient').innerHTML = '<option value="">Sélectionner</option>' + pOpts;
    document.getElementById('admChambre').innerHTML = '<option value="">Sélectionner</option>' + cOpts;
    document.getElementById('admMedecin').innerHTML = '<option value="">Sélectionner</option>' + mOpts;
}

function saveAdmission(e) {
    e.preventDefault();
    const patientId = parseInt(document.getElementById('admPatient').value);
    const chambreId = parseInt(document.getElementById('admChambre').value);
    DATA.hospitalisations.push({
        id: DATA.hospitalisations.length + 1,
        patientId, chambreId,
        medecinId: parseInt(document.getElementById('admMedecin').value),
        dateAdmission: new Date().toISOString().split('T')[0],
        dateSortie: null,
        motif: document.getElementById('admMotif').value,
        statut: 'en_cours'
    });
    DATA.patients.find(p => p.id === patientId).statut = 'hospitalise';
    DATA.chambres.find(c => c.id === chambreId).statut = 'occupee';
    DATA.chambres.find(c => c.id === chambreId).patientId = patientId;
    closeModal('modalAdmission');
    loadChambres();
    loadHospitalisations();
    loadAdmissionSelects();
    alert('Patient admis');
}

function sortiePatient(hospId) {
    if (!confirm('Confirmer la sortie du patient?')) return;
    const h = DATA.hospitalisations.find(x => x.id === hospId);
    h.statut = 'termine';
    h.dateSortie = new Date().toISOString().split('T')[0];
    DATA.patients.find(p => p.id === h.patientId).statut = 'actif';
    const c = DATA.chambres.find(x => x.id === h.chambreId);
    c.statut = 'libre';
    c.patientId = null;
    loadChambres();
    loadHospitalisations();
    loadAdmissionSelects();
}

// ==================== PHARMACIE ====================
function loadMedicaments() {
    renderMedicaments(DATA.medicaments);
    const alertes = DATA.getMedicamentsStockBas();
    document.getElementById('alertesContainer').innerHTML = alertes.length ? `<div class="card-header"><h2 class="card-title text-danger">Alertes Stock (${alertes.length})</h2></div><div class="card-body">${alertes.map(m => `<div class="alert-item"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg><div class="alert-text"><div class="alert-title">${m.nom}</div><div class="alert-sub">Stock: ${m.stock} / Min: ${m.stockMin}</div></div></div>`).join('')}</div>` : '';
}

function renderMedicaments(meds) {
    document.getElementById('tableMedicaments').innerHTML = meds.map(m => {
        const isLow = m.stock <= m.stockMin;
        return `<tr><td class="font-medium">${m.nom}</td><td>${m.categorie}</td><td>${m.forme}</td><td class="${isLow ? 'text-danger font-bold' : ''}">${m.stock}</td><td>${m.stockMin}</td><td>${formatMoney(m.prixUnitaire)}</td><td>${isLow ? '<span class="badge badge-danger">Stock bas</span>' : '<span class="badge badge-success">OK</span>'}</td></tr>`;
    }).join('');
}

function filterMedicaments() {
    const search = document.getElementById('searchMedic').value.toLowerCase();
    const cat = document.getElementById('filterCategorie').value;
    const filtered = DATA.medicaments.filter(m => m.nom.toLowerCase().includes(search) && (!cat || m.categorie === cat));
    renderMedicaments(filtered);
}

function loadMedicamentSelect() {
    document.getElementById('mouvMedic').innerHTML = '<option value="">Sélectionner</option>' + DATA.medicaments.map(m => `<option value="${m.id}">${m.nom}</option>`).join('');
}

function saveMedicament(e) {
    e.preventDefault();
    DATA.medicaments.push({
        id: DATA.medicaments.length + 1,
        nom: document.getElementById('medicNom').value,
        categorie: document.getElementById('medicCategorie').value,
        forme: document.getElementById('medicForme').value,
        stock: parseInt(document.getElementById('medicStock').value) || 0,
        stockMin: parseInt(document.getElementById('medicStockMin').value) || 10,
        prixUnitaire: parseInt(document.getElementById('medicPrix').value) || 0,
        fournisseur: document.getElementById('medicFournisseur').value
    });
    closeModal('modalMedicament');
    document.getElementById('formMedicament').reset();
    loadMedicaments();
    loadMedicamentSelect();
    alert('Médicament ajouté');
}

function saveMouvement(e) {
    e.preventDefault();
    const medId = parseInt(document.getElementById('mouvMedic').value);
    const type = document.getElementById('mouvType').value;
    const qte = parseInt(document.getElementById('mouvQte').value);
    const med = DATA.medicaments.find(m => m.id === medId);
    if (type === 'entree') med.stock += qte;
    else if (med.stock >= qte) med.stock -= qte;
    else { alert('Stock insuffisant'); return; }
    closeModal('modalMouvement');
    document.getElementById('formMouvement').reset();
    loadMedicaments();
    alert('Mouvement enregistré');
}

// ==================== CAISSE ====================
function loadCaisse() {
    const today = "2024-02-20";
    const transToday = DATA.transactions.filter(t => t.date === today);
    const recettes = transToday.filter(t => t.type === 'entree').reduce((s, t) => s + t.montant, 0);
    const depenses = transToday.filter(t => t.type === 'sortie').reduce((s, t) => s + t.montant, 0);
    const impayes = DATA.getPaiementsEnAttente().reduce((s, p) => s + p.montant, 0);

    document.getElementById('statRecettesJ').textContent = formatMoney(recettes);
    document.getElementById('statDepensesJ').textContent = formatMoney(depenses);
    document.getElementById('statSoldeJ').textContent = formatMoney(recettes - depenses);
    document.getElementById('statImpayes').textContent = formatMoney(impayes);

    renderPaiements(DATA.paiements);
    renderTransactions(DATA.transactions);
}

function renderPaiements(paiements) {
    document.getElementById('tablePaiements').innerHTML = paiements.map(p => {
        const patient = DATA.getPatientById(p.patientId);
        return `<tr><td><div class="user-cell"><div class="avatar">${getInitials(patient.nom, patient.prenom)}</div><span>${patient.prenom} ${patient.nom}</span></div></td><td>${formatDate(p.date)}</td><td>${p.type}</td><td class="font-medium">${formatMoney(p.montant)}</td><td>${p.modePaiement || '-'}</td><td>${statusBadge(p.statut)}</td><td>${p.statut === 'en_attente' ? `<button class="btn btn-success btn-sm" onclick="encaisser(${p.id})">Encaisser</button>` : '-'}</td></tr>`;
    }).join('');
}

function renderTransactions(trans) {
    document.getElementById('tableTransactions').innerHTML = trans.map(t =>
        `<tr><td>${formatDate(t.date)}</td><td>${t.description}</td><td>${t.categorie}</td><td class="text-success font-medium">${t.type === 'entree' ? formatMoney(t.montant) : '-'}</td><td class="text-danger font-medium">${t.type === 'sortie' ? formatMoney(t.montant) : '-'}</td></tr>`
    ).join('');
}

function filterPaiements() {
    const search = document.getElementById('searchPaiement').value.toLowerCase();
    const statut = document.getElementById('filterStatutP').value;
    const filtered = DATA.paiements.filter(p => {
        const patient = DATA.getPatientById(p.patientId);
        return (patient.nom + ' ' + patient.prenom).toLowerCase().includes(search) && (!statut || p.statut === statut);
    });
    renderPaiements(filtered);
}

function loadPaiementSelect() {
    document.getElementById('paiementPatient').innerHTML = '<option value="">Sélectionner</option>' + DATA.patients.map(p => `<option value="${p.id}">${p.prenom} ${p.nom}</option>`).join('');
}

function encaisser(id) {
    const mode = prompt('Mode de paiement (especes, mobile_money, carte, virement):');
    if (!mode) return;
    const p = DATA.paiements.find(x => x.id === id);
    p.statut = 'paye';
    p.modePaiement = mode;
    DATA.transactions.push({ id: DATA.transactions.length + 1, date: new Date().toISOString().split('T')[0], type: 'entree', montant: p.montant, description: p.description, categorie: p.type.toLowerCase() });
    loadCaisse();
}

function savePaiement(e) {
    e.preventDefault();
    const montant = parseInt(document.getElementById('paiementMontant').value);
    DATA.paiements.push({
        id: DATA.paiements.length + 1,
        patientId: parseInt(document.getElementById('paiementPatient').value),
        date: document.getElementById('paiementDate').value,
        montant,
        type: document.getElementById('paiementType').value,
        description: document.getElementById('paiementDesc').value || document.getElementById('paiementType').value,
        modePaiement: document.getElementById('paiementMode').value,
        statut: 'paye'
    });
    DATA.transactions.push({ id: DATA.transactions.length + 1, date: document.getElementById('paiementDate').value, type: 'entree', montant, description: document.getElementById('paiementDesc').value || 'Paiement patient', categorie: document.getElementById('paiementType').value.toLowerCase() });
    closeModal('modalPaiement');
    document.getElementById('formPaiement').reset();
    loadCaisse();
    alert('Paiement enregistré');
}

function saveTransaction(e) {
    e.preventDefault();
    DATA.transactions.push({
        id: DATA.transactions.length + 1,
        date: new Date().toISOString().split('T')[0],
        type: document.getElementById('transType').value,
        montant: parseInt(document.getElementById('transMontant').value),
        description: document.getElementById('transDesc').value,
        categorie: document.getElementById('transCategorie').value
    });
    closeModal('modalTransaction');
    document.getElementById('formTransaction').reset();
    loadCaisse();
    alert('Transaction enregistrée');
}

// ==================== DOSSIER MÉDICAL ====================
function loadPatientSelectDossier() {
    document.getElementById('selectPatientDossier').innerHTML = '<option value="">Sélectionner un patient</option>' + DATA.patients.map(p => `<option value="${p.id}">${p.prenom} ${p.nom}</option>`).join('');
}

function loadDossierMedical() {
    const patientId = parseInt(document.getElementById('selectPatientDossier').value);
    if (!patientId) { document.getElementById('dossierContent').classList.add('hidden'); document.getElementById('noDossier').classList.remove('hidden'); return; }

    document.getElementById('dossierContent').classList.remove('hidden');
    document.getElementById('noDossier').classList.add('hidden');

    const p = DATA.getPatientById(patientId);
    const dossier = DATA.dossiersMedicaux.find(d => d.patientId === patientId) || { antecedents: [], maladiesChroniques: [], chirurgies: [], notes: '' };
    const consults = DATA.getConsultationsByPatient(patientId);
    const prescriptions = DATA.getPrescriptionsByPatient(patientId);
    const hosps = DATA.hospitalisations.filter(h => h.patientId === patientId);

    document.getElementById('patientInfo').innerHTML = `<div class="avatar lg">${getInitials(p.nom, p.prenom)}</div><div><h2>${p.prenom} ${p.nom}</h2><p class="text-muted">${getAge(p.dateNaissance)} ans - ${p.sexe === 'M' ? 'Masculin' : 'Féminin'} - ${p.groupeSanguin || 'GS inconnu'}</p><p>${p.telephone} | ${p.email || '-'}</p></div>`;

    document.getElementById('antecedentsContent').innerHTML = `
        ${p.allergies.length ? `<div class="mb-3"><strong class="text-danger">Allergies:</strong> ${p.allergies.join(', ')}</div>` : ''}
        ${dossier.antecedents.length ? `<div class="mb-3"><strong>Antécédents:</strong> ${dossier.antecedents.join(', ')}</div>` : ''}
        ${dossier.maladiesChroniques.length ? `<div class="mb-3"><strong>Maladies chroniques:</strong> ${dossier.maladiesChroniques.join(', ')}</div>` : ''}
        ${dossier.chirurgies.length ? `<div class="mb-3"><strong>Chirurgies:</strong> ${dossier.chirurgies.join(', ')}</div>` : ''}
        ${dossier.notes ? `<div><strong>Notes:</strong> ${dossier.notes}</div>` : ''}
        ${!p.allergies.length && !dossier.antecedents.length && !dossier.notes ? '<p class="text-muted">Aucun antécédent enregistré</p>' : ''}`;

    document.getElementById('historiqueConsult').innerHTML = consults.length ? consults.map(c => {
        const m = DATA.getMedecinById(c.medecinId);
        return `<tr><td>${formatDate(c.date)}</td><td>Dr. ${m.nom}</td><td>${c.diagnostic || '-'}</td></tr>`;
    }).join('') : '<tr><td colspan="3" class="text-center text-muted">Aucune consultation</td></tr>';

    document.getElementById('prescriptionsContent').innerHTML = prescriptions.length ? prescriptions.map(pr =>
        `<div style="background:var(--gray-100);padding:12px;border-radius:8px;margin-bottom:12px;"><div class="text-xs text-muted mb-2">${formatDate(pr.date)}</div>${pr.medicaments.map(m => `<div class="mb-1"><strong>${m.nom}</strong> - ${m.posologie} (${m.duree})</div>`).join('')}</div>`
    ).join('') : '<p class="text-muted">Aucune prescription</p>';

    document.getElementById('historiqueHosp').innerHTML = hosps.length ? hosps.map(h => {
        const c = DATA.getChambreById(h.chambreId);
        return `<tr><td>${formatDate(h.dateAdmission)}</td><td>${c.numero}</td><td>${h.motif}</td><td>${statusBadge(h.statut)}</td></tr>`;
    }).join('') : '<tr><td colspan="4" class="text-center text-muted">Aucune hospitalisation</td></tr>';
}

// ==================== PLANNING ====================
function loadRendezvous() {
    renderRendezvous(DATA.rendezvous);
}

function renderRendezvous(rdvs) {
    document.getElementById('tableRdv').innerHTML = rdvs.map(r => {
        const p = DATA.getPatientById(r.patientId);
        const m = DATA.getMedecinById(r.medecinId);
        return `<tr><td><div class="user-cell"><div class="avatar">${getInitials(p.nom, p.prenom)}</div><span>${p.prenom} ${p.nom}</span></div></td><td>${formatDate(r.date)}</td><td>${r.heure}</td><td>Dr. ${m.nom}</td><td>${r.motif}</td><td>${statusBadge(r.statut)}</td><td><button class="btn btn-outline btn-sm" onclick="confirmerRdv(${r.id})">Confirmer</button></td></tr>`;
    }).join('');
}

function filterRdv() {
    const date = document.getElementById('filterDateRdv').value;
    const medecin = document.getElementById('filterMedecinRdv').value;
    const filtered = DATA.rendezvous.filter(r => (!date || r.date === date) && (!medecin || r.medecinId == medecin));
    renderRendezvous(filtered);
}

function loadPlanningSelects() {
    const mOpts = DATA.medecins.map(m => `<option value="${m.id}">Dr. ${m.prenom} ${m.nom}</option>`).join('');
    const pOpts = DATA.patients.map(p => `<option value="${p.id}">${p.prenom} ${p.nom}</option>`).join('');
    document.getElementById('filterMedecinRdv').innerHTML = '<option value="">Tous</option>' + mOpts;
    document.getElementById('selectMedecinPlanning').innerHTML = '<option value="">Sélectionner</option>' + mOpts;
    document.getElementById('rdvPatient').innerHTML = '<option value="">Sélectionner</option>' + pOpts;
    document.getElementById('rdvMedecin').innerHTML = '<option value="">Sélectionner</option>' + mOpts;
}

function loadPlanningMedecin() {
    const medecinId = parseInt(document.getElementById('selectMedecinPlanning').value);
    if (!medecinId) { document.getElementById('tablePlanning').innerHTML = '<tr><td colspan="3" class="text-center text-muted">Sélectionner un médecin</td></tr>'; return; }
    const planning = DATA.getPlanningMedecin(medecinId);
    const jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
    document.getElementById('tablePlanning').innerHTML = jours.map(j => {
        const p = planning.find(x => x.jour === j);
        return `<tr><td class="font-medium">${j.charAt(0).toUpperCase() + j.slice(1)}</td><td>${p ? p.debut : '-'}</td><td>${p ? p.fin : '-'}</td></tr>`;
    }).join('');
}

function confirmerRdv(id) {
    const r = DATA.rendezvous.find(x => x.id === id);
    if (r) { r.statut = 'confirme'; loadRendezvous(); }
}

function saveRdv(e) {
    e.preventDefault();
    DATA.rendezvous.push({
        id: DATA.rendezvous.length + 1,
        patientId: parseInt(document.getElementById('rdvPatient').value),
        medecinId: parseInt(document.getElementById('rdvMedecin').value),
        date: document.getElementById('rdvDate').value,
        heure: document.getElementById('rdvHeure').value,
        motif: document.getElementById('rdvMotif').value,
        statut: 'en_attente'
    });
    closeModal('modalRdv');
    document.getElementById('formRdv').reset();
    loadRendezvous();
    alert('Rendez-vous enregistré');
}

// ==================== RÉCEPTION ====================
function loadReceptionDashboard() {
    const today = new Date().toISOString().split('T')[0];
    const patientsToday = DATA.consultations.filter(c => c.date === today).length;
    const enAttente = DATA.consultations.filter(c => c.statut === 'en_attente').length;
    const facturesEnvoyees = DATA.factures.filter(f => f.date === today).length;
    const attentePaiement = DATA.getFacturesEnAttente().length;

    document.getElementById('statPatientsToday').textContent = patientsToday;
    document.getElementById('statConsultAttente').textContent = enAttente;
    document.getElementById('statFactures').textContent = facturesEnvoyees;
    document.getElementById('statAttentePaiement').textContent = attentePaiement;

    // File d'attente
    const fileAttente = DATA.consultations.filter(c => c.statut === 'en_attente').slice(0, 5);
    document.getElementById('fileAttente').innerHTML = fileAttente.length ? fileAttente.map(c => {
        const p = DATA.getPatientById(c.patientId);
        const m = DATA.getMedecinById(c.medecinId);
        return `<tr><td><div class="user-cell"><div class="avatar">${getInitials(p.nom, p.prenom)}</div><span>${p.prenom} ${p.nom}</span></div></td><td>${c.heure}</td><td>Dr. ${m.nom}</td><td>${statusBadge(c.statut)}</td></tr>`;
    }).join('') : '<tr><td colspan="4" class="text-center text-muted">Aucun patient</td></tr>';
}

function loadSelectsReception() {
    const pOpts = DATA.patients.map(p => `<option value="${p.id}">${p.prenom} ${p.nom}</option>`).join('');
    const mOpts = DATA.medecins.map(m => `<option value="${m.id}">Dr. ${m.prenom} ${m.nom} - ${m.specialite}</option>`).join('');
    if (document.getElementById('cPatient')) document.getElementById('cPatient').innerHTML = '<option value="">Sélectionner</option>' + pOpts;
    if (document.getElementById('cMedecin')) document.getElementById('cMedecin').innerHTML = '<option value="">Sélectionner</option>' + mOpts;
    if (document.getElementById('cDate')) document.getElementById('cDate').value = new Date().toISOString().split('T')[0];
    if (document.getElementById('cHeure')) document.getElementById('cHeure').value = new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
}

function loadPatientsR() {
    document.getElementById('tablePatientsR').innerHTML = DATA.patients.map(p =>
        `<tr><td><div class="user-cell"><div class="avatar">${getInitials(p.nom, p.prenom)}</div><div><div class="user-name">${p.prenom} ${p.nom}</div><div class="user-sub">${getAge(p.dateNaissance)} ans - ${p.sexe}</div></div></div></td><td>${p.telephone}</td><td>${formatDate(p.dateInscription)}</td><td><button class="btn btn-outline btn-sm" onclick="openModalConsultFor(${p.id})">Consultation</button></td></tr>`
    ).join('');
}

function filterPatientsR() {
    const search = document.getElementById('searchPatientR').value.toLowerCase();
    const filtered = DATA.patients.filter(p => (p.nom + ' ' + p.prenom + ' ' + p.telephone).toLowerCase().includes(search));
    document.getElementById('tablePatientsR').innerHTML = filtered.map(p =>
        `<tr><td><div class="user-cell"><div class="avatar">${getInitials(p.nom, p.prenom)}</div><div><div class="user-name">${p.prenom} ${p.nom}</div><div class="user-sub">${getAge(p.dateNaissance)} ans - ${p.sexe}</div></div></div></td><td>${p.telephone}</td><td>${formatDate(p.dateInscription)}</td><td><button class="btn btn-outline btn-sm" onclick="openModalConsultFor(${p.id})">Consultation</button></td></tr>`
    ).join('');
}

function openModalConsultFor(patientId) {
    loadSelectsReception();
    document.getElementById('cPatient').value = patientId;
    openModal('modalConsultRapide');
}

function savePatientR(e) {
    e.preventDefault();
    const newPatient = {
        id: DATA.patients.length + 1,
        nom: document.getElementById('pNom').value,
        prenom: document.getElementById('pPrenom').value,
        dateNaissance: document.getElementById('pDN').value,
        sexe: document.getElementById('pSexe').value,
        groupeSanguin: document.getElementById('pGS').value,
        telephone: document.getElementById('pTel').value,
        email: document.getElementById('pEmail').value,
        adresse: document.getElementById('pAdresse').value,
        allergies: document.getElementById('pAllergies').value.split(',').map(a => a.trim()).filter(a => a),
        dateInscription: new Date().toISOString().split('T')[0],
        statut: 'actif'
    };
    DATA.patients.push(newPatient);

    const consultDirecte = document.getElementById('pConsultDirecte').checked;
    closeModal('modalPatient');
    document.getElementById('formPatientR').reset();
    loadReceptionDashboard();
    loadSelectsReception();

    if (consultDirecte) {
        document.getElementById('cPatient').value = newPatient.id;
        openModal('modalConsultRapide');
    } else {
        alert('Patient enregistré avec succès');
    }
}

function saveConsultR(e) {
    e.preventDefault();
    const patientId = parseInt(document.getElementById('cPatient').value);
    const medecinId = parseInt(document.getElementById('cMedecin').value);

    const newConsult = {
        id: DATA.consultations.length + 1,
        patientId,
        medecinId,
        date: document.getElementById('cDate').value,
        heure: document.getElementById('cHeure').value,
        motif: document.getElementById('cMotif').value,
        diagnostic: '',
        notes: '',
        statut: 'en_attente'
    };
    DATA.consultations.push(newConsult);

    // Ajouter à la file d'attente
    DATA.fileAttente.push({
        id: DATA.fileAttente.length + 1,
        consultationId: newConsult.id,
        patientId,
        medecinId,
        heureArrivee: document.getElementById('cHeure').value,
        position: DATA.fileAttente.filter(f => f.medecinId === medecinId).length + 1,
        statut: 'en_attente'
    });

    // Note: La facture détaillée sera créée par le médecin après la consultation
    // avec tous les actes médicaux réalisés

    closeModal('modalConsultRapide');
    document.getElementById('formConsultR').reset();
    loadReceptionDashboard();
    loadSelectsReception();
    alert('Patient ajouté à la file d\'attente.\nLa facture sera générée après la consultation avec tous les actes réalisés.');
}

function loadConsultR() {
    renderConsultR(DATA.consultations);
}

function renderConsultR(consults) {
    document.getElementById('tableConsultR').innerHTML = consults.map(c => {
        const p = DATA.getPatientById(c.patientId);
        const m = DATA.getMedecinById(c.medecinId);
        const facture = DATA.factures.find(f => f.consultationId === c.id);
        return `<tr><td><div class="user-cell"><div class="avatar">${getInitials(p.nom, p.prenom)}</div><span>${p.prenom} ${p.nom}</span></div></td><td>${formatDate(c.date)}</td><td>Dr. ${m.nom}</td><td>${statusBadge(c.statut)}</td><td>${facture ? statusBadge(facture.statut === 'payee' ? 'paye' : 'non_paye') : '<span class="badge badge-secondary">Non créée</span>'}</td><td>${!facture ? `<button class="btn btn-outline btn-sm" onclick="creerFacture(${c.id})">Facturer</button>` : '-'}</td></tr>`;
    }).join('');
}

function filterConsultR() {
    const statut = document.getElementById('filterStatutR').value;
    const filtered = DATA.consultations.filter(c => !statut || c.statut === statut);
    renderConsultR(filtered);
}

function creerFacture(consultId) {
    const c = DATA.consultations.find(x => x.id === consultId);
    const m = DATA.getMedecinById(c.medecinId);
    DATA.factures.push({
        id: DATA.factures.length + 1,
        numero: DATA.generateNumeroFacture(),
        patientId: c.patientId,
        consultationId: consultId,
        date: new Date().toISOString().split('T')[0],
        type: 'consultation',
        montant: m.tarifConsultation,
        statut: 'en_attente',
        envoyePar: 'reception',
        modePaiement: '',
        datePaiement: null
    });
    loadConsultR();
    alert('Facture créée et envoyée à la caisse');
}

function loadFactures() {
    renderFactures(DATA.factures);
}

function renderFactures(factures) {
    document.getElementById('tableFactures').innerHTML = factures.map(f => {
        const p = DATA.getPatientById(f.patientId);
        return `<tr><td class="font-medium">${f.numero}</td><td>${p.prenom} ${p.nom}</td><td>${formatDate(f.date)}</td><td class="font-medium">${formatMoney(f.montant)}</td><td>${statusBadge(f.statut === 'payee' ? 'paye' : f.statut === 'en_attente' ? 'en_attente' : f.statut)}</td><td>${f.statut === 'en_attente' ? '<span class="badge badge-info">En caisse</span>' : '-'}</td></tr>`;
    }).join('');
}

function filterFactures() {
    const statut = document.getElementById('filterFacture').value;
    const filtered = DATA.factures.filter(f => {
        if (!statut) return true;
        if (statut === 'payee') return f.statut === 'payee';
        if (statut === 'en_attente') return f.statut === 'en_attente';
        if (statut === 'envoyee') return f.statut === 'en_attente';
        return true;
    });
    renderFactures(filtered);
}

// ==================== MÉDECIN ====================
let actesSelectionnes = []; // Liste des actes ajoutés à la fiche de traitement
let lignesAppro = []; // Lignes de la fiche d'approvisionnement en cours

function loadMedecinDashboard() {
    const medecinId = 1; // Dr. Yao par défaut
    const today = new Date().toISOString().split('T')[0];
    const enAttente = DATA.getConsultationsEnAttenteMedecin(medecinId).length;
    const consultToday = DATA.consultations.filter(c => c.medecinId === medecinId && c.date === today && c.statut === 'termine').length;
    const ordonnances = DATA.ordonnances.filter(o => o.medecinId === medecinId).length;

    document.getElementById('medAttente').textContent = enAttente;
    document.getElementById('medConsultToday').textContent = consultToday;
    document.getElementById('medOrdonnances').textContent = ordonnances;

    // Prochains patients
    const file = DATA.getConsultationsEnAttenteMedecin(medecinId).slice(0, 5);
    document.getElementById('medFileAttente').innerHTML = file.length ? file.map(c => {
        const p = DATA.getPatientById(c.patientId);
        return `<tr><td>${c.heure}</td><td><div class="user-cell"><div class="avatar">${getInitials(p.nom, p.prenom)}</div><span>${p.prenom} ${p.nom}</span></div></td><td>${c.motif}</td><td><button class="btn btn-primary btn-sm" onclick="appelPatient(${c.id})">Appeler</button></td></tr>`;
    }).join('') : '<tr><td colspan="4" class="text-center text-muted">Aucun patient en attente</td></tr>';
}

function loadActesMedicaux() {
    const select = document.getElementById('selectActeMedical');
    if (!select) return;

    let options = '<option value="">-- Choisir un acte --</option>';
    const categories = [
        { key: 'consultation', label: '📋 Consultations' },
        { key: 'examen', label: '🔬 Examens' },
        { key: 'soin', label: '💉 Soins' },
        { key: 'acte', label: '🏥 Actes spécialisés' }
    ];

    categories.forEach(cat => {
        const actes = DATA.getActesMedicauxByCategorie(cat.key);
        if (actes.length) {
            options += `<optgroup label="${cat.label}">`;
            actes.forEach(a => {
                const prixLabel = a.prix > 0 ? formatMoney(a.prix) : 'Gratuit';
                options += `<option value="${a.id}" data-prix="${a.prix}" data-facturable="${a.facturable}">${a.nom} - ${prixLabel}</option>`;
            });
            options += '</optgroup>';
        }
    });

    select.innerHTML = options;
}

function ajouterActe() {
    const select = document.getElementById('selectActeMedical');
    const qte = parseInt(document.getElementById('qteActe').value) || 1;
    const acteId = parseInt(select.value);

    if (!acteId) { alert('Veuillez sélectionner un acte'); return; }

    const acte = DATA.getActeMedicalById(acteId);
    if (!acte) return;

    // Vérifier si l'acte est déjà ajouté
    const existant = actesSelectionnes.find(a => a.acteId === acteId);
    if (existant) {
        existant.quantite += qte;
    } else {
        actesSelectionnes.push({
            acteId: acte.id,
            code: acte.code,
            nom: acte.nom,
            categorie: acte.categorie,
            prix: acte.prix,
            quantite: qte,
            facturable: acte.facturable
        });
    }

    select.value = '';
    document.getElementById('qteActe').value = 1;
    renderActesSelectionnes();
}

function supprimerActe(index) {
    actesSelectionnes.splice(index, 1);
    renderActesSelectionnes();
}

function renderActesSelectionnes() {
    const container = document.getElementById('listeActes');
    const noActes = document.getElementById('noActes');
    const totalDiv = document.getElementById('totalActes');

    if (actesSelectionnes.length === 0) {
        container.innerHTML = '<p class="text-muted text-center" id="noActes">Aucun acte ajouté</p>';
        totalDiv.style.display = 'none';
        return;
    }

    let html = '<table style="width:100%;"><tbody>';
    let total = 0;

    actesSelectionnes.forEach((acte, index) => {
        const sousTotal = acte.prix * acte.quantite;
        if (acte.facturable) total += sousTotal;

        const catIcon = { consultation: '📋', examen: '🔬', soin: '💉', acte: '🏥' }[acte.categorie] || '•';

        html += `<tr style="border-bottom:1px solid var(--gray-200);">
            <td style="padding:8px 0;"><span style="margin-right:6px;">${catIcon}</span>${acte.nom}</td>
            <td style="text-align:center;width:60px;">x${acte.quantite}</td>
            <td style="text-align:right;width:100px;" class="${acte.facturable ? 'font-medium' : 'text-muted'}">${acte.facturable ? formatMoney(sousTotal) : '<span class="badge badge-secondary">Gratuit</span>'}</td>
            <td style="text-align:right;width:40px;"><button class="btn btn-outline btn-sm" style="padding:2px 6px;color:var(--danger);" onclick="supprimerActe(${index})">×</button></td>
        </tr>`;
    });

    html += '</tbody></table>';
    container.innerHTML = html;

    document.getElementById('montantTotal').textContent = formatMoney(total);
    totalDiv.style.display = 'block';
}

function loadMedAttenteTable() {
    const medecinId = 1;
    const file = DATA.getConsultationsEnAttenteMedecin(medecinId);
    document.getElementById('medAttenteTable').innerHTML = file.length ? file.map((c, idx) => {
        const p = DATA.getPatientById(c.patientId);
        return `<tr><td class="font-medium">${idx + 1}</td><td>${c.heure}</td><td><div class="user-cell"><div class="avatar">${getInitials(p.nom, p.prenom)}</div><span>${p.prenom} ${p.nom}</span></div></td><td>${getAge(p.dateNaissance)} ans</td><td>${c.motif}</td><td><button class="btn btn-primary btn-sm" onclick="appelPatient(${c.id})">Appeler</button></td></tr>`;
    }).join('') : '<tr><td colspan="6" class="text-center text-muted">Aucun patient en attente</td></tr>';
}

function appelPatient(consultId) {
    currentConsultId = consultId;
    actesSelectionnes = []; // Reset des actes

    const c = DATA.consultations.find(x => x.id === consultId);
    const p = DATA.getPatientById(c.patientId);
    const m = DATA.getMedecinById(c.medecinId);
    const dossier = DATA.dossiersMedicaux.find(d => d.patientId === c.patientId) || { antecedents: [], maladiesChroniques: [], notes: '' };

    c.statut = 'en_cours';

    document.getElementById('noConsult').classList.add('hidden');
    document.getElementById('consultEnCours').classList.remove('hidden');

    document.getElementById('consultPatientInfo').innerHTML = `
        <div class="user-cell mb-3"><div class="avatar lg">${getInitials(p.nom, p.prenom)}</div><div><div class="user-name" style="font-size:1.25rem;">${p.prenom} ${p.nom}</div><div class="text-muted">${getAge(p.dateNaissance)} ans - ${p.sexe === 'M' ? 'Masculin' : 'Féminin'}</div></div></div>
        <div class="grid-2"><div style="background:var(--gray-100);padding:8px;border-radius:6px;"><span class="text-xs text-muted">Tél:</span> ${p.telephone}</div><div style="background:var(--gray-100);padding:8px;border-radius:6px;"><span class="text-xs text-muted">GS:</span> ${p.groupeSanguin || '-'}</div></div>
        ${p.allergies.length ? `<div class="mt-3" style="background:var(--danger-light);padding:8px;border-radius:6px;"><span class="text-danger font-medium">Allergies:</span> ${p.allergies.join(', ')}</div>` : ''}`;

    document.getElementById('consultAntecedents').innerHTML = `
        ${dossier.antecedents.length ? `<div class="mb-2"><strong>Antécédents:</strong> ${dossier.antecedents.join(', ')}</div>` : ''}
        ${dossier.maladiesChroniques.length ? `<div class="mb-2"><strong>Maladies chroniques:</strong> ${dossier.maladiesChroniques.join(', ')}</div>` : ''}
        ${dossier.notes ? `<div><strong>Notes:</strong> ${dossier.notes}</div>` : ''}
        ${!dossier.antecedents.length && !dossier.maladiesChroniques.length && !dossier.notes ? '<p class="text-muted">Aucun antécédent</p>' : ''}`;

    document.getElementById('consultMotif').textContent = c.motif;

    // Charger les actes médicaux
    loadActesMedicaux();

    // Ajouter automatiquement la consultation selon la spécialité
    const consultActe = m.specialite === 'Médecine générale' ? DATA.getActeMedicalById(1) : DATA.getActeMedicalById(2);
    if (consultActe) {
        actesSelectionnes.push({
            acteId: consultActe.id,
            code: consultActe.code,
            nom: consultActe.nom,
            categorie: consultActe.categorie,
            prix: consultActe.prix,
            quantite: 1,
            facturable: consultActe.facturable
        });
        renderActesSelectionnes();
    }

    // Reset form
    document.getElementById('diagConsult').value = '';
    document.getElementById('notesConsult').value = '';
    document.getElementById('recommandations').value = '';
    document.getElementById('prescriptionRows').innerHTML = '<div class="form-row-3 prescription-row" style="margin-bottom:12px;"><input type="text" class="form-control" placeholder="Médicament"><input type="text" class="form-control" placeholder="Posologie"><input type="text" class="form-control" placeholder="Durée"></div>';

    loadMedecinDashboard();
    loadMedAttenteTable();
}

function annulerConsult() {
    if (!currentConsultId || !confirm('Annuler cette consultation?')) return;
    const c = DATA.consultations.find(x => x.id === currentConsultId);
    c.statut = 'en_attente';
    currentConsultId = null;
    document.getElementById('consultEnCours').classList.add('hidden');
    document.getElementById('noConsult').classList.remove('hidden');
    loadMedecinDashboard();
}

function terminerConsult() {
    if (!currentConsultId) return;
    const c = DATA.consultations.find(x => x.id === currentConsultId);
    const diag = document.getElementById('diagConsult').value;
    if (!diag) { alert('Veuillez saisir un diagnostic'); return; }
    if (actesSelectionnes.length === 0) { alert('Veuillez ajouter au moins un acte médical'); return; }

    c.diagnostic = diag;
    c.notes = document.getElementById('notesConsult').value;
    c.statut = 'termine';

    const today = new Date().toISOString().split('T')[0];

    // Créer la fiche de traitement
    const ficheTraitement = {
        id: DATA.fichesTraitement.length + 1,
        consultationId: currentConsultId,
        patientId: c.patientId,
        medecinId: c.medecinId,
        date: today,
        actes: actesSelectionnes.map(a => ({
            acteId: a.acteId,
            nom: a.nom,
            prix: a.prix,
            quantite: a.quantite,
            facturable: a.facturable
        })),
        observations: c.notes,
        totalFacturable: actesSelectionnes.filter(a => a.facturable).reduce((sum, a) => sum + (a.prix * a.quantite), 0)
    };
    DATA.fichesTraitement.push(ficheTraitement);

    // Créer la facture détaillée avec toutes les lignes
    const lignesFacture = actesSelectionnes
        .filter(a => a.facturable && a.prix > 0)
        .map(a => ({
            description: a.nom,
            quantite: a.quantite,
            prixUnitaire: a.prix,
            total: a.prix * a.quantite
        }));

    const montantTotal = lignesFacture.reduce((sum, l) => sum + l.total, 0);

    // Supprimer l'ancienne facture provisoire si elle existe
    const factureIdx = DATA.factures.findIndex(f => f.consultationId === currentConsultId);
    if (factureIdx > -1) {
        DATA.factures.splice(factureIdx, 1);
    }

    // Créer la nouvelle facture complète
    DATA.factures.push({
        id: DATA.factures.length + 1,
        numero: DATA.generateNumeroFacture(),
        patientId: c.patientId,
        consultationId: currentConsultId,
        ficheTraitementId: ficheTraitement.id,
        date: today,
        lignes: lignesFacture,
        montant: montantTotal,
        statut: 'en_attente',
        envoyePar: 'medecin',
        modePaiement: '',
        datePaiement: null
    });

    // Récupérer les prescriptions
    const rows = document.querySelectorAll('.prescription-row');
    const medicaments = [];
    rows.forEach(row => {
        const inputs = row.querySelectorAll('input');
        if (inputs[0].value.trim()) {
            medicaments.push({ nom: inputs[0].value, posologie: inputs[1].value, duree: inputs[2].value });
        }
    });

    if (medicaments.length > 0) {
        DATA.ordonnances.push({
            id: DATA.ordonnances.length + 1,
            consultationId: currentConsultId,
            patientId: c.patientId,
            medecinId: c.medecinId,
            date: today,
            medicaments,
            statut: 'a_traiter',
            recommandations: document.getElementById('recommandations').value
        });
    }

    // Retirer de la file
    const fileIdx = DATA.fileAttente.findIndex(f => f.consultationId === currentConsultId);
    if (fileIdx > -1) DATA.fileAttente.splice(fileIdx, 1);

    // Reset
    currentConsultId = null;
    actesSelectionnes = [];
    document.getElementById('consultEnCours').classList.add('hidden');
    document.getElementById('noConsult').classList.remove('hidden');
    loadMedecinDashboard();

    alert(`Consultation terminée!\n\n📋 Fiche de traitement créée\n💰 Facture: ${formatMoney(montantTotal)} envoyée à la caisse${medicaments.length > 0 ? '\n💊 Ordonnance envoyée à la pharmacie' : ''}`);
}

function loadPatientSelectMed() {
    document.getElementById('selectPatientDossierMed').innerHTML = '<option value="">Sélectionner un patient</option>' + DATA.patients.map(p => `<option value="${p.id}">${p.prenom} ${p.nom}</option>`).join('');
}

function loadDossierMed() {
    const patientId = parseInt(document.getElementById('selectPatientDossierMed').value);
    if (!patientId) { document.getElementById('dossierMedContent').innerHTML = '<div class="text-center text-muted" style="padding:40px;">Sélectionnez un patient</div>'; return; }

    const p = DATA.getPatientById(patientId);
    const dossier = DATA.dossiersMedicaux.find(d => d.patientId === patientId) || { antecedents: [], maladiesChroniques: [], chirurgies: [], notes: '' };
    const consults = DATA.getConsultationsByPatient(patientId).filter(c => c.statut === 'termine');

    document.getElementById('dossierMedContent').innerHTML = `
        <div class="card mb-4"><div class="card-body"><div class="user-cell"><div class="avatar lg">${getInitials(p.nom, p.prenom)}</div><div><h2>${p.prenom} ${p.nom}</h2><p class="text-muted">${getAge(p.dateNaissance)} ans - ${p.sexe === 'M' ? 'Masculin' : 'Féminin'} - ${p.groupeSanguin || 'GS inconnu'}</p></div></div></div></div>
        <div class="grid-2 mb-4">
            <div class="card"><div class="card-header"><h2 class="card-title">Antécédents</h2></div><div class="card-body">${p.allergies.length ? `<div class="mb-2 text-danger"><strong>Allergies:</strong> ${p.allergies.join(', ')}</div>` : ''}${dossier.antecedents.length ? `<div class="mb-2">${dossier.antecedents.join(', ')}</div>` : ''}${dossier.maladiesChroniques.length ? `<div class="mb-2"><strong>Maladies chroniques:</strong> ${dossier.maladiesChroniques.join(', ')}</div>` : ''}${!p.allergies.length && !dossier.antecedents.length ? '<p class="text-muted">Aucun antécédent</p>' : ''}</div></div>
            <div class="card"><div class="card-header"><h2 class="card-title">Dernières consultations</h2></div><div class="card-body no-pad"><div class="table-wrap"><table><thead><tr><th>Date</th><th>Diagnostic</th></tr></thead><tbody>${consults.length ? consults.slice(0, 5).map(c => `<tr><td>${formatDate(c.date)}</td><td>${c.diagnostic || '-'}</td></tr>`).join('') : '<tr><td colspan="2" class="text-center text-muted">Aucune</td></tr>'}</tbody></table></div></div></div>
        </div>`;
}

function loadFichesTraitement() {
    const medecinId = 1;
    const fiches = DATA.fichesTraitement.filter(f => f.medecinId === medecinId);
    document.getElementById('tableFiches').innerHTML = fiches.length ? fiches.map(f => {
        const p = DATA.getPatientById(f.patientId);
        const nbActes = f.actes.length;
        return `<tr>
            <td>${formatDate(f.date)}</td>
            <td><div class="user-cell"><div class="avatar">${getInitials(p.nom, p.prenom)}</div><span>${p.prenom} ${p.nom}</span></div></td>
            <td><span class="badge badge-info">${nbActes} acte${nbActes > 1 ? 's' : ''}</span> ${f.actes.slice(0, 2).map(a => a.nom).join(', ')}${nbActes > 2 ? '...' : ''}</td>
            <td class="font-medium text-success">${formatMoney(f.totalFacturable)}</td>
            <td><button class="btn btn-outline btn-sm" onclick="voirFicheTraitement(${f.id})">Voir</button></td>
        </tr>`;
    }).join('') : '<tr><td colspan="5" class="text-center text-muted">Aucune fiche de traitement</td></tr>';
}

function voirFicheTraitement(ficheId) {
    const f = DATA.fichesTraitement.find(x => x.id === ficheId);
    if (!f) return;

    const p = DATA.getPatientById(f.patientId);
    const m = DATA.getMedecinById(f.medecinId);
    const c = DATA.consultations.find(x => x.id === f.consultationId);

    let html = `
        <div style="max-width:600px;margin:0 auto;padding:20px;">
            <div style="text-align:center;border-bottom:2px solid var(--primary);padding-bottom:16px;margin-bottom:16px;">
                <h2 style="color:var(--primary);margin:0;">Fiche de Traitement</h2>
                <p class="text-muted">MediCare Pro - ${formatDate(f.date)}</p>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:20px;">
                <div><strong>Patient:</strong> ${p.prenom} ${p.nom}<br><span class="text-muted">${getAge(p.dateNaissance)} ans</span></div>
                <div style="text-align:right;"><strong>Médecin:</strong> Dr. ${m.prenom} ${m.nom}<br><span class="text-muted">${m.specialite}</span></div>
            </div>
            ${c && c.motif ? `<div style="background:var(--gray-100);padding:12px;border-radius:8px;margin-bottom:16px;"><strong>Motif:</strong> ${c.motif}</div>` : ''}
            ${c && c.diagnostic ? `<div style="background:var(--primary-light);padding:12px;border-radius:8px;margin-bottom:16px;"><strong>Diagnostic:</strong> ${c.diagnostic}</div>` : ''}
            <h4 style="margin:20px 0 12px;">Actes réalisés</h4>
            <table style="width:100%;border-collapse:collapse;">
                <thead><tr style="background:var(--gray-100);"><th style="padding:8px;text-align:left;">Acte</th><th style="padding:8px;text-align:center;">Qté</th><th style="padding:8px;text-align:right;">Montant</th></tr></thead>
                <tbody>
                    ${f.actes.map(a => `<tr style="border-bottom:1px solid var(--gray-200);"><td style="padding:8px;">${a.nom}</td><td style="padding:8px;text-align:center;">${a.quantite}</td><td style="padding:8px;text-align:right;">${a.facturable ? formatMoney(a.prix * a.quantite) : '<span class="text-muted">Gratuit</span>'}</td></tr>`).join('')}
                </tbody>
                <tfoot><tr style="background:var(--success-light);"><td colspan="2" style="padding:12px;text-align:right;"><strong>Total facturable:</strong></td><td style="padding:12px;text-align:right;"><strong class="text-success">${formatMoney(f.totalFacturable)}</strong></td></tr></tfoot>
            </table>
            ${f.observations ? `<div style="margin-top:16px;"><strong>Observations:</strong><p>${f.observations}</p></div>` : ''}
        </div>
    `;

    // Afficher dans une modal ou une nouvelle fenêtre
    const printWindow = window.open('', '_blank', 'width=700,height=600');
    printWindow.document.write(`<html><head><title>Fiche de Traitement</title><link rel="stylesheet" href="css/style.css"><style>body{font-family:Arial,sans-serif;}</style></head><body>${html}<div style="text-align:center;margin-top:20px;"><button onclick="window.print()" style="padding:10px 20px;cursor:pointer;">Imprimer</button></div></body></html>`);
    printWindow.document.close();
}

function loadOrdonnances() {
    const medecinId = 1;
    const ords = DATA.ordonnances.filter(o => o.medecinId === medecinId);
    document.getElementById('tableOrdonnances').innerHTML = ords.length ? ords.map(o => {
        const p = DATA.getPatientById(o.patientId);
        return `<tr><td>${formatDate(o.date)}</td><td>${p.prenom} ${p.nom}</td><td>${o.medicaments.map(m => m.nom).join(', ')}</td><td>${statusBadge(o.statut === 'delivree' ? 'termine' : 'en_attente')}</td></tr>`;
    }).join('') : '<tr><td colspan="4" class="text-center text-muted">Aucune ordonnance</td></tr>';
}

// ==================== CAISSE ====================
function loadCaisseDashboard() {
    const today = new Date().toISOString().split('T')[0];
    const facturesAttente = DATA.getFacturesEnAttente();
    const paiementsToday = DATA.factures.filter(f => f.datePaiement === today && f.statut === 'payee');
    const encaisse = paiementsToday.reduce((s, f) => s + f.montant, 0);
    const montantAttente = facturesAttente.reduce((s, f) => s + f.montant, 0);

    document.getElementById('caisseEncaisse').textContent = formatMoney(encaisse);
    document.getElementById('caisseAttente').textContent = facturesAttente.length;
    document.getElementById('caisseMontantAttente').textContent = formatMoney(montantAttente);
    document.getElementById('caisseTrans').textContent = paiementsToday.length;
    document.getElementById('badgeFactures').textContent = facturesAttente.length;

    // Nouvelles factures
    document.getElementById('caisseNouvellesFactures').innerHTML = facturesAttente.slice(0, 5).map(f => {
        const p = DATA.getPatientById(f.patientId);
        return `<tr><td>${p.prenom} ${p.nom}</td><td>${f.type}</td><td class="font-medium">${formatMoney(f.montant)}</td><td><button class="btn btn-success btn-sm" onclick="ouvrirEncaissement(${f.id})">Encaisser</button></td></tr>`;
    }).join('') || '<tr><td colspan="4" class="text-center text-muted">Aucune facture</td></tr>';

    // Derniers paiements
    const derniers = DATA.factures.filter(f => f.statut === 'payee').slice(-5).reverse();
    document.getElementById('caisseDerniersPaiements').innerHTML = derniers.length ? derniers.map(f => {
        const p = DATA.getPatientById(f.patientId);
        return `<tr><td>${p.prenom} ${p.nom}</td><td class="font-medium text-success">${formatMoney(f.montant)}</td><td>${f.modePaiement || '-'}</td><td>${f.datePaiement ? new Date(f.datePaiement).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) : '-'}</td></tr>`;
    }).join('') : '<tr><td colspan="4" class="text-center text-muted">Aucun paiement</td></tr>';
}

function loadFacturesAttente() {
    const factures = DATA.getFacturesEnAttente();
    document.getElementById('caisseFacturesTable').innerHTML = factures.length ? factures.map(f => {
        const p = DATA.getPatientById(f.patientId);
        return `<tr><td class="font-medium">${f.numero}</td><td>${p.prenom} ${p.nom}</td><td>${formatDate(f.date)}</td><td>${f.type}</td><td class="font-medium">${formatMoney(f.montant)}</td><td>${f.envoyePar}</td><td><button class="btn btn-success btn-sm" onclick="ouvrirEncaissement(${f.id})">Encaisser</button></td></tr>`;
    }).join('') : '<tr><td colspan="7" class="text-center text-muted">Aucune facture en attente</td></tr>';
}

function ouvrirEncaissement(factureId) {
    currentFactureId = factureId;
    const f = DATA.factures.find(x => x.id === factureId);
    const p = DATA.getPatientById(f.patientId);

    // Header avec infos patient et facture
    document.getElementById('encaissementHeader').innerHTML = `
        <div style="display:flex;justify-content:space-between;align-items:center;background:var(--gray-100);padding:16px;border-radius:8px;">
            <div class="user-cell">
                <div class="avatar">${getInitials(p.nom, p.prenom)}</div>
                <div>
                    <div class="user-name">${p.prenom} ${p.nom}</div>
                    <div class="text-muted text-sm">${p.telephone}</div>
                </div>
            </div>
            <div style="text-align:right;">
                <div class="font-medium">${f.numero}</div>
                <div class="text-muted text-sm">${formatDate(f.date)} - ${f.envoyePar}</div>
            </div>
        </div>`;

    // Lignes de la facture
    const lignes = f.lignes || [{ description: 'Consultation', quantite: 1, prixUnitaire: f.montant, total: f.montant }];
    document.getElementById('encaissementLignes').innerHTML = lignes.map(l => `
        <tr>
            <td>${l.description}</td>
            <td style="text-align:center;">${l.quantite}</td>
            <td style="text-align:right;">${formatMoney(l.prixUnitaire)}</td>
            <td style="text-align:right;" class="font-medium">${formatMoney(l.total)}</td>
        </tr>
    `).join('');

    // Total
    document.getElementById('encaissementTotal').innerHTML = `
        <tr style="background:var(--success-light);">
            <td colspan="3" style="text-align:right;padding:12px;"><strong>TOTAL À PAYER:</strong></td>
            <td style="text-align:right;padding:12px;"><strong class="text-success" style="font-size:1.25rem;">${formatMoney(f.montant)}</strong></td>
        </tr>`;

    document.getElementById('encaisseMode').value = '';
    document.getElementById('encaisseRef').value = '';
    openModal('modalEncaissement');
}

function imprimerFacture() {
    if (!currentFactureId) return;
    const f = DATA.factures.find(x => x.id === currentFactureId);
    const p = DATA.getPatientById(f.patientId);
    const lignes = f.lignes || [{ description: 'Consultation', quantite: 1, prixUnitaire: f.montant, total: f.montant }];

    const printContent = `
        <html>
        <head>
            <title>Facture ${f.numero}</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
                .header { text-align: center; border-bottom: 2px solid #0891b2; padding-bottom: 20px; margin-bottom: 20px; }
                .header h1 { color: #0891b2; margin: 0; }
                .info { display: flex; justify-content: space-between; margin-bottom: 20px; }
                .info-box { background: #f3f4f6; padding: 15px; border-radius: 8px; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
                th { background: #f9fafb; }
                .total { background: #dcfce7; font-size: 1.2em; }
                .footer { text-align: center; margin-top: 40px; color: #6b7280; font-size: 0.9em; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>MediCare Pro</h1>
                <p>Centre de Santé - Abidjan</p>
            </div>
            <h2 style="text-align:center;">FACTURE ${f.numero}</h2>
            <div class="info">
                <div class="info-box">
                    <strong>Patient:</strong><br>
                    ${p.prenom} ${p.nom}<br>
                    ${p.telephone}
                </div>
                <div class="info-box" style="text-align:right;">
                    <strong>Date:</strong> ${formatDate(f.date)}<br>
                    <strong>Statut:</strong> ${f.statut === 'payee' ? 'PAYÉE' : 'EN ATTENTE'}
                </div>
            </div>
            <table>
                <thead><tr><th>Désignation</th><th style="text-align:center;">Qté</th><th style="text-align:right;">Prix Unit.</th><th style="text-align:right;">Total</th></tr></thead>
                <tbody>
                    ${lignes.map(l => `<tr><td>${l.description}</td><td style="text-align:center;">${l.quantite}</td><td style="text-align:right;">${l.prixUnitaire.toLocaleString('fr-FR')} F</td><td style="text-align:right;">${l.total.toLocaleString('fr-FR')} F</td></tr>`).join('')}
                </tbody>
                <tfoot>
                    <tr class="total"><td colspan="3" style="text-align:right;"><strong>TOTAL:</strong></td><td style="text-align:right;"><strong>${f.montant.toLocaleString('fr-FR')} FCFA</strong></td></tr>
                </tfoot>
            </table>
            <div class="footer">
                <p>Merci pour votre confiance - MediCare Pro</p>
                <p>Document généré le ${new Date().toLocaleDateString('fr-FR')}</p>
            </div>
        </body>
        </html>
    `;

    const printWindow = window.open('', '_blank');
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.print();
}

function validerEncaissement() {
    if (!currentFactureId) return;
    const mode = document.getElementById('encaisseMode').value;
    if (!mode) { alert('Sélectionnez un mode de paiement'); return; }

    const f = DATA.factures.find(x => x.id === currentFactureId);
    f.statut = 'payee';
    f.modePaiement = mode;
    f.datePaiement = new Date().toISOString().split('T')[0];

    // Ajouter transaction
    DATA.transactions.push({
        id: DATA.transactions.length + 1,
        date: f.datePaiement,
        type: 'entree',
        montant: f.montant,
        description: `Facture ${f.numero}`,
        categorie: f.type
    });

    currentFactureId = null;
    closeModal('modalEncaissement');
    loadCaisseDashboard();
    loadFacturesAttente();
    alert('Paiement enregistré avec succès');
}

function loadHistoriqueCaisse() {
    renderHistoriqueCaisse(DATA.factures.filter(f => f.statut === 'payee'));
}

function renderHistoriqueCaisse(factures) {
    document.getElementById('caisseHistTable').innerHTML = factures.length ? factures.map(f => {
        const p = DATA.getPatientById(f.patientId);
        return `<tr><td>${formatDate(f.datePaiement)}</td><td>${p.prenom} ${p.nom}</td><td>${f.type}</td><td class="font-medium text-success">${formatMoney(f.montant)}</td><td>${f.modePaiement}</td><td><span class="badge badge-success">Payé</span></td></tr>`;
    }).join('') : '<tr><td colspan="6" class="text-center text-muted">Aucun historique</td></tr>';
}

function filterHistorique() {
    const date = document.getElementById('filterDateHist').value;
    const mode = document.getElementById('filterModeHist').value;
    const filtered = DATA.factures.filter(f => f.statut === 'payee' && (!date || f.datePaiement === date) && (!mode || f.modePaiement === mode));
    renderHistoriqueCaisse(filtered);
}

function loadJournal() {
    const trans = DATA.transactions;
    const entrees = trans.filter(t => t.type === 'entree').reduce((s, t) => s + t.montant, 0);
    const sorties = trans.filter(t => t.type === 'sortie').reduce((s, t) => s + t.montant, 0);

    document.getElementById('journalEntrees').textContent = formatMoney(entrees);
    document.getElementById('journalSorties').textContent = formatMoney(sorties);
    document.getElementById('journalSolde').textContent = formatMoney(entrees - sorties);

    let solde = 0;
    document.getElementById('journalTable').innerHTML = trans.map(t => {
        solde += t.type === 'entree' ? t.montant : -t.montant;
        return `<tr><td>${formatDate(t.date)}</td><td>${t.description}</td><td class="text-success">${t.type === 'entree' ? formatMoney(t.montant) : '-'}</td><td class="text-danger">${t.type === 'sortie' ? formatMoney(t.montant) : '-'}</td><td class="font-medium">${formatMoney(solde)}</td></tr>`;
    }).join('');
}

function saveDepense(e) {
    e.preventDefault();
    DATA.transactions.push({
        id: DATA.transactions.length + 1,
        date: new Date().toISOString().split('T')[0],
        type: 'sortie',
        montant: parseInt(document.getElementById('depenseMontant').value),
        description: document.getElementById('depenseDesc').value,
        categorie: document.getElementById('depenseCat').value
    });
    closeModal('modalDepense');
    document.getElementById('formDepense').reset();
    loadJournal();
    alert('Dépense enregistrée');
}

// ==================== PHARMACIE ====================
function loadPharmaDashboard() {
    const stockBas = DATA.getMedicamentsStockBas();
    const demandesATraiter = DATA.getOrdonnancesATraiter();
    const valeurStock = DATA.medicaments.reduce((s, m) => s + (m.stock * m.prixUnitaire), 0);

    document.getElementById('pharmaTotalMed').textContent = DATA.medicaments.length;
    document.getElementById('pharmaStockBas').textContent = stockBas.length;
    document.getElementById('pharmaOrdonnances').textContent = demandesATraiter.length;
    document.getElementById('pharmaValeur').textContent = formatMoney(valeurStock);
    document.getElementById('badgeAlertes').textContent = stockBas.length;
    if (document.getElementById('badgeDemandes')) {
        document.getElementById('badgeDemandes').textContent = demandesATraiter.length;
    }

    // Alertes preview
    document.getElementById('pharmaAlertesPreview').innerHTML = stockBas.length ? stockBas.slice(0, 3).map(m =>
        `<div style="background:var(--danger-light);padding:12px;border-radius:8px;margin-bottom:8px;"><strong class="text-danger">${m.nom}</strong><div class="text-sm">Stock: ${m.stock} / Min: ${m.stockMin}</div></div>`
    ).join('') : '<p class="text-muted">Aucune alerte</p>';

    // Demandes en attente (preview)
    document.getElementById('pharmaLastOrd').innerHTML = demandesATraiter.slice(0, 3).map(o => {
        const p = DATA.getPatientById(o.patientId);
        const statutBadge = o.statutDispensation === 'prepare' ?
            '<span class="badge badge-warning">Préparé</span>' :
            '<span class="badge badge-info">En attente</span>';
        return `<tr><td><strong>${o.numeroRetrait}</strong></td><td>${p.prenom} ${p.nom}</td><td>${statutBadge}</td></tr>`;
    }).join('') || '<tr><td colspan="3" class="text-center text-muted">Aucune demande</td></tr>';
}

function loadStockPharma() {
    renderStockPharma(DATA.medicaments);
    loadMedicSelectPharma();
}

function renderStockPharma(meds) {
    document.getElementById('pharmaStockTable').innerHTML = meds.map(m => {
        const isLow = m.stock <= m.stockMin;
        return `<tr><td class="font-medium">${m.nom}</td><td>${m.categorie}</td><td class="${isLow ? 'text-danger font-bold' : ''}">${m.stock}</td><td>${m.stockMin}</td><td>${formatMoney(m.prixUnitaire)}</td><td>${isLow ? '<span class="badge badge-danger">Critique</span>' : '<span class="badge badge-success">OK</span>'}</td><td>${isLow ? `<button class="btn btn-warning btn-sm" onclick="ouvrirFicheAppro()">Réapprovisionner</button>` : '<span class="text-muted">-</span>'}</td></tr>`;
    }).join('');
}

function filterStockPharma() {
    const search = document.getElementById('searchMedicP').value.toLowerCase();
    const cat = document.getElementById('filterCatP').value;
    const filtered = DATA.medicaments.filter(m => m.nom.toLowerCase().includes(search) && (!cat || m.categorie === cat));
    renderStockPharma(filtered);
}

function loadMedicSelectPharma() {
    const options = '<option value="">Sélectionner un médicament</option>' + DATA.medicaments.map(m => `<option value="${m.id}">${m.nom} (Stock: ${m.stock})</option>`).join('');
    if (document.getElementById('entreeMedicP')) {
        document.getElementById('entreeMedicP').innerHTML = options;
    }
}

function loadAlertesPharma() {
    const alertes = DATA.getMedicamentsStockBas();
    document.getElementById('pharmaAlertesListe').innerHTML = alertes.length ? alertes.map(m =>
        `<div style="background:var(--danger-light);padding:16px;border-radius:8px;margin-bottom:12px;display:flex;justify-content:space-between;align-items:center;"><div><strong class="text-danger">${m.nom}</strong><div class="text-sm text-muted">${m.categorie} - ${m.forme}</div></div><div class="text-right"><div class="text-danger font-bold">${m.stock} en stock</div><div class="text-sm">Minimum: ${m.stockMin}</div></div></div>`
    ).join('') : '<div class="text-center text-muted" style="padding:40px;"><svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" style="margin:0 auto 12px;display:block;"><circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/></svg><p>Aucune alerte de stock</p></div>';
}

// ===== Demandes de Dispensation =====
function loadDemandesPharma() {
    const ordonnances = DATA.ordonnances;
    const filterValue = document.getElementById('filterDispStatut')?.value || '';
    const filtered = ordonnances.filter(o => !filterValue || o.statutDispensation === filterValue);

    document.getElementById('pharmaDemandesTable').innerHTML = filtered.length ? filtered.map(o => {
        const p = DATA.getPatientById(o.patientId);
        const m = DATA.getMedecinById(o.medecinId);
        const medsResume = o.medicaments.map(med => `${med.nom} (x${med.quantite || 1})`).join(', ');

        // Statut badge
        let statutHtml = '';
        if (o.statutDispensation === 'en_attente') {
            statutHtml = '<span class="badge badge-info">En attente</span>';
        } else if (o.statutDispensation === 'prepare') {
            statutHtml = '<span class="badge badge-warning">Préparé</span>';
        } else {
            statutHtml = '<span class="badge badge-success">Remis</span>';
        }

        // Actions selon statut
        let actionsHtml = '';
        if (o.statutDispensation === 'en_attente') {
            actionsHtml = `<button class="btn btn-primary btn-sm" onclick="voirDetailDispensation(${o.id})">Détail</button> <button class="btn btn-warning btn-sm" onclick="preparerDispensation(${o.id})">Préparer</button>`;
        } else if (o.statutDispensation === 'prepare') {
            actionsHtml = `<button class="btn btn-primary btn-sm" onclick="voirDetailDispensation(${o.id})">Détail</button> <button class="btn btn-success btn-sm" onclick="ouvrirRemise(${o.id})">Marquer remis</button>`;
        } else {
            actionsHtml = `<button class="btn btn-secondary btn-sm" onclick="voirDetailDispensation(${o.id})">Voir</button>`;
        }

        return `<tr><td><strong>${o.numeroRetrait}</strong></td><td>${formatDate(o.date)}</td><td>${p.prenom} ${p.nom}</td><td>Dr. ${m.nom}</td><td class="text-sm">${medsResume}</td><td>${statutHtml}</td><td>${actionsHtml}</td></tr>`;
    }).join('') : '<tr><td colspan="7" class="text-center text-muted">Aucune demande</td></tr>';
}

function filterDemandesPharma() {
    loadDemandesPharma();
}

function voirDetailDispensation(ordId) {
    const o = DATA.ordonnances.find(x => x.id === ordId);
    const p = DATA.getPatientById(o.patientId);
    const m = DATA.getMedecinById(o.medecinId);

    let statutLabel = o.statutDispensation === 'en_attente' ? 'En attente de préparation' :
                      o.statutDispensation === 'prepare' ? 'Préparé - En attente de retrait' : 'Remis';
    let statutClass = o.statutDispensation === 'en_attente' ? 'info' :
                      o.statutDispensation === 'prepare' ? 'warning' : 'success';

    let html = `
        <div style="background:var(--${statutClass}-light);padding:12px 16px;border-radius:8px;margin-bottom:16px;">
            <strong>Statut:</strong> ${statutLabel}
        </div>
        <div class="grid-2 mb-4" style="gap:16px;">
            <div><strong>N° Retrait:</strong><br><span style="font-size:1.2rem;font-weight:bold;color:var(--primary);">${o.numeroRetrait}</span></div>
            <div><strong>Date prescription:</strong><br>${formatDate(o.date)}</div>
        </div>
        <div class="grid-2 mb-4" style="gap:16px;">
            <div style="background:#f8f9fa;padding:12px;border-radius:8px;">
                <strong>Patient:</strong><br>${p.prenom} ${p.nom}<br>
                <span class="text-sm text-muted">${p.telephone}</span>
            </div>
            <div style="background:#f8f9fa;padding:12px;border-radius:8px;">
                <strong>Médecin prescripteur:</strong><br>Dr. ${m.prenom} ${m.nom}<br>
                <span class="text-sm text-muted">${m.specialite}</span>
            </div>
        </div>
        <div class="mb-4">
            <strong>Médicaments prescrits:</strong>
            <table class="mt-2" style="width:100%;"><thead><tr style="background:#f1f5f9;"><th style="padding:8px;">Médicament</th><th style="padding:8px;">Posologie</th><th style="padding:8px;">Durée</th><th style="padding:8px;text-align:center;">Qté</th></tr></thead><tbody>
            ${o.medicaments.map(med => `<tr><td style="padding:8px;">${med.nom}</td><td style="padding:8px;">${med.posologie}</td><td style="padding:8px;">${med.duree}</td><td style="padding:8px;text-align:center;font-weight:bold;">${med.quantite || 1}</td></tr>`).join('')}
            </tbody></table>
        </div>
        ${o.recommandations ? `<div class="mb-3"><strong>Recommandations:</strong><br><em>${o.recommandations}</em></div>` : ''}
        ${o.statutDispensation === 'prepare' ? `<div style="background:var(--warning-light);padding:12px;border-radius:8px;"><strong>Préparé le:</strong> ${formatDate(o.datePreparation)}</div>` : ''}
        ${o.statutDispensation === 'remis' ? `<div style="background:var(--success-light);padding:12px;border-radius:8px;"><strong>Remis le:</strong> ${formatDate(o.dateRemise)}<br><strong>Remis à:</strong> ${o.remisA}</div>` : ''}
    `;

    document.getElementById('detailDispContent').innerHTML = html;
    openModal('modalDetailDisp');
}

function preparerDispensation(ordId) {
    if (!confirm('Confirmer la préparation des médicaments ?')) return;

    const o = DATA.ordonnances.find(x => x.id === ordId);

    // Déduire du stock et créer mouvements
    o.medicaments.forEach(med => {
        const medicament = DATA.medicaments.find(m => m.nom.toLowerCase().includes(med.nom.toLowerCase().split(' ')[0]));
        if (medicament) {
            const qte = med.quantite || 1;
            if (medicament.stock >= qte) {
                medicament.stock -= qte;
                DATA.mouvementsStock.push({
                    id: DATA.mouvementsStock.length + 1,
                    medicamentId: medicament.id,
                    type: 'sortie',
                    quantite: qte,
                    date: new Date().toISOString().split('T')[0],
                    motif: `Dispensation ${o.numeroRetrait}`
                });
            }
        }
    });

    o.statutDispensation = 'prepare';
    o.datePreparation = new Date().toISOString().split('T')[0];

    loadPharmaDashboard();
    loadDemandesPharma();
    alert(`Préparation terminée.\nN° Retrait: ${o.numeroRetrait}\nEn attente de retrait par l'infirmier.`);
}

function ouvrirRemise(ordId) {
    document.getElementById('remiseOrdId').value = ordId;
    document.getElementById('remiseInfirmier').value = '';
    openModal('modalRemise');
}

function confirmerRemise(e) {
    e.preventDefault();
    const ordId = parseInt(document.getElementById('remiseOrdId').value);
    const infirmier = document.getElementById('remiseInfirmier').value.trim();

    if (!infirmier) {
        alert('Veuillez saisir le nom de l\'infirmier');
        return;
    }

    const o = DATA.ordonnances.find(x => x.id === ordId);
    o.statutDispensation = 'remis';
    o.dateRemise = new Date().toISOString().split('T')[0];
    o.remisA = infirmier;

    closeModal('modalRemise');
    loadPharmaDashboard();
    loadDemandesPharma();
    alert(`Médicaments remis à ${infirmier}.\nN° Retrait: ${o.numeroRetrait}`);
}

function loadMouvementsPharma() {
    document.getElementById('pharmaMouvTable').innerHTML = DATA.mouvementsStock.length ? DATA.mouvementsStock.slice().reverse().map(m => {
        const med = DATA.medicaments.find(x => x.id === m.medicamentId);
        const typeHtml = m.type === 'entree' ?
            '<span class="badge badge-success">Entrée</span>' :
            '<span class="badge badge-danger">Sortie (Dispensation)</span>';
        return `<tr><td>${formatDate(m.date)}</td><td>${med ? med.nom : '-'}</td><td>${typeHtml}</td><td class="font-medium">${m.type === 'entree' ? '+' : '-'}${m.quantite}</td><td>${m.motif}</td></tr>`;
    }).join('') : '<tr><td colspan="5" class="text-center text-muted">Aucun mouvement enregistré</td></tr>';
}

// ===== Fiches d'Approvisionnement =====
function ouvrirFicheAppro() {
    lignesAppro = [];
    document.getElementById('approNumero').value = DATA.generateNumeroApprovisionnement();
    document.getElementById('approDate').value = new Date().toISOString().split('T')[0];
    document.getElementById('approFournisseur').value = '';
    document.getElementById('approObservations').value = '';

    // Charger la liste des médicaments
    document.getElementById('approSelectMedic').innerHTML = '<option value="">-- Sélectionner --</option>' +
        DATA.medicaments.map(m => `<option value="${m.id}" data-prix="${m.prixUnitaire}">${m.nom} (Stock: ${m.stock})</option>`).join('');

    document.getElementById('approQte').value = 1;
    document.getElementById('approPrix').value = '';

    renderLignesAppro();
    openModal('modalFicheAppro');
}

function ajouterLigneAppro() {
    const select = document.getElementById('approSelectMedic');
    const medId = parseInt(select.value);
    const qte = parseInt(document.getElementById('approQte').value) || 0;
    const prix = parseInt(document.getElementById('approPrix').value) || 0;

    if (!medId) {
        alert('Veuillez sélectionner un médicament');
        return;
    }
    if (qte <= 0) {
        alert('La quantité doit être supérieure à 0');
        return;
    }

    // Vérifier si le médicament est déjà dans la liste
    if (lignesAppro.find(l => l.medicamentId === medId)) {
        alert('Ce médicament est déjà dans la liste. Modifiez la quantité existante.');
        return;
    }

    const med = DATA.medicaments.find(m => m.id === medId);
    lignesAppro.push({
        medicamentId: medId,
        nom: med.nom,
        quantite: qte,
        prixUnitaire: prix > 0 ? prix : med.prixUnitaire
    });

    // Réinitialiser le formulaire
    select.value = '';
    document.getElementById('approQte').value = 1;
    document.getElementById('approPrix').value = '';

    renderLignesAppro();
}

function supprimerLigneAppro(index) {
    lignesAppro.splice(index, 1);
    renderLignesAppro();
}

function renderLignesAppro() {
    const tbody = document.getElementById('approLignesTable');

    if (lignesAppro.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted" style="padding:20px;">Aucun médicament ajouté</td></tr>';
        document.getElementById('approTotalArticles').textContent = '0';
        document.getElementById('approTotalQte').textContent = '0';
        document.getElementById('approMontantTotal').textContent = '0 FCFA';
        return;
    }

    tbody.innerHTML = lignesAppro.map((l, i) => {
        const total = l.quantite * l.prixUnitaire;
        return `<tr>
            <td>${l.nom}</td>
            <td style="text-align:center;">${l.quantite}</td>
            <td style="text-align:right;">${formatMoney(l.prixUnitaire)}</td>
            <td style="text-align:right;">${formatMoney(total)}</td>
            <td><button class="btn btn-danger btn-sm" onclick="supprimerLigneAppro(${i})" title="Supprimer">&times;</button></td>
        </tr>`;
    }).join('');

    // Calculer les totaux
    const totalArticles = lignesAppro.length;
    const totalQte = lignesAppro.reduce((sum, l) => sum + l.quantite, 0);
    const montantTotal = lignesAppro.reduce((sum, l) => sum + (l.quantite * l.prixUnitaire), 0);

    document.getElementById('approTotalArticles').textContent = totalArticles;
    document.getElementById('approTotalQte').textContent = totalQte;
    document.getElementById('approMontantTotal').textContent = formatMoney(montantTotal);
}

function saveFicheAppro() {
    const fournisseur = document.getElementById('approFournisseur').value.trim();
    const date = document.getElementById('approDate').value;
    const observations = document.getElementById('approObservations').value.trim();

    if (!fournisseur) {
        alert('Veuillez saisir le nom du fournisseur');
        return;
    }
    if (lignesAppro.length === 0) {
        alert('Veuillez ajouter au moins un médicament');
        return;
    }

    const numero = document.getElementById('approNumero').value;
    const totalQte = lignesAppro.reduce((sum, l) => sum + l.quantite, 0);
    const montantTotal = lignesAppro.reduce((sum, l) => sum + (l.quantite * l.prixUnitaire), 0);

    // Créer la fiche d'approvisionnement
    const fiche = {
        id: DATA.fichesApprovisionnement.length + 1,
        numero: numero,
        date: date,
        fournisseur: fournisseur,
        lignes: [...lignesAppro],
        totalArticles: lignesAppro.length,
        totalQuantite: totalQte,
        montantTotal: montantTotal,
        observations: observations,
        creePar: 'Pharmacien'
    };

    DATA.fichesApprovisionnement.push(fiche);

    // Mettre à jour les stocks et créer les mouvements
    lignesAppro.forEach(ligne => {
        const med = DATA.medicaments.find(m => m.id === ligne.medicamentId);
        if (med) {
            med.stock += ligne.quantite;

            DATA.mouvementsStock.push({
                id: DATA.mouvementsStock.length + 1,
                medicamentId: ligne.medicamentId,
                type: 'entree',
                quantite: ligne.quantite,
                date: date,
                motif: `Appro. ${numero} - ${fournisseur}`
            });
        }
    });

    closeModal('modalFicheAppro');
    lignesAppro = [];
    loadPharmaDashboard();
    loadStockPharma();
    loadMouvementsPharma();
    if (document.getElementById('pharmaApproTable')) {
        loadApproPharma();
    }
    alert(`Approvisionnement enregistré!\n${numero}\nFournisseur: ${fournisseur}\n${lignesAppro.length} articles - ${totalQte} unités`);
}

function loadApproPharma() {
    const fiches = DATA.getFichesApprovisionnement();
    const search = document.getElementById('searchApproP')?.value.toLowerCase() || '';
    const filtered = fiches.filter(f => !search || f.fournisseur.toLowerCase().includes(search) || f.numero.toLowerCase().includes(search));

    document.getElementById('pharmaApproTable').innerHTML = filtered.length ? filtered.map(f => {
        return `<tr>
            <td><strong>${f.numero}</strong></td>
            <td>${formatDate(f.date)}</td>
            <td>${f.fournisseur}</td>
            <td style="text-align:center;">${f.totalArticles}</td>
            <td style="text-align:center;">${f.totalQuantite}</td>
            <td style="text-align:right;">${formatMoney(f.montantTotal)}</td>
            <td><button class="btn btn-primary btn-sm" onclick="voirDetailAppro(${f.id})">Détail</button></td>
        </tr>`;
    }).join('') : '<tr><td colspan="7" class="text-center text-muted">Aucun approvisionnement</td></tr>';
}

function filterApproPharma() {
    loadApproPharma();
}

function voirDetailAppro(ficheId) {
    const f = DATA.fichesApprovisionnement.find(x => x.id === ficheId);
    if (!f) return;

    let html = `
        <div style="background:var(--success-light);padding:12px 16px;border-radius:8px;margin-bottom:16px;">
            <strong>Fiche N°:</strong> ${f.numero}
        </div>
        <div class="grid-2 mb-4" style="gap:16px;">
            <div><strong>Date:</strong><br>${formatDate(f.date)}</div>
            <div><strong>Fournisseur:</strong><br><span style="font-size:1.1rem;font-weight:bold;">${f.fournisseur}</span></div>
        </div>
        <div class="mb-4">
            <strong>Médicaments reçus:</strong>
            <table class="mt-2" style="width:100%;"><thead><tr style="background:#f1f5f9;"><th style="padding:8px;">Médicament</th><th style="padding:8px;text-align:center;">Quantité</th><th style="padding:8px;text-align:right;">Prix unit.</th><th style="padding:8px;text-align:right;">Total</th></tr></thead><tbody>
            ${f.lignes.map(l => `<tr><td style="padding:8px;">${l.nom}</td><td style="padding:8px;text-align:center;">${l.quantite}</td><td style="padding:8px;text-align:right;">${formatMoney(l.prixUnitaire)}</td><td style="padding:8px;text-align:right;">${formatMoney(l.quantite * l.prixUnitaire)}</td></tr>`).join('')}
            </tbody>
            <tfoot style="background:#f8fafc;"><tr><td colspan="3" style="padding:8px;text-align:right;font-weight:bold;">Total:</td><td style="padding:8px;text-align:right;font-weight:bold;color:var(--success);">${formatMoney(f.montantTotal)}</td></tr></tfoot>
            </table>
        </div>
        ${f.observations ? `<div class="mb-3"><strong>Observations:</strong><br><em>${f.observations}</em></div>` : ''}
        <div class="text-sm text-muted">Créé par: ${f.creePar}</div>
    `;

    document.getElementById('detailApproContent').innerHTML = html;
    openModal('modalDetailAppro');
}

function imprimerFicheAppro() {
    const content = document.getElementById('detailApproContent').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html><head><title>Fiche Approvisionnement</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            table { width: 100%; border-collapse: collapse; margin: 10px 0; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background: #f5f5f5; }
            .text-right { text-align: right; }
            @media print { body { padding: 0; } }
        </style>
        </head><body>
        <h2>MediCare Pro - Fiche d'Approvisionnement</h2>
        ${content}
        <script>window.onload = function() { window.print(); }</script>
        </body></html>
    `);
    printWindow.document.close();
}

function saveMedicPharma(e) {
    e.preventDefault();
    DATA.medicaments.push({
        id: DATA.medicaments.length + 1,
        nom: document.getElementById('medicNomP').value,
        categorie: document.getElementById('medicCatP').value,
        forme: document.getElementById('medicFormeP').value,
        stock: parseInt(document.getElementById('medicStockP').value) || 0,
        stockMin: parseInt(document.getElementById('medicMinP').value) || 10,
        prixUnitaire: parseInt(document.getElementById('medicPrixP').value) || 0,
        fournisseur: ''
    });
    closeModal('modalMedicP');
    document.getElementById('formMedicP').reset();
    loadPharmaDashboard();
    loadStockPharma();
    alert('Médicament ajouté');
}
