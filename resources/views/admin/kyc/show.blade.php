@extends('layouts.admin')
@section('title', 'Dossier KYC — ' . $kyc->user->name)

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Barre supérieure avec retour et statut global --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('admin.kyc.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-[#006837] mb-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Retour à la liste des dossiers</span>
                </a>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900">Dossier KYC : {{ $kyc->user->name }}</h1>
                    <span class="rounded-full bg-slate-200/80 px-3 py-1 text-xs font-mono font-bold text-slate-700">#{{ $kyc->id }}</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @php
                    $statusStyles = [
                        'pending'   => 'bg-slate-100 text-slate-700 border-slate-300',
                        'reviewing' => 'bg-amber-100 text-amber-800 border-amber-300',
                        'approved'  => 'bg-emerald-100 text-[#006837] border-emerald-300',
                        'rejected'  => 'bg-red-100 text-red-800 border-red-300',
                    ];
                @endphp
                <div class="text-right">
                    <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Statut Ali-Kamer</div>
                    <span class="inline-flex items-center gap-1.5 rounded-xl border px-3 py-1 text-xs font-black uppercase {{ $statusStyles[$kyc->status] ?? 'bg-slate-100 text-slate-700' }}">
                        <span class="h-2 w-2 rounded-full {{ $kyc->status === 'approved' ? 'bg-[#006837]' : ($kyc->status === 'rejected' ? 'bg-red-600' : 'bg-amber-500') }}"></span>
                        {{ $kyc->status }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Notifications flash --}}
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-bold text-[#006837] flex items-center gap-2 shadow-xs">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('fail'))
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-xs font-bold text-red-800 flex items-center gap-2 shadow-xs">
                <span>⚠️</span>
                <span>{{ session('fail') }}</span>
            </div>
        @endif

        {{-- Bannière récapitulative : DIDIT vs ALI-KAMER (Spécification Section 5) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- DIDIT STATS --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-blue-600"></span>
                        <h2 class="text-xs font-black uppercase tracking-widest text-slate-700">Preuves & Signaux Didit</h2>
                    </div>
                    <span class="rounded-lg bg-blue-50 text-blue-700 px-2 py-0.5 text-[10px] font-bold">Session: {{ $kyc->didit_session_status ?: 'Non renseigné' }}</span>
                </div>
                <div class="grid grid-cols-3 gap-3 mt-4 text-center">
                    <div class="rounded-2xl bg-slate-50 p-3 border border-slate-100">
                        <div class="text-[10px] font-black uppercase text-slate-400">Face Match</div>
                        <div class="mt-1 text-base font-black {{ ($kyc->face_match_score !== null && $kyc->face_match_score < 70) ? 'text-red-600' : 'text-slate-800' }}">
                            {{ $kyc->face_match_score !== null ? $kyc->face_match_score . '%' : ($kyc->face_match_status ?: '—') }}
                        </div>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-3 border border-slate-100">
                        <div class="text-[10px] font-black uppercase text-slate-400">Liveness</div>
                        <div class="mt-1 text-base font-black {{ ($kyc->liveness_score !== null && $kyc->liveness_score < 70) ? 'text-red-600' : 'text-slate-800' }}">
                            {{ $kyc->liveness_score !== null ? $kyc->liveness_score . '%' : ($kyc->liveness_status ?: '—') }}
                        </div>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-3 border border-slate-100">
                        <div class="text-[10px] font-black uppercase text-slate-400">Document</div>
                        <div class="mt-1 text-sm font-black text-slate-800 truncate">
                            {{ $kyc->document_status ?: '—' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- ALI-KAMER STATS --}}
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50/40 p-5 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-emerald-100">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-[#006837]"></span>
                        <h2 class="text-xs font-black uppercase tracking-widest text-[#006837]">Gouvernance Ali-Kamer</h2>
                    </div>
                    <span class="rounded-lg bg-[#006837]/10 text-[#006837] px-2 py-0.5 text-[10px] font-bold">
                        Décision: {{ $kyc->admin_decision ?: ($kyc->decision ?: 'REVIEW') }}
                    </span>
                </div>
                <div class="grid grid-cols-3 gap-3 mt-4 text-center">
                    <div class="rounded-2xl bg-white p-3 border border-emerald-100">
                        <div class="text-[10px] font-black uppercase text-slate-400">TrustScore</div>
                        <div class="mt-1 text-base font-black text-[#006837]">{{ $user->trust_score ?? 50 }}/100</div>
                    </div>
                    <div class="rounded-2xl bg-white p-3 border border-emerald-100">
                        <div class="text-[10px] font-black uppercase text-slate-400">RiskProfile</div>
                        <div class="mt-1 text-base font-black uppercase {{ in_array($user->risk_profile, ['HIGH','CRITICAL']) ? 'text-red-600' : 'text-slate-800' }}">
                            {{ $user->risk_profile ?? 'LOW' }}
                        </div>
                    </div>
                    <div class="rounded-2xl bg-white p-3 border border-emerald-100">
                        <div class="text-[10px] font-black uppercase text-slate-400">Identité locale</div>
                        <div class="mt-1 text-sm font-black text-slate-800 uppercase truncate">
                            {{ $kyc->identity_resolution_status ?: 'Vérifiée' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Organisation principale en 2 colonnes --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- COLONNE GAUCHE (Contenu du dossier - 2 tiers) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- SECTION A : IDENTITÉ DU VENDEUR --}}
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-500">A. Identité du Vendeur & Boutique</h3>
                        <span class="text-xs text-slate-400">Inscrit le {{ $user->created_at->format('d/m/Y') }}</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-slate-400 block text-[11px]">Nom complet :</span>
                            <span class="font-bold text-slate-900 text-sm">{{ $user->name }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[11px]">Adresse email :</span>
                            <span class="font-bold text-slate-900">{{ $user->email }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[11px]">Téléphone compte :</span>
                            <span class="font-bold text-slate-900">{{ $user->phone ?: 'Non renseigné' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[11px]">Téléphone Mobile Money :</span>
                            <span class="font-bold text-slate-900">{{ $user->phone_momo ?: ($kyc->momo_number ?: 'Non renseigné') }} ({{ strtoupper($user->momo_operator ?: ($kyc->momo_operator ?? '—')) }})</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[11px]">Statut utilisateur :</span>
                            <span class="font-bold uppercase text-slate-900">{{ $user->status }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[11px]">Statut Vendeur / Boutique :</span>
                            <span class="font-bold text-slate-900">{{ $shop ? "{$shop->name} (" . strtoupper($shop->status) . ")" : 'Aucune boutique' }}</span>
                        </div>
                        @if($shop && $shop->city)
                            <div>
                                <span class="text-slate-400 block text-[11px]">Ville boutique :</span>
                                <span class="font-bold text-slate-900">{{ $shop->city }}</span>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- SECTION B : DOCUMENT D'IDENTITÉ (OCR & IMAGES) --}}
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-500">B. Document d'Identité & Données OCR</h3>
                        <span class="rounded px-2 py-0.5 text-[10px] font-bold {{ $kyc->document_status === 'Approved' ? 'bg-emerald-100 text-[#006837]' : 'bg-amber-100 text-amber-800' }}">
                            {{ $kyc->document_status ?: 'Non vérifié' }}
                        </span>
                    </div>

                    {{-- Visualisation des pièces d'identité --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center">
                            <div class="text-[11px] font-bold text-slate-600 mb-2">CNI / Document Recto</div>
                            @if(!empty($kyc->didit_media['cni_front']) || $kyc->cni_front_url)
                                <div class="relative group rounded-xl overflow-hidden bg-slate-200 aspect-video flex items-center justify-center">
                                    <img src="{{ route('admin.kyc.media', ['kyc' => $kyc, 'type' => 'front']) }}" alt="CNI Recto" class="object-cover w-full h-full" onerror="this.src=''; this.alt='Aperçu non disponible (Lien expiré ou protégé)';" />
                                    <a href="{{ route('admin.kyc.media', ['kyc' => $kyc, 'type' => 'front']) }}" target="_blank" class="absolute inset-0 bg-black/40 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-xs font-bold">
                                        Ouvrir en grand ↗
                                    </a>
                                </div>
                            @else
                                <div class="rounded-xl border border-dashed border-slate-300 p-6 text-slate-400 text-xs">
                                    Aucune image recto disponible
                                </div>
                            @endif
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center">
                            <div class="text-[11px] font-bold text-slate-600 mb-2">CNI / Document Verso</div>
                            @if(!empty($kyc->didit_media['cni_back']) || $kyc->cni_back_url)
                                <div class="relative group rounded-xl overflow-hidden bg-slate-200 aspect-video flex items-center justify-center">
                                    <img src="{{ route('admin.kyc.media', ['kyc' => $kyc, 'type' => 'back']) }}" alt="CNI Verso" class="object-cover w-full h-full" onerror="this.src=''; this.alt='Aperçu non disponible';" />
                                    <a href="{{ route('admin.kyc.media', ['kyc' => $kyc, 'type' => 'back']) }}" target="_blank" class="absolute inset-0 bg-black/40 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-xs font-bold">
                                        Ouvrir en grand ↗
                                    </a>
                                </div>
                            @else
                                <div class="rounded-xl border border-dashed border-slate-300 p-6 text-slate-400 text-xs">
                                    Aucune image verso disponible
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Données extraites par Didit --}}
                    @php
                        $docData = $kyc->didit_document_result ?? [];
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs bg-slate-50 rounded-2xl p-4 border border-slate-100">
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Type document</span>
                            <span class="font-bold text-slate-900">{{ $docData['document_type'] ?? 'ID Card' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Pays émetteur</span>
                            <span class="font-bold text-slate-900">{{ $docData['issuing_state'] ?? ($kyc->nationality ?? 'CMR') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Numéro document</span>
                            <span class="font-bold font-mono text-slate-900">{{ $kyc->document_number_masked ?: '—' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Nom extrait</span>
                            <span class="font-bold text-slate-900">{{ $kyc->full_name ?: ($docData['full_name'] ?? '—') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Date de naissance</span>
                            <span class="font-bold text-slate-900">{{ $kyc->date_of_birth?->format('d/m/Y') ?: ($docData['date_of_birth'] ?? '—') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Nationalité</span>
                            <span class="font-bold text-slate-900">{{ $kyc->nationality ?: ($docData['nationality'] ?? '—') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Date d'expiration</span>
                            <span class="font-bold text-slate-900">{{ $kyc->expiration_date?->format('d/m/Y') ?: ($docData['expiration_date'] ?? '—') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Date d'émission</span>
                            <span class="font-bold text-slate-900">{{ $docData['date_of_issue'] ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Qualité OCR</span>
                            <span class="font-bold text-slate-900">
                                @if(!empty($docData['front_image_quality_score']['overall_score']))
                                    {{ $docData['front_image_quality_score']['overall_score'] }}/100
                                @else
                                    Correcte
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- Warnings Didit --}}
                    @if(!empty($kyc->warnings))
                        <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                            <div class="text-xs font-bold text-amber-800 mb-2">⚠️ Alertes et Avertissements Didit :</div>
                            <ul class="space-y-1 text-xs text-amber-900">
                                @foreach($kyc->warnings as $w)
                                    <li>• {{ is_array($w) ? ($w['short_description'] ?? ($w['risk'] ?? json_encode($w))) : $w }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </section>

                {{-- SECTION C & D : SELFIE, BIOMÉTRIE ET VIDÉO --}}
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-500">C & D. Biométrie, Selfie & Liveness Vidéo</h3>
                        <div class="flex gap-2">
                            <span class="rounded px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-700">Liveness: {{ $kyc->liveness_status ?: '—' }}</span>
                            <span class="rounded px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-700">Face Match: {{ $kyc->face_match_status ?: '—' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Selfie / Portrait --}}
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center">
                            <div class="text-[11px] font-bold text-slate-600 mb-2">Selfie / Portrait Référence</div>
                            @if(!empty($kyc->didit_media['selfie']) || $kyc->selfie_url)
                                <div class="rounded-xl overflow-hidden bg-slate-200 aspect-square max-w-[200px] mx-auto flex items-center justify-center">
                                    <img src="{{ route('admin.kyc.media', ['kyc' => $kyc, 'type' => 'selfie']) }}" alt="Selfie Didit" class="object-cover w-full h-full" onerror="this.src=''; this.alt='Selfie protégé';" />
                                </div>
                            @else
                                <div class="rounded-xl border border-dashed border-slate-300 p-8 text-slate-400 text-xs">
                                    Aucun selfie direct
                                </div>
                            @endif
                        </div>

                        {{-- Vidéo Liveness --}}
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center">
                            <div class="text-[11px] font-bold text-slate-600 mb-2">Vidéo de Liveness Active</div>
                            @if(!empty($kyc->didit_media['video']))
                                <video controls class="w-full rounded-xl aspect-square max-w-[200px] mx-auto bg-black">
                                    <source src="{{ route('admin.kyc.media', ['kyc' => $kyc, 'type' => 'video']) }}" type="video/mp4">
                                    Votre navigateur ne supporte pas la vidéo.
                                </video>
                                <div class="mt-2">
                                    <a href="{{ route('admin.kyc.media', ['kyc' => $kyc, 'type' => 'video']) }}" target="_blank" class="text-xs font-bold text-[#006837] hover:underline">
                                        Visionner la vidéo dans un nouvel onglet ↗
                                    </a>
                                </div>
                            @else
                                <div class="rounded-xl border border-dashed border-slate-300 p-8 text-slate-400 text-xs">
                                    Aucun enregistrement vidéo transmis par Didit
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Détail des scores biométriques --}}
                    @php
                        $livenessData = $kyc->didit_liveness_result ?? [];
                        $faceMatchData = $kyc->didit_face_match_result ?? [];
                    @endphp
                    <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs bg-slate-50 rounded-2xl p-4 border border-slate-100">
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Méthode Liveness</span>
                            <span class="font-bold text-slate-900">{{ $livenessData['method'] ?? 'Passive 3D' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Score Liveness</span>
                            <span class="font-bold text-slate-900">{{ $kyc->liveness_score !== null ? $kyc->liveness_score . '%' : '—' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Score Face Match</span>
                            <span class="font-bold text-slate-900">{{ $kyc->face_match_score !== null ? $kyc->face_match_score . '%' : '—' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Âge estimé</span>
                            <span class="font-bold text-slate-900">{{ $livenessData['age_estimation'] ?? '—' }} ans</span>
                        </div>
                    </div>
                </section>

                {{-- SECTION E : IP, APPAREIL & SÉCURITÉ --}}
                @php
                    $ipData = $kyc->didit_ip_result ?? [];
                @endphp
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-500">E. Analyse IP, Appareil & Sécurité</h3>
                        <span class="rounded px-2 py-0.5 text-[10px] font-bold {{ $kyc->ip_analysis_status === 'Approved' ? 'bg-emerald-100 text-[#006837]' : 'bg-slate-100 text-slate-700' }}">
                            {{ $kyc->ip_analysis_status ?: 'Non analysé' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs bg-slate-50 rounded-2xl p-4 border border-slate-100">
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Adresse IP</span>
                            <span class="font-mono font-bold text-slate-900">{{ $ipData['ip_address'] ?? ($kyc->verification_ip ?: '—') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Pays IP</span>
                            <span class="font-bold text-slate-900">{{ $ipData['ip_country'] ?? ($ipData['ip_country_code'] ?? '—') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Ville / Région</span>
                            <span class="font-bold text-slate-900">{{ $ipData['ip_city'] ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">VPN / Tor / Proxy</span>
                            <span class="font-bold {{ !empty($ipData['is_vpn_or_tor']) ? 'text-red-600' : 'text-emerald-700' }}">
                                {{ !empty($ipData['is_vpn_or_tor']) ? 'Détecté ⚠️' : 'Non détecté' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Appareil</span>
                            <span class="font-bold text-slate-900">{{ $ipData['device_brand'] ?? 'Inconnu' }} {{ $ipData['device_model'] ?? '' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Navigateur & OS</span>
                            <span class="font-bold text-slate-900">{{ $ipData['browser_family'] ?? '—' }} ({{ $ipData['os_family'] ?? '—' }})</span>
                        </div>
                        <div class="sm:col-span-2">
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Empreinte Device (Fingerprint)</span>
                            <span class="font-mono text-[10px] text-slate-700 truncate block">{{ $ipData['device_fingerprint'] ?? '—' }}</span>
                        </div>
                    </div>
                </section>

                {{-- SECTION F : AML & CONTRÔLE BASES DE DONNÉES --}}
                @php
                    $amlData = $kyc->didit_aml_result ?? [];
                @endphp
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-500">F. Criblage AML, Listes de Sanctions & PEP</h3>
                        <span class="rounded px-2 py-0.5 text-[10px] font-bold {{ ($amlData['status'] ?? '') === 'Approved' ? 'bg-emerald-100 text-[#006837]' : 'bg-slate-100 text-slate-700' }}">
                            {{ $amlData['status'] ?? ($kyc->aml_status ?: 'Aucun hit') }}
                        </span>
                    </div>

                    @if(!empty($amlData['hits']))
                        <div class="space-y-3">
                            @foreach($amlData['hits'] as $hit)
                                <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-xs">
                                    <div class="font-bold text-red-900 text-sm">Hit : {{ $hit['caption'] ?? 'Entité détectée' }}</div>
                                    <div class="text-red-700 mt-1">Jeux de données : {{ implode(', ', $hit['datasets'] ?? ['PEP / Sanctions']) }}</div>
                                    @if(!empty($hit['adverse_media_matches']))
                                        <div class="mt-2 text-[11px] font-semibold text-red-800">Presse négative associée détectée.</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4 text-xs text-emerald-800 font-bold flex items-center gap-2">
                            <span>✓</span>
                            <span>Aucune correspondance dans les listes internationales AML, PEP ou sanctions financières.</span>
                        </div>
                    @endif
                </section>

                {{-- SECTION G : DONNÉES INTERNES ALI-KAMER --}}
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs">
                    <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-500">G. Historique & Signaux Internes Ali-Kamer</h3>
                        <span class="text-xs text-slate-400">TrustEngine</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs mb-5">
                        <div class="rounded-2xl bg-slate-50 p-3 border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Commandes totales</span>
                            <div class="mt-1 text-base font-black text-slate-900">{{ $stats['orders_count'] }}</div>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-3 border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Commandes livrées</span>
                            <div class="mt-1 text-base font-black text-emerald-700">{{ $stats['completed_orders_count'] }}</div>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-3 border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Commandes annulées</span>
                            <div class="mt-1 text-base font-black text-slate-700">{{ $stats['cancelled_orders_count'] }}</div>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-3 border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Litiges reçus</span>
                            <div class="mt-1 text-base font-black {{ $stats['disputes_count'] > 0 ? 'text-red-600' : 'text-slate-900' }}">{{ $stats['disputes_count'] }}</div>
                        </div>
                    </div>

                    {{-- Comptes liés / Identités associées --}}
                    @if($linkedAccounts->isNotEmpty())
                        <div class="rounded-2xl border border-orange-200 bg-orange-50 p-4 mb-4">
                            <div class="text-xs font-bold text-orange-900 mb-2">🔗 Comptes associés / Liens détectés :</div>
                            <ul class="space-y-1 text-xs text-orange-800">
                                @foreach($linkedAccounts as $link)
                                    @php
                                        $other = $link->user_a_id === $user->id ? $link->userB : $link->userA;
                                    @endphp
                                    @if($other)
                                        <li>• Lié au compte #{{ $other->id }} ({{ $other->name }} - {{ $other->email }}) — Confiance : {{ $link->confidence }}% [{{ $link->link_type }}]</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Événements Trust récents --}}
                    @if($user->trustEvents->isNotEmpty())
                        <div class="mt-3">
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Derniers événements de réputation :</div>
                            <div class="divide-y divide-slate-100 text-xs">
                                @foreach($user->trustEvents->take(5) as $ev)
                                    <div class="py-2 flex items-center justify-between">
                                        <div>
                                            <span class="font-bold text-slate-800">{{ $ev->reason }}</span>
                                            <span class="text-[10px] text-slate-400 ml-2">{{ $ev->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <span class="font-bold {{ $ev->direction === 'increase' ? 'text-emerald-700' : 'text-red-600' }}">
                                            {{ $ev->direction === 'increase' ? '+' : '-' }}{{ $ev->points_applied }} pts
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </section>

            </div>

            {{-- COLONNE DROITE (PANNEAU D'ACTION ADMIN ALI-KAMER) --}}
            <aside class="space-y-6">

                {{-- DÉCISION SOUVERAINE DE L'ADMINISTRATEUR --}}
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sticky top-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="inline-block h-2.5 w-2.5 rounded-full bg-[#006837]"></span>
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-800">Décision Admin Ali-Kamer</h3>
                    </div>
                    <p class="text-xs text-slate-500 mb-5 leading-relaxed">
                        Prenez une décision souveraine indépendamment du statut Didit. Le vendeur sera immédiatement synchronisé.
                    </p>

                    <div class="space-y-4">
                        {{-- 1. APPROUVER --}}
                        <form method="POST" action="{{ route('admin.kyc.approve', $kyc) }}">
                            @csrf
                            <input type="text" name="notes" placeholder="Notes optionnelles pour la validation..."
                                   class="w-full rounded-xl border border-slate-200 p-2.5 text-xs mb-2 focus:border-[#006837] focus:ring-1 focus:ring-[#006837] focus:outline-none" />
                            <button type="submit" class="w-full rounded-2xl bg-[#006837] hover:bg-[#004d28] px-4 py-3 text-xs font-black text-white transition shadow-sm">
                                ✓ APPROUVER LE KYC (Valider Vendeur)
                            </button>
                        </form>

                        <div class="border-t border-slate-100 pt-3"></div>

                        {{-- 2. METTRE EN REVUE --}}
                        <form method="POST" action="{{ route('admin.kyc.review', $kyc) }}">
                            @csrf
                            <input type="text" name="reason" placeholder="Préciser l'axe de revue..."
                                   class="w-full rounded-xl border border-slate-200 p-2 text-xs mb-2 focus:outline-none focus:ring-1 focus:ring-amber-500" />
                            <button type="submit" class="w-full rounded-2xl bg-amber-500 hover:bg-amber-600 px-4 py-2.5 text-xs font-black text-white transition">
                                ⏳ PLACER EN REVUE (Attente d'arbitrage)
                            </button>
                        </form>

                        <div class="border-t border-slate-100 pt-3"></div>

                        {{-- 3. REJETER --}}
                        <form method="POST" action="{{ route('admin.kyc.reject', $kyc) }}" class="space-y-2">
                            @csrf
                            <label class="block text-[11px] font-bold text-slate-700">Rejeter le dossier :</label>
                            <textarea name="reason" required minlength="5" rows="2"
                                      placeholder="Motif du rejet (visible par le vendeur pour corriger)..."
                                      class="w-full rounded-xl border border-slate-200 p-2.5 text-xs focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500"></textarea>
                            <button type="submit" class="w-full rounded-2xl bg-red-600 hover:bg-red-700 px-4 py-2.5 text-xs font-black text-white transition">
                                ✕ REJETER LE DOSSIER
                            </button>
                        </form>

                        <div class="border-t border-slate-100 pt-3"></div>

                        {{-- 4. CHALLENGER --}}
                        <form method="POST" action="{{ route('admin.kyc.challenge', $kyc) }}" class="space-y-2">
                            @csrf
                            <label class="block text-[11px] font-bold text-slate-700">Challenger le vendeur :</label>
                            <input type="text" name="reason" required placeholder="Motif du challenge (ex: pré-paiement exigé)..."
                                   class="w-full rounded-xl border border-slate-200 p-2 text-xs focus:outline-none focus:ring-1 focus:ring-orange-500" />
                            <button type="submit" class="w-full rounded-2xl bg-orange-600 hover:bg-orange-700 px-4 py-2.5 text-xs font-black text-white transition">
                                ⚡ CHALLENGER (Pré-paiement forcé)
                            </button>
                        </form>

                        <div class="border-t border-slate-100 pt-3"></div>

                        {{-- 5. RESTREINDRE --}}
                        <form method="POST" action="{{ route('admin.kyc.restrict', $kyc) }}" class="space-y-2">
                            @csrf
                            <label class="block text-[11px] font-bold text-slate-700">Restreindre les privilèges :</label>
                            <input type="text" name="reason" required placeholder="Motif de la restriction temporaire..."
                                   class="w-full rounded-xl border border-slate-200 p-2 text-xs focus:outline-none focus:ring-1 focus:ring-purple-500" />
                            <button type="submit" class="w-full rounded-2xl bg-purple-700 hover:bg-purple-800 px-4 py-2.5 text-xs font-black text-white transition">
                                🛡 RESTREINDRE (Sanction temporaire)
                            </button>
                        </form>

                        <div class="border-t border-slate-100 pt-3"></div>

                        {{-- 6. BLOQUER & BLACKLISTER --}}
                        <form method="POST" action="{{ route('admin.kyc.block', $kyc) }}" class="space-y-2" onsubmit="return confirm('Attention : ce vendeur sera banni et inscrit sur la liste noire définitive. Confirmer ?');">
                            @csrf
                            <label class="block text-[11px] font-bold text-red-700">Bannir et inscrire en liste noire :</label>
                            <input type="text" name="reason" required placeholder="Motif du blocage définitif..."
                                   class="w-full rounded-xl border border-red-300 p-2 text-xs focus:outline-none focus:ring-1 focus:ring-red-600" />
                            <button type="submit" class="w-full rounded-2xl bg-slate-900 hover:bg-black px-4 py-2.5 text-xs font-black text-white transition">
                                ⛔ BLOQUER ET BLACKLISTER
                            </button>
                        </form>
                    </div>

                    {{-- Synthèse métadonnées techniques session --}}
                    <div class="mt-6 pt-4 border-t border-slate-100 text-[10px] text-slate-400 space-y-1 font-mono">
                        <div>Session UUID : {{ $kyc->didit_session_id }}</div>
                        <div>Workflow ID : {{ $kyc->didit_workflow_id }}</div>
                        <div>Vendor Data : {{ $kyc->didit_vendor_data }}</div>
                        <div>Env : {{ $kyc->didit_environment ?? 'sandbox' }}</div>
                    </div>
                </div>

            </aside>

        </div>

    </div>
</div>
@endsection
