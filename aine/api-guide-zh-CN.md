# AineNext Headless CMS — 完整 API 参考文档

## 概述

AineNext 是一个 Headless CMS，通过 RESTful API 对外提供内容管理能力。所有 API 端点统一前缀 `/api`。

**Base URL：** `https://your-cms-domain.com/api`

---

## 方式 A：Access Token（服务端调用）

**适用场景：** Node.js 后端、CI/CD 脚本、服务端渲染等
**可用权限：** read / create / update / delete / admin
**认证方式：** 在请求头中携带 `Authorization: Bearer <token>` 和 `project-id: <uuid>`

### 接口总表

#### 项目信息
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| GET | [/api](#a11-获取项目信息) | read | 获取项目信息 |

#### 项目语言管理
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| POST | [/api/project/locales](#a21-添加语言) | admin | 添加语言 |
| PUT | [/api/project/locales/default](#a22-设置默认语言) | admin | 设置默认语言 |
| DELETE | [/api/project/locales/{locale}](#a23-删除语言) | admin | 删除语言 |

#### 资产管理
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| GET | [/api/files](#a31-列出资产) | read | 列出资产 |
| GET | [/api/files/{identifier}](#a32-按-id-获取资产) | read | 按 ID 获取资产 |
| GET | [/api/files/name/{filename}](#a33-按文件名获取资产) | read | 按文件名获取 |
| POST | [/api/files](#a34-上传单个资产) | create | 上传单个资产 |
| POST | [/api/files/bulk/upload](#a35-批量上传资产) | create | 批量上传 |
| PATCH | [/api/files/bulk/metadata](#a36-批量更新资产元数据) | update | 批量更新元数据 |
| POST | [/api/files/upload-url](#a37-发起直接上传预签名-url) | create | 发起直接上传 |
| POST | [/api/files/upload-finalize](#a38-完成直接上传) | create | 完成直接上传 |
| POST | [/api/files/multipart/initiate](#a39-发起多部分上传) | create | 发起多部分上传 |
| POST | [/api/files/multipart/part-url](#a310-获取多部分上传分片-url) | create | 获取分片 URL |
| POST | [/api/files/multipart/complete](#a311-完成多部分上传) | create | 完成多部分上传 |
| DELETE | [/api/files/{identifier}](#a312-删除资产) | delete | 删除资产 |

#### 集合管理
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| GET | [/api/collections](#a41-列出所有集合) | read | 列出集合 |
| GET | [/api/collections/{collection}](#a42-获取集合详情) | read | 获取集合详情 |
| POST | [/api/collections](#a43-创建集合) | admin | 创建集合 |
| PUT | [/api/collections/{collection}](#a44-更新集合) | admin | 更新集合 |
| DELETE | [/api/collections/{collection}](#a45-删除集合) | admin | 删除集合 |
| POST | [/api/collections/reorder](#a46-重排集合顺序) | admin | 重排集合 |

#### 字段管理
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| POST | [/api/collections/{collection}/fields](#a51-创建字段) | admin | 创建字段 |
| PUT | [/api/collections/{collection}/fields/{field}](#a52-更新字段) | admin | 更新字段 |
| DELETE | [/api/collections/{collection}/fields/{field}](#a53-删除字段) | admin | 删除字段 |
| POST | [/api/collections/{collection}/fields/reorder](#a54-重排字段顺序) | admin | 重排字段 |

#### 内容条目管理
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| GET | [/api/{collection}](#a61-列出内容条目) | read | 列出内容 |
| GET | [/api/{collection}/{uuid}](#a62-获取单条内容) | read | 获取单条内容 |
| POST | [/api/{collection}](#a63-创建内容条目) | create | 创建内容 |
| PUT/PATCH | [/api/{collection}/{uuid}](#a64-更新内容条目) | update | 更新内容 |
| DELETE | [/api/{collection}/{uuid}](#a65-删除内容条目) | delete | 删除内容 |
| POST | [/api/{collection}/{uuid}/publish](#a66-发布内容) | update | 发布内容 |
| POST | [/api/{collection}/{uuid}/unpublish](#a67-取消发布) | update | 取消发布 |
| POST | [/api/{collection}/{uuid}/discard-draft](#a68-丢弃草稿) | update | 丢弃草稿 |
| POST | [/api/{collection}/{uuid}/link-translation](#a69-关联翻译) | update | 关联翻译 |

#### 批量操作
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| POST | [/api/bulk/{collection}/entries](#a71-批量创建内容) | create | 批量创建 |
| PATCH | [/api/bulk/{collection}/entries](#a72-批量更新内容) | update | 批量更新 |
| DELETE | [/api/bulk/{collection}/entries](#a73-批量删除内容) | delete | 批量删除 |

#### 内容版本管理
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| GET | [/api/{collection}/{uuid}/versions](#a81-列出版本) | read | 列出版本 |
| GET | [/api/{collection}/{uuid}/versions/{version}](#a82-获取版本详情) | read | 获取版本详情 |
| POST | [/api/{collection}/{uuid}/versions/{version}/revert](#a83-回滚到指定版本) | update | 回滚版本 |
| PATCH | [/api/{collection}/{uuid}/versions/{version}](#a84-更新版本标签) | update | 更新版本标签 |

#### Webhook 管理
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| GET | [/api/webhooks](#a91-列出-webhook) | admin | 列出 Webhook |
| POST | [/api/webhooks](#a92-创建-webhook) | admin | 创建 Webhook |
| GET | [/api/webhooks/{uuid}](#a93-获取-webhook-详情) | admin | 获取详情 |
| GET | [/api/webhooks/{uuid}/logs](#a94-获取-webhook-日志) | admin | 获取日志 |
| PUT | [/api/webhooks/{uuid}](#a95-更新-webhook) | admin | 更新 Webhook |
| DELETE | [/api/webhooks/{uuid}](#a96-删除-webhook) | admin | 删除 Webhook |

#### API 密钥管理
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| POST | [/api/auth/api-keys/introspect](#a101-内省-api-密钥) | introspect | 内省 API 密钥 |

---

## 方式 B：域名白名单（纯前端调用）

### 接口总表

#### 项目信息
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| GET | [/api](#b11-获取项目信息) | read | 获取项目信息 |

#### 资产管理
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| GET | [/api/files](#b2-资产管理-12-个接口) | read | 列出资产 |
| GET | [/api/files/{identifier}](#b2-资产管理-12-个接口) | read | 按 ID 获取 |
| GET | [/api/files/name/{filename}](#b2-资产管理-12-个接口) | read | 按文件名获取 |
| POST | [/api/files](#b2-资产管理-12-个接口) | create | 上传资产 |
| POST | [/api/files/bulk/upload](#b2-资产管理-12-个接口) | create | 批量上传 |
| PATCH | [/api/files/bulk/metadata](#b2-资产管理-12-个接口) | update | 批量更新元数据 |
| POST | [/api/files/upload-url](#b2-资产管理-12-个接口) | create | 发起直接上传 |
| POST | [/api/files/upload-finalize](#b2-资产管理-12-个接口) | create | 完成直接上传 |
| POST | [/api/files/multipart/initiate](#b2-资产管理-12-个接口) | create | 发起多部分上传 |
| POST | [/api/files/multipart/part-url](#b2-资产管理-12-个接口) | create | 获取分片 URL |
| POST | [/api/files/multipart/complete](#b2-资产管理-12-个接口) | create | 完成多部分上传 |
| DELETE | /api/files/{identifier} | - | **不可用**（需 Token） |

#### 集合管理
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| GET | [/api/collections](#b31-列出所有集合) | read | 列出集合 |
| GET | [/api/collections/{collection}](#b32-获取集合详情) | read | 获取集合详情 |

#### 内容条目管理
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| GET | [/api/{collection}](#b41-列出内容条目) | read | 列出内容 |
| GET | [/api/{collection}/{uuid}](#b42-获取单条内容) | read | 获取单条内容 |
| POST | [/api/{collection}](#b43-创建内容条目) | create | 创建内容 |
| PUT/PATCH | [/api/{collection}/{uuid}](#b44-更新内容条目) | update | 更新内容 |
| POST | [/api/{collection}/{uuid}/publish](#b45-发布内容) | update | 发布内容 |
| POST | [/api/{collection}/{uuid}/unpublish](#b46-取消发布) | update | 取消发布 |

#### 批量操作
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| POST | [/api/bulk/{collection}/entries](#b51-批量创建内容) | create | 批量创建 |
| PATCH | [/api/bulk/{collection}/entries](#b52-批量更新内容) | update | 批量更新 |

#### 内容版本管理
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| GET | [/api/{collection}/{uuid}/versions](#b61-列出版本) | read | 列出版本 |
| GET | [/api/{collection}/{uuid}/versions/{version}](#b62-获取版本详情) | read | 获取版本详情 |
| POST | [/api/{collection}/{uuid}/versions/{version}/revert](#b63-回滚到指定版本) | update | 回滚版本 |
| PATCH | [/api/{collection}/{uuid}/versions/{version}](#b64-更新版本标签) | update | 更新版本标签 |

---

### 方式 C：JWT 认证（终端用户）

#### 用户注册登录
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| POST | [/api/auth/signup](#c11-用户注册) | 公开 | 用户注册 |
| POST | [/api/auth/login](#c12-用户登录) | 公开 | 用户登录 |
| POST | [/api/auth/refresh](#c13-刷新令牌) | 公开 | 刷新令牌 |
| POST | [/api/auth/verify-email/resend](#c14-重发验证邮件) | 公开 | 重发验证邮件 |
| POST | [/api/auth/verify-email/confirm](#c15-确认验证邮件) | 公开 | 确认验证邮件 |

#### 会话管理
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| GET | [/api/auth/me](#c21-获取当前用户信息) | JWT | 获取当前用户 |
| POST | [/api/auth/logout](#c22-登出当前会话) | JWT | 登出当前会话 |
| POST | [/api/auth/logout-all](#c23-登出所有会话) | JWT | 登出所有会话 |

#### API 密钥管理
| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| GET | [/api/auth/api-keys](#c31-列出-api-密钥) | JWT | 列出 API 密钥 |
| POST | [/api/auth/api-keys](#c32-创建-api-密钥) | JWT | 创建 API 密钥 |
| POST | [/api/auth/api-keys/{keyId}/revoke](#c33-撤销-api-密钥) | JWT | 撤销 API 密钥 |
| POST | [/api/auth/change-password](#c34-修改密码) | JWT | 修改密码 |

---

### 文件流服务（公开访问）

| 方法 | 路径 | 权限 | 说明 |
|------|------|------|------|
| GET | [/uploads/{path}](#文件流服务公开访问) | 公开 | 访问上传的文件 |

---

<br />

## 方式 A：Access Token（服务端调用） - 详细使用

### A.1 项目信息

#### A.1.1 获取项目信息

**接口：** `GET /api`  
**权限：** `read`  
**作用：** 获取项目基本信息，可选加载集合和字段定义。

**入参：**

| 参数 | 位置 | 类型 | 必填 | 说明 |
|------|------|------|------|------|
| `project-id` | Header | string | 是 | 项目 UUID |
| `Authorization` | Header | string | 是 | Bearer Token |
| `with` | Query | string | 否 | 关联加载，可选值：`collections`, `fields`，多个用逗号分隔 |

**出参：**

| 字段 | 类型 | 说明 |
|------|------|------|
| `data.uuid` | string | 项目 UUID |
| `data.name` | string | 项目名称 |
| `data.description` | string | 项目描述 |
| `data.preview_url` | string | 预览地址 |
| `data.default_locale` | string | 默认语言代码 |
| `data.locales` | string[] | 支持的语言列表 |
| `data.collections` | array | 集合列表（需 `with=collections`） |
| `data.collections[].uuid` | string | 集合 UUID |
| `data.collections[].name` | string | 集合名称 |
| `data.collections[].slug` | string | 集合 slug |
| `data.collections[].is_singleton` | boolean | 是否为单例集合 |
| `data.collections[].fields` | array | 字段列表（需 `with=fields`） |

**调用示例：**

```javascript
const response = await fetch('/api/?with=collections,fields', {
    method: 'GET',
    headers: {
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

**响应示例：**

```json
{
    "data": {
        "uuid": "550e8400-e29b-41d4-a716-446655440000",
        "name": "My Blog",
        "description": "A personal blog",
        "preview_url": "https://mysite.com",
        "default_locale": "en",
        "locales": ["en", "zh"],
        "collections": [
            {
                "uuid": "col-uuid-1",
                "name": "Posts",
                "slug": "posts",
                "is_singleton": false,
                "fields": [
                    {
                        "uuid": "field-uuid-1",
                        "name": "title",
                        "label": "Title",
                        "type": "text",
                        "required": true
                    }
                ]
            }
        ]
    }
}
```

---

### A.2 项目语言管理

#### A.2.1 添加语言

**接口：** `POST /api/project/locales`  
**权限：** `admin`  
**作用：** 为项目添加新的语言支持。

**入参：**

| 参数 | 位置 | 类型 | 必填 | 说明 |
|------|------|------|------|------|
| `project-id` | Header | string | 是 | 项目 UUID |
| `Authorization` | Header | string | 是 | Bearer Token（需 admin 权限） |
| `locale` | Body | string | 是 | 语言代码，如 `fr`, `ja`, `zh` |

**出参：**

| 字段 | 类型 | 说明 |
|------|------|------|
| `uuid` | string | 项目 UUID |
| `name` | string | 项目名称 |
| `default_locale` | string | 默认语言代码 |
| `locales` | string[] | 更新后的语言列表 |

**调用示例：**

```javascript
const response = await fetch('/api/project/locales', {
    method: 'POST',
    headers: {
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({ locale: 'fr' }),
});
const result = await response.json();
console.log(result);
```

**响应示例：**

```json
{
    "uuid": "550e8400-...",
    "name": "My Blog",
    "default_locale": "en",
    "locales": ["en", "zh", "fr"]
}
```

---

#### A.2.2 设置默认语言

**接口：** `PUT /api/project/locales/default`  
**权限：** `admin`  
**作用：** 设置项目的默认语言。

**入参：**

| 参数 | 位置 | 类型 | 必填 | 说明 |
|------|------|------|------|------|
| `project-id` | Header | string | 是 | 项目 UUID |
| `Authorization` | Header | string | 是 | Bearer Token（需 admin 权限） |
| `locale` | Body | string | 是 | 语言代码，必须在项目已配置的语言列表中 |

**出参：**

| 字段 | 类型 | 说明 |
|------|------|------|
| `uuid` | string | 项目 UUID |
| `default_locale` | string | 更新后的默认语言 |
| `locales` | string[] | 语言列表 |

**调用示例：**

```javascript
const response = await fetch('/api/project/locales/default', {
    method: 'PUT',
    headers: {
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({ locale: 'zh' }),
});
const result = await response.json();
console.log(result);
```

---

#### A.2.3 删除语言

**接口：** `DELETE /api/project/locales/{locale}`  
**权限：** `admin`  
**作用：** 从项目中删除指定语言。

**入参：**

| 参数 | 位置 | 类型 | 必填 | 说明 |
|------|------|------|------|------|
| `project-id` | Header | string | 是 | 项目 UUID |
| `Authorization` | Header | string | 是 | Bearer Token（需 admin 权限） |
| `locale` | Path | string | 是 | 要删除的语言代码 |

**出参：**

| 字段 | 类型 | 说明 |
|------|------|------|
| `uuid` | string | 项目 UUID |
| `default_locale` | string | 默认语言 |
| `locales` | string[] | 更新后的语言列表 |

**调用示例：**

```javascript
const response = await fetch('/api/project/locales/fr', {
    method: 'DELETE',
    headers: {
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

### A.3 资产管理

#### A.3.1 列出资产

**接口：** `GET /api/files`  
**权限：** `read`  
**作用：** 列出项目下的所有资产文件，支持搜索、类型过滤和分页。

**入参：**

| 参数 | 位置 | 类型 | 必填 | 说明 |
|------|------|------|------|------|
| `project-id` | Header | string | 是 | 项目 UUID |
| `Authorization` | Header | string | 是 | Bearer Token |
| `search` | Query | string | 否 | 搜索关键词，匹配文件名 |
| `type` | Query | string | 否 | 文件类型过滤，如 `image`, `video`, `document`, `audio` |
| `page` | Query | integer | 否 | 页码，默认 1 |
| `per_page` | Query | integer | 否 | 每页数量，默认 20 |

**出参：**

| 字段 | 类型 | 说明 |
|------|------|------|
| `data` | array | 资产列表 |
| `data[].uuid` | string | 资产 UUID |
| `data[].filename` | string | 文件名 |
| `data[].mime_type` | string | MIME 类型 |
| `data[].size` | string | 格式化后的文件大小 |
| `data[].url` | string | 文件访问 URL |
| `data[].original_url` | string | 原始文件 URL |
| `data[].thumbnail_url` | string | 缩略图 URL（图片类型） |
| `data[].pending_image_processing` | boolean | 是否正在处理图片 |
| `data[].metadata` | object | 元数据（需额外请求） |
| `data[].metadata.width` | integer | 图片宽度（像素） |
| `data[].metadata.height` | integer | 图片高度（像素） |
| `data[].metadata.alt_text` | string | 替代文本 |
| `data[].metadata.title` | string | 标题 |
| `data[].metadata.caption` | string | 说明文字 |
| `data[].metadata.description` | string | 描述 |
| `data[].metadata.author` | string | 作者 |
| `data[].metadata.copyright` | string | 版权信息 |
| `meta` | object | 分页信息 |
| `meta.current_page` | integer | 当前页码 |
| `meta.last_page` | integer | 最后一页 |
| `meta.per_page` | integer | 每页数量 |
| `meta.total` | integer | 总记录数 |

**调用示例：**

```javascript
const response = await fetch('/api/files?type=image&search=cover&per_page=10', {
    method: 'GET',
    headers: {
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

**响应示例：**

```json
{
    "data": [
        {
            "uuid": "asset-uuid-1",
            "filename": "cover-photo.jpg",
            "mime_type": "image/jpeg",
            "size": "2.5 MB",
            "url": "/uploads/projects/uuid/assets/uuid/cover-photo.jpg",
            "original_url": "/uploads/.../cover-photo.jpg",
            "thumbnail_url": "/uploads/.../thumb.jpg",
            "pending_image_processing": false,
            "metadata": {
                "width": 1920,
                "height": 1080,
                "alt_text": "Cover image"
            }
        }
    ],
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 10,
        "total": 50
    }
}
```

---

#### A.3.2 按 ID 获取资产

**接口：** `GET /api/files/{identifier}`  
**权限：** `read`  
**作用：** 通过 UUID 或 ID 获取单个资产的详细信息。

**入参：**

| 参数 | 位置 | 类型 | 必填 | 说明 |
|------|------|------|------|------|
| `project-id` | Header | string | 是 | 项目 UUID |
| `Authorization` | Header | string | 是 | Bearer Token |
| `identifier` | Path | string | 是 | 资产 UUID 或数字 ID |

**出参：** 同 A.3.1 中的 `data[]` 单个对象

**调用示例：**

```javascript
const response = await fetch('/api/files/asset-uuid-1', {
    method: 'GET',
    headers: {
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.3.3 按文件名获取资产

**接口：** `GET /api/files/name/{filename}`  
**权限：** `read`  
**作用：** 通过原始文件名获取资产信息。

**入参：**

| 参数 | 位置 | 类型 | 必填 | 说明 |
|------|------|------|------|------|
| `project-id` | Header | string | 是 | 项目 UUID |
| `Authorization` | Header | string | 是 | Bearer Token |
| `filename` | Path | string | 是 | 原始文件名（含扩展名） |

**出参：** 同 A.3.2

**调用示例：**

```javascript
const response = await fetch('/api/files/name/cover-photo.jpg', {
    method: 'GET',
    headers: {
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
});
const result = await response.json();
console.log(result);
```

---

#### A.3.4 上传单个资产

**接口：** `POST /api/files`  
**权限：** `create`  
**作用：** 上传单个文件，使用 `multipart/form-data` 格式。

**入参：**

| 参数 | 位置 | 类型 | 必填 | 说明 |
|------|------|------|------|------|
| `project-id` | Header | string | 是 | 项目 UUID |
| `Authorization` | Header | string | 是 | Bearer Token |
| `file` | Body (FormData) | File | 是 | 上传的文件 |

**出参：**

| 字段 | 类型 | 说明 |
|------|------|------|
| `data.uuid` | string | 新资产 UUID |
| `data.filename` | string | 文件名 |
| `data.mime_type` | string | MIME 类型 |
| `data.size` | string | 文件大小 |
| `data.url` | string | 访问 URL |

**调用示例：**

```javascript
const formData = new FormData();
formData.append('file', fileInput.files[0]);

const response = await fetch('/api/files', {
    method: 'POST',
    headers: {
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
    body: formData,
});
const result = await response.json();
console.log(result);
```

---

#### A.3.5 批量上传资产

**接口：** `POST /api/files/bulk/upload`  
**权限：** `create`  
**作用：** 批量上传多个文件，最多 20 个，每个最大 100MB。

**入参：**

| 参数 | 位置 | 类型 | 必填 | 说明 |
|------|------|------|------|------|
| `project-id` | Header | string | 是 | 项目 UUID |
| `Authorization` | Header | string | 是 | Bearer Token |
| `files[]` | Body (FormData) | File[] | 是 | 文件数组（1-20 个） |

**出参：**

| 字段 | 类型 | 说明 |
|------|------|------|
| `data` | array | 上传成功的资产列表 |
| `data[].uuid` | string | 资产 UUID |
| `data[].filename` | string | 文件名 |
| `data[].url` | string | 访问 URL |

**调用示例：**

```javascript
const formData = new FormData();
formData.append('files[]', file1);
formData.append('files[]', file2);

const response = await fetch('/api/files/bulk/upload', {
    method: 'POST',
    headers: {
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        'Authorization': 'Bearer 1|abcDefGhIjK...',
    },
    body: formData,
});
const result = await response.json();
console.log(result);
```

---

#### A.3.6 批量更新资产元数据

**接口：** `PATCH /api/files/bulk/metadata`  
**权限：** `update`  
**作用：** 批量更新多个资产的元数据信息，最多 50 条。

**入参：**

| 参数 | 位置 | 类型 | 必填 | 说明 |
|------|------|------|------|------|
| `project-id` | Header | string | 是 | 项目 UUID |
| `Authorization` | Header | string | 是 | Bearer Token |
| `items` | Body | array | 是 | 更新项数组（1-50 个） |
| `items[].uuid` | Body | string | 是 | 资产 UUID |
| `items[].alt_text` | Body | string | 否 | 替代文本 |
| `items[].title` | Body | string | 否 | 标题 |
| `items[].caption` | Body | string | 否 | 说明文字 |
| `items[].description` | Body | string | 否 | 描述 |
| `items[].author` | Body | string | 否 | 作者 |
| `items[].copyright` | Body | string | 否 | 版权信息 |

**出参：**

| 字段 | 类型 | 说明 |
|------|------|------|
| `message` | string | 操作结果消息 |

**调用示例：**

```javascript
const response = await fetch('/api/files/bulk/metadata', {
    method: 'PATCH',
    headers: {
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        items: [
            { uuid: 'asset-uuid-1', alt_text: 'Updated', title: 'Cover' },
            { uuid: 'asset-uuid-2', author: 'Photographer' },
        ],
    }),
});
const result = await response.json();
console.log(result);
```

---

#### A.3.7 发起直接上传（预签名 URL）

**接口：** `POST /api/files/upload-url`  
**权限：** `create`  
**作用：** 获取预签名 URL，用于客户端直传大文件到存储服务（如 S3），绕过服务器中转。

**入参：**

| 参数 | 位置 | 类型 | 必填 | 说明 |
|------|------|------|------|------|
| `project-id` | Header | string | 是 | 项目 UUID |
| `Authorization` | Header | string | 是 | Bearer Token |
| `original_filename` | Body | string | 是 | 原始文件名（含扩展名） |
| `client_mime_type` | Body | string | 否 | MIME 类型 |
| `byte_size` | Body | integer | 是 | 文件大小（字节） |

**出参：**

| 字段 | 类型 | 说明 |
|------|------|------|
| `upload_url` | string | 预签名上传 URL |
| `asset_uuid` | string | 预分配的资产 UUID |

**调用示例：**

```javascript
const response = await fetch('/api/files/upload-url', {
    method: 'POST',
    headers: {
        'project-id': '550e8400-e29b-41d4-a716-446655440000',
        'Authorization': 'Bearer 1|abcDefGhIjK...',
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        original_filename: 'large-video.mp4',
        client_mime_type: 'video/mp4',
        byte_size: 52428800,
    }),
});
const result = await response.json();
console.log(result);
```

---

#### A.3.8 完成直接上传

**接口：** `POST /api/files/upload-finalize`  
**权限：** `create`  
**作用：** 客户端完成预签名 URL 上传后，通知服务器确认上传完成。

**入参：**

| 参数 | 位置 | 类型 | 必填 | 说明 |
|------|------|------|------|------|
| `project-id` | Header | string | 是 | 项目 UUID |
| `Authorization` | Header | string | 是 | Bearer Tok
... [ACP display truncated; original 66193 bytes] ...
改密码

**接口：** `POST /api/auth/change-password`  
**权限：** JWT  
**作用：** 修改当前用户密码。

**入参：**

| 参数 | 位置 | 类型 | 必填 | 说明 |
|------|------|------|------|------|
| `project-id` | Header | string | 是 | 项目 UUID |
| `Authorization` | Header | string | 是 | `Bearer <jwt_access_token>` |
| `current_password` | Body | string | 是 | 当前密码 |
| `password` | Body | string | 是 | 新密码 |
| `password_confirmation` | Body | string | 是 | 确认新密码 |

**出参：**

| 字段 | 类型 | 说明 |
|------|------|------|
| `message` | string | 操作结果消息 |

**调用示例：**

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

## 文件流服务（公开访问）

#### 访问上传的文件

**接口：** `GET /uploads/{path}`  
**权限：** 公开（无需任何认证）  
**作用：** 直接访问已上传的文件资源。速率限制 120 次/分钟。

**入参：**

| 参数 | 位置 | 类型 | 必填 | 说明 |
|------|------|------|------|------|
| `path` | Path | string | 是 | 文件路径 |

**出参：** 直接返回文件二进制内容。

**调用示例：**

```javascript
// 直接在 HTML 中使用
<img src="/uploads/projects/uuid/assets/uuid/image.jpg" />

// 通过 fetch 获取
const blob = await fetch('/uploads/projects/uuid/assets/uuid/image.jpg')
    .then(r => r.blob());
```

---

## 域名白名单配置指南

### 配置步骤

1. 进入后台 → **Settings → API Access**
2. 在 **Allowed Domain Whitelist** 区域点击 **Add Domain**
3. 输入前端域名（如 `https://mysite.com`）
4. 选择权限（默认 `read`，可按需勾选 `create`、`update`）
5. 点击 **Add**

### 配置建议

| 场景 | 域名 | 权限 |
|------|------|------|
| 生产前端 | `https://mysite.com` | read |
| 预发布环境 | `https://staging.mysite.com` | read, create |
| 本地开发 | `http://localhost:3000` | read, create, update |
| 管理后台 | 使用 Token | admin（全部） |

### 安全说明

- `Origin` 头由浏览器内核强制设置，JavaScript 无法伪造
- 精确匹配：`https://evil.mysite.com` **不会**匹配 `https://mysite.com`
- `delete` 和 `admin` 权限**不可**通过白名单授权，始终需要 Token
- 白名单域名同样受速率限制保护

---

## 权限速查表

| 操作 | Token | 域名白名单 |
|------|:-----:|:---------:|
| 获取项目信息 | read | read |
| 列出/获取集合 | read | read |
| 列出/获取内容 | read | read |
| 获取资产 | read | read |
| 列出版本 | read | read |
| 创建内容 | create | create |
| 批量创建 | create | create |
| 更新内容 | update | update |
| 发布/取消发布 | update | update |
| 版本回滚 | update | update |
| 上传资产 | create | create |
| 删除内容 | delete | -- |
| 删除资产 | delete | -- |
| 管理集合/字段 | admin | -- |
| 管理 Webhook | admin | -- |
| 管理语言 | admin | -- |

---