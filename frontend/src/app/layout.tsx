import type { Metadata, Viewport } from "next";
import { Inter, Orbitron } from "next/font/google";
import Script from "next/script";
import "./globals.css";
import { AnalyticsProvider } from "@/context/AnalyticsContext";
import AnalyticsTracker from "@/components/AnalyticsTracker";

const inter = Inter({
  subsets: ["latin"],
  variable: "--font-inter",
  display: "swap",
});

const orbitron = Orbitron({
  subsets: ["latin"],
  variable: "--font-orbitron",
  display: "swap",
});

export const metadata: Metadata = {
  title: "Paullada DJ - O Melhor do Flashback",
  description: "Músicas que marcaram as décadas de 70, 80, 90 e 2000",
  keywords: ["DJ", "flashback", "Paullada", "música", "70", "80", "90", "2000", "Rio de Janeiro", "festa"],
  authors: [{ name: "Paullada DJ" }],
  openGraph: {
    title: "Paullada DJ - O Melhor do Flashback",
    description: "Músicas que marcaram as décadas de 70, 80, 90 e 2000",
    type: "website",
    locale: "pt_BR",
    siteName: "Paullada DJ",
  },
  twitter: {
    card: "summary_large_image",
    title: "Paullada DJ - O Melhor do Flashback",
    description: "Músicas que marcaram as décadas de 70, 80, 90 e 2000",
  },
  robots: { index: true, follow: true },
};

export const viewport: Viewport = {
  width: "device-width",
  initialScale: 1,
  themeColor: "#0a0a0a",
};

const jsonLd = {
  "@context": "https://schema.org",
  "@type": "Person",
  name: "Paullada DJ",
  description: "DJ especializado em flashback - músicas das décadas de 70, 80, 90 e 2000",
  url: "https://paulladadj.com",
  sameAs: ["https://www.instagram.com/paulladadjrj"],
  jobTitle: "DJ",
  genre: ["Anos 70", "Anos 80", "Anos 90", "Anos 2000"],
  location: { "@type": "Place", name: "Rio de Janeiro" },
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="pt-BR" className={`${inter.variable} ${orbitron.variable}`}>
      <head>
        <Script id="json-ld" type="application/ld+json" strategy="beforeInteractive">
          {JSON.stringify(jsonLd)}
        </Script>
      </head>
      <body className="font-body bg-dark text-white antialiased">
        <script dangerouslySetInnerHTML={{
          __html: `(function(){var p=document.getElementById('preloader');if(p){var h=function(){p.classList.add('hidden')};setTimeout(h,3000);window.addEventListener('load',function(){setTimeout(h,800)})}})()`
        }} />
        <AnalyticsProvider>
          {children}
          <AnalyticsTracker />
        </AnalyticsProvider>
      </body>
    </html>
  );
}
