import { createClient } from '../sdk';

/** Server-only CMS client. Never import this from client components. */
export const aine = createClient({
  baseUrl: process.env.AINE_BASE_URL!,
  projectId: process.env.AINE_PROJECT_ID!,
  apiKey: process.env.AINE_API_KEY!,
});
