import { createClient } from '../../sdk'

type AineServerClient = ReturnType<typeof createClient>

let _client: AineServerClient | null = null
let _clientKey = ''

/**
 * Resolve Aine server config without baking secrets into the build.
 * Prefer live process.env (AINE_* / NUXT_AINE_*), then runtimeConfig.
 */
function resolveAineConfig() {
  const config = useRuntimeConfig()
  const env = typeof process !== 'undefined' ? process.env : undefined

  const baseUrl = String(
    env?.['AINE_BASE_URL']
    || env?.['NUXT_AINE_BASE_URL']
    || config.aineBaseUrl
    || '',
  ).trim()
  const projectId = String(
    env?.['AINE_PROJECT_ID']
    || env?.['NUXT_AINE_PROJECT_ID']
    || config.aineProjectId
    || '',
  ).trim()
  const apiKey = String(
    env?.['AINE_API_KEY']
    || env?.['NUXT_AINE_API_KEY']
    || config.aineApiKey
    || '',
  ).trim()

  return { baseUrl, projectId, apiKey }
}

/** Server-only CMS client with the project API token. */
export function useAineServer() {
  const { baseUrl, projectId, apiKey } = resolveAineConfig()

  if (!baseUrl || !projectId || !apiKey) {
    throw createError({
      statusCode: 500,
      statusMessage: 'Missing AINE_BASE_URL, AINE_PROJECT_ID, or AINE_API_KEY',
    })
  }

  const nextKey = `${baseUrl}|${projectId}|${apiKey}`
  if (!_client || _clientKey !== nextKey) {
    _client = createClient({
      baseUrl,
      projectId,
      apiKey,
    })
    _clientKey = nextKey
  }

  return _client
}

/** Auth client for project-user identity (no API key). */
export function createAuthClient(session?: {
  accessToken?: string
  refreshToken?: string
}) {
  const { baseUrl, projectId } = resolveAineConfig()
  if (!projectId) {
    throw createError({
      statusCode: 500,
      statusMessage: 'AINE_PROJECT_ID is not configured',
    })
  }

  return createClient({
    baseUrl,
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
  })
}
