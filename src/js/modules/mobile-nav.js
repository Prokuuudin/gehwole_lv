function mobileNav() {
  const navBtn = document.querySelector(".mobile-nav-btn");
  const nav = document.querySelector(".mobile-nav");
  const menuIcon = document.querySelector(".nav-icon");
  const navLinks = document.querySelectorAll(".mobile-nav__link");
  const subToggles = nav.querySelectorAll(".mobile-nav__toggle");

  const closeMenu = () => {
    nav.classList.remove("mobile-nav--open");
    menuIcon.classList.remove("nav-icon--active");
    document.body.classList.remove("no-scroll");
    nav.querySelectorAll(".mobile-nav__item--open").forEach((item) => {
      item.classList.remove("mobile-nav__item--open");
    });
  };

  navBtn.onclick = function () {
    nav.classList.toggle("mobile-nav--open");
    menuIcon.classList.toggle("nav-icon--active");
    document.body.classList.toggle("no-scroll");
  };

  subToggles.forEach((toggle) => {
    toggle.addEventListener("click", () => {
      toggle.parentElement.classList.toggle("mobile-nav__item--open");
    });
  });

  navLinks.forEach((link) => {
    link.addEventListener("click", closeMenu);
  });

  document.addEventListener("click", (e) => {
    const clickedInsideNav = nav.contains(e.target);
    const clickedOnBtn = navBtn.contains(e.target);

    if (!clickedInsideNav && !clickedOnBtn) {
      closeMenu();
    }
  });
}

export default mobileNav;
