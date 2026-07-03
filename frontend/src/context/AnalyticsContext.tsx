"use client";

import { createContext, useContext, useCallback, type ReactNode } from "react";
import { trackEvent } from "@/lib/api";

interface AnalyticsContextType {
  track: (tipo: string, pagina: string, detalhes?: string) => void;
}

const AnalyticsContext = createContext<AnalyticsContextType>({
  track: () => {},
});

export function AnalyticsProvider({ children }: { children: ReactNode }) {
  const track = useCallback((tipo: string, pagina: string, detalhes?: string) => {
    trackEvent(tipo, pagina, detalhes);
  }, []);

  return (
    <AnalyticsContext.Provider value={{ track }}>
      {children}
    </AnalyticsContext.Provider>
  );
}

export function useAnalytics() {
  return useContext(AnalyticsContext);
}
