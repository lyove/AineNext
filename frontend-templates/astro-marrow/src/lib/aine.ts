import { createClient } from '../../sdk';
import {
  AINE_API_KEY,
  AINE_BASE_URL,
  AINE_PROJECT_ID,
} from 'astro:env/server';

export const aine = createClient({
  baseUrl: AINE_BASE_URL,
  projectId: AINE_PROJECT_ID,
  apiKey: AINE_API_KEY,
});
