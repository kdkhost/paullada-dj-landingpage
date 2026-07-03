"use client";

import { useEffect, useState } from "react";

export default function Preloader() {
  const [hidden, setHidden] = useState(false);

  useEffect(() => {
    const timer = setTimeout(() => setHidden(true), 1500);
    return () => clearTimeout(timer);
  }, []);

  return (
    <div id="preloader" className={hidden ? "hidden" : ""}>
      <div className="flex flex-col items-center gap-6">
        <div className="vinyl-record">
          <div className="vinyl-grooves" />
        </div>
        <div className="flex flex-col items-center gap-2">
          <span className="font-heading text-xl tracking-[0.15em] text-accent animate-pulse-neon">
            PAULLADA DJ
          </span>
          <span className="text-xs tracking-[0.3em] text-zinc-500 uppercase">
            Flashback
          </span>
        </div>
      </div>
    </div>
  );
}
