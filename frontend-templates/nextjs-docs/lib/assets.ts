import type { AineAsset } from "./types";

export function firstAsset(value: unknown): AineAsset | null {
  if (!value) return null;
  if (Array.isArray(value)) return (value[0] as AineAsset) ?? null;
  return value as AineAsset;
}
