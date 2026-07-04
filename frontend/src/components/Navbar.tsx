"use client";

import { useState, useEffect } from "react";
import Image from "next/image";
import { FaBars, FaXmark, FaInstagram } from "react-icons/fa6";

const links = [
  { label: "Agenda", href: "#agenda" },
  { label: "Galeria", href: "#galeria" },
  { label: "Contato", href: "#contato" },
];

export default function Navbar() {
  const [open, setOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 50);
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  const handleClick = (href: string) => {
    setOpen(false);
    const el = document.querySelector(href);
    el?.scrollIntoView({ behavior: "smooth" });
  };

  return (
    <nav
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
        scrolled ? "glass shadow-lg shadow-black/20" : "bg-transparent"
      }`}
    >
      <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 md:px-8">
        <a
          href="#"
          className="flex items-center gap-2 text-lg font-heading tracking-widest text-accent"
        >
          <Image src="/images/logo.png" alt="Paullada DJ" width={32} height={32} className="h-8 w-auto" />
          Paullada DJ
        </a>

        <div className="hidden items-center gap-8 md:flex">
          {links.map((link) => (
            <button
              key={link.href}
              onClick={() => handleClick(link.href)}
              className="relative text-sm uppercase tracking-[0.15em] text-zinc-300 transition-colors hover:text-accent after:absolute after:-bottom-1 after:left-0 after:h-px after:w-0 after:bg-accent after:transition-all after:duration-300 hover:after:w-full"
            >
              {link.label}
            </button>
          ))}
          <a
            href="https://www.instagram.com/paulladadjrj"
            target="_blank"
            rel="noopener noreferrer"
            className="flex items-center gap-2 rounded-full bg-gradient-to-r from-logo-gold to-gold px-5 py-2 text-sm font-semibold text-white transition-all hover:shadow-lg hover:shadow-gold/40"
          >
            <FaInstagram />
            Instagram
          </a>
        </div>

        <button
          className="text-2xl text-white md:hidden"
          onClick={() => setOpen(!open)}
          aria-label="Menu"
        >
          {open ? <FaXmark /> : <FaBars />}
        </button>
      </div>

      {open && (
        <div className="glass border-t border-white/5 md:hidden">
          <div className="flex flex-col items-center gap-4 px-4 py-6">
            {links.map((link) => (
              <button
                key={link.href}
                onClick={() => handleClick(link.href)}
                className="text-sm uppercase tracking-[0.15em] text-zinc-300 transition-colors hover:text-accent"
              >
                {link.label}
              </button>
            ))}
            <a
              href="https://www.instagram.com/paulladadjrj"
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center gap-2 rounded-full bg-gradient-to-r from-logo-gold to-gold px-5 py-2 text-sm font-semibold text-white"
            >
              <FaInstagram />
              Instagram
            </a>
          </div>
        </div>
      )}
    </nav>
  );
}
