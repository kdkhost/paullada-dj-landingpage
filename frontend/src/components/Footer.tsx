"use client";

import { useEffect, useState } from "react";
import { FaChevronUp, FaInstagram, FaWhatsapp, FaYoutube, FaFacebook, FaTiktok, FaSpotify, FaSoundcloud } from "react-icons/fa6";
import { fetchSocialLinks, type SocialLink } from "@/lib/api";

const iconMap: Record<string, React.ReactNode> = {
  FaInstagram: <FaInstagram />,
  FaWhatsapp: <FaWhatsapp />,
  FaYoutube: <FaYoutube />,
  FaFacebook: <FaFacebook />,
  FaTiktok: <FaTiktok />,
  FaSpotify: <FaSpotify />,
  FaSoundcloud: <FaSoundcloud />,
};

const fallbackLinks: SocialLink[] = [
  { id: 1, platform: "Instagram", url: "https://www.instagram.com/paulladadjrj", icon: "FaInstagram" },
  { id: 2, platform: "YouTube", url: "https://www.youtube.com/@paulladadj", icon: "FaYoutube" },
  { id: 3, platform: "Facebook", url: "https://www.facebook.com/paulladadj", icon: "FaFacebook" },
];

function getSocialIcon(link: SocialLink): React.ReactNode {
  if (link.icon && iconMap[link.icon]) return iconMap[link.icon];
  const lower = link.platform.toLowerCase();
  if (lower.includes("instagram")) return <FaInstagram />;
  if (lower.includes("whatsapp")) return <FaWhatsapp />;
  if (lower.includes("youtube")) return <FaYoutube />;
  if (lower.includes("facebook")) return <FaFacebook />;
  if (lower.includes("tiktok")) return <FaTiktok />;
  if (lower.includes("spotify")) return <FaSpotify />;
  if (lower.includes("soundcloud")) return <FaSoundcloud />;
  return <FaInstagram />;
}

export default function Footer() {
  const [socialLinks, setSocialLinks] = useState<SocialLink[]>([]);
  const [showTop, setShowTop] = useState(false);

  useEffect(() => {
    fetchSocialLinks().then(setSocialLinks);
  }, []);

  useEffect(() => {
    const onScroll = () => setShowTop(window.scrollY > 400);
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  const links = socialLinks.length > 0 ? socialLinks : fallbackLinks;

  const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  return (
    <footer className="relative border-t border-white/5 pt-16 pb-8">
      <div className="mx-auto max-w-7xl px-4 md:px-8">
        <div className="flex flex-col items-center gap-8 md:flex-row md:justify-between">
          <div className="text-center md:text-left">
            <a href="#" className="font-heading text-xl tracking-widest text-accent">
              <span className="text-gold">&#9835;</span> Paullada DJ
            </a>
            <p className="mt-2 text-xs tracking-[0.2em] uppercase text-zinc-600">
              O Melhor do Flashback
            </p>
            <p className="mt-1 text-xs text-zinc-700">
              70 &bull; 80 &bull; 90 &bull; 2000
            </p>
          </div>

          <div className="flex gap-3">
            {links.map((link, i) => (
              <a
                key={i}
                href={link.url}
                target="_blank"
                rel="noopener noreferrer"
                className="flex h-10 w-10 items-center justify-center rounded-full border border-zinc-700 text-zinc-400 transition-all hover:border-accent hover:text-accent hover:shadow-lg hover:shadow-accent/20"
                aria-label={link.platform}
              >
                {getSocialIcon(link)}
              </a>
            ))}
          </div>
        </div>

        <div className="mt-12 border-t border-white/5 pt-8 text-center">
          <p className="text-xs text-zinc-600">
            &copy; {new Date().getFullYear()} Paullada DJ. Todos os direitos reservados.
          </p>
          <p className="mt-1 text-xs text-zinc-700">
            Made with &#9835; for the love of music
          </p>
        </div>
      </div>

      <button
        onClick={scrollToTop}
        className={`fixed bottom-8 right-8 z-40 flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-accent to-neon-cyan text-dark shadow-lg shadow-accent/30 transition-all hover:scale-110 hover:shadow-xl hover:shadow-accent/50 ${
          showTop ? "translate-y-0 opacity-100" : "translate-y-4 opacity-0 pointer-events-none"
        }`}
        aria-label="Voltar ao topo"
      >
        <FaChevronUp />
      </button>
    </footer>
  );
}
