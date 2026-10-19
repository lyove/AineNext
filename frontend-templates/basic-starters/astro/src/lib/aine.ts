import { createClient } from '../../sdk';
import {
  AINE_API_KEY,
  AINE_BASE_URL,
  AINE_PROJECT_ID,
} from "astro:env/server";

/**
 * Server-only CMS client with the project API token.
 * Never import this into client-side islands with the API key.
 */
export const aine = createClient({
  baseUrl: AINE_BASE_URL,
  projectId: AINE_PROJECT_ID,
  apiKey: AINE_API_KEY,
});
