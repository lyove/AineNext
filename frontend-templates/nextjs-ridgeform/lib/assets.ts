import type { AineAsset } from "./types";

export function firstAsset(value: unknown): AineAsset | null {
  if (!value) return null;
  if (Array.isArray(value)) return (value[0] as AineAsset) ?? null;
  return value as AineAsset;
}

export function assetList(value: unknown): AineAsset[] {
  if (!value) return [];
  if (Array.isArray(value)) return value.filter(Boolean) as AineAsset[];
  return [value as AineAsset];
}

export function assetAlt(asset: AineAsset | null, fallback: string): string {
  return asset?.metadata?.alt_text?.trim() || fallback;
}

export function enumValue(value: string | string[] | null | undefined): string {
  if (!value) return "";
  return Array.isArray(value) ? (value[0] ?? "") : value;
}
