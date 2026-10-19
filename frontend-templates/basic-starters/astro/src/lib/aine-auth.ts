import { createClient } from '../../sdk';
import { AINE_BASE_URL, AINE_PROJECT_ID } from "astro:env/server";

/** Auth client for project-user identity (no API key). */
export function createAuthClient(session?: {
  accessToken?: string;
  refreshToken?: string;
}) {
  if (!AINE_PROJECT_ID) {
    throw new Error("AINE_PROJECT_ID is not configured");
  }

  return createClient({
    baseUrl: AINE_BASE_URL,
    projectId: AINE_PROJECT_ID,
    projectUserAuth: session?.accessToken
      ? {
          accessToken: session.accessToken,
          refreshToken: session.refreshToken,
          autoRefresh: false,
        }
      : {
          autoRefresh: false,
        },
  });
}
