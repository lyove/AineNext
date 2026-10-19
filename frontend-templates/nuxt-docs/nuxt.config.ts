// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  modules: ['@nuxt/ui'],

  devtools: {
    enabled: true
  },

  css: ['~/assets/css/main.css'],

  colorMode: {
    preference: 'system',
    fallback: 'light',
    storageKey: 'docs-color-mode'
  },

  /**
   * Keep defaults empty / non-secret so values are not baked into the Nitro build.
   * Runtime: `NUXT_AINE_*` map onto these keys; server utils also read `AINE_*`.
   */
  runtimeConfig: {
    aineBaseUrl: '',
    aineProjectId: '',
    aineApiKey: '',
    revalidateSecret: '',
    cmsCacheMaxAge: 3600,
    public: {
      siteUrl: 'http://localhost:3000'
    }
  },

  routeRules: {
    '/api/revalidate': {
      swr: false,
      headers: { 'Cache-Control': 'no-store' }
    }
  },

  compatibilityDate: '2026-06-30'
})
