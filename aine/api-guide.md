# AineNext Headless CMS — Complete API Reference

## Overview

AineNext is a Headless CMS that provides content management capabilities through RESTful APIs. All API endpoints share a unified `/api` prefix.

**Base URL:** `https://your-cms-domain.com/api`

---

## Method A: Access Token (Server-Side)

**Use Cases:** Node.js backends, CI/CD scripts, server-side rendering, etc.
**Available Abilities:** read / create / update / delete / admin
**Authentication:** Include `Authorization: Bearer <token>` and `project-id: <uuid>` in request headers

### Endpoint Summary

#### Project Info
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| GET | [/api](#a11-get-project-info) | read | Get project info |

#### Project Locale Management
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| POST | [/api/project/locales](#a21-add-locale) | admin | Add locale |
| PUT | [/api/project/locales/default](#a22-set-default-locale) | admin | Set default locale |
| DELETE | [/api/project/locales/{locale}](#a23-delete-locale) | admin | Delete locale |

#### Asset Management
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| GET | [/api/files](#a31-list-assets) | read | List assets |
| GET | [/api/files/{identifier}](#a32-get-asset-by-id) | read | Get asset by ID |
| GET | [/api/files/name/{filename}](#a33-get-asset-by-filename) | read | Get asset by filename |
| POST | [/api/files](#a34-upload-single-asset) | create | Upload single asset |
| POST | [/api/files/bulk/upload](#a35-bulk-upload-assets) | create | Bulk upload |
| PATCH | [/api/files/bulk/metadata](#a36-bulk-update-asset-metadata) | update | Bulk update metadata |
| POST | [/api/files/upload-url](#a37-initiate-direct-upload-presigned-url) | create | Initiate direct upload |
| POST | [/api/files/upload-finalize](#a38-finalize-direct-upload) | create | Finalize direct upload |
| POST | [/api/files/multipart/initiate](#a39-initiate-multipart-upload) | create | Initiate multipart upload |
| POST | [/api/files/multipart/part-url](#a310-get-multipart-upload-part-url) | create | Get part URL |
| POST | [/api/files/multipart/complete](#a311-complete-multipart-upload) | create | Complete multipart upload |
| DELETE | [/api/files/{identifier}](#a312-delete-asset) | delete | Delete asset |

#### Collection Management
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| GET | [/api/collections](#a41-list-all-collections) | read | List collections |
| GET | [/api/collections/{collection}](#a42-get-collection-detail) | read | Get collection detail |
| POST | [/api/collections](#a43-create-collection) | admin | Create collection |
| PUT | [/api/collections/{collection}](#a44-update-collection) | admin | Update collection |
| DELETE | [/api/collections/{collection}](#a45-delete-collection) | admin | Delete collection |
| POST | [/api/collections/reorder](#a46-reorder-collections) | admin | Reorder collections |

#### Field Management
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| POST | [/api/collections/{collection}/fields](#a51-create-field) | admin | Create field |
| PUT | [/api/collections/{collection}/fields/{field}](#a52-update-field) | admin | Update field |
| DELETE | [/api/collections/{collection}/fields/{field}](#a53-delete-field) | admin | Delete field |
| POST | [/api/collections/{collection}/fields/reorder](#a54-reorder-fields) | admin | Reorder fields |

#### Content Entry Management
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| GET | [/api/{collection}](#a61-list-content-entries) | read | List entries |
| GET | [/api/{collection}/{uuid}](#a62-get-single-entry) | read | Get single entry |
| POST | [/api/{collection}](#a63-create-content-entry) | create | Create entry |
| PUT/PATCH | [/api/{collection}/{uuid}](#a64-update-content-entry) | update | Update entry |
| DELETE | [/api/{collection}/{uuid}](#a65-delete-content-entry) | delete | Delete entry |
| POST | [/api/{collection}/{uuid}/publish](#a66-publish-entry) | update | Publish entry |
| POST | [/api/{collection}/{uuid}/unpublish](#a67-unpublish-entry) | update | Unpublish entry |
| POST | [/api/{collection}/{uuid}/discard-draft](#a68-discard-draft) | update | Discard draft |
| POST | [/api/{collection}/{uuid}/link-translation](#a69-link-translation) | update | Link translation |

#### Bulk Operations
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| POST | [/api/bulk/{collection}/entries](#a71-bulk-create-entries) | create | Bulk create |
| PATCH | [/api/bulk/{collection}/entries](#a72-bulk-update-entries) | update | Bulk update |
| DELETE | [/api/bulk/{collection}/entries](#a73-bulk-delete-entries) | delete | Bulk delete |

#### Content Version Management
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| GET | [/api/{collection}/{uuid}/versions](#a81-list-versions) | read | List versions |
| GET | [/api/{collection}/{uuid}/versions/{version}](#a82-get-version-detail) | read | Get version detail |
| POST | [/api/{collection}/{uuid}/versions/{version}/revert](#a83-revert-to-version) | update | Revert to version |
| PATCH | [/api/{collection}/{uuid}/versions/{version}](#a84-update-version-label) | update | Update version label |

#### Webhook Management
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| GET | [/api/webhooks](#a91-list-webhooks) | admin | List webhooks |
| POST | [/api/webhooks](#a92-create-webhook) | admin | Create webhook |
| GET | [/api/webhooks/{uuid}](#a93-get-webhook-detail) | admin | Get webhook detail |
| GET | [/api/webhooks/{uuid}/logs](#a94-get-webhook-logs) | admin | Get webhook logs |
| PUT | [/api/webhooks/{uuid}](#a95-update-webhook) | admin | Update webhook |
| DELETE | [/api/webhooks/{uuid}](#a96-delete-webhook) | admin | Delete webhook |

#### API Key Management
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| POST | [/api/auth
... [ACP display truncated; original 98436 bytes] ...
'GET',
    headers: {
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGc...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### C.3.2 Create API Key

**Endpoint:** `POST /api/auth/api-keys`  
**Ability:** JWT  
**Description:** Create a new API key for the current user.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | `Bearer <jwt_access_token>` |
| `name` | Body | string | Yes | Key name |
| `abilities` | Body | string[] | Yes | Key abilities |

**Output Parameters:**

| Field | Type | Description |
|-------|------|-------------|
| `data.id` | integer | Key ID |
| `data.name` | string | Key name |
| `data.abilities` | string[] | Key abilities |
| `data.plain_text_token` | string | The API key (shown only once) |

**Example:**

```javascript
const response = await fetch('/api/auth/api-keys', {
    method: 'POST',
    headers: {
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGc...',
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        name: 'My API Key',
        abilities: ['read', 'create'],
    }),
});
const result = await response.json();
console.log(result);
```

---

#### C.3.3 Revoke API Key

**Endpoint:** `POST /api/auth/api-keys/{keyId}/revoke`  
**Ability:** JWT  
**Description:** Revoke (delete) an API key.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | `Bearer <jwt_access_token>` |
| `keyId` | Path | integer | Yes | API key ID |

**Output Parameters:**

| Field | Type | Description |
|-------|------|-------------|
| `message` | string | Operation result message |

**Example:**

```javascript
const response = await fetch('/api/auth/api-keys/1/revoke', {
    method: 'POST',
    headers: {
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGc...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### C.3.4 Change Password

**Endpoint:** `POST /api/auth/change-password`  
**Ability:** JWT  
**Description:** Change the current user's password.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | `Bearer <jwt_access_token>` |
| `current_password` | Body | string | Yes | Current password |
| `password` | Body | string | Yes | New password |
| `password_confirmation` | Body | string | Yes | Confirm new password |

**Output Parameters:**

| Field | Type | Description |
|-------|------|-------------|
| `message` | string | Operation result message |

**Example:**

```javascript
const response = await fetch('/api/auth/change-password', {
    method: 'POST',
    headers: {
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGc...',
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        current_password: 'oldpass',
        password: 'newpass123',
        password_confirmation: 'newpass123',
    }),
});
const result = await response.json();
console.log(result);
```

---

## File Streaming Service (Public Access)

#### Access Uploaded Files

**Endpoint:** `GET /uploads/{path}`  
**Ability:** Public (no authentication required)  
**Description:** Directly access uploaded file resources. Rate limited to 120 requests/minute.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `path` | Path | string | Yes | File path |

**Output:** Returns file binary content directly.

**Example:**

```javascript
// Direct use in HTML
<img src="/uploads/projects/uuid/assets/uuid/image.jpg" />

// Fetch via JavaScript
const blob = await fetch('/uploads/projects/uuid/assets/uuid/image.jpg')
    .then(r => r.blob());
```

---

## Domain Whitelist Configuration Guide

### Configuration Steps

1. Go to Admin Panel → **Settings → API Access**
2. In the **Allowed Domain Whitelist** section, click **Add Domain**
3. Enter the frontend domain (e.g. `https://mysite.com`)
4. Select abilities (default `read`, optionally check `create`, `update`)
5. Click **Add**

### Configuration Recommendations

| Scenario | Domain | Abilities |
|----------|--------|-----------|
| Production frontend | `https://mysite.com` | read |
| Staging environment | `https://staging.mysite.com` | read, create |
| Local development | `http://localhost:3000` | read, create, update |
| Admin panel | Use Token | admin (all) |

### Security Notes

- The `Origin` header is enforced by the browser engine and cannot be forged by JavaScript
- Exact matching: `https://evil.mysite.com` will **NOT** match `https://mysite.com`
- `delete` and `admin` abilities **cannot** be granted via whitelist, always require Token
- Whitelisted domains are also protected by rate limiting

---

## Ability Quick Reference

| Operation | Token | Domain Whitelist |
|-----------|:-----:|:----------------:|
| Get project info | read | read |
| List/get collections | read | read |
| List/get entries | read | read |
| Get assets | read | read |
| List versions | read | read |
| Create entries | create | create |
| Bulk create | create | create |
| Update entries | update | update |
| Publish/unpublish | update | update |
| Version revert | update | update |
| Upload assets | create | create |
| Delete entries | delete | -- |
| Delete assets | delete | -- |
| Manage collections/fields | admin | -- |
| Manage webhooks | admin | -- |
| Manage locales | admin | -- |

---
