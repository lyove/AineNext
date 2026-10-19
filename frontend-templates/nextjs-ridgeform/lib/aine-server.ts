import "server-only";
import { createClient } from '../sdk';

export const aine = createClient({
  baseUrl: process.env.AINE_BASE_URL!,
  projectId: process.env.AINE_PROJECT_ID!,
  apiKey: process.env.AINE_API_KEY!,
});
