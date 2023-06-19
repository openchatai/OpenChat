/** @type {import('tailwindcss').Config} */
import { black, transparent,gray } from "tailwindcss/colors";
import TailwindScrollBar from "tailwind-scrollbar";
import TailwindTypography from "@tailwindcss/typography";
export default {
  content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx}"],
  prefix: "ch-",
  theme: {
    extend: {
      keyframes: {
        overlayShow: {
          from: { opacity: 0, blur: '10px' },
          to: { opacity: 1, blur: "4px" },
        },
        contentShow: {
          from: { opacity: 0, blur: "2px", transform: "translateZ(-80px)" },
          to: { opacity: 1, blur: "0px", transform: "translateZ(0px)" },
        },
      },
      animation: {
        overlayShow: "overlayShow 150ms cubic-bezier(0.16, 1, 0.3, 1)",
        contentShow: "contentShow 0.6s cubic-bezier(0.390, 0.575, 0.565, 1.000) both",
      },
    },
    fontFamily: {
      manrope: ["Manrope", "sans-serif"],
    },
    colors: {
      primary: "var(--color-primary)",
      accent: "var(--color-accent)",
      bg: "var(--color-bg)",
      fg: "var(--color-fg)",
      bg_light: "var(--color-bg-light)",
      black,
      transparent,gray
    },
  },
  plugins: [TailwindScrollBar, TailwindTypography],
};
