function placeholderLinks() {
  document.querySelectorAll('a[href="#"]').forEach((link) => {
    link.addEventListener("click", (e) => e.preventDefault());
  });
}

export default placeholderLinks;
