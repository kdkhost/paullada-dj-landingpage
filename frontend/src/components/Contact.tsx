"use client";

import { useState, useEffect } from "react";
import { FaEnvelope, FaPhone, FaUser, FaWhatsapp, FaPaperPlane } from "react-icons/fa6";
import { fetchSocialLinks, fetchSettings, type SocialLink, type SiteSettings } from "@/lib/api";
import { useAnalytics } from "@/context/AnalyticsContext";

import {
  FaInstagram,
  FaYoutube,
  FaFacebook,
  FaTiktok,
  FaSpotify,
  FaSoundcloud,
} from "react-icons/fa6";

const iconMap: Record<string, React.ReactNode> = {
  FaInstagram: <FaInstagram />,
  FaWhatsapp: <FaWhatsapp />,
  FaYoutube: <FaYoutube />,
  FaFacebook: <FaFacebook />,
  FaTiktok: <FaTiktok />,
  FaSpotify: <FaSpotify />,
  FaSoundcloud: <FaSoundcloud />,
};

export default function Contact() {
  const [socialLinks, setSocialLinks] = useState<SocialLink[]>([]);
  const [settings, setSettings] = useState<SiteSettings | null>(null);
  const [form, setForm] = useState({ nome: "", email: "", telefone: "", mensagem: "" });
  const [sending, setSending] = useState(false);
  const [sent, setSent] = useState(false);
  const [error, setError] = useState("");
  const { track } = useAnalytics();

  useEffect(() => {
    fetchSocialLinks().then(setSocialLinks);
    fetchSettings().then(setSettings);
  }, []);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!form.nome.trim() || !form.mensagem.trim()) {
      setError("Preencha nome e mensagem.");
      return;
    }
    setSending(true);
    setError("");
    try {
      const res = await fetch("/api/contact", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(form),
      });
      if (!res.ok) throw new Error("Erro ao enviar");
      setSent(true);
      setForm({ nome: "", email: "", telefone: "", mensagem: "" });
      track("contato", "formulario", "enviado");
    } catch {
      setError("Erro ao enviar mensagem. Tente novamente.");
    } finally {
      setSending(false);
    }
  };

  const whatsappNumber = settings?.whatsapp || "5521999999999";
  const whatsappMsg = encodeURIComponent("Olá Paullada DJ! Gostaria de mais informações.");
  const whatsappUrl = `https://wa.me/${whatsappNumber.replace(/\D/g, "")}?text=${whatsappMsg}`;

  return (
    <section id="contato" className="py-24 md:py-32">
      <div className="mx-auto max-w-7xl px-4 md:px-8">
        <div className="mb-12 text-center">
          <span className="text-xs tracking-[0.3em] uppercase text-accent">Contato</span>
          <h2 className="mt-3 font-heading text-3xl font-bold text-white md:text-5xl">
            Vamos <span className="text-shadow-primary text-primary">Conversar</span>
          </h2>
          <p className="mt-3 text-zinc-500">
            Quer contratar o Paullada DJ para o seu evento? Entre em contato!
          </p>
        </div>

        <div className="grid gap-8 lg:grid-cols-2 lg:gap-16">
          <div>
            <a
              href={whatsappUrl}
              target="_blank"
              rel="noopener noreferrer"
              onClick={() => track("clique", "contato", "whatsapp-banner")}
              className="mb-8 flex items-center gap-4 rounded-2xl bg-gradient-to-r from-green-600/20 to-green-700/10 p-6 border border-green-500/20 transition-all hover:border-green-500/40 hover:shadow-lg hover:shadow-green-500/10"
            >
              <div className="flex h-14 w-14 items-center justify-center rounded-full bg-green-500 text-2xl text-white">
                <FaWhatsapp />
              </div>
              <div>
                <p className="font-heading text-lg font-bold text-green-400">
                  Fale pelo WhatsApp
                </p>
                <p className="text-sm text-zinc-400">Clique e mande uma mensagem agora!</p>
              </div>
            </a>

            <form onSubmit={handleSubmit} className="glass-card space-y-5 rounded-2xl p-6 md:p-8">
              <h3 className="font-heading text-lg font-bold text-white">
                Envie sua mensagem
              </h3>

              <div className="relative">
                <FaUser className="absolute left-4 top-1/2 -translate-y-1/2 text-zinc-500" />
                <input
                  type="text"
                  placeholder="Seu nome"
                  value={form.nome}
                  onChange={(e) => setForm({ ...form, nome: e.target.value })}
                  className="w-full rounded-xl border border-zinc-700 bg-dark/50 py-3 pl-11 pr-4 text-sm text-white placeholder-zinc-600 outline-none transition-colors focus:border-accent focus:ring-1 focus:ring-accent/50"
                />
              </div>

              <div className="relative">
                <FaEnvelope className="absolute left-4 top-1/2 -translate-y-1/2 text-zinc-500" />
                <input
                  type="email"
                  placeholder="Seu e-mail"
                  value={form.email}
                  onChange={(e) => setForm({ ...form, email: e.target.value })}
                  className="w-full rounded-xl border border-zinc-700 bg-dark/50 py-3 pl-11 pr-4 text-sm text-white placeholder-zinc-600 outline-none transition-colors focus:border-accent focus:ring-1 focus:ring-accent/50"
                />
              </div>

              <div className="relative">
                <FaPhone className="absolute left-4 top-1/2 -translate-y-1/2 text-zinc-500" />
                <input
                  type="tel"
                  placeholder="Seu telefone"
                  value={form.telefone}
                  onChange={(e) => setForm({ ...form, telefone: e.target.value })}
                  className="w-full rounded-xl border border-zinc-700 bg-dark/50 py-3 pl-11 pr-4 text-sm text-white placeholder-zinc-600 outline-none transition-colors focus:border-accent focus:ring-1 focus:ring-accent/50"
                />
              </div>

              <div className="relative">
                <textarea
                  placeholder="Sua mensagem"
                  rows={4}
                  value={form.mensagem}
                  onChange={(e) => setForm({ ...form, mensagem: e.target.value })}
                  className="w-full resize-none rounded-xl border border-zinc-700 bg-dark/50 py-3 px-4 text-sm text-white placeholder-zinc-600 outline-none transition-colors focus:border-accent focus:ring-1 focus:ring-accent/50"
                />
              </div>

              {error && (
                <p className="text-sm text-red-400">{error}</p>
              )}

              {sent ? (
                <div className="rounded-xl bg-green-500/10 p-4 text-center text-sm text-green-400">
                  Mensagem enviada com sucesso! Entraremos em contato em breve.
                </div>
              ) : (
                <button
                  type="submit"
                  disabled={sending}
                  className="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-accent to-neon-cyan px-6 py-3 text-sm font-bold text-dark transition-all hover:shadow-lg hover:shadow-accent/30 disabled:opacity-60"
                >
                  {sending ? (
                    <div className="h-5 w-5 animate-spin rounded-full border-2 border-dark border-t-transparent" />
                  ) : (
                    <>
                      <FaPaperPlane />
                      Enviar Mensagem
                    </>
                  )}
                </button>
              )}
            </form>
          </div>

          <div className="flex flex-col gap-6">
            <div className="glass-card rounded-2xl p-6 md:p-8">
              <h3 className="mb-6 font-heading text-lg font-bold text-white">
                Redes Sociais
              </h3>
              <div className="grid grid-cols-2 gap-3">
                {socialLinks.length > 0 ? (
                  socialLinks.map((link) => (
                    <a
                      key={link.id}
                      href={link.url}
                      target="_blank"
                      rel="noopener noreferrer"
                      onClick={() => track("clique", "contato", link.platform)}
                      className="flex items-center gap-3 rounded-xl border border-zinc-700/50 p-3 text-sm text-zinc-300 transition-all hover:border-accent/30 hover:text-accent"
                    >
                      <span className="text-lg">
                        {link.icon && iconMap[link.icon]
                          ? iconMap[link.icon]
                          : <FaInstagram />}
                      </span>
                      {link.platform}
                    </a>
                  ))
                ) : (
                  <>
                    <a
                      href="https://www.instagram.com/paulladadjrj"
                      target="_blank"
                      rel="noopener noreferrer"
                      className="flex items-center gap-3 rounded-xl border border-zinc-700/50 p-3 text-sm text-zinc-300 transition-all hover:border-accent/30 hover:text-accent"
                    >
                      <FaInstagram className="text-lg text-logo-gold" />
                      Instagram
                    </a>
                    <a
                      href={whatsappUrl}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="flex items-center gap-3 rounded-xl border border-zinc-700/50 p-3 text-sm text-zinc-300 transition-all hover:border-green-500/30 hover:text-green-400"
                    >
                      <FaWhatsapp className="text-lg text-green-500" />
                      WhatsApp
                    </a>
                    <a
                      href="https://www.youtube.com/@paulladadj"
                      target="_blank"
                      rel="noopener noreferrer"
                      className="flex items-center gap-3 rounded-xl border border-zinc-700/50 p-3 text-sm text-zinc-300 transition-all hover:border-red-500/30 hover:text-red-400"
                    >
                      <FaYoutube className="text-lg text-red-500" />
                      YouTube
                    </a>
                    <a
                      href="https://www.facebook.com/paulladadj"
                      target="_blank"
                      rel="noopener noreferrer"
                      className="flex items-center gap-3 rounded-xl border border-zinc-700/50 p-3 text-sm text-zinc-300 transition-all hover:border-blue-500/30 hover:text-blue-400"
                    >
                      <FaFacebook className="text-lg text-blue-500" />
                      Facebook
                    </a>
                  </>
                )}
              </div>
            </div>

            <div className="glass-card rounded-2xl p-6 md:p-8">
              <h3 className="mb-4 font-heading text-lg font-bold text-white">
                Informações
              </h3>
              <div className="space-y-4 text-sm text-zinc-400">
                {settings?.email && (
                  <div className="flex items-center gap-3">
                    <FaEnvelope className="shrink-0 text-accent" />
                    <a href={`mailto:${settings.email}`} className="hover:text-accent transition-colors">
                      {settings.email}
                    </a>
                  </div>
                )}
                {settings?.phone && (
                  <div className="flex items-center gap-3">
                    <FaPhone className="shrink-0 text-accent" />
                    <span>{settings.phone}</span>
                  </div>
                )}
                {settings?.instagram && (
                  <div className="flex items-center gap-3">
                    <FaInstagram className="shrink-0 text-logo-gold" />
                    <a
                      href={`https://instagram.com/${settings.instagram.replace("@", "")}`}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="hover:text-logo-gold transition-colors"
                    >
                      {settings.instagram}
                    </a>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
