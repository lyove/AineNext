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
| POST | [/api/auth/api-keys/introspect](#a101-introspect-api-key) | introspect | Introspect API key |

---

## Method B: Domain Whitelist (Frontend Calls)

> **Note:** Method B uses the same endpoints as Method A, but authenticates via domain whitelist instead of Bearer Token. No `Authorization` header needed — only `project-id` header is required. Refer to Method A detailed sections for full documentation.

### Endpoint Summary

#### Project Info
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| GET | [/api](#b11-get-project-info) | read | Get project info |

#### Asset Management
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| GET | [/api/files](#b21-list-assets) | read | List assets |
| GET | [/api/files/{identifier}](#b22-get-asset-by-id) | read | Get asset by ID |
| GET | [/api/files/name/{filename}](#b23-get-asset-by-filename) | read | Get asset by filename |
| POST | [/api/files](#b24-upload-single-asset) | create | Upload asset |
| POST | [/api/files/bulk/upload](#b25-bulk-upload-assets) | create | Bulk upload |
| PATCH | [/api/files/bulk/metadata](#b26-bulk-update-asset-metadata) | update | Bulk update metadata |
| POST | [/api/files/upload-url](#b27-initiate-direct-upload-presigned-url) | create | Initiate direct upload |
| POST | [/api/files/upload-finalize](#b28-finalize-direct-upload) | create | Finalize direct upload |
| POST | [/api/files/multipart/initiate](#b29-initiate-multipart-upload) | create | Initiate multipart upload |
| POST | [/api/files/multipart/part-url](#b210-get-multipart-upload-part-url) | create | Get part URL |
| POST | [/api/files/multipart/complete](#b211-complete-multipart-upload) | create | Complete multipart upload |
| DELETE | /api/files/{identifier} | - | **Unavailable** (requires Token) |

#### Collection Management
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| GET | [/api/collections](#b31-list-all-collections) | read | List collections |
| GET | [/api/collections/{collection}](#b32-get-collection-detail) | read | Get collection detail |

#### Content Entry Management
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| GET | [/api/{collection}](#b41-list-content-entries) | read | List entries |
| GET | [/api/{collection}/{uuid}](#b42-get-single-entry) | read | Get single entry |
| POST | [/api/{collection}](#b43-create-content-entry) | create | Create entry |
| PUT/PATCH | [/api/{collection}/{uuid}](#b44-update-content-entry) | update | Update entry |
| POST | [/api/{collection}/{uuid}/publish](#b45-publish-entry) | update | Publish entry |
| POST | [/api/{collection}/{uuid}/unpublish](#b46-unpublish-entry) | update | Unpublish entry |

#### Bulk Operations
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| POST | [/api/bulk/{collection}/entries](#b51-bulk-create-entries) | create | Bulk create |
| PATCH | [/api/bulk/{collection}/entries](#b52-bulk-update-entries) | update | Bulk update |

#### Content Version Management
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| GET | [/api/{collection}/{uuid}/versions](#b61-list-versions) | read | List versions |
| GET | [/api/{collection}/{uuid}/versions/{version}](#b62-get-version-detail) | read | Get version detail |
| POST | [/api/{collection}/{uuid}/versions/{version}/revert](#b63-revert-to-version) | update | Revert to version |
| PATCH | [/api/{collection}/{uuid}/versions/{version}](#b64-update-version-label) | update | Update version label |

---

## Method B: Domain Whitelist (Frontend Calls) - Detailed Usage

> **Note:** Method B endpoints are identical to Method A, with the difference being authentication. Method B uses domain whitelist auto-authentication — no `Authorization` header needed, but `project-id` header is still required.

### B.1 Project Info

#### B.1.1 Get Project Info

**Endpoint:** `GET /api`
**Ability:** `read`
**Description:** Get project basic info.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `with` | Query | string | No | Eager loading: `collections`, `fields` |

**Output:** Same as A.1.1

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// ?with=collections,fields is an optional query parameter: eager loading: collections, fields
const response = await fetch('/api/?with=collections,fields', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
    },
});
const result = await response.json();
console.log(result);
```

---

### B.2 Asset Management

#### B.2.1 List Assets

**Endpoint:** `GET /api/files`
**Ability:** `read`
**Description:** List all asset files.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `search` | Query | string | No | Search keyword |
| `type` | Query | string | No | File type filter |
| `page` | Query | integer | No | Page number |
| `per_page` | Query | integer | No | Items per page |

**Output:** Same as A.3.1

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// ?type=image is an optional query parameter: file type filter; replace the example value image
// &per_page=10 is an optional query parameter: items per page; replace the example value 10
const response = await fetch('/api/files?type=image&per_page=10', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
    },
});
const result = await response.json();
console.log(result);
```

---

#### B.2.2 Get Asset by ID

**Endpoint:** `GET /api/files/{identifier}`
**Ability:** `read`
**Description:** Get a single asset by UUID or ID.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `identifier` | Path | string | Yes | Asset UUID or numeric ID |

**Output:** Same as A.3.2

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /asset-uuid-1 is a dynamic value (not a fixed path); it is the asset UUID or numeric ID; replace the example value asset-uuid-1 with a real one
const response = await fetch('/api/files/asset-uuid-1', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
    },
});
const result = await response.json();
console.log(result);
```

---

#### B.2.3 Get Asset by Filename

**Endpoint:** `GET /api/files/name/{filename}`
**Ability:** `read`
**Description:** Get asset info by original filename.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `filename` | Path | string | Yes | Original filename (with extension) |

**Output:** Same as A.3.3

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /name is a fixed path segment (lookup by original filename)
// /cover-photo.jpg is a dynamic value (not a fixed path); it is the original filename (with extension); replace the example value cover-photo.jpg with a real one
const response = await fetch('/api/files/name/cover-photo.jpg', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
    },
});
const result = await response.json();
console.log(result);
```

---

#### B.2.4 Upload Single Asset

**Endpoint:** `POST /api/files`
**Ability:** `create`
**Description:** Upload a single file.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `file` | Body (FormData) | File | Yes | File to upload |

**Output:** Same as A.3.4

**Example:**

```javascript
// FormData is the fixed way to build a multipart/form-data request body
const formData = new FormData();
// file is a fixed field name: File to upload; replace the right side with the actual file
formData.append('file', fileInput.files[0]);

// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
const response = await fetch('/api/files', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
    },
    // body passes the FormData directly; the browser sets the multipart/form-data Content-Type automatically
    body: formData,
});
const result = await response.json();
console.log(result);
```

---

#### B.2.5 Bulk Upload Assets

**Endpoint:** `POST /api/files/bulk/upload`
**Ability:** `create`
**Description:** Upload multiple files.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `files[]` | Body (FormData) | File[] | Yes | File array (1-20) |

**Output:** Same as A.3.5

**Example:**

```javascript
// FormData is the fixed way to build a multipart/form-data request body
const formData = new FormData();
// files[] is a fixed field name: File array (1-20); replace the right side with the actual file
formData.append('files[]', file1);
// files[] is a fixed field name: File array (1-20); replace the right side with the actual file
formData.append('files[]', file2);

// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /bulk is a fixed path segment (bulk operations)
// /upload is a fixed path segment (direct upload)
const response = await fetch('/api/files/bulk/upload', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
    },
    // body passes the FormData directly; the browser sets the multipart/form-data Content-Type automatically
    body: formData,
});
const result = await response.json();
console.log(result);
```

---

#### B.2.6 Bulk Update Asset Metadata

**Endpoint:** `PATCH /api/files/bulk/metadata`
**Ability:** `update`
**Description:** Bulk update metadata for multiple assets.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `items` | Body | array | Yes | Update items (1-50) |

**Output:** Same as A.3.6

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /bulk is a fixed path segment (bulk operations)
// /metadata is a fixed path segment (fixed path segment)
const response = await fetch('/api/files/bulk/metadata', {
    // method is the HTTP method; this example always uses PATCH
    method: 'PATCH',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // items is a request body parameter: update items (1-50); each array item contains uuid (asset UUID) and metadata fields to update (such as alt_text, title, author); replace the example value
        items: [
            { uuid: 'asset-uuid-1', alt_text: 'Updated' },
        ],
    }),
});
const result = await response.json();
console.log(result);
```

---

#### B.2.7 Initiate Direct Upload (Presigned URL)

**Endpoint:** `POST /api/files/upload-url`
**Ability:** `create`
**Description:** Get a presigned URL for direct client-to-storage uploads.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `original_filename` | Body | string | Yes | Original filename |
| `byte_size` | Body | integer | Yes | File size in bytes |

**Output:** Same as A.3.7

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /upload-url is a fixed path segment (initiate pre-signed upload)
const response = await fetch('/api/files/upload-url', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // original_filename is a request body parameter: original filename; replace the example value
        original_filename: 'large-video.mp4',
        // byte_size is a request body parameter: file size in bytes; replace the example value
        byte_size: 52428800,
    }),
});
const result = await response.json();
console.log(result);
```

---

#### B.2.8 Finalize Direct Upload

**Endpoint:** `POST /api/files/upload-finalize`
**Ability:** `create`
**Description:** Notify server that presigned URL upload is complete.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `asset_uuid` | Body | string | Yes | Pre-allocated asset UUID |

**Output:** Same as A.3.8

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /upload-finalize is a fixed path segment (finalize direct upload)
const response = await fetch('/api/files/upload-finalize', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // asset_uuid is a request body parameter: pre-allocated asset UUID; replace the example value
    body: JSON.stringify({ asset_uuid: 'pre-assigned-uuid' }),
});
const result = await response.json();
console.log(result);
```

---

#### B.2.9 Initiate Multipart Upload

**Endpoint:** `POST /api/files/multipart/initiate`
**Ability:** `create`
**Description:** Initiate a multipart upload and get an upload ID.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `original_filename` | Body | string | Yes | Original filename |
| `mime_type` | Body | string | Yes | MIME type |
| `byte_size` | Body | integer | Yes | File size in bytes |

**Output:** Same as A.3.9

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /multipart is a fixed path segment (multipart upload)
// /initiate is a fixed path segment (initiate multipart upload)
const response = await fetch('/api/files/multipart/initiate', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // original_filename is a request body parameter: original filename; replace the example value
        original_filename: 'large-video.mp4',
        // mime_type is a request body parameter: mIME type; replace the example value
        mime_type: 'video/mp4',
        // byte_size is a request body parameter: file size in bytes; replace the example value
        byte_size: 104857600,
    }),
});
const result = await response.json();
console.log(result);
```

---

#### B.2.10 Get Multipart Upload Part URL

**Endpoint:** `POST /api/files/multipart/part-url`
**Ability:** `create`
**Description:** Get a presigned URL for uploading a specific part.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `upload_id` | Body | string | Yes | Upload ID |
| `part_number` | Body | integer | Yes | Part number (1-10000) |

**Output:** Same as A.3.10

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /multipart is a fixed path segment (multipart upload)
// /part-url is a fixed path segment (get multipart part URL)
const response = await fetch('/api/files/multipart/part-url', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // upload_id is a request body parameter: upload ID; replace the example value
    // part_number is a request body parameter: part number (1-10000); replace the example value
    body: JSON.stringify({ upload_id: 'upload-id-xxx', part_number: 1 }),
});
const result = await response.json();
console.log(result);
```

---

#### B.2.11 Complete Multipart Upload

**Endpoint:** `POST /api/files/multipart/complete`
**Ability:** `create`
**Description:** Complete a multipart upload.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `upload_id` | Body | string | Yes | Upload ID |
| `parts` | Body | array | Yes | Parts info array |

**Output:** Same as A.3.11

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /multipart is a fixed path segment (multipart upload)
// /complete is a fixed path segment (complete multipart upload)
const response = await fetch('/api/files/multipart/complete', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // upload_id is a request body parameter: upload ID; replace the example value
        upload_id: 'upload-id-xxx',
        // parts is a request body parameter: parts info array; each array item is part information (part_number and etag); replace the example value
        parts: [
            { part_number: 1, etag: '"etag1"' },
            { part_number: 2, etag: '"etag2"' },
        ],
    }),
});
const result = await response.json();
console.log(result);
```

---

### B.3 Collection Management

#### B.3.1 List All Collections

**Endpoint:** `GET /api/collections`
**Ability:** `read`
**Description:** List all collections in the project.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |

**Output:** Same as A.4.1

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /collections is a fixed path segment (collections resource)
const response = await fetch('/api/collections', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
    },
});
const result = await response.json();
console.log(result);
```

---

#### B.3.2 Get Collection Detail

**Endpoint:** `GET /api/collections/{collection}`
**Ability:** `read`
**Description:** Get details of a single collection.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `collection` | Path | string | Yes | Collection UUID or slug |

**Output:** Same as A.4.2

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /collections is a fixed path segment (collections resource)
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
const response = await fetch('/api/collections/posts', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
    },
});
const result = await response.json();
console.log(result);
```

---

### B.4 Content Entry Management

#### B.4.1 List Content Entries

**Endpoint:** `GET /api/{collection}`
**Ability:** `read`
**Description:** List all content entries in a collection.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `page` | Query | integer | No | Page number |
| `per_page` | Query | integer | No | Items per page |
| `locale` | Query | string | No | Locale code |

**Output:** Same as A.6.1

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// ?page=1 is an optional query parameter: page number; replace the example value 1
// &per_page=10 is an optional query parameter: items per page; replace the example value 10
// &locale=zh is an optional query parameter: locale code; replace the example value zh
const response = await fetch('/api/posts?page=1&per_page=10&locale=zh', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
    },
});
const result = await response.json();
console.log(result);
```

---

#### B.4.2 Get Single Entry

**Endpoint:** `GET /api/{collection}/{uuid}`
**Ability:** `read`
**Description:** Get a single content entry.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |
| `locale` | Query | string | No | Locale code |

**Output:** Same as A.6.2

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
// ?locale=zh is an optional query parameter: locale code; replace the example value zh
const response = await fetch('/api/posts/entry-uuid-1?locale=zh', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
    },
});
const result = await response.json();
console.log(result);
```

---

#### B.4.3 Create Content Entry

**Endpoint:** `POST /api/{collection}`
**Ability:** `create`
**Description:** Create a new content entry.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `locale` | Body | string | No | Locale code |
| `fields` | Body | object | Yes | Field data |

**Output:** Same as A.6.3

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
const response = await fetch('/api/posts', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // locale is a request body parameter: locale code; replace the example value
        locale: 'zh',
        // fields is a request body parameter: field data; the inner keys (such as title and content) are example field names and depend on the collection schema; replace the example value
        fields: {
            // title is an example field name (not fixed); the actual key depends on the collection schema
            title: 'New Article',
            // content is an example field name (not fixed); the actual key depends on the collection schema
            content: 'Article content...',
        },
    }),
});
const result = await response.json();
console.log(result);
```

---

#### B.4.4 Update Content Entry

**Endpoint:** `PUT/PATCH /api/{collection}/{uuid}`
**Ability:** `update`
**Description:** Update a content entry.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |
| `fields` | Body | object | Yes | Field data |

**Output:** Same as A.6.4

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
const response = await fetch('/api/posts/entry-uuid-1', {
    // method is the HTTP method; this example always uses PATCH
    method: 'PATCH',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // fields is a request body parameter: field data; the inner keys (such as title and content) are example field names and depend on the collection schema; replace the example value
        fields: {
            // title is an example field name (not fixed); the actual key depends on the collection schema
            title: 'Updated Title',
        },
    }),
});
const result = await response.json();
console.log(result);
```

---

#### B.4.5 Publish Entry

**Endpoint:** `POST /api/{collection}/{uuid}/publish`
**Ability:** `update`
**Description:** Publish a content entry.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |

**Output:** Same as A.6.6

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
// /publish is a fixed path segment (publish entry)
const response = await fetch('/api/posts/entry-uuid-1/publish', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
    },
});
const result = await response.json();
console.log(result);
```

---

#### B.4.6 Unpublish Entry

**Endpoint:** `POST /api/{collection}/{uuid}/unpublish`
**Ability:** `update`
**Description:** Unpublish a content entry.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |

**Output:** Same as A.6.7

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
// /unpublish is a fixed path segment (unpublish entry)
const response = await fetch('/api/posts/entry-uuid-1/unpublish', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
    },
});
const result = await response.json();
console.log(result);
```

---

### B.5 Bulk Operations

#### B.5.1 Bulk Create Entries

**Endpoint:** `POST /api/bulk/{collection}/entries`
**Ability:** `create`
**Description:** Bulk create content entries.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `entries` | Body | array | Yes | Entries array |

**Output:** Same as A.7.1

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /bulk is a fixed path segment (bulk operations)
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entries is a fixed path segment (bulk content entries)
const response = await fetch('/api/bulk/posts/entries', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // entries is a request body parameter: entries array; each array item is a single entry object: uuid is the entry UUID and fields is the field data (inner keys are collection field names); replace the example value
        entries: [
            { locale: 'zh', fields: { title: 'Article 1' } },
            { locale: 'zh', fields: { title: 'Article 2' } },
        ],
    }),
});
const result = await response.json();
console.log(result);
```

---

#### B.5.2 Bulk Update Entries

**Endpoint:** `PATCH /api/bulk/{collection}/entries`
**Ability:** `update`
**Description:** Bulk update content entries.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `entries` | Body | array | Yes | Update items array |

**Output:** Same as A.7.2

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /bulk is a fixed path segment (bulk operations)
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entries is a fixed path segment (bulk content entries)
const response = await fetch('/api/bulk/posts/entries', {
    // method is the HTTP method; this example always uses PATCH
    method: 'PATCH',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // entries is a request body parameter: update items array; each array item is a single entry object: uuid is the entry UUID and fields is the field data (inner keys are collection field names); replace the example value
        entries: [
            { uuid: 'entry-uuid-1', fields: { title: 'Updated 1' } },
            { uuid: 'entry-uuid-2', fields: { title: 'Updated 2' } },
        ],
    }),
});
const result = await response.json();
console.log(result);
```

---

### B.6 Content Version Management

#### B.6.1 List Versions

**Endpoint:** `GET /api/{collection}/{uuid}/versions`
**Ability:** `read`
**Description:** List all versions of a content entry.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |

**Output:** Same as A.8.1

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
// /versions is a fixed path segment (content versions sub-resource)
const response = await fetch('/api/posts/entry-uuid-1/versions', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
    },
});
const result = await response.json();
console.log(result);
```

---

#### B.6.2 Get Version Detail

**Endpoint:** `GET /api/{collection}/{uuid}/versions/{version}`
**Ability:** `read`
**Description:** Get details of a specific version.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |
| `version` | Path | integer | Yes | Version number |

**Output:** Same as A.8.2

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
// /versions is a fixed path segment (content versions sub-resource)
// /2 is a dynamic value (not a fixed path); it is the version number; replace the example value 2 with a real one
const response = await fetch('/api/posts/entry-uuid-1/versions/2', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
    },
});
const result = await response.json();
console.log(result);
```

---

#### B.6.3 Revert to Version

**Endpoint:** `POST /api/{collection}/{uuid}/versions/{version}/revert`
**Ability:** `update`
**Description:** Revert content to a specific version.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |
| `version` | Path | integer | Yes | Target version number |

**Output:** Same as A.8.3

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
// /versions is a fixed path segment (content versions sub-resource)
// /2 is a dynamic value (not a fixed path); it is the target version number; replace the example value 2 with a real one
// /revert is a fixed path segment (revert version)
const response = await fetch('/api/posts/entry-uuid-1/versions/2/revert', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
    },
});
const result = await response.json();
console.log(result);
```

---

#### B.6.4 Update Version Label

**Endpoint:** `PATCH /api/{collection}/{uuid}/versions/{version}`
**Ability:** `update`
**Description:** Update the label of a version.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |
| `version` | Path | integer | Yes | Version number |
| `label` | Body | string | No | Version label |

**Output:** Same as A.8.4

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
// /versions is a fixed path segment (content versions sub-resource)
// /2 is a dynamic value (not a fixed path); it is the version number; replace the example value 2 with a real one
const response = await fetch('/api/posts/entry-uuid-1/versions/2', {
    // method is the HTTP method; this example always uses PATCH
    method: 'PATCH',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // label is a request body parameter: version label; replace the example value
        label: 'Important Version',
    }),
});
const result = await response.json();
console.log(result);
```

---

## Method C: JWT Authentication (End Users)

#### User Registration & Login
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| POST | [/api/auth/signup](#c11-user-signup) | Public | User signup |
| POST | [/api/auth/login](#c12-user-login) | Public | User login |
| POST | [/api/auth/refresh](#c13-refresh-token) | Public | Refresh token |
| POST | [/api/auth/verify-email/resend](#c14-resend-verification-email) | Public | Resend verification email |
| POST | [/api/auth/verify-email/confirm](#c15-confirm-verification-email) | Public | Confirm verification email |

#### Session Management
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| GET | [/api/auth/me](#c21-get-current-user-info) | JWT | Get current user |
| POST | [/api/auth/logout](#c22-logout-current-session) | JWT | Logout current session |
| POST | [/api/auth/logout-all](#c23-logout-all-sessions) | JWT | Logout all sessions |

#### API Key Management
| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| GET | [/api/auth/api-keys](#c31-list-api-keys) | JWT | List API keys |
| POST | [/api/auth/api-keys](#c32-create-api-key) | JWT | Create API key |
| POST | [/api/auth/api-keys/{keyId}/revoke](#c33-revoke-api-key) | JWT | Revoke API key |
| POST | [/api/auth/change-password](#c34-change-password) | JWT | Change password |

---

### File Streaming Service (Public Access)

| Method | Path | Ability | Description |
|--------|------|---------|-------------|
| GET | [/uploads/{path}](#file-streaming-service-public-access) | Public | Access uploaded files |

---

<br />

## Method A: Access Token (Server-Side) - Detailed Usage

### A.1 Project Info

#### A.1.1 Get Project Info

**Endpoint:** `GET /api`  
**Ability:** `read`  
**Description:** Get project basic info, optionally with collections and field definitions.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `with` | Query | string | No | Eager loading: `collections`, `fields` (comma-separated) |

**Output Parameters:**

| Field | Type | Description |
|-------|------|-------------|
| `data.uuid` | string | Project UUID |
| `data.name` | string | Project name |
| `data.description` | string | Project description |
| `data.preview_url` | string | Preview URL |
| `data.default_locale` | string | Default locale code |
| `data.locales` | string[] | Supported locales |
| `data.collections` | array | Collections list (requires `with=collections`) |

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// ?with=collections,fields is an optional query parameter: eager loading: collections, fields (comma-separated)
const response = await fetch('/api/?with=collections,fields', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

### A.2 Project Locale Management

#### A.2.1 Add Locale

**Endpoint:** `POST /api/project/locales`
**Ability:** `admin`
**Description:** Add a new locale to the project.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (admin) |
| `locale` | Body | string | Yes | Locale code (e.g. `fr`, `ja`, `zh`) |

**Output:** Returns updated project info with locales list.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /project is a fixed path segment (project configuration)
// /locales is a fixed path segment (project locales)
const response = await fetch('/api/project/locales', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // locale is a request body parameter: locale code (e.g. fr, ja, zh); replace the example value
    body: JSON.stringify({ locale: 'fr' }),
});
const result = await response.json();
console.log(result);
```

---

#### A.2.2 Set Default Locale

**Endpoint:** `PUT /api/project/locales/default`
**Ability:** `admin`
**Description:** Set the project's default locale.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (admin) |
| `locale` | Body | string | Yes | Locale code (must exist in project) |

**Output:** Returns updated project info.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /project is a fixed path segment (project configuration)
// /locales is a fixed path segment (project locales)
// /default is a fixed path segment (default locale (fixed special value))
const response = await fetch('/api/project/locales/default', {
    // method is the HTTP method; this example always uses PUT
    method: 'PUT',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // locale is a request body parameter: locale code (must exist in project); replace the example value
    body: JSON.stringify({ locale: 'zh' }),
});
const result = await response.json();
console.log(result);
```

---

#### A.2.3 Delete Locale

**Endpoint:** `DELETE /api/project/locales/{locale}`
**Ability:** `admin`
**Description:** Delete a locale from the project.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (admin) |
| `locale` | Path | string | Yes | Locale code to delete |

**Output:** Returns updated project info.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /project is a fixed path segment (project configuration)
// /locales is a fixed path segment (project locales)
// /fr is a dynamic value (not a fixed path); it is the locale code to delete; replace the example value fr with a real one
const response = await fetch('/api/project/locales/fr', {
    // method is the HTTP method; this example always uses DELETE
    method: 'DELETE',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

### A.3 Asset Management

#### A.3.1 List Assets

**Endpoint:** `GET /api/files`
**Ability:** `read`
**Description:** List all asset files with search, type filter, and pagination.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `search` | Query | string | No | Search keyword (filename) |
| `type` | Query | string | No | File type: `image`, `video`, `document`, `audio` |
| `page` | Query | integer | No | Page number (default 1) |
| `per_page` | Query | integer | No | Items per page (default 20) |

**Output:** Returns asset list with pagination metadata.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// ?type=image is an optional query parameter: file type: image, video, document, audio; replace the example value image
// &per_page=10 is an optional query parameter: items per page (default 20); replace the example value 10
const response = await fetch('/api/files?type=image&per_page=10', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.3.2 Get Asset by ID

**Endpoint:** `GET /api/files/{identifier}`
**Ability:** `read`
**Description:** Get a single asset by UUID or numeric ID.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `identifier` | Path | string | Yes | Asset UUID or numeric ID |

**Output:** Returns single asset object.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /asset-uuid-1 is a dynamic value (not a fixed path); it is the asset UUID or numeric ID; replace the example value asset-uuid-1 with a real one
const response = await fetch('/api/files/asset-uuid-1', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.3.3 Get Asset by Filename

**Endpoint:** `GET /api/files/name/{filename}`
**Ability:** `read`
**Description:** Get asset info by original filename.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `filename` | Path | string | Yes | Original filename (with extension) |

**Output:** Returns single asset object.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /name is a fixed path segment (lookup by original filename)
// /cover-photo.jpg is a dynamic value (not a fixed path); it is the original filename (with extension); replace the example value cover-photo.jpg with a real one
const response = await fetch('/api/files/name/cover-photo.jpg', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.3.4 Upload Single Asset

**Endpoint:** `POST /api/files`
**Ability:** `create`
**Description:** Upload a single file using `multipart/form-data`.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `file` | Body (FormData) | File | Yes | File to upload |

**Output:** Returns new asset object.

**Example:**

```javascript
// FormData is the fixed way to build a multipart/form-data request body
const formData = new FormData();
// file is a fixed field name: File to upload; replace the right side with the actual file
formData.append('file', fileInput.files[0]);
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
const response = await fetch('/api/files', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
    // body passes the FormData directly; the browser sets the multipart/form-data Content-Type automatically
    body: formData,
});
const result = await response.json();
console.log(result);
```

---

#### A.3.5 Bulk Upload Assets

**Endpoint:** `POST /api/files/bulk/upload`
**Ability:** `create`
**Description:** Upload multiple files (max 20, each up to 100MB).

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `files[]` | Body (FormData) | File[] | Yes | File array (1-20) |

**Output:** Returns array of uploaded asset objects.

**Example:**

```javascript
// FormData is the fixed way to build a multipart/form-data request body
const formData = new FormData();
// files[] is a fixed field name: File array (1-20); replace the right side with the actual file
formData.append('files[]', file1);
// files[] is a fixed field name: File array (1-20); replace the right side with the actual file
formData.append('files[]', file2);
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /bulk is a fixed path segment (bulk operations)
// /upload is a fixed path segment (direct upload)
const response = await fetch('/api/files/bulk/upload', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
    // body passes the FormData directly; the browser sets the multipart/form-data Content-Type automatically
    body: formData,
});
const result = await response.json();
console.log(result);
```

---

#### A.3.6 Bulk Update Asset Metadata

**Endpoint:** `PATCH /api/files/bulk/metadata`
**Ability:** `update`
**Description:** Bulk update metadata for multiple assets (max 50).

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `items` | Body | array | Yes | Update items (1-50) |
| `items[].uuid` | Body | string | Yes | Asset UUID |
| `items[].alt_text` | Body | string | No | Alt text |
| `items[].title` | Body | string | No | Title |

**Output:** Returns operation result message.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /bulk is a fixed path segment (bulk operations)
// /metadata is a fixed path segment (fixed path segment)
const response = await fetch('/api/files/bulk/metadata', {
    // method is the HTTP method; this example always uses PATCH
    method: 'PATCH',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // items is a request body parameter: update items (1-50); each array item contains uuid (asset UUID) and metadata fields to update (such as alt_text, title, author); replace the example value
        items: [
            { uuid: 'asset-uuid-1', alt_text: 'Updated', title: 'Cover' },
        ],
    }),
});
const result = await response.json();
console.log(result);
```

---

#### A.3.7 Initiate Direct Upload (Presigned URL)

**Endpoint:** `POST /api/files/upload-url`
**Ability:** `create`
**Description:** Get a presigned URL for direct client-to-storage uploads.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `original_filename` | Body | string | Yes | Original filename |
| `client_mime_type` | Body | string | No | MIME type |
| `byte_size` | Body | integer | Yes | File size in bytes |

**Output:** Returns `upload_url` and `asset_uuid`.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /upload-url is a fixed path segment (initiate pre-signed upload)
const response = await fetch('/api/files/upload-url', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // original_filename is a request body parameter: original filename; replace the example value
        original_filename: 'large-video.mp4',
        // client_mime_type is a request body parameter: mIME type; replace the example value
        client_mime_type: 'video/mp4',
        // byte_size is a request body parameter: file size in bytes; replace the example value
        byte_size: 52428800,
    }),
});
const result = await response.json();
console.log(result);
```

---

#### A.3.8 Finalize Direct Upload

**Endpoint:** `POST /api/files/upload-finalize`
**Ability:** `create`
**Description:** Notify server that presigned URL upload is complete.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `asset_uuid` | Body | string | Yes | Pre-allocated asset UUID |

**Output:** Returns asset object.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /upload-finalize is a fixed path segment (finalize direct upload)
const response = await fetch('/api/files/upload-finalize', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // asset_uuid is a request body parameter: pre-allocated asset UUID; replace the example value
    body: JSON.stringify({ asset_uuid: 'pre-assigned-uuid' }),
});
const result = await response.json();
console.log(result);
```

---

#### A.3.9 Initiate Multipart Upload

**Endpoint:** `POST /api/files/multipart/initiate`
**Ability:** `create`
**Description:** Initiate a multipart upload and get an upload ID.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `original_filename` | Body | string | Yes | Original filename |
| `mime_type` | Body | string | Yes | MIME type |
| `byte_size` | Body | integer | Yes | File size in bytes |

**Output:** Returns `upload_id` and `asset_uuid`.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /multipart is a fixed path segment (multipart upload)
// /initiate is a fixed path segment (initiate multipart upload)
const response = await fetch('/api/files/multipart/initiate', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // original_filename is a request body parameter: original filename; replace the example value
        original_filename: 'large-video.mp4',
        // mime_type is a request body parameter: mIME type; replace the example value
        mime_type: 'video/mp4',
        // byte_size is a request body parameter: file size in bytes; replace the example value
        byte_size: 104857600,
    }),
});
const result = await response.json();
console.log(result);
```

---

#### A.3.10 Get Multipart Upload Part URL

**Endpoint:** `POST /api/files/multipart/part-url`
**Ability:** `create`
**Description:** Get a presigned URL for uploading a specific part.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `upload_id` | Body | string | Yes | Upload ID |
| `part_number` | Body | integer | Yes | Part number (1-10000) |

**Output:** Returns `upload_url`.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /multipart is a fixed path segment (multipart upload)
// /part-url is a fixed path segment (get multipart part URL)
const response = await fetch('/api/files/multipart/part-url', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // upload_id is a request body parameter: upload ID; replace the example value
    // part_number is a request body parameter: part number (1-10000); replace the example value
    body: JSON.stringify({ upload_id: 'upload-id-xxx', part_number: 1 }),
});
const result = await response.json();
console.log(result);
```

---

#### A.3.11 Complete Multipart Upload

**Endpoint:** `POST /api/files/multipart/complete`
**Ability:** `create`
**Description:** Complete a multipart upload.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `upload_id` | Body | string | Yes | Upload ID |
| `parts` | Body | array | Yes | Parts info array |

**Output:** Returns asset object.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /multipart is a fixed path segment (multipart upload)
// /complete is a fixed path segment (complete multipart upload)
const response = await fetch('/api/files/multipart/complete', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // upload_id is a request body parameter: upload ID; replace the example value
        upload_id: 'upload-id-xxx',
        // parts is a request body parameter: parts info array; each array item is part information (part_number and etag); replace the example value
        parts: [
            { part_number: 1, etag: '"etag1"' },
            { part_number: 2, etag: '"etag2"' },
        ],
    }),
});
const result = await response.json();
console.log(result);
```

---

#### A.3.12 Delete Asset

**Endpoint:** `DELETE /api/files/{identifier}`
**Ability:** `delete`
**Description:** Delete an asset.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (delete ability) |
| `identifier` | Path | string | Yes | Asset UUID or numeric ID |

**Output:** Returns operation result message.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /files is a fixed path segment (assets resource)
// /asset-uuid-1 is a dynamic value (not a fixed path); it is the asset UUID or numeric ID; replace the example value asset-uuid-1 with a real one
const response = await fetch('/api/files/asset-uuid-1', {
    // method is the HTTP method; this example always uses DELETE
    method: 'DELETE',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

### A.4 Collection Management

#### A.4.1 List All Collections

**Endpoint:** `GET /api/collections`
**Ability:** `read`
**Description:** List all collections in the project.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |

**Output:** Returns collections list.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /collections is a fixed path segment (collections resource)
const response = await fetch('/api/collections', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.4.2 Get Collection Detail

**Endpoint:** `GET /api/collections/{collection}`
**Ability:** `read`
**Description:** Get details of a single collection.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `collection` | Path | string | Yes | Collection UUID or slug |

**Output:** Returns single collection object.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /collections is a fixed path segment (collections resource)
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
const response = await fetch('/api/collections/posts', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.4.3 Create Collection

**Endpoint:** `POST /api/collections`
**Ability:** `admin`
**Description:** Create a new collection.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (admin) |
| `name` | Body | string | Yes | Collection name |
| `slug` | Body | string | Yes | Collection slug |
| `is_singleton` | Body | boolean | No | Whether singleton |

**Output:** Returns new collection object.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /collections is a fixed path segment (collections resource)
const response = await fetch('/api/collections', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // name is a request body parameter: collection name; replace the example value
    // slug is a request body parameter: collection slug; replace the example value
    body: JSON.stringify({ name: 'Posts', slug: 'posts' }),
});
const result = await response.json();
console.log(result);
```

---

#### A.4.4 Update Collection

**Endpoint:** `PUT /api/collections/{collection}`
**Ability:** `admin`
**Description:** Update collection info.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (admin) |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `name` | Body | string | No | Collection name |
| `slug` | Body | string | No | Collection slug |

**Output:** Returns updated collection object.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /collections is a fixed path segment (collections resource)
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
const response = await fetch('/api/collections/posts', {
    // method is the HTTP method; this example always uses PUT
    method: 'PUT',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // name is a request body parameter: collection name; replace the example value
    body: JSON.stringify({ name: 'Blog Posts' }),
});
const result = await response.json();
console.log(result);
```

---

#### A.4.5 Delete Collection

**Endpoint:** `DELETE /api/collections/{collection}`
**Ability:** `admin`
**Description:** Delete a collection and all its content.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (admin) |
| `collection` | Path | string | Yes | Collection UUID or slug |

**Output:** Returns operation result message.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /collections is a fixed path segment (collections resource)
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
const response = await fetch('/api/collections/posts', {
    // method is the HTTP method; this example always uses DELETE
    method: 'DELETE',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.4.6 Reorder Collections

**Endpoint:** `POST /api/collections/reorder`
**Ability:** `admin`
**Description:** Reorder collections display order.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (admin) |
| `order` | Body | array | Yes | Collection UUIDs array (in order) |

**Output:** Returns operation result message.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /collections is a fixed path segment (collections resource)
// /reorder is a fixed path segment (reorder)
const response = await fetch('/api/collections/reorder', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // order is a request body parameter: collection UUIDs array (in order); each array item is a collection UUID in the desired order; replace the example value
    body: JSON.stringify({ order: ['col-uuid-1', 'col-uuid-2'] }),
});
const result = await response.json();
console.log(result);
```

---

### A.5 Field Management

#### A.5.1 Create Field

**Endpoint:** `POST /api/collections/{collection}/fields`
**Ability:** `admin`
**Description:** Create a new field in a collection.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (admin) |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `name` | Body | string | Yes | Field name |
| `label` | Body | string | Yes | Field label |
| `type` | Body | string | Yes | Field type |
| `required` | Body | boolean | No | Whether required |

**Output:** Returns new field object.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /collections is a fixed path segment (collections resource)
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /fields is a fixed path segment (fields sub-resource)
const response = await fetch('/api/collections/posts/fields', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // name is a request body parameter: field name; replace the example value
    // label is a request body parameter: field label; replace the example value
    // type is a request body parameter: field type; replace the example value
    // required is a request body parameter: whether required; replace the example value
    body: JSON.stringify({ name: 'title', label: 'Title', type: 'text', required: true }),
});
const result = await response.json();
console.log(result);
```

---

#### A.5.2 Update Field

**Endpoint:** `PUT /api/collections/{collection}/fields/{field}`
**Ability:** `admin`
**Description:** Update field info.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (admin) |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `field` | Path | string | Yes | Field UUID |
| `label` | Body | string | No | Field label |
| `required` | Body | boolean | No | Whether required |

**Output:** Returns updated field object.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /collections is a fixed path segment (collections resource)
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /fields is a fixed path segment (fields sub-resource)
// /field-uuid-1 is a dynamic value (not a fixed path); it is the field UUID; replace the example value field-uuid-1 with a real one
const response = await fetch('/api/collections/posts/fields/field-uuid-1', {
    // method is the HTTP method; this example always uses PUT
    method: 'PUT',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // label is a request body parameter: field label; replace the example value
    // required is a request body parameter: whether required; replace the example value
    body: JSON.stringify({ label: 'Article Title', required: true }),
});
const result = await response.json();
console.log(result);
```

---

#### A.5.3 Delete Field

**Endpoint:** `DELETE /api/collections/{collection}/fields/{field}`
**Ability:** `admin`
**Description:** Delete a field and its data.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (admin) |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `field` | Path | string | Yes | Field UUID |

**Output:** Returns operation result message.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /collections is a fixed path segment (collections resource)
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /fields is a fixed path segment (fields sub-resource)
// /field-uuid-1 is a dynamic value (not a fixed path); it is the field UUID; replace the example value field-uuid-1 with a real one
const response = await fetch('/api/collections/posts/fields/field-uuid-1', {
    // method is the HTTP method; this example always uses DELETE
    method: 'DELETE',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.5.4 Reorder Fields

**Endpoint:** `POST /api/collections/{collection}/fields/reorder`
**Ability:** `admin`
**Description:** Reorder fields display order.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (admin) |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `order` | Body | array | Yes | Field UUIDs array (in order) |

**Output:** Returns operation result message.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /collections is a fixed path segment (collections resource)
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /fields is a fixed path segment (fields sub-resource)
// /reorder is a fixed path segment (reorder)
const response = await fetch('/api/collections/posts/fields/reorder', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // order is a request body parameter: field UUIDs array (in order); each array item is a collection UUID in the desired order; replace the example value
    body: JSON.stringify({ order: ['field-uuid-1', 'field-uuid-2'] }),
});
const result = await response.json();
console.log(result);
```

---

### A.6 Content Entry Management

#### A.6.1 List Content Entries

**Endpoint:** `GET /api/{collection}`
**Ability:** `read`
**Description:** List all content entries in a collection.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `page` | Query | integer | No | Page number |
| `per_page` | Query | integer | No | Items per page |
| `locale` | Query | string | No | Locale code |

**Output:** Returns entries list with pagination.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// ?page=1 is an optional query parameter: page number; replace the example value 1
// &per_page=10 is an optional query parameter: items per page; replace the example value 10
// &locale=zh is an optional query parameter: locale code; replace the example value zh
const response = await fetch('/api/posts?page=1&per_page=10&locale=zh', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.6.2 Get Single Entry

**Endpoint:** `GET /api/{collection}/{uuid}`
**Ability:** `read`
**Description:** Get a single content entry.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |
| `locale` | Query | string | No | Locale code |

**Output:** Returns single entry object.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
// ?locale=zh is an optional query parameter: locale code; replace the example value zh
const response = await fetch('/api/posts/entry-uuid-1?locale=zh', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.6.3 Create Content Entry

**Endpoint:** `POST /api/{collection}`
**Ability:** `create`
**Description:** Create a new content entry.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `locale` | Body | string | No | Locale code |
| `fields` | Body | object | Yes | Field data |

**Output:** Returns new entry object.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
const response = await fetch('/api/posts', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // locale is a request body parameter: locale code; replace the example value
    // fields is a request body parameter: field data; the inner keys (such as title and content) are example field names and depend on the collection schema; replace the example value
    // title is an example field name (not fixed); the actual key depends on the collection schema
    // content is an example field name (not fixed); the actual key depends on the collection schema
    body: JSON.stringify({ locale: 'zh', fields: { title: 'New Article', content: '...' } }),
});
const result = await response.json();
console.log(result);
```

---

#### A.6.4 Update Content Entry

**Endpoint:** `PUT/PATCH /api/{collection}/{uuid}`
**Ability:** `update`
**Description:** Update a content entry.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |
| `fields` | Body | object | Yes | Field data |

**Output:** Returns updated entry object.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
const response = await fetch('/api/posts/entry-uuid-1', {
    // method is the HTTP method; this example always uses PATCH
    method: 'PATCH',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // fields is a request body parameter: field data; the inner keys (such as title and content) are example field names and depend on the collection schema; replace the example value
    // title is an example field name (not fixed); the actual key depends on the collection schema
    body: JSON.stringify({ fields: { title: 'Updated Title' } }),
});
const result = await response.json();
console.log(result);
```

---

#### A.6.5 Delete Content Entry

**Endpoint:** `DELETE /api/{collection}/{uuid}`
**Ability:** `delete`
**Description:** Delete a content entry.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (delete) |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |

**Output:** Returns operation result message.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
const response = await fetch('/api/posts/entry-uuid-1', {
    // method is the HTTP method; this example always uses DELETE
    method: 'DELETE',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.6.6 Publish Entry

**Endpoint:** `POST /api/{collection}/{uuid}/publish`
**Ability:** `update`
**Description:** Publish a content entry.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |

**Output:** Returns operation result message.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
// /publish is a fixed path segment (publish entry)
const response = await fetch('/api/posts/entry-uuid-1/publish', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.6.7 Unpublish Entry

**Endpoint:** `POST /api/{collection}/{uuid}/unpublish`
**Ability:** `update`
**Description:** Unpublish a content entry.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |

**Output:** Returns operation result message.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
// /unpublish is a fixed path segment (unpublish entry)
const response = await fetch('/api/posts/entry-uuid-1/unpublish', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.6.8 Discard Draft

**Endpoint:** `POST /api/{collection}/{uuid}/discard-draft`
**Ability:** `update`
**Description:** Discard the draft version of a content entry.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |

**Output:** Returns operation result message.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
// /discard-draft is a fixed path segment (discard draft)
const response = await fetch('/api/posts/entry-uuid-1/discard-draft', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.6.9 Link Translation

**Endpoint:** `POST /api/{collection}/{uuid}/link-translation`
**Ability:** `update`
**Description:** Link a content entry with its translation in another locale.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |
| `translation_uuid` | Body | string | Yes | Translation entry UUID |

**Output:** Returns operation result message.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
// /link-translation is a fixed path segment (link translation)
const response = await fetch('/api/posts/entry-uuid-1/link-translation', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // translation_uuid is a request body parameter: translation entry UUID; replace the example value
    body: JSON.stringify({ translation_uuid: 'entry-uuid-2' }),
});
const result = await response.json();
console.log(result);
```

---

### A.7 Bulk Operations

#### A.7.1 Bulk Create Entries

**Endpoint:** `POST /api/bulk/{collection}/entries`
**Ability:** `create`
**Description:** Bulk create content entries.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `entries` | Body | array | Yes | Entries array |

**Output:** Returns created entries list.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /bulk is a fixed path segment (bulk operations)
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entries is a fixed path segment (bulk content entries)
const response = await fetch('/api/bulk/posts/entries', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // entries is a request body parameter: entries array; each array item is a single entry object: uuid is the entry UUID and fields is the field data (inner keys are collection field names); replace the example value
        entries: [
            { locale: 'zh', fields: { title: 'Article 1' } },
            { locale: 'zh', fields: { title: 'Article 2' } },
        ],
    }),
});
const result = await response.json();
console.log(result);
```

---

#### A.7.2 Bulk Update Entries

**Endpoint:** `PATCH /api/bulk/{collection}/entries`
**Ability:** `update`
**Description:** Bulk update content entries.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `entries` | Body | array | Yes | Update items array |

**Output:** Returns updated entries list.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /bulk is a fixed path segment (bulk operations)
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entries is a fixed path segment (bulk content entries)
const response = await fetch('/api/bulk/posts/entries', {
    // method is the HTTP method; this example always uses PATCH
    method: 'PATCH',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // entries is a request body parameter: update items array; each array item is a single entry object: uuid is the entry UUID and fields is the field data (inner keys are collection field names); replace the example value
        entries: [
            { uuid: 'entry-uuid-1', fields: { title: 'Updated 1' } },
            { uuid: 'entry-uuid-2', fields: { title: 'Updated 2' } },
        ],
    }),
});
const result = await response.json();
console.log(result);
```

---

#### A.7.3 Bulk Delete Entries

**Endpoint:** `DELETE /api/bulk/{collection}/entries`
**Ability:** `delete`
**Description:** Bulk delete content entries.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (delete) |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuids` | Body | array | Yes | Entry UUIDs array |

**Output:** Returns operation result message.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /bulk is a fixed path segment (bulk operations)
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entries is a fixed path segment (bulk content entries)
const response = await fetch('/api/bulk/posts/entries', {
    // method is the HTTP method; this example always uses DELETE
    method: 'DELETE',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // uuids is a request body parameter: entry UUIDs array; each array item is a content entry UUID; replace the example value
    body: JSON.stringify({ uuids: ['entry-uuid-1', 'entry-uuid-2'] }),
});
const result = await response.json();
console.log(result);
```

---

### A.8 Content Version Management

#### A.8.1 List Versions

**Endpoint:** `GET /api/{collection}/{uuid}/versions`
**Ability:** `read`
**Description:** List all versions of a content entry.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |

**Output:** Returns versions list.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
// /versions is a fixed path segment (content versions sub-resource)
const response = await fetch('/api/posts/entry-uuid-1/versions', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.8.2 Get Version Detail

**Endpoint:** `GET /api/{collection}/{uuid}/versions/{version}`
**Ability:** `read`
**Description:** Get details of a specific version.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |
| `version` | Path | integer | Yes | Version number |

**Output:** Returns version detail with field data.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
// /versions is a fixed path segment (content versions sub-resource)
// /2 is a dynamic value (not a fixed path); it is the version number; replace the example value 2 with a real one
const response = await fetch('/api/posts/entry-uuid-1/versions/2', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.8.3 Revert to Version

**Endpoint:** `POST /api/{collection}/{uuid}/versions/{version}/revert`
**Ability:** `update`
**Description:** Revert content to a specific version.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |
| `version` | Path | integer | Yes | Target version number |

**Output:** Returns operation result message.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
// /versions is a fixed path segment (content versions sub-resource)
// /2 is a dynamic value (not a fixed path); it is the target version number; replace the example value 2 with a real one
// /revert is a fixed path segment (revert version)
const response = await fetch('/api/posts/entry-uuid-1/versions/2/revert', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.8.4 Update Version Label

**Endpoint:** `PATCH /api/{collection}/{uuid}/versions/{version}`
**Ability:** `update`
**Description:** Update the label of a version.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |
| `collection` | Path | string | Yes | Collection UUID or slug |
| `uuid` | Path | string | Yes | Entry UUID |
| `version` | Path | integer | Yes | Version number |
| `label` | Body | string | No | Version label |

**Output:** Returns operation result message.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /posts is a dynamic value (not a fixed path); it is the collection UUID or slug; replace the example value posts with a real one
// /entry-uuid-1 is a dynamic value (not a fixed path); it is the entry UUID; replace the example value entry-uuid-1 with a real one
// /versions is a fixed path segment (content versions sub-resource)
// /2 is a dynamic value (not a fixed path); it is the version number; replace the example value 2 with a real one
const response = await fetch('/api/posts/entry-uuid-1/versions/2', {
    // method is the HTTP method; this example always uses PATCH
    method: 'PATCH',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // label is a request body parameter: version label; replace the example value
    body: JSON.stringify({ label: 'Important Version' }),
});
const result = await response.json();
console.log(result);
```

---

### A.9 Webhook Management

#### A.9.1 List Webhooks

**Endpoint:** `GET /api/webhooks`
**Ability:** `admin`
**Description:** List all webhooks in the project.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (admin) |

**Output:** Returns webhooks list.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /webhooks is a fixed path segment (webhooks resource)
const response = await fetch('/api/webhooks', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.9.2 Create Webhook

**Endpoint:** `POST /api/webhooks`
**Ability:** `admin`
**Description:** Create a new webhook.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (admin) |
| `url` | Body | string | Yes | Callback URL |
| `events` | Body | array | Yes | Events to monitor |

**Output:** Returns new webhook object.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /webhooks is a fixed path segment (webhooks resource)
const response = await fetch('/api/webhooks', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // url is a request body parameter: callback URL; replace the example value
    // events is a request body parameter: events to monitor; each array item is an event name such as entry.created; replace the example value
    body: JSON.stringify({ url: 'https://mysite.com/webhook', events: ['entry.created'] }),
});
const result = await response.json();
console.log(result);
```

---

#### A.9.3 Get Webhook Detail

**Endpoint:** `GET /api/webhooks/{uuid}`
**Ability:** `admin`
**Description:** Get details of a single webhook.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (admin) |
| `uuid` | Path | string | Yes | Webhook UUID |

**Output:** Returns webhook object.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /webhooks is a fixed path segment (webhooks resource)
// /webhook-uuid-1 is a dynamic value (not a fixed path); it is the webhook UUID; replace the example value webhook-uuid-1 with a real one
const response = await fetch('/api/webhooks/webhook-uuid-1', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.9.4 Get Webhook Logs

**Endpoint:** `GET /api/webhooks/{uuid}/logs`
**Ability:** `admin`
**Description:** Get webhook trigger logs.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (admin) |
| `uuid` | Path | string | Yes | Webhook UUID |

**Output:** Returns logs list.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /webhooks is a fixed path segment (webhooks resource)
// /webhook-uuid-1 is a dynamic value (not a fixed path); it is the webhook UUID; replace the example value webhook-uuid-1 with a real one
// /logs is a fixed path segment (webhook logs)
const response = await fetch('/api/webhooks/webhook-uuid-1/logs', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.9.5 Update Webhook

**Endpoint:** `PUT /api/webhooks/{uuid}`
**Ability:** `admin`
**Description:** Update webhook configuration.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (admin) |
| `uuid` | Path | string | Yes | Webhook UUID |
| `url` | Body | string | No | Callback URL |
| `events` | Body | array | No | Events to monitor |

**Output:** Returns updated webhook object.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /webhooks is a fixed path segment (webhooks resource)
// /webhook-uuid-1 is a dynamic value (not a fixed path); it is the webhook UUID; replace the example value webhook-uuid-1 with a real one
const response = await fetch('/api/webhooks/webhook-uuid-1', {
    // method is the HTTP method; this example always uses PUT
    method: 'PUT',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    // url is a request body parameter: callback URL; replace the example value
    body: JSON.stringify({ url: 'https://mysite.com/new-webhook' }),
});
const result = await response.json();
console.log(result);
```

---

#### A.9.6 Delete Webhook

**Endpoint:** `DELETE /api/webhooks/{uuid}`
**Ability:** `admin`
**Description:** Delete a webhook.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token (admin) |
| `uuid` | Path | string | Yes | Webhook UUID |

**Output:** Returns operation result message.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /webhooks is a fixed path segment (webhooks resource)
// /webhook-uuid-1 is a dynamic value (not a fixed path); it is the webhook UUID; replace the example value webhook-uuid-1 with a real one
const response = await fetch('/api/webhooks/webhook-uuid-1', {
    // method is the HTTP method; this example always uses DELETE
    method: 'DELETE',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

### A.10 API Key Management

#### A.10.1 Introspect API Key

**Endpoint:** `POST /api/auth/api-keys/introspect`
**Ability:** `introspect`
**Description:** Validate an API key and retrieve its abilities.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | Bearer Token |

**Output:** Returns `data.valid` (boolean) and `data.abilities` (array).

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /auth is a fixed path segment (authentication resource)
// /api-keys is a fixed path segment (API keys resource)
// /introspect is a fixed path segment (introspect API key)
const response = await fetch('/api/auth/api-keys/introspect', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

## Method C: JWT Authentication (End Users) - Detailed Usage

### C.1 User Registration & Login

#### C.1.1 User Signup

**Endpoint:** `POST /api/auth/signup`
**Ability:** Public
**Description:** Register a new user.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `name` | Body | string | Yes | User name |
| `email` | Body | string | Yes | Email address |
| `password` | Body | string | Yes | Password |
| `password_confirmation` | Body | string | Yes | Confirm password |

**Output:** Returns user info, access token, and refresh token.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /auth is a fixed path segment (authentication resource)
// /signup is a fixed path segment (sign up)
const response = await fetch('/api/auth/signup', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // name is a request body parameter: user name; replace the example value
        name: 'John Doe',
        // email is a request body parameter: email address; replace the example value
        email: 'john@example.com',
        // password is a request body parameter: password; replace the example value
        password: 'password123',
        // password_confirmation is a request body parameter: confirm password; replace the example value
        password_confirmation: 'password123',
    }),
});
const result = await response.json();
console.log(result);
```

---

#### C.1.2 User Login

**Endpoint:** `POST /api/auth/login`
**Ability:** Public
**Description:** User login to get JWT.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `email` | Body | string | Yes | Email address |
| `password` | Body | string | Yes | Password |

**Output:** Returns user info, access token, and refresh token.

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /auth is a fixed path segment (authentication resource)
// /login is a fixed path segment (log in)
const response = await fetch('/api/auth/login', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // email is a request body parameter: email address; replace the example value
        email: 'john@example.com',
        // password is a request body parameter: password; replace the example value
        password: 'password123',
    }),
});
const result = await response.json();
console.log(result);
```

---

#### C.1.3 Refresh Token

**Endpoint:** `POST /api/auth/refresh`
**Ability:** Public
**Description:** Use refresh token to get a new access token.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `refresh_token` | Body | string | Yes | Refresh token |

**Output:** Same as C.1.1

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /auth is a fixed path segment (authentication resource)
// /refresh is a fixed path segment (refresh token)
const response = await fetch('/api/auth/refresh', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // refresh_token is a request body parameter: refresh token; replace the example value
        refresh_token: 'eyJ0eXAiOiJKV1QiLCJhbGc...',
    }),
});
const result = await response.json();
console.log(result);
```

---

#### C.1.4 Resend Verification Email

**Endpoint:** `POST /api/auth/verify-email/resend`  
**Ability:** Public  
**Description:** Resend email verification email.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `email` | Body | string | Yes | Email address |

**Output Parameters:**

| Field | Type | Description |
|-------|------|-------------|
| `message` | string | Operation result message |

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /auth is a fixed path segment (authentication resource)
// /verify-email is a fixed path segment (email verification)
// /resend is a fixed path segment (resend verification email)
const response = await fetch('/api/auth/verify-email/resend', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // email is a request body parameter: email address; replace the example value
        email: 'john@example.com',
    }),
});
const result = await response.json();
console.log(result);
```

---

#### C.1.5 Confirm Verification Email

**Endpoint:** `POST /api/auth/verify-email/confirm`  
**Ability:** Public  
**Description:** Confirm email verification.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `token` | Body | string | Yes | Verification token |

**Output Parameters:**

| Field | Type | Description |
|-------|------|-------------|
| `message` | string | Operation result message |

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /auth is a fixed path segment (authentication resource)
// /verify-email is a fixed path segment (email verification)
// /confirm is a fixed path segment (confirm verification)
const response = await fetch('/api/auth/verify-email/confirm', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // token is a request body parameter: verification token; replace the example value
        token: 'verification-token-xxx',
    }),
});
const result = await response.json();
console.log(result);
```

---

### C.2 Session Management

#### C.2.1 Get Current User Info

**Endpoint:** `GET /api/auth/me`  
**Ability:** JWT  
**Description:** Get current logged-in user info.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | `Bearer <jwt_access_token>` |

**Output Parameters:**

| Field | Type | Description |
|-------|------|-------------|
| `data.id` | integer | User ID |
| `data.name` | string | User name |
| `data.email` | string | Email address |

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /auth is a fixed path segment (authentication resource)
// /me is a fixed path segment (current user)
const response = await fetch('/api/auth/me', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGc...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### C.2.2 Logout Current Session

**Endpoint:** `POST /api/auth/logout`  
**Ability:** JWT  
**Description:** Logout current session.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | `Bearer <jwt_access_token>` |

**Output Parameters:**

| Field | Type | Description |
|-------|------|-------------|
| `message` | string | Operation result message |

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /auth is a fixed path segment (authentication resource)
// /logout is a fixed path segment (log out current session)
const response = await fetch('/api/auth/logout', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGc...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### C.2.3 Logout All Sessions

**Endpoint:** `POST /api/auth/logout-all`  
**Ability:** JWT  
**Description:** Logout all sessions.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | `Bearer <jwt_access_token>` |

**Output Parameters:**

| Field | Type | Description |
|-------|------|-------------|
| `message` | string | Operation result message |

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /auth is a fixed path segment (authentication resource)
// /logout-all is a fixed path segment (log out all sessions)
const response = await fetch('/api/auth/logout-all', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGc...',
    },
});
const result = await response.json();
console.log(result);
```

---

### C.3 API Key Management

#### C.3.1 List API Keys

**Endpoint:** `GET /api/auth/api-keys`  
**Ability:** JWT  
**Description:** List all API keys for the current user.

**Input Parameters:**

| Parameter | Location | Type | Required | Description |
|-----------|----------|------|----------|-------------|
| `project-id` | Header | string | Yes | Project UUID |
| `Authorization` | Header | string | Yes | `Bearer <jwt_access_token>` |

**Output Parameters:**

| Field | Type | Description |
|-------|------|-------------|
| `data` | array | API keys list |
| `data[].id` | integer | Key ID |
| `data[].name` | string | Key name |
| `data[].abilities` | array | Abilities list |

**Example:**

```javascript
// /api is the fixed API prefix shared by every endpoint
// /auth is a fixed path segment (authentication resource)
// /api-keys is a fixed path segment (API keys resource)
const response = await fetch('/api/auth/api-keys', {
    // method is the HTTP method; this example always uses GET
    method: 'GET',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
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
// /api is the fixed API prefix shared by every endpoint
// /auth is a fixed path segment (authentication resource)
// /api-keys is a fixed path segment (API keys resource)
const response = await fetch('/api/auth/api-keys', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGc...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // name is a request body parameter: key name; replace the example value
        name: 'My API Key',
        // abilities is a request body parameter: key abilities; each array item is an ability such as read or create; replace the example value
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
// /api is the fixed API prefix shared by every endpoint
// /auth is a fixed path segment (authentication resource)
// /api-keys is a fixed path segment (API keys resource)
// /1 is a dynamic value (not a fixed path); it is the aPI key ID; replace the example value 1 with a real one
// /revoke is a fixed path segment (revoke key)
const response = await fetch('/api/auth/api-keys/1/revoke', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
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
// /api is the fixed API prefix shared by every endpoint
// /auth is a fixed path segment (authentication resource)
// /change-password is a fixed path segment (change password)
const response = await fetch('/api/auth/change-password', {
    // method is the HTTP method; this example always uses POST
    method: 'POST',
    headers: {
        // project-id is a fixed header name; the value must be the project UUID. Replace the example UUID
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        // Authorization is a fixed header name; the value must be an access token. Replace the example token after Bearer
        'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGc...',
        // Content-Type is a fixed header name; application/json means the request body is JSON
        'Content-Type': 'application/json',
    },
    // body is the request body: JSON.stringify serializes the object below into JSON; the request needs Content-Type: application/json
    body: JSON.stringify({
        // current_password is a request body parameter: current password; replace the example value
        current_password: 'oldpass',
        // password is a request body parameter: new password; replace the example value
        password: 'newpass123',
        // password_confirmation is a request body parameter: confirm new password; replace the example value
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
// src is the public file URL: /uploads is the fixed prefix and the rest is a dynamic path (uuid segments are placeholders and image.jpg is an example filename)
<img src="/uploads/projects/uuid/assets/uuid/image.jpg" />

// Fetch via JavaScript
// /uploads is a fixed path segment (public upload access path)
// /projects/uuid/assets/uuid/image.jpg is a dynamic value (not a fixed path); it is the file storage path. The uuid segments are placeholders and image.jpg is an example filename
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
