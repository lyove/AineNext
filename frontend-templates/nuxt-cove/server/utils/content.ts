import { cachedCms } from './cms-cache'
import { asList } from './list'
import type {
  BlogPostFields,
  ChangelogFields,
  ContentEntry,
  CustomerFields,
  FaqFields,
  FeatureFields,
  HomeFields,
  PageFields,
  PricingPlanFields,
  SiteSettingsFields,
  TestimonialFields
} from './types'

function byOrder<T extends { fields: { order?: number } }>(a: T, b: T) {
  return Number(a.fields.order ?? 0) - Number(b.fields.order ?? 0)
}

export async function getSiteSettings() {
  return cachedCms('site-settings', () =>
    useAineServer().content.list('site-settings', {
      state: 'published'
    }) as Promise<ContentEntry<SiteSettingsFields>>
  )
}

export async function getHome() {
  return cachedCms('home', () =>
    useAineServer().content.list('home', {
      state: 'published'
    }) as Promise<ContentEntry<HomeFields>>
  )
}

export async function getFeaturesPage() {
  return cachedCms('features-page', () =>
    useAineServer().content.list('features-page', {
      state: 'published'
    }) as Promise<ContentEntry<PageFields>>
  )
}

export async function getFeatures() {
  return cachedCms('features', async () => {
    const res = await useAineServer().content.list('features', {
      state: 'published',
      sort: 'order:asc'
    })
    return asList<ContentEntry<FeatureFields>>(res).sort(byOrder)
  })
}

export async function getFeatureBySlug(slug: string) {
  return cachedCms(`feature:${slug}`, async () => {
    const res = await useAineServer().content.list('features', {
      state: 'published',
      where: { slug: { eq: slug } },
      first: true
    })
    return res as ContentEntry<FeatureFields> | null
  })
}

export async function getPricingPage() {
  return cachedCms('pricing-page', () =>
    useAineServer().content.list('pricing-page', {
      state: 'published'
    }) as Promise<ContentEntry<PageFields>>
  )
}

export async function getPricingPlans() {
  return cachedCms('pricing-plans', async () => {
    const res = await useAineServer().content.list('pricing-plans', {
      state: 'published',
      sort: 'order:asc'
    })
    return asList<ContentEntry<PricingPlanFields>>(res).sort(byOrder)
  })
}

export async function getTestimonials() {
  return cachedCms('testimonials', async () => {
    const res = await useAineServer().content.list('testimonials', {
      state: 'published',
      sort: 'order:asc'
    })
    return asList<ContentEntry<TestimonialFields>>(res).sort(byOrder)
  })
}

export async function getCustomers() {
  return cachedCms('customers', async () => {
    const res = await useAineServer().content.list('customers', {
      state: 'published',
      sort: 'order:asc'
    })
    return asList<ContentEntry<CustomerFields>>(res).sort(byOrder)
  })
}

export async function getChangelogPage() {
  return cachedCms('changelog-page', () =>
    useAineServer().content.list('changelog-page', {
      state: 'published'
    }) as Promise<ContentEntry<PageFields>>
  )
}

export async function getChangelogEntries() {
  return cachedCms('changelog', async () => {
    const res = await useAineServer().content.list('changelog', {
      state: 'published',
      sort: 'published_at:desc'
    })
    return asList<ContentEntry<ChangelogFields>>(res)
  })
}

export async function getChangelogBySlug(slug: string) {
  return cachedCms(`changelog:${slug}`, async () => {
    const res = await useAineServer().content.list('changelog', {
      state: 'published',
      where: { slug: { eq: slug } },
      first: true
    })
    return res as ContentEntry<ChangelogFields> | null
  })
}

export async function getBlogPage() {
  return cachedCms('blog-page', () =>
    useAineServer().content.list('blog-page', {
      state: 'published'
    }) as Promise<ContentEntry<PageFields>>
  )
}

export async function getBlogPosts() {
  return cachedCms('blog-posts', async () => {
    const res = await useAineServer().content.list('blog-posts', {
      state: 'published',
      sort: 'published_at:desc'
    })
    return asList<ContentEntry<BlogPostFields>>(res)
  })
}

export async function getBlogPostBySlug(slug: string) {
  return cachedCms(`blog-post:${slug}`, async () => {
    const res = await useAineServer().content.list('blog-posts', {
      state: 'published',
      where: { slug: { eq: slug } },
      first: true
    })
    return res as ContentEntry<BlogPostFields> | null
  })
}

export async function getAboutPage() {
  return cachedCms('about-page', () =>
    useAineServer().content.list('about-page', {
      state: 'published'
    }) as Promise<ContentEntry<PageFields>>
  )
}

export async function getContactPage() {
  return cachedCms('contact-page', () =>
    useAineServer().content.list('contact-page', {
      state: 'published'
    }) as Promise<ContentEntry<PageFields>>
  )
}

export async function getFaqs() {
  return cachedCms('faqs', async () => {
    const res = await useAineServer().content.list('faqs', {
      state: 'published',
      sort: 'order:asc'
    })
    return asList<ContentEntry<FaqFields>>(res).sort(byOrder)
  })
}
