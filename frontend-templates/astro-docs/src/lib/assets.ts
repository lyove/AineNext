import type { AineAsset } from './types';

export function firstAsset(value: AineAsset | AineAsset[] | null | undefined): AineAsset | null {
  if (!value) return null;
  if (Array.isArray(value)) return value[0] || null;
  return value;
}

export function assetUrl(value: AineAsset | AineAsset[] | null | undefined): string | undefined {
  return firstAsset(value)?.url || undefined;
}
