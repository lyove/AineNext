import { createClient } from '../sdk';

type ServerClient = ReturnType<typeof createClient>;

/**
 * Server-only CMS client. Never import this from client components.
 */
function createServerClient(): ServerClient {
  const baseUrl = process.env.AINE_BASE_URL;
  const projectId = process.env.AINE_PROJECT_ID;
  const apiKey = process.env.AINE_API_KEY;

  if (!baseUrl || !projectId || !apiKey) {
    throw new Error(
      "Missing Aine env: set AINE_BASE_URL, AINE_PROJECT_ID, and AINE_API_KEY (server-only).",
    );
  }

  return createClient({ baseUrl, projectId, apiKey });
}

let client: ServerClient | null = null;

export const aine: ServerClient = new Proxy({} as ServerClient, {
  get(_target, prop, receiver) {
    if (!client) client = createServerClient();
    const value = Reflect.get(client as object, prop, receiver);
    return typeof value === "function" ? value.bind(client) : value;
  },
});
