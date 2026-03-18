<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Certificat {{ $certificat->numero }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 13px; color: #1a1a1a; line-height: 1.6; }
        .page { padding: 40px 50px; position: relative; min-height: 100%; }

        /* Header */
        .header { text-align: center; border-bottom: 3px solid #2563eb; padding-bottom: 20px; margin-bottom: 30px; }
        .hospital-name { font-size: 22px; font-weight: bold; color: #2563eb; letter-spacing: 1px; }
        .hospital-subtitle { font-size: 14px; color: #64748b; margin-top: 4px; }
        .hospital-info { font-size: 11px; color: #94a3b8; margin-top: 8px; }

        /* Title */
        .cert-title { text-align: center; margin: 30px 0; }
        .cert-title h1 { font-size: 18px; text-transform: uppercase; letter-spacing: 2px; color: #1e293b; border: 2px solid #1e293b; display: inline-block; padding: 10px 30px; }
        .cert-numero { text-align: center; font-size: 11px; color: #64748b; margin-top: 8px; }

        /* Body */
        .cert-body { margin: 30px 0; text-align: justify; }
        .cert-body p { margin-bottom: 14px; }
        .soussigne { font-size: 13px; }
        .patient-info { margin: 20px 0; padding: 16px 20px; background: #f8fafc; border-left: 4px solid #2563eb; }
        .patient-info table { width: 100%; }
        .patient-info td { padding: 4px 10px; font-size: 13px; }
        .patient-info td:first-child { font-weight: bold; color: #475569; width: 140px; }
        .arret-block { margin: 20px 0; padding: 16px 20px; background: #fef3c7; border-left: 4px solid #f59e0b; }
        .motif-block { margin: 20px 0; }
        .motif-block .label { font-weight: bold; color: #475569; margin-bottom: 6px; }

        /* Footer */
        .foi { margin: 30px 0; font-style: italic; text-align: justify; }
        .signature-block { margin-top: 50px; text-align: right; }
        .signature-block .lieu-date { margin-bottom: 40px; }
        .signature-block .doctor-name { font-weight: bold; font-size: 14px; }
        .signature-block .signature-line { border-top: 1px solid #cbd5e1; display: inline-block; width: 250px; padding-top: 8px; text-align: center; }

        .footer { position: fixed; bottom: 30px; left: 50px; right: 50px; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; font-style: italic; }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="hospital-name">MediCare Pro</div>
            <div class="hospital-subtitle">Centre Hospitalier</div>
            <div class="hospital-info">Adresse du centre hospitalier | Tel: +000 000 000 | Email: contact@medicare-pro.com</div>
        </div>

        <!-- Title -->
        <div class="cert-title">
            <h1>
                @switch($certificat->type)
                    @case('arret_maladie') CERTIFICAT MÉDICAL D'ARRÊT DE TRAVAIL @break
                    @case('aptitude') CERTIFICAT D'APTITUDE @break
                    @case('inaptitude') CERTIFICAT D'INAPTITUDE @break
                    @case('medical_general') CERTIFICAT MÉDICAL @break
                    @case('deces') CERTIFICAT DE DÉCÈS @break
                @endswitch
            </h1>
            <div class="cert-numero">N° {{ $certificat->numero }}</div>
        </div>

        <!-- Body -->
        <div class="cert-body">
            <p class="soussigne">
                Je soussigné(e), <strong>Dr. {{ $certificat->medecin->nom }} {{ $certificat->medecin->prenom }}</strong>,
                {{ $certificat->medecin->specialite ?? 'Médecin' }},
                exerçant au Centre Hospitalier MediCare Pro,
                certifie avoir examiné ce jour :
            </p>

            <!-- Patient info -->
            <div class="patient-info">
                <table>
                    <tr>
                        <td>Nom</td>
                        <td>{{ $certificat->patient->nom }}</td>
                    </tr>
                    <tr>
                        <td>Prénom</td>
                        <td>{{ $certificat->patient->prenom }}</td>
                    </tr>
                    <tr>
                        <td>Âge</td>
                        <td>{{ $certificat->patient->date_naissance->age }} ans</td>
                    </tr>
                    <tr>
                        <td>Date d'examen</td>
                        <td>{{ $certificat->date_emission->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>

            @if($certificat->type === 'arret_maladie' && $certificat->date_debut && $certificat->date_fin)
            <div class="arret-block">
                <p>
                    Et lui prescrit un <strong>arrêt de travail de {{ $certificat->nb_jours }} jour(s)</strong>,
                    du <strong>{{ $certificat->date_debut->format('d/m/Y') }}</strong>
                    au <strong>{{ $certificat->date_fin->format('d/m/Y') }}</strong> inclus.
                </p>
            </div>
            @endif

            <div class="motif-block">
                <div class="label">Motif :</div>
                <p>{{ $certificat->motif }}</p>
            </div>

            @if($certificat->observations)
            <div class="motif-block">
                <div class="label">Observations :</div>
                <p>{{ $certificat->observations }}</p>
            </div>
            @endif

            @if($certificat->conclusion)
            <div class="motif-block">
                <div class="label">Conclusion :</div>
                <p>{{ $certificat->conclusion }}</p>
            </div>
            @endif

            <p class="foi">
                En foi de quoi, le présent certificat est délivré pour servir et valoir ce que de droit.
            </p>

            <div class="signature-block">
                <div class="lieu-date">
                    Fait à MediCare Pro, le {{ $certificat->date_emission->format('d/m/Y') }}
                </div>
                <div class="signature-line">
                    <div class="doctor-name">Dr. {{ $certificat->medecin->nom }} {{ $certificat->medecin->prenom }}</div>
                    <div style="font-size:11px;color:#64748b;">{{ $certificat->medecin->specialite ?? 'Médecin' }}</div>
                    <div style="font-size:10px;color:#94a3b8;margin-top:4px;">Signature et cachet</div>
                </div>
            </div>
        </div>

        <div class="footer">
            Ce certificat ne peut être utilisé que dans le cadre pour lequel il a été établi.
        </div>
    </div>
</body>
</html>
