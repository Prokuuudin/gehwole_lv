import { createNewsSwiper } from "./swipers.js";

export default function newsTabs() {
  const tablist = document.querySelector(".news__tabs");
  if (!tablist) return;

  const tabs = Array.from(tablist.querySelectorAll(".news__tab"));

  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      if (tab.classList.contains("news__tab--active")) return;

      tabs.forEach((current) => {
        const isActive = current === tab;
        current.classList.toggle("news__tab--active", isActive);
        current.setAttribute("aria-selected", String(isActive));

        const panel = document.getElementById(
          current.getAttribute("aria-controls"),
        );
        if (!panel) return;

        // hidden снимается до создания Swiper — иначе ширина слайдов = 0
        panel.hidden = !isActive;

        const swiper = createNewsSwiper(`#${panel.id}`);
        if (!swiper) return;

        if (isActive) {
          swiper.autoplay.start();
        } else {
          swiper.autoplay.stop();
        }
      });
    });
  });
}
