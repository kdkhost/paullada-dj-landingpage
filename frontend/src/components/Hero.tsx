"use client";

import { FaInstagram, FaMusic } from "react-icons/fa6";
import { useAnalytics } from "@/context/AnalyticsContext";

export default function Hero() {
  const { track } = useAnalytics();

  return (
    <section className="relative flex min-h-screen items-center justify-center overflow-hidden">
      <div className="absolute inset-0 bg-gradient-to-br from-dark via-dark-2 to-dark-3 animate-gradient" />
      <div className="absolute inset-0 opacity-30">
        <div className="absolute top-1/4 left-1/4 h-64 w-64 rounded-full bg-neon-purple/20 blur-[128px] animate-float" />
        <div className="absolute bottom-1/4 right-1/4 h-80 w-80 rounded-full bg-accent/10 blur-[128px] animate-float" style={{ animationDelay: "-3s" }} />
      </div>

      <div className="relative z-10 mx-auto flex max-w-5xl flex-col items-center px-4 text-center">
        <div className="mb-6 flex items-center gap-3 text-2xl text-gold animate-fade-in">
          <FaMusic />
          <span className="text-xs tracking-[0.3em] uppercase text-zinc-500">Flashback</span>
          <FaMusic />
        </div>

        <h1 className="animate-slide-up mb-4 font-heading text-4xl font-bold tracking-[0.05em] text-white md:text-7xl lg:text-8xl">
          <span className="text-shadow-neon text-accent">PAULLADA</span>
          <br />
          <span className="text-shadow-primary text-primary">DJ</span>
        </h1>

        <p className="animate-fade-in mb-2 max-w-2xl text-lg leading-relaxed text-zinc-400 md:text-xl" style={{ animationDelay: "0.3s" }}>
          O Melhor do Flashback
        </p>

        <div className="animate-fade-in mb-10 flex flex-wrap justify-center gap-3" style={{ animationDelay: "0.5s" }}>
          {["70", "80", "90", "2000"].map((decade, i) => (
            <span
              key={decade}
              className={`rounded-full border px-5 py-1.5 text-sm font-semibold tracking-wider ${
                i % 2 === 0
                  ? "border-neon-cyan/40 text-neon-cyan"
                  : "border-neon-purple/40 text-neon-purple"
              }`}
            >
              &#8226; {decade}
            </span>
          ))}
        </div>

        <a
          href="https://www.instagram.com/paulladadjrj"
          target="_blank"
          rel="noopener noreferrer"
          onClick={() => track("clique", "hero", "instagram")}
          className="animate-fade-in flex items-center gap-3 rounded-full bg-gradient-to-r from-neon-orange to-secondary px-8 py-4 text-base font-bold text-white shadow-lg shadow-secondary/30 transition-all hover:scale-105 hover:shadow-xl hover:shadow-secondary/50"
          style={{ animationDelay: "0.7s" }}
        >
          <FaInstagram className="text-xl" />
          Siga no Instagram
        </a>
      </div>

      <div className="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <div className="h-10 w-6 rounded-full border-2 border-zinc-600 flex items-start justify-center p-1">
          <div className="h-2 w-1 rounded-full bg-accent animate-pulse-neon" />
        </div>
      </div>
    </section>
  );
}
