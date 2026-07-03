"use client";

import { useEffect } from "react";
import { useAnalytics } from "@/context/AnalyticsContext";

export default function AnalyticsTracker() {
  const { track } = useAnalytics();

  useEffect(() => {
    track("pageview", window.location.pathname);
  }, [track]);

  return null;
}
