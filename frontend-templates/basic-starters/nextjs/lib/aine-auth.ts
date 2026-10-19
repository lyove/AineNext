import { createClient } from '../sdk';

/** Auth client without the project API key (identity-only). */
export function createAuthClient(session?: {
  accessToken?: string;
  refreshToken?: string;
}) {
  const projectId = process.env.AINE_PROJECT_ID;
  if (!projectId) {
    throw new Error("AINE_PROJECT_ID is not configured");
  }

  return createClient({
    baseUrl: process.env.AINE_BASE_URL!,
    projectId,
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
