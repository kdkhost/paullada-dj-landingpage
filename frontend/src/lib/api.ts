export interface Show {
  id: number;
  date: string;
  time: string;
  venue: string;
  city: string;
  ticket_price: number | null;
  ticket_url: string | null;
  status: "disponivel" | "esgotado" | "realizado";
}

export interface GalleryItem {
  id: number;
  type: "foto" | "video";
  url: string;
  thumbnail: string | null;
  title: string | null;
}

export interface SocialLink {
  id: number;
  platform: string;
  url: string;
  icon: string | null;
}

export interface SiteSettings {
  site_name: string;
  description: string;
  logo_url: string | null;
  instagram: string;
  whatsapp: string | null;
  email: string | null;
  phone: string | null;
  hero_bg_type: "gradient" | "image" | "video" | null;
  hero_bg_image: string | null;
  hero_bg_video: string | null;
}

export interface TrackEventPayload {
  tipo: string;
  pagina: string;
  detalhes?: string;
}

async function fetchAPI<T>(endpoint: string): Promise<T> {
  try {
    const res = await fetch(endpoint);
    if (!res.ok) throw new Error(`Erro ${res.status}: ${res.statusText}`);
    return await res.json();
  } catch {
    return [] as unknown as T;
  }
}

export async function fetchShows(): Promise<Show[]> {
  return fetchAPI<Show[]>("/api/shows");
}

export async function fetchGallery(): Promise<GalleryItem[]> {
  return fetchAPI<GalleryItem[]>("/api/gallery");
}

export async function fetchSocialLinks(): Promise<SocialLink[]> {
  return fetchAPI<SocialLink[]>("/api/social-links");
}

export async function fetchSettings(): Promise<SiteSettings | null> {
  try {
    const res = await fetch("/api/settings");
    if (!res.ok) return null;
    return await res.json();
  } catch {
    return null;
  }
}

export async function trackEvent(tipo: string, pagina: string, detalhes?: string): Promise<void> {
  try {
    await fetch("/api/track-event", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ tipo, pagina, detalhes } satisfies TrackEventPayload),
    });
  } catch {
    // silently fail
  }
}
