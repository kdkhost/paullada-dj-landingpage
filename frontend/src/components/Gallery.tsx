"use client";

import { useEffect, useState, useCallback } from "react";
import { FaCamera, FaVideo, FaImage, FaXmark, FaChevronLeft, FaChevronRight } from "react-icons/fa6";
import { fetchGallery, type GalleryItem } from "@/lib/api";
import { useAnalytics } from "@/context/AnalyticsContext";

type FilterType = "todas" | "foto" | "video";

function getYouTubeEmbedId(url: string): string | null {
  const patterns = [
    /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/,
  ];
  for (const p of patterns) {
    const match = url.match(p);
    if (match) return match[1];
  }
  return null;
}

export default function Gallery() {
  const [items, setItems] = useState<GalleryItem[]>([]);
  const [filter, setFilter] = useState<FilterType>("todas");
  const [lightbox, setLightbox] = useState<{ url: string; title: string } | null>(null);
  const [lightboxIndex, setLightboxIndex] = useState(-1);
  const [loading, setLoading] = useState(true);
  const { track } = useAnalytics();

  useEffect(() => {
    fetchGallery()
      .then(setItems)
      .finally(() => setLoading(false));
  }, []);

  const filtered = items.filter((item) => {
    if (filter === "todas") return true;
    return item.type === filter;
  });

  const photosOnly = items.filter((i) => i.type === "foto");

  const openLightbox = useCallback(
    (item: GalleryItem, index: number) => {
      setLightbox({ url: item.url, title: item.title || "" });
      setLightboxIndex(index);
      track("visualizacao", "galeria", item.title || "foto");
    },
    [track]
  );

  const closeLightbox = useCallback(() => {
    setLightbox(null);
    setLightboxIndex(-1);
  }, []);

  const navigateLightbox = useCallback(
    (dir: 1 | -1) => {
      const newIdx = lightboxIndex + dir;
      if (newIdx < 0 || newIdx >= photosOnly.length) return;
      const item = photosOnly[newIdx];
      setLightbox({ url: item.url, title: item.title || "" });
      setLightboxIndex(newIdx);
    },
    [lightboxIndex, photosOnly]
  );

  useEffect(() => {
    if (lightbox === null) return;
    const handleKey = (e: KeyboardEvent) => {
      if (e.key === "Escape") closeLightbox();
      if (e.key === "ArrowLeft") navigateLightbox(-1);
      if (e.key === "ArrowRight") navigateLightbox(1);
    };
    window.addEventListener("keydown", handleKey);
    return () => window.removeEventListener("keydown", handleKey);
  }, [lightbox, closeLightbox, navigateLightbox]);

  const filters: { key: FilterType; label: string; icon: typeof FaImage }[] = [
    { key: "todas", label: "Todas", icon: FaImage },
    { key: "foto", label: "Fotos", icon: FaCamera },
    { key: "video", label: "Vídeos", icon: FaVideo },
  ];

  return (
    <section id="galeria" className="py-24 md:py-32">
      <div className="mx-auto max-w-7xl px-4 md:px-8">
        <div className="mb-12 text-center">
          <span className="text-xs tracking-[0.3em] uppercase text-accent">Galeria</span>
          <h2 className="mt-3 font-heading text-3xl font-bold text-white md:text-5xl">
            Momentos <span className="text-shadow-purple text-neon-purple">Inesquecíveis</span>
          </h2>
        </div>

        <div className="mb-8 flex flex-wrap justify-center gap-3">
          {filters.map((f) => {
            const Icon = f.icon;
            const active = filter === f.key;
            return (
              <button
                key={f.key}
                onClick={() => setFilter(f.key)}
                className={`flex items-center gap-2 rounded-full border px-5 py-2 text-sm font-semibold uppercase tracking-wider transition-all ${
                  active
                    ? "border-accent bg-accent/10 text-accent shadow-lg shadow-accent/20"
                    : "border-zinc-700 text-zinc-400 hover:border-zinc-500 hover:text-white"
                }`}
              >
                <Icon />
                {f.label}
              </button>
            );
          })}
        </div>

        {loading ? (
          <div className="flex justify-center py-16">
            <div className="h-10 w-10 animate-spin rounded-full border-2 border-accent border-t-transparent" />
          </div>
        ) : filtered.length === 0 ? (
          <div className="glass-card mx-auto max-w-md rounded-2xl p-12 text-center">
            <FaCamera className="mx-auto mb-4 text-4xl text-zinc-600" />
            <p className="text-zinc-400">Nenhum item encontrado.</p>
          </div>
        ) : (
          <div className="columns-1 gap-4 sm:columns-2 lg:columns-3">
            {filtered.map((item) => {
              const photoIndex = photosOnly.findIndex((p) => p.id === item.id);

              if (item.type === "video") {
                const videoId = getYouTubeEmbedId(item.url);
                return (
                  <div key={item.id} className="glass-card mb-4 break-inside-avoid overflow-hidden rounded-xl">
                    {videoId ? (
                      <div className="relative aspect-video">
                        <iframe
                          src={`https://www.youtube.com/embed/${videoId}`}
                          title={item.title || "Vídeo"}
                          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                          allowFullScreen
                          className="absolute inset-0 h-full w-full"
                        />
                      </div>
                    ) : (
                      <div className="flex aspect-video items-center justify-center bg-dark-2">
                        <FaVideo className="text-3xl text-zinc-600" />
                      </div>
                    )}
                    {item.title && (
                      <div className="p-3">
                        <p className="text-sm text-zinc-400">{item.title}</p>
                      </div>
                    )}
                  </div>
                );
              }

              return (
                <div
                  key={item.id}
                  onClick={() => openLightbox(item, photoIndex)}
                  className="glass-card group relative mb-4 cursor-pointer break-inside-avoid overflow-hidden rounded-xl"
                >
                  <div className="relative">
                    {/* eslint-disable-next-line @next/next/no-img-element */}
                  <img
                      src={item.thumbnail || item.url}
                      alt={item.title || "Foto"}
                      className="w-full transition-transform duration-500 group-hover:scale-110"
                      loading="lazy"
                    />
                    <div className="absolute inset-0 flex items-center justify-center bg-black/0 transition-all duration-300 group-hover:bg-black/40">
                      <FaCamera className="scale-0 text-2xl text-white transition-transform duration-300 group-hover:scale-100" />
                    </div>
                  </div>
                  {item.title && (
                    <div className="absolute bottom-0 left-0 right-0 translate-y-full bg-gradient-to-t from-black/80 to-transparent p-4 transition-transform duration-300 group-hover:translate-y-0">
                      <p className="text-sm text-white">{item.title}</p>
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        )}
      </div>

      {lightbox && (
        <div
          className="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 p-4"
          onClick={closeLightbox}
        >
          <button
            onClick={closeLightbox}
            className="absolute top-4 right-4 z-10 text-3xl text-white transition-colors hover:text-accent"
            aria-label="Fechar"
          >
            <FaXmark />
          </button>

          {photosOnly.length > 1 && (
            <>
              <button
                onClick={(e) => {
                  e.stopPropagation();
                  navigateLightbox(-1);
                }}
                className={`absolute left-4 z-10 text-3xl text-white transition-colors hover:text-accent ${
                  lightboxIndex <= 0 ? "opacity-30 pointer-events-none" : ""
                }`}
                aria-label="Anterior"
              >
                <FaChevronLeft />
              </button>
              <button
                onClick={(e) => {
                  e.stopPropagation();
                  navigateLightbox(1);
                }}
                className={`absolute right-4 z-10 text-3xl text-white transition-colors hover:text-accent ${
                  lightboxIndex >= photosOnly.length - 1 ? "opacity-30 pointer-events-none" : ""
                }`}
                aria-label="Próximo"
              >
                <FaChevronRight />
              </button>
            </>
          )}

          <div
            className="flex max-h-full max-w-4xl flex-col items-center"
            onClick={(e) => e.stopPropagation()}
          >
            {/* eslint-disable-next-line @next/next/no-img-element */}
            <img
              src={lightbox.url}
              alt={lightbox.title}
              className="max-h-[85vh] rounded-xl object-contain shadow-2xl"
            />
            {lightbox.title && (
              <p className="mt-4 text-center text-zinc-400">{lightbox.title}</p>
            )}
            <p className="mt-2 text-xs text-zinc-600">
              {lightboxIndex + 1} / {photosOnly.length}
            </p>
          </div>
        </div>
      )}
    </section>
  );
}
