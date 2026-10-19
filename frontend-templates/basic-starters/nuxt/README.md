# Aine basic starter (Nuxt)

Reference Nuxt starter for developers wiring Aine into a Nuxt project. Stub pages and clear file layout.

Part of the [basic starters](../README.md) shared project (`en` / `de`, `site-settings`, `notes`). Same schema as the Next.js starter.

## Requirements

- Node.js 20+ (Nuxt 4 may prefer newer patch versions)
- An AineNext project. Import [`../aine-project-basic-starters.json`](../aine-project-basic-starters.json)

## Import the Aine project

1. In AineNext, click **+ New Project**.
2. Choose **Import from file** and select `../aine-project-basic-starters.json`.
3. Click **Create Project**.
4. Create a project API token with read + write abilities.

## Configure environment

Copy `.env.example` to `.env` and fill in:

| Variable | Notes |
|----------|--------|
| `AINE_BASE_URL` | Instance API root, include `/api` |
| `AINE_PROJECT_ID` | Project UUID (also exposed as public for auth client base config) |
| `AINE_API_KEY` | Project API token (**server-only** via `runtimeConfig`, not `public`) |
| `NUXT_PUBLIC_SITE_URL` | Optional public origin |

## Install and run

```bash
npm install
npm run dev
```

Open [http://localhost:3000](http://localhost:3000). Unprefixed paths redirect to `/en` or `/de`.

## Wiring map

| Concern | Where |
|---------|--------|
| Server CMS client (API key) | `server/utils/aine.ts` → `useAineServer()` |
| Content helpers | `server/utils/content.ts` |
| Richtext | `server/utils/rich-text.ts`, `app/components/RichText.vue` |
| Auth cookies + refresh | `server/utils/auth-cookies.ts` |
| Login / register / logout / me | `server/api/auth/*` |
| Notes list/get/create | `server/api/notes/*` |
| Asset upload (+ metadata patch) | `server/api/assets/upload.post.ts` |
| Locales + dictionaries | `app/utils/i18n.ts`, `app/utils/dictionaries.ts` |
| Locale redirect | `app/middleware/locale.global.ts` |
| Account guard | `app/middleware/auth.ts` |
| Auth composable | `app/composables/useAuth.ts` |
| Pages | `app/pages/[locale]/…` |

## Auth contract (checklist)

1. Login stores access + refresh tokens in httpOnly cookies
2. `/api/auth/me` and BFF writes refresh expired access tokens once
3. Refresh failure clears cookies and requires sign-in
4. Logout calls `client.signOut()` (backend revoke) **before** clearing cookies
5. Failed revoke returns an error and does not clear cookies
6. Project API key stays in server `runtimeConfig` only

## Manual checks

1. Visit `/` → lands on `/en` or `/de`
2. Switch locale on a note page
3. Sign up or sign in → Account → Sign out → Sign in again
4. Create a note (auth optional; signed-in users get author fields)
5. Upload a file with alt text; confirm it appears in the asset library
6. On Notes, try filters, sort, and pagination

## Deploy notes

Set the same env vars on your host. Keep `AINE_API_KEY` out of `runtimeConfig.public`.

## Customizing

- Editorial copy: Aine `site-settings` and `notes`
- Static UI strings: `app/utils/dictionaries.ts`
- Schema: shared project only; keep field names in sync with `server/utils/types.ts`
