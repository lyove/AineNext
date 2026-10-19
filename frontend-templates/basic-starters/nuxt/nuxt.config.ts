import tailwindcss from '@tailwindcss/vite'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  css: ['~/assets/css/main.css'],
  vite: {
    plugins: [tailwindcss()],
  },
  /**
   * Keep defaults empty so secrets are not baked into the Nitro build.
   * At runtime, Nuxt maps `NUXT_AINE_*` onto these keys. Server utils also
   * read `AINE_*` from process.env (bracket access) for local `.env` / hosts
   * that use the shared Aine env names.
   */
  runtimeConfig: {
    aineBaseUrl: '',
    aineProjectId: '',
    aineApiKey: '',
    public: {
      siteUrl: 'http://localhost:3000',
    },
  },
})
