"use client";

import { useEffect, useRef, useState } from "react";
import Image from "next/image";
import { FaMusic, FaHeadphones, FaCompactDisc } from "react-icons/fa6";

const stats = [
  { icon: FaMusic, value: 15, label: "Anos de Carreira", suffix: "+" },
  { icon: FaHeadphones, value: 500, label: "Shows Realizados", suffix: "+" },
  { icon: FaCompactDisc, value: 4, label: "Décadas Tocadas", suffix: "" },
];

function useScrollReveal() {
  const ref = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          el.classList.add("revealed");
        }
      },
      { threshold: 0.15 }
    );
    observer.observe(el);
    return () => observer.disconnect();
  }, []);

  return ref;
}

export default function About() {
  const sectionRef = useScrollReveal();
  const [counters, setCounters] = useState(stats.map(() => 0));

  useEffect(() => {
    const el = sectionRef.current;
    if (!el) return;

    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          stats.forEach((stat, i) => {
            const duration = 2000;
            const steps = 60;
            const increment = stat.value / steps;
            let current = 0;
            const timer = setInterval(() => {
              current += increment;
              if (current >= stat.value) {
                setCounters((prev) => {
                  const next = [...prev];
                  next[i] = stat.value;
                  return next;
                });
                clearInterval(timer);
              } else {
                setCounters((prev) => {
                  const next = [...prev];
                  next[i] = Math.floor(current);
                  return next;
                });
              }
            }, duration / steps);
          });
          observer.disconnect();
        }
      },
      { threshold: 0.15 }
    );
    observer.observe(el);
    return () => observer.disconnect();
  }, [sectionRef]);

  return (
    <section id="sobre" className="relative overflow-hidden py-24 md:py-32">
      <div className="absolute top-0 right-0 h-96 w-96 rounded-full bg-neon-purple/5 blur-[100px]" />

      <div ref={sectionRef} className="scroll-reveal mx-auto max-w-7xl px-4 md:px-8">
        <div className="grid items-center gap-12 md:grid-cols-2 md:gap-16">
          <div>
            <span className="text-xs tracking-[0.3em] uppercase text-accent">Sobre</span>
            <h2 className="mt-3 font-heading text-3xl font-bold text-white md:text-5xl">
              O DJ que faz
              <br />
              <span className="text-shadow-primary text-primary">você viajar</span> no tempo
            </h2>
            <div className="mt-6 space-y-4 text-zinc-400 leading-relaxed">
              <p>
                Com mais de 15 anos de estrada, Paullada DJ é referência quando o assunto
                é flashback no Rio de Janeiro. Especialista em transformar qualquer festa
                em uma verdadeira máquina do tempo musical.
              </p>
              <p>
                Dos grooves dos anos 70 aos hits dos anos 2000, cada set é cuidadosamente
                preparado para fazer você dançar do começo ao fim. Música de qualidade
                que atravessa gerações.
              </p>
              <p>
                Já passou pelos palcos e pistas mais importantes da cidade, levando
                energia e nostalgia para milhares de pessoas. Cada apresentação é única.
              </p>
            </div>
          </div>

          <div className="relative flex items-center justify-center">
            <div className="relative h-80 w-80 md:h-96 md:w-96">
              <div className="absolute inset-0 rounded-2xl border border-neon-purple/20 bg-gradient-to-br from-neon-purple/10 to-transparent animate-glow" />
              <div className="absolute inset-4 flex items-center justify-center rounded-2xl bg-gradient-to-br from-dark-2 to-dark-3">
                <div className="flex flex-col items-center gap-4 text-center">
                  <div className="flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-logo-gold to-gold shadow-lg shadow-gold/30">
                    <Image src="/images/logo.png" alt="Paullada DJ" width={80} height={80} className="h-20 w-20 rounded-full object-cover" />
                  </div>
                  <div>
                    <p className="font-heading text-2xl font-bold text-accent">Paullada</p>
                    <p className="text-xs tracking-[0.2em] uppercase text-zinc-500">Flashback desde 2010</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="mt-16 grid grid-cols-1 gap-6 sm:grid-cols-3 md:mt-24">
          {stats.map((stat, i) => {
            const Icon = stat.icon;
            return (
              <div
                key={stat.label}
                className="glass-card flex flex-col items-center rounded-2xl p-8 text-center"
              >
                <Icon className="mb-4 text-3xl text-gold" />
                <div className="flex items-baseline gap-1">
                  <span className="font-heading text-4xl font-bold text-white">
                    {counters[i]}
                  </span>
                  <span className="text-lg text-accent">{stat.suffix}</span>
                </div>
                <p className="mt-2 text-sm uppercase tracking-[0.15em] text-zinc-500">
                  {stat.label}
                </p>
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
}
