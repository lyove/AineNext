<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\ContentEntry;
use App\Models\ContentFieldValue;
use App\Models\Field;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Demo preset projects, mirroring the projects the original Aine
 * installer seeded (CMS, Business Directory, Note, Navigation),
 * including the full collection/field schemas of those templates.
 */
class DemoProjectsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@admin.com')->first();
        $adminId = $admin?->id ?? 1;

        $this->seedCmsProject($adminId);
        $this->seedDirectoryProject($adminId);
        $this->seedNoteProject($adminId);
        $this->seedNavigationProject($adminId);
    }

    /* ------------------------------------------------------------------ */
    /* Project builders                                                    */
    /* ------------------------------------------------------------------ */

    protected function createProject(array $data, int $adminId): Project
    {
        $existing = Project::where('name', $data['name'])->first();

        if ($existing) {
            $this->removeProject($existing);
        }

        $project = Project::create($data);

        $project->members()->attach($adminId);

        return $project;
    }

    protected function removeProject(Project $project): void
    {
        ContentFieldValue::where('project_id', $project->id)->delete();
        ContentEntry::where('project_id', $project->id)->forceDelete();
        Field::where('project_id', $project->id)->delete();
        Collection::where('project_id', $project->id)->delete();
        $project->members()->detach();
        $project->forceDelete();
    }

    protected function createCollection(Project $project, string $name, string $slug, int $order): Collection
    {
        return Collection::create([
            'project_id' => $project->id,
            'name' => $name,
            'slug' => $slug,
            'order' => $order,
            'is_singleton' => false,
        ]);
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function createField(Project $project, Collection $collection, string $type, string $label, string $name, int $order, array $options = []): Field
    {
        return Field::create([
            'project_id' => $project->id,
            'collection_id' => $collection->id,
            'type' => $type,
            'label' => $label,
            'name' => $name,
            'order' => $order,
            'options' => $options,
            'validations' => [],
        ]);
    }

    /**
     * @param  array<string, mixed>  $values  field_name => value
     */
    protected function createEntry(Project $project, Collection $collection, array $values, int $adminId, string $locale = 'en', bool $published = true): ContentEntry
    {
        $entry = ContentEntry::create([
            'project_id' => $project->id,
            'collection_id' => $collection->id,
            'locale' => $locale,
            'state' => $published ? 'published' : 'draft',
            'created_by' => $adminId,
            'updated_by' => $adminId,
            'published_at' => $published ? now()->subMinutes(random_int(60, 60 * 24 * 7)) : null,
        ]);

        foreach ($values as $fieldName => $value) {
            $field = Field::where('collection_id', $collection->id)->where('name', $fieldName)->first();

            if (! $field || $value === null || $value === '') {
                continue;
            }

            $fieldValue = new ContentFieldValue([
                'project_id' => $project->id,
                'collection_id' => $collection->id,
                'content_entry_id' => $entry->id,
                'field_id' => $field->id,
                'field_type' => $field->type,
            ]);

            $this->applyValueByType($fieldValue, $field->type, $value);

            $fieldValue->save();
        }

        return $entry;
    }

    protected function applyValueByType(ContentFieldValue $fieldValue, string $type, mixed $value): void
    {
        switch ($type) {
            case 'number':
                $fieldValue->number_value = $value;
                break;
            case 'boolean':
                $fieldValue->boolean_value = (bool) $value;
                break;
            case 'date':
                $fieldValue->date_value = $value;
                break;
            case 'enumeration':
            case 'relation':
            case 'json':
                $fieldValue->json_value = is_array($value) ? $value : [$value];
                break;
            case 'richtext':
                // HTML snapshot; the editor falls back to this when no JSON state exists.
                $fieldValue->text_value = $value;
                break;
            default:
                $fieldValue->text_value = (string) $value;
                break;
        }
    }

    /**
     * Point a relation field at an existing entry id.
     */
    protected function relate(ContentEntry $entry, Field $field, int $relatedId, int $sortOrder = 0): void
    {
        $fieldValue = ContentFieldValue::where('content_entry_id', $entry->id)
            ->where('field_id', $field->id)
            ->first();

        if (! $fieldValue) {
            $fieldValue = new ContentFieldValue([
                'project_id' => $entry->project_id,
                'collection_id' => $entry->collection_id,
                'content_entry_id' => $entry->id,
                'field_id' => $field->id,
                'field_type' => $field->type,
            ]);
            $fieldValue->save();
        }

        $fieldValue->json_value = [$relatedId];
        $fieldValue->save();

        $fieldValue->valueRelations()->create([
            'related_id' => $relatedId,
            'related_type' => ContentEntry::class,
            'sort_order' => $sortOrder,
        ]);
    }

    protected function fieldByName(Collection $collection, string $name): ?Field
    {
        return Field::where('collection_id', $collection->id)->where('name', $name)->first();
    }

    protected function firstEntry(Collection $collection): ?ContentEntry
    {
        return ContentEntry::where('collection_id', $collection->id)->first();
    }

    /* ------------------------------------------------------------------ */
    /* CMS                                                                 */
    /* ------------------------------------------------------------------ */

    protected function seedCmsProject(int $adminId): void
    {
        $project = $this->createProject([
            'name' => 'CMS',
            'description' => '完整的内容管理系统——文章、分类、标签、评论与页面。',
            'default_locale' => 'en',
            'locales' => ['en', 'zh'],
            'disk' => 'local',
            'public_api' => true,
        ], $adminId);

        /* Pages */
        $pages = $this->createCollection($project, 'Pages', 'pages', 1);
        $this->createField($project, $pages, 'text', 'Title', 'title', 1);
        $this->createField($project, $pages, 'slug', 'Path', 'slug', 2, ['slug' => ['field' => 'title', 'readonly' => true]]);
        $this->createField($project, $pages, 'richtext', 'Content', 'content', 3);
        $this->createField($project, $pages, 'media', 'Image', 'image', 4, ['media' => ['type' => 1]]);
        $this->createField($project, $pages, 'text', 'Author', 'author', 5);

        foreach ([
            ['title' => 'Home', 'slug' => 'home', 'content' => '<h1>Welcome to Content Management System</h1><p>Articles, categories, tags, comments and pages — every field type and content state is covered.</p>', 'author' => 'Admin'],
            ['title' => 'About Us', 'slug' => 'about', 'content' => '<h2>About Content Management System</h2><p>This site is powered by AineNext, a self-hosted headless content management framework built with Laravel and Vue.js.</p>', 'author' => 'Admin'],
            ['title' => 'Contact', 'slug' => 'contact', 'content' => '<h2>Contact Us</h2><p>Reach the team at <strong>hello@cms.example</strong>.</p>', 'author' => 'Admin'],
        ] as $page) {
            $this->createEntry($project, $pages, $page, $adminId);
        }

        /* Articles */
        $articles = $this->createCollection($project, 'Articles', 'articles', 2);
        $this->createField($project, $articles, 'text', 'Title', 'title', 1);
        $this->createField($project, $articles, 'slug', 'Path', 'slug', 2, ['slug' => ['field' => 'title', 'readonly' => true]]);
        $this->createField($project, $articles, 'longtext', 'Excerpt', 'excerpt', 3);
        $this->createField($project, $articles, 'richtext', 'Content', 'content', 4);
        $this->createField($project, $articles, 'media', 'Featured Image', 'featured-image', 5, ['media' => ['type' => 1]]);
        $this->createField($project, $articles, 'relation', 'Category', 'category', 6, ['relation' => ['type' => 1, 'collection' => 'categories']]);
        $this->createField($project, $articles, 'text', 'Author', 'author', 7);
        $this->createField($project, $articles, 'relation', 'Tags', 'tags', 8, ['relation' => ['type' => 2, 'collection' => 'tags']]);
        $this->createField($project, $articles, 'boolean', 'Slider', 'slider', 9);
        $this->createField($project, $articles, 'boolean', 'Featured', 'featured', 10);
        $this->createField($project, $articles, 'boolean', 'Recommended', 'recommended', 11);
        $this->createField($project, $articles, 'enumeration', 'Comments Moderation', 'comments_moderation', 12, ['enumeration' => ['list' => ['auto', 'approval', 'disabled']]]);

        foreach ([
            ['title' => 'Getting Started with AineNext', 'slug' => 'getting-started-with-ainenext', 'excerpt' => 'A gentle introduction to AineNext: install it, create your first project from the CMS Template and serve content through the REST API.', 'content' => '<h2>Welcome</h2><p>This article is part of the demo content shipped with AineNext. Create a project, add collections and start publishing content in minutes.</p>', 'author' => 'Admin', 'slider' => true, 'featured' => true, 'recommended' => true, 'comments_moderation' => 'auto'],
            ['title' => 'Building a Frontend with Vue 3', 'slug' => 'building-a-frontend-with-vue-3', 'excerpt' => 'Consume the AineNext content API from a Vue 3 single page app using the domain-whitelist authentication method.', 'content' => '<h2>Headless by default</h2><p>AineNext exposes a RESTful API so any framework can fetch content and render it however you like.</p>', 'author' => 'Admin', 'slider' => false, 'featured' => false, 'recommended' => true, 'comments_moderation' => 'approval'],
        ] as $article) {
            $this->createEntry($project, $articles, $article, $adminId);
        }

        /* Categories */
        $categories = $this->createCollection($project, 'Categories', 'categories', 3);
        $this->createField($project, $categories, 'text', 'Title', 'title', 1);
        $this->createField($project, $categories, 'slug', 'Path', 'slug', 2, ['slug' => ['field' => 'title', 'readonly' => true]]);
        $this->createField($project, $categories, 'media', 'Image', 'image', 3, ['media' => ['type' => 1]]);
        $this->createField($project, $categories, 'longtext', 'Description', 'description', 4);

        $categoryIds = [];
        foreach ([
            ['title' => 'Tutorials', 'slug' => 'tutorials', 'description' => 'Step-by-step learning material.'],
            ['title' => 'News', 'slug' => 'news', 'description' => 'Project and community news.'],
            ['title' => 'Community', 'slug' => 'community', 'description' => 'Stories from the community.'],
        ] as $category) {
            $categoryIds[] = $this->createEntry($project, $categories, $category, $adminId)->id;
        }

        /* Tags */
        $tags = $this->createCollection($project, 'Tags', 'tags', 4);
        $this->createField($project, $tags, 'text', 'Tag', 'tag', 1);

        $tagIds = [];
        foreach (['Laravel', 'Vue', 'API'] as $tag) {
            $tagIds[] = $this->createEntry($project, $tags, ['tag' => $tag], $adminId)->id;
        }

        /* Comments */
        $comments = $this->createCollection($project, 'Comments', 'comments', 5);
        $this->createField($project, $comments, 'text', 'Name', 'name', 1);
        $this->createField($project, $comments, 'email', 'E-mail', 'e-mail', 2);
        $this->createField($project, $comments, 'longtext', 'Comment', 'comment', 3);
        $this->createField($project, $comments, 'relation', 'Article', 'article', 4, ['relation' => ['type' => 1, 'collection' => 'articles']]);
        $this->createField($project, $comments, 'enumeration', 'Status', 'status', 5, ['enumeration' => ['list' => ['pending', 'approved', 'spam', 'trash']]]);

        $commentEntry = $this->createEntry($project, $comments, [
            'name' => 'Jane Doe', 'e-mail' => 'jane@example.com', 'comment' => 'Great article, keep it up!', 'status' => 'approved',
        ], $adminId);
        $this->relate($commentEntry, $this->fieldByName($comments, 'article'), $this->firstEntry($articles)->id);

        /* Globals */
        $globals = $this->createCollection($project, 'Globals', 'globals', 6);
        $this->createField($project, $globals, 'slug', 'Label', 'label', 1, ['slug' => ['field' => null]]);
        $this->createField($project, $globals, 'text', 'Value', 'value', 2);

        foreach ([
            ['label' => 'site_name', 'value' => 'AineNext'],
            ['label' => 'tagline', 'value' => 'The API-driven content platform'],
        ] as $global) {
            $this->createEntry($project, $globals, $global, $adminId);
        }
    }

    /* ------------------------------------------------------------------ */
    /* Business Directory                                                   */
    /* ------------------------------------------------------------------ */

    protected function seedDirectoryProject(int $adminId): void
    {
        $project = $this->createProject([
            'name' => 'Business Directory',
            'description' => '完整的商家目录——分类、标签、地点、点评、Logo 与相册。',
            'default_locale' => 'en',
            'locales' => ['en', 'zh'],
            'disk' => 'local',
            'public_api' => true,
        ], $adminId);

        /* Listings */
        $listings = $this->createCollection($project, 'Listings', 'listings', 1);
        $this->createField($project, $listings, 'text', 'Business Name', 'title', 1);
        $this->createField($project, $listings, 'slug', 'Path', 'slug', 2, ['slug' => ['field' => 'title', 'readonly' => true]]);
        $this->createField($project, $listings, 'text', 'Website', 'website', 3);
        $this->createField($project, $listings, 'longtext', 'Description', 'description', 4);
        $this->createField($project, $listings, 'relation', 'Category', 'category', 5, ['relation' => ['type' => 1, 'collection' => 'categories']]);
        $this->createField($project, $listings, 'relation', 'Tags', 'tags', 6, ['relation' => ['type' => 2, 'collection' => 'tags']]);
        $this->createField($project, $listings, 'relation', 'Location', 'location', 7, ['relation' => ['type' => 1, 'collection' => 'locations']]);
        $this->createField($project, $listings, 'media', 'Logo', 'logo', 8, ['media' => ['type' => 1]]);
        $this->createField($project, $listings, 'media', 'Gallery', 'gallery', 9, ['media' => ['type' => 2]]);
        $this->createField($project, $listings, 'text', 'Phone', 'phone', 10);
        $this->createField($project, $listings, 'email', 'Email', 'email', 11);
        $this->createField($project, $listings, 'longtext', 'Address', 'address', 12);
        $this->createField($project, $listings, 'longtext', 'Opening Hours', 'opening-hours', 13);
        $this->createField($project, $listings, 'enumeration', 'Price Range', 'price-range', 14, ['enumeration' => ['list' => ['$', '$$', '$$$', '$$$$']]]);
        $this->createField($project, $listings, 'boolean', 'Featured', 'featured', 15);

        /* Categories */
        $categories = $this->createCollection($project, 'Categories', 'categories', 2);
        $this->createField($project, $categories, 'text', 'Title', 'title', 1);
        $this->createField($project, $categories, 'slug', 'Path', 'slug', 2, ['slug' => ['field' => 'title', 'readonly' => true]]);
        $this->createField($project, $categories, 'longtext', 'Description', 'description', 3);

        $categoryIds = [];
        foreach ([
            ['title' => 'Restaurants', 'slug' => 'restaurants', 'description' => 'Places to eat.'],
            ['title' => 'Cafes', 'slug' => 'cafes', 'description' => 'Coffee and light bites.'],
            ['title' => 'Hotels', 'slug' => 'hotels', 'description' => 'Places to stay.'],
            ['title' => 'Shops', 'slug' => 'shops', 'description' => 'Retail stores.'],
        ] as $category) {
            $categoryIds[] = $this->createEntry($project, $categories, $category, $adminId)->id;
        }

        /* Tags */
        $tags = $this->createCollection($project, 'Tags', 'tags', 3);
        $this->createField($project, $tags, 'text', 'Tag', 'tag', 1);

        $tagIds = [];
        foreach (['Family Friendly', 'Outdoor Seating', 'Free WiFi'] as $tag) {
            $tagIds[] = $this->createEntry($project, $tags, ['tag' => $tag], $adminId)->id;
        }

        /* Locations */
        $locations = $this->createCollection($project, 'Locations', 'locations', 4);
        $this->createField($project, $locations, 'text', 'Name', 'name', 1);
        $this->createField($project, $locations, 'slug', 'Path', 'slug', 2, ['slug' => ['field' => 'name', 'readonly' => true]]);

        $locationIds = [];
        foreach ([
            ['name' => 'Downtown', 'slug' => 'downtown'],
            ['name' => 'Riverside', 'slug' => 'riverside'],
        ] as $location) {
            $locationIds[] = $this->createEntry($project, $locations, $location, $adminId)->id;
        }

        /* Reviews */
        $reviews = $this->createCollection($project, 'Reviews', 'reviews', 5);
        $this->createField($project, $reviews, 'text', 'Name', 'name', 1);
        $this->createField($project, $reviews, 'number', 'Rating', 'rating', 2);
        $this->createField($project, $reviews, 'longtext', 'Review', 'review', 3);
        $this->createField($project, $reviews, 'relation', 'Listing', 'listing', 4, ['relation' => ['type' => 1, 'collection' => 'listings']]);

        /* Globals */
        $globals = $this->createCollection($project, 'Globals', 'globals', 6);
        $this->createField($project, $globals, 'slug', 'Label', 'label', 1, ['slug' => ['field' => null]]);
        $this->createField($project, $globals, 'text', 'Value', 'value', 2);

        /* Listings content (needs category/location/tags ids) */
        $listingData = [
            [
                'title' => 'Golden Dragon', 'slug' => 'golden-dragon',
                'website' => 'https://goldendragon.example',
                'description' => 'Award-winning Chinese restaurant in the heart of the city.',
                'phone' => '+1 555 0100', 'email' => 'hello@goldendragon.example',
                'address' => '128 Main Street', 'opening-hours' => 'Mon–Sun 11:00–22:00',
                'price-range' => '$$', 'featured' => true,
                '_category' => 0, '_location' => 0, '_tags' => [0, 2],
            ],
            [
                'title' => 'Harbor View Hotel', 'slug' => 'harbor-view-hotel',
                'website' => 'https://harborview.example',
                'description' => 'Boutique hotel with panoramic river views.',
                'phone' => '+1 555 0101', 'email' => 'stay@harborview.example',
                'address' => '5 Riverside Road', 'opening-hours' => 'Reception 24/7',
                'price-range' => '$$$', 'featured' => false,
                '_category' => 2, '_location' => 1, '_tags' => [1],
            ],
        ];

        $listingEntries = [];
        foreach ($listingData as $listing) {
            $raw = $listing;
            unset($raw['_category'], $raw['_location'], $raw['_tags']);
            $entry = $this->createEntry($project, $listings, $raw, $adminId);
            $listingEntries[] = $entry;

            if (isset($listing['_category'])) {
                $this->relate($entry, $this->fieldByName($listings, 'category'), $categoryIds[$listing['_category']]);
            }
            if (isset($listing['_location'])) {
                $this->relate($entry, $this->fieldByName($listings, 'location'), $locationIds[$listing['_location']]);
            }
            if (isset($listing['_tags'])) {
                foreach ($listing['_tags'] as $i => $tagIndex) {
                    $this->relate($entry, $this->fieldByName($listings, 'tags'), $tagIds[$tagIndex], $i);
                }
            }
        }

        /* Reviews content */
        $review1 = $this->createEntry($project, $reviews, [
            'name' => 'Alice', 'rating' => 5, 'review' => 'The dim sum is fantastic and the staff are lovely.',
        ], $adminId);
        $this->relate($review1, $this->fieldByName($reviews, 'listing'), $listingEntries[0]->id);

        $review2 = $this->createEntry($project, $reviews, [
            'name' => 'Bob', 'rating' => 4, 'review' => 'Beautiful rooms with a great view of the river.',
        ], $adminId);
        $this->relate($review2, $this->fieldByName($reviews, 'listing'), $listingEntries[1]->id);

        foreach ([
            ['label' => 'site_name', 'value' => 'Business Directory'],
            ['label' => 'tagline', 'value' => 'Find the best local businesses'],
        ] as $global) {
            $this->createEntry($project, $globals, $global, $adminId);
        }
    }

    /* ------------------------------------------------------------------ */
    /* 笔记 (Note)                                                         */
    /* ------------------------------------------------------------------ */

    protected function seedNoteProject(int $adminId): void
    {
        $project = $this->createProject([
            'name' => '笔记',
            'description' => '云笔记、记事本与备忘录应用——用结构化的方式记录灵感、待办与日常。',
            'default_locale' => 'zh',
            'locales' => ['zh', 'en'],
            'disk' => 'local',
            'public_api' => true,
        ], $adminId);

        /* Pages */
        $pages = $this->createCollection($project, 'Pages', 'pages', 1);
        $this->createField($project, $pages, 'text', 'Title', 'title', 1);
        $this->createField($project, $pages, 'slug', 'Path', 'slug', 2, ['slug' => ['field' => 'title', 'readonly' => true]]);
        $this->createField($project, $pages, 'richtext', 'Content', 'content', 3);
        $this->createField($project, $pages, 'media', 'Image', 'image', 4, ['media' => ['type' => 1]]);

        foreach ([
            ['title' => '欢迎', 'slug' => 'welcome', 'content' => '<h1>欢迎使用笔记</h1><p>记录灵感、待办与日常。</p>'],
        ] as $page) {
            $this->createEntry($project, $pages, $page, $adminId, 'zh');
        }

        /* Posts */
        $posts = $this->createCollection($project, 'Posts', 'posts', 2);
        $this->createField($project, $posts, 'text', 'Title', 'title', 1);
        $this->createField($project, $posts, 'slug', 'Path', 'slug', 2, ['slug' => ['field' => 'title', 'readonly' => true]]);
        $this->createField($project, $posts, 'longtext', 'Excerpt', 'excerpt', 3);
        $this->createField($project, $posts, 'richtext', 'Content', 'content', 4);
        $this->createField($project, $posts, 'media', 'Featured Image', 'featured-image', 5, ['media' => ['type' => 1]]);
        $this->createField($project, $posts, 'relation', 'Category', 'category', 6, ['relation' => ['type' => 1, 'collection' => 'categories']]);
        $this->createField($project, $posts, 'relation', 'Tags', 'tags', 7, ['relation' => ['type' => 2, 'collection' => 'tags']]);
        $this->createField($project, $posts, 'boolean', 'Slider', 'slider', 8);
        $this->createField($project, $posts, 'boolean', 'Featured', 'featured', 9);
        $this->createField($project, $posts, 'boolean', 'Recommended', 'recommended', 10);

        /* Categories */
        $categories = $this->createCollection($project, 'Categories', 'categories', 3);
        $this->createField($project, $categories, 'text', 'Title', 'title', 1);
        $this->createField($project, $categories, 'slug', 'Path', 'slug', 2, ['slug' => ['field' => 'title', 'readonly' => true]]);
        $this->createField($project, $categories, 'media', 'Image', 'image', 3, ['media' => ['type' => 1]]);
        $this->createField($project, $categories, 'longtext', 'Description', 'description', 4);

        $categoryIds = [];
        foreach ([
            ['title' => '灵感', 'slug' => 'ideas', 'description' => '一闪而过的灵感。'],
            ['title' => '工作', 'slug' => 'work', 'description' => '工作相关的记录。'],
        ] as $category) {
            $categoryIds[] = $this->createEntry($project, $categories, $category, $adminId, 'zh')->id;
        }

        /* Tags */
        $tags = $this->createCollection($project, 'Tags', 'tags', 4);
        $this->createField($project, $tags, 'text', 'Tag', 'tag', 1);

        $tagIds = [];
        foreach (['todo', 'idea', 'study'] as $tag) {
            $tagIds[] = $this->createEntry($project, $tags, ['tag' => $tag], $adminId, 'zh')->id;
        }

        /* Globals */
        $globals = $this->createCollection($project, 'Globals', 'globals', 5);
        $this->createField($project, $globals, 'slug', 'Label', 'label', 1, ['slug' => ['field' => null]]);
        $this->createField($project, $globals, 'text', 'Value', 'value', 2);

        /* Posts content */
        $postsData = [
            [
                'title' => '项目灵感', 'slug' => 'project-idea',
                'excerpt' => '把 AineNext 用于内部知识库。',
                'content' => '<p>把 AineNext 用于内部知识库，团队协作编辑文档。</p>',
                'slider' => false, 'featured' => true, 'recommended' => true,
                '_category' => 0, '_tags' => [0],
            ],
            [
                'title' => '本周计划', 'slug' => 'weekly-plan',
                'excerpt' => '完成 API 文档，准备 v1.0 发布。',
                'content' => '<p>完成 API 文档，准备 v1.0 发布。</p>',
                'slider' => false, 'featured' => false, 'recommended' => false,
                '_category' => 1, '_tags' => [0, 2],
            ],
        ];

        $postEntries = [];
        foreach ($postsData as $post) {
            $raw = $post;
            unset($raw['_category'], $raw['_tags']);
            $entry = $this->createEntry($project, $posts, $raw, $adminId, 'zh');
            $postEntries[] = $entry;

            if (isset($post['_category'])) {
                $this->relate($entry, $this->fieldByName($posts, 'category'), $categoryIds[$post['_category']]);
            }
            if (isset($post['_tags'])) {
                foreach ($post['_tags'] as $i => $tagIndex) {
                    $this->relate($entry, $this->fieldByName($posts, 'tags'), $tagIds[$tagIndex], $i);
                }
            }
        }

        foreach ([
            ['label' => 'site_name', 'value' => '云笔记'],
            ['label' => 'tagline', 'value' => '记录每一次灵感'],
        ] as $global) {
            $this->createEntry($project, $globals, $global, $adminId, 'zh');
        }
    }

    /* ------------------------------------------------------------------ */
    /* 网址导航 (Navigation)                                                */
    /* ------------------------------------------------------------------ */

    protected function seedNavigationProject(int $adminId): void
    {
        $project = $this->createProject([
            'name' => '网址导航',
            'description' => '网址导航——分类收录常用网站，支持搜索、置顶推荐与私有链接。',
            'default_locale' => 'zh',
            'locales' => ['zh'],
            'disk' => 'local',
            'public_api' => true,
        ], $adminId);

        /* Links */
        $links = $this->createCollection($project, 'Links', 'links', 1);
        $this->createField($project, $links, 'text', 'Name', 'title', 1);
        $this->createField($project, $links, 'text', 'URL', 'url', 2);
        $this->createField($project, $links, 'longtext', 'Description', 'description', 3);
        $this->createField($project, $links, 'media', 'Icon', 'favicon', 4, ['media' => ['type' => 1]]);
        $this->createField($project, $links, 'relation', 'Category', 'category', 5, ['relation' => ['type' => 1, 'collection' => 'categories']]);
        $this->createField($project, $links, 'relation', 'Tags', 'tags', 6, ['relation' => ['type' => 2, 'collection' => 'tags']]);
        $this->createField($project, $links, 'number', 'Weight', 'weight', 7);
        $this->createField($project, $links, 'enumeration', 'Link Status', 'link_status', 8, ['enumeration' => ['list' => ['unknown', 'ok', 'dead']]]);
        $this->createField($project, $links, 'enumeration', 'Click Behavior', 'click_behavior', 9, ['enumeration' => ['list' => ['detail', 'direct']]]);
        $this->createField($project, $links, 'boolean', 'Featured', 'featured', 10);
        $this->createField($project, $links, 'boolean', 'Private', 'private', 11);
        $this->createField($project, $links, 'number', 'HTTP Code', 'http_code', 12);
        $this->createField($project, $links, 'text', 'Last Checked At', 'last_checked_at', 13);

        /* Categories */
        $categories = $this->createCollection($project, 'Categories', 'categories', 2);
        $this->createField($project, $categories, 'text', 'Name', 'title', 1);
        $this->createField($project, $categories, 'slug', 'Path', 'slug', 2, ['slug' => ['field' => 'title', 'readonly' => true]]);
        $this->createField($project, $categories, 'text', 'Icon', 'icon', 3);
        $this->createField($project, $categories, 'color', 'Color', 'color', 4);
        $this->createField($project, $categories, 'relation', 'Parent Category', 'parent', 5, ['relation' => ['type' => 1, 'collection' => 'categories']]);
        $this->createField($project, $categories, 'number', 'Weight', 'weight', 6);
        $this->createField($project, $categories, 'boolean', 'Featured', 'featured', 7);

        $categoryIds = [];
        foreach ([
            ['title' => '开发工具', 'slug' => 'dev-tools', 'icon' => '🔧', 'color' => '#2563eb', 'weight' => 1, 'featured' => true],
            ['title' => '设计资源', 'slug' => 'design', 'icon' => '🎨', 'color' => '#db2777', 'weight' => 2, 'featured' => true],
            ['title' => '学习社区', 'slug' => 'learning', 'icon' => '📚', 'color' => '#059669', 'weight' => 3, 'featured' => false],
            ['title' => '效率办公', 'slug' => 'productivity', 'icon' => '⚡', 'color' => '#d97706', 'weight' => 4, 'featured' => false],
        ] as $category) {
            $categoryIds[] = $this->createEntry($project, $categories, $category, $adminId, 'zh')->id;
        }

        /* Tags */
        $tags = $this->createCollection($project, 'Tags', 'tags', 3);
        $this->createField($project, $tags, 'text', 'Tag', 'tag', 1);

        $tagIds = [];
        foreach (['常用', '推荐'] as $tag) {
            $tagIds[] = $this->createEntry($project, $tags, ['tag' => $tag], $adminId, 'zh')->id;
        }

        /* Globals */
        $globals = $this->createCollection($project, 'Globals', 'globals', 4);
        $this->createField($project, $globals, 'slug', 'Label', 'label', 1, ['slug' => ['field' => null]]);
        $this->createField($project, $globals, 'text', 'Value', 'value', 2);

        /* Links content */
        $linksData = [
            ['title' => 'GitHub', 'url' => 'https://github.com', 'description' => '代码托管平台', 'weight' => 1, 'link_status' => 'ok', 'click_behavior' => 'direct', 'featured' => true, 'private' => false, 'http_code' => 200, 'last_checked_at' => now()->toDateString(), '_category' => 0, '_tags' => [0]],
            ['title' => 'Laravel', 'url' => 'https://laravel.com', 'description' => 'PHP 框架官网', 'weight' => 2, 'link_status' => 'ok', 'click_behavior' => 'direct', 'featured' => true, 'private' => false, 'http_code' => 200, 'last_checked_at' => now()->toDateString(), '_category' => 0, '_tags' => [0, 1]],
            ['title' => 'Dribbble', 'url' => 'https://dribbble.com', 'description' => '设计灵感社区', 'weight' => 3, 'link_status' => 'unknown', 'click_behavior' => 'direct', 'featured' => false, 'private' => false, '_category' => 1, '_tags' => []],
            ['title' => '掘金', 'url' => 'https://juejin.cn', 'description' => '技术文章社区', 'weight' => 4, 'link_status' => 'ok', 'click_behavior' => 'direct', 'featured' => true, 'private' => false, 'http_code' => 200, 'last_checked_at' => now()->toDateString(), '_category' => 2, '_tags' => [0]],
            ['title' => 'Notion', 'url' => 'https://notion.so', 'description' => '笔记与协作工具', 'weight' => 5, 'link_status' => 'unknown', 'click_behavior' => 'detail', 'featured' => false, 'private' => true, '_category' => 3, '_tags' => [1]],
        ];

        foreach ($linksData as $link) {
            $raw = $link;
            unset($raw['_category'], $raw['_tags']);
            $entry = $this->createEntry($project, $links, $raw, $adminId, 'zh');

            if (isset($link['_category'])) {
                $this->relate($entry, $this->fieldByName($links, 'category'), $categoryIds[$link['_category']]);
            }
            foreach ($link['_tags'] as $i => $tagIndex) {
                $this->relate($entry, $this->fieldByName($links, 'tags'), $tagIds[$tagIndex], $i);
            }
        }

        foreach ([
            ['label' => 'site_name', 'value' => '网址导航'],
            ['label' => 'tagline', 'value' => '收藏每一个好网站'],
        ] as $global) {
            $this->createEntry($project, $globals, $global, $adminId, 'zh');
        }
    }
}
