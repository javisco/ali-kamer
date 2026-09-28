import { createClient } from '@supabase/supabase-js';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

// Nécessaire avec les ES Modules pour reconstruire les chemins locaux
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// 1. CONFIGURATION (Remplacer avec vos accès Supabase)
const SUPABASE_URL = 'https://hljegexybqwnidxylnft.supabase.co';
const SUPABASE_SERVICE_ROLE_KEY = '';
const BUCKET_NAME = 'images-tests'; // Exemple: 'images' ou 'uploads'

// Le dossier local actuel où se trouvent physiquement vos images de test
const DOSSIER_LOCAL_IMAGES = path.join(__dirname, 'public/storage/products/pindd');

const supabase = createClient(SUPABASE_URL, SUPABASE_SERVICE_ROLE_KEY);

async function uploadDossier(cheminDossierActuel, cheminRelatifBucket = '') {
  const fichiers = fs.readdirSync(cheminDossierActuel);

  for (const fichier of fichiers) {
    const cheminCompletLocal = path.join(cheminDossierActuel, fichier);
    const cheminFichierBucket = path.join(cheminRelatifBucket, fichier).replace(/\\/g, '/');

    if (fs.statSync(cheminCompletLocal).isDirectory()) {
      await uploadDossier(cheminCompletLocal, cheminFichierBucket);
    } else {
      const bufferFichier = fs.readFileSync(cheminCompletLocal);

      const { data, error } = await supabase.storage
        .from(BUCKET_NAME)
        .upload(cheminFichierBucket, bufferFichier, {
          upsert: true
        });

      if (error) {
        console.error(`❌ Erreur pour ${cheminFichierBucket}:`, error.message);
      } else {
        console.log(`✅ Envoyé avec成功 : ${cheminFichierBucket}`);
      }
    }
  }
}

console.log('Début de l\'envoi des images de test...');
uploadDossier(DOSSIER_LOCAL_IMAGES).catch(err => console.error("Erreur globale :", err));
