import type { NextConfig } from "next";

type ImageRemotePattern = {
  protocol?: "http" | "https";
  hostname: string;
  port?: string;
  pathname?: string;
};

/** Allow next/image for the Aine host from AINE_BASE_URL (set at build time). */
function aineRemotePatterns(): ImageRemotePattern[] {
  const patterns: ImageRemotePattern[] = [
    { protocol: "http", hostname: "localhost", pathname: "/**" },
    {
      protocol: "https",
      hostname: "**.unsplash.com",
      pathname: "/**",
    },
  ];

  const base = process.env.AINE_BASE_URL?.trim();
  if (!base) {
    patterns.push({
      protocol: "https",
      hostname: "ainenext.test",
      pathname: "/uploads/**",
    });
    return patterns;
  }

  try {
    const url = new URL(base);
    patterns.push({
      protocol: url.protocol === "http:" ? "http" : "https",
      hostname: url.hostname,
      ...(url.port ? { port: url.port } : {}),
      pathname: "/**",
    });
  } catch {
    // ignore invalid AINE_BASE_URL
  }

  return patterns;
}

const nextConfig: NextConfig = {
  images: {
    dangerouslyAllowLocalIP: process.env.NODE_ENV === "development",
    remotePatterns: aineRemotePatterns(),
  },
  };

export default nextConfig;
