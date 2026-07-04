"use client";

import { useEffect, useState } from "react";
import { FaCalendarDays, FaClock, FaLocationDot, FaTicket } from "react-icons/fa6";
import { fetchShows, type Show } from "@/lib/api";
import { useAnalytics } from "@/context/AnalyticsContext";

function formatDate(dateStr: string): string {
  const d = new Date(dateStr);
  return d.toLocaleDateString("pt-BR", {
    day: "2-digit",
    month: "long",
    year: "numeric",
  });
}

function formatTime(timeStr: string): string {
  if (!timeStr) return "";
  const [h, m] = timeStr.split(":");
  return `${h}:${m}`;
}

function formatPrice(price: number | null): string {
  if (price === null || price === undefined) return "Gratuito";
  return price.toLocaleString("pt-BR", {
    style: "currency",
    currency: "BRL",
  });
}

const statusConfig: Record<string, { label: string; class: string }> = {
  disponivel: { label: "Disponível", class: "bg-green-500/20 text-green-400 border-green-500/30" },
  esgotado: { label: "Esgotado", class: "bg-red-500/20 text-red-400 border-red-500/30" },
  realizado: { label: "Realizado", class: "bg-zinc-500/20 text-zinc-400 border-zinc-500/30" },
};

function getStatus(status: string) {
  return statusConfig[status] ?? { label: status, class: "bg-zinc-500/20 text-zinc-400 border-zinc-500/30" };
}

function useScrollReveal() {
  const [revealed, setRevealed] = useState(false);
  const ref = (el: HTMLDivElement | null) => {
    if (!el || revealed) return;
    const obs = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setRevealed(true);
          obs.disconnect();
        }
      },
      { threshold: 0.1 }
    );
    obs.observe(el);
  };
  return { ref, revealed };
}

export default function Shows() {
  const [shows, setShows] = useState<Show[]>([]);
  const [loading, setLoading] = useState(true);
  const { track } = useAnalytics();
  const { ref, revealed } = useScrollReveal();

  useEffect(() => {
    fetchShows()
      .then(setShows)
      .finally(() => setLoading(false));
  }, []);

  return (
    <section id="agenda" className="py-24 md:py-32">
      <div ref={ref} className={`mx-auto max-w-7xl px-4 transition-all duration-800 md:px-8 ${revealed ? "opacity-100 translate-y-0" : "opacity-0 translate-y-10"}`}>
        <div className="mb-12 text-center">
          <span className="text-xs tracking-[0.3em] uppercase text-accent">Agenda</span>
          <h2 className="mt-3 font-heading text-3xl font-bold text-white md:text-5xl">
            Próximos <span className="text-shadow-primary text-primary">Shows</span>
          </h2>
          <p className="mt-3 text-zinc-500">Confira onde o Paullada DJ estará tocando</p>
        </div>

        {loading ? (
          <div className="flex justify-center py-16">
            <div className="h-10 w-10 animate-spin rounded-full border-2 border-accent border-t-transparent" />
          </div>
        ) : shows.length === 0 ? (
          <div className="glass-card mx-auto max-w-md rounded-2xl p-12 text-center">
            <FaCalendarDays className="mx-auto mb-4 text-4xl text-zinc-600" />
            <p className="text-zinc-400">Nenhum show agendado no momento.</p>
            <p className="mt-1 text-sm text-zinc-600">Volte em breve para conferir as novidades!</p>
          </div>
        ) : (
          <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            {shows.map((show) => {
              const status = getStatus(show.status);
              return (
                <div key={show.id} className="glass-card group rounded-2xl overflow-hidden">
                  <div className="p-6">
                    <div className="mb-4 flex items-start justify-between">
                      <span className={`rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wider ${status.class}`}>
                        {status.label}
                      </span>
                      {show.ticket_price !== null && show.ticket_price > 0 && (
                        <span className="font-heading text-lg font-bold text-gold">{formatPrice(show.ticket_price)}</span>
                      )}
                    </div>

                    <div className="mb-4 space-y-2">
                      <div className="flex items-center gap-3 text-zinc-400">
                        <FaCalendarDays className="shrink-0 text-accent" />
                        <span className="text-sm">{formatDate(show.date)}</span>
                      </div>
                      {show.time && (
                        <div className="flex items-center gap-3 text-zinc-400">
                          <FaClock className="shrink-0 text-accent" />
                          <span className="text-sm">{formatTime(show.time)}</span>
                        </div>
                      )}
                      <div className="flex items-center gap-3 text-zinc-400">
                        <FaLocationDot className="shrink-0 text-accent" />
                        <div>
                          <p className="text-sm font-medium text-white">{show.venue}</p>
                          <p className="text-xs text-zinc-500">{show.city}</p>
                        </div>
                      </div>
                    </div>

                    {show.status === "disponivel" ? (
                      show.ticket_url ? (
                        <a
                          href={show.ticket_url}
                          target="_blank"
                          rel="noopener noreferrer"
                          onClick={() => track("clique", "agenda", `ingresso-show-${show.id}`)}
                          className="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-accent to-neon-cyan px-4 py-3 text-sm font-bold text-dark transition-all hover:shadow-lg hover:shadow-accent/30"
                        >
                          <FaTicket />
                          Comprar Ingresso
                        </a>
                      ) : (
                        <div className="mt-4 flex w-full items-center justify-center gap-2 rounded-xl border border-zinc-700 px-4 py-3 text-sm text-zinc-400">
                          <FaTicket />
                          Em breve
                        </div>
                      )
                    ) : (
                      <div
                        className={`mt-4 flex w-full items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm ${
                          show.status === "realizado"
                            ? "border border-zinc-700 text-zinc-500"
                            : "bg-zinc-800/50 text-zinc-500"
                        }`}
                      >
                        <FaTicket />
                        {status.label}
                      </div>
                    )}
                  </div>
                </div>
              );
            })}
          </div>
        )}
      </div>
    </section>
  );
}
