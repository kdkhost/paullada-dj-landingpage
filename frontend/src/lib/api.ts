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

function mapStatus(status: string): Show["status"] {
  if (status === "active" || status === "disponivel") return "disponivel";
  if (status === "esgotado") return "esgotado";
  return "realizado";
}

export async function fetchShows(): Promise<Show[]> {
  const raw = await fetchAPI<Record<string, unknown>[]>("/api/shows");
  return raw.map((r) => ({
    id: r.id as number,
    date: (r.data_evento || r.date || "") as string,
    time: (r.hora_evento || r.time || "") as string,
    venue: (r.local || r.venue || "") as string,
    city: (r.cidade || r.city || "") as string,
    ticket_price: r.preco_ingresso != null ? Number(r.preco_ingresso) : (r.ticket_price as number | null),
    ticket_url: (r.link_ingresso || r.ticket_url || null) as string | null,
    status: mapStatus((r.status as string) || "active"),
  }));
}

export async function fetchGallery(): Promise<GalleryItem[]> {
  const raw = await fetchAPI<Record<string, unknown>[]>("/api/gallery");
  return raw.map((r) => ({
    id: r.id as number,
    type: ((r.type || r.tipo) as "foto" | "video") || "foto",
    url: (r.url || r.arquivo || "") as string,
    thumbnail: (r.thumbnail || r.foto_capa || null) as string | null,
    title: (r.title || r.titulo || null) as string | null,
  }));
}

export async function fetchSocialLinks(): Promise<SocialLink[]> {
  const raw = await fetchAPI<Record<string, unknown>[]>("/api/social-links");
  return raw.map((r) => ({
    id: r.id as number,
    platform: (r.platform || r.plataforma || "") as string,
    url: (r.url || "") as string,
    icon: (r.icon || r.icone || null) as string | null,
  }));
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
