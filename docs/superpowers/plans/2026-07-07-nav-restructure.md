# Nav Restructure + Risinājumi Removal — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Новое меню (Ražotājs / Produkti+мега-меню / Informācija un jaunumi / Kontakti) на десктопе и в мобильном оверлее; полное удаление секции Risinājumi.

**Architecture:** Одностраничный gulp-лендинг (gulp-file-include для HTML, SCSS с глобом `./blocks/*.scss`, webpack-бандл JS). Мега-меню — чистый CSS (`:hover`/`:focus-within`), позиционируется от `.header__row`. Мобильные подуровни — аккордеон на `max-height`, тогглы в `mobile-nav.js`.

**Tech Stack:** gulp 4, SCSS, vanilla JS (ES modules → webpack-stream), без фреймворков.

**Спека:** `docs/superpowers/specs/2026-07-07-nav-restructure-design.md`

## Global Constraints

- Тексты пунктов меню — латышские, копировать точно, включая `GEHWOL MED®` (символ `®`) и диакритику (`Kosmētika`, `Spiedienu uz pēdām mazinoši līdzekļi`, `Rotējošie instrumenti` и т.д.).
- Все ссылки дерева Produkti — заглушки `href="#"`.
- HTML/SCSS — отступы табами (как в существующих файлах), JS — 2 пробела.
- Тестового фреймворка нет: проверка = сборка gulp-тасков + grep собранного вывода + ручная проверка в браузере.
- Цвета/шрифты — только существующие CSS-переменные (`--accent`, `--accent-dark`, `--accent-tint`, `--page-bg`, `--font-accent`).
- Брейкпоинт скрытия десктоп-меню — `tablet-small` (990px), как сейчас у `.nav__list`.

---

### Task 1: Десктоп — новое меню + мега-панель

**Files:**
- Modify: `src/html/blocks/header.html` (весь файл)
- Modify: `src/scss/blocks/_header.scss` (добавить `position: relative` в `.header__row`, дописать блок мега-меню в конец)

**Interfaces:**
- Produces: классы `nav__item--mega`, `nav__arrow`, `mega-menu`, `mega-menu__col`, `mega-menu__title`, `mega-menu__list`, `mega-menu__link`, `mega-menu__subtitle`, `mega-menu__sublist`. Task 4 проверяет их в собранном CSS.

- [ ] **Step 1: Переписать `src/html/blocks/header.html`**

Полное новое содержимое файла:

```html
<header class="header" id="header">
	<div class="container">
		<div class="header__row">
			<a href="index.html" class="logo">
				<svg class="icon icon--logo">
						<use href="./img/svgsprite/sprite.symbol.svg#logo"></use>
				</svg>

			</a>
			<nav class="header__nav nav">
				<ul class="nav__list">
					<li><a href="index.html#about" class="nav__link">Ražotājs</a></li>
					<li class="nav__item--mega">
						<a href="index.html#products" class="nav__link">Produkti<span class="nav__arrow"></span></a>
						<div class="mega-menu">
							<div class="mega-menu__col">
								<a href="#" class="mega-menu__title">Kosmētika</a>
								<ul class="mega-menu__list">
									<li><a href="#" class="mega-menu__link">GEHWOL Classic</a></li>
									<li><a href="#" class="mega-menu__link">GEHWOL MED®</a></li>
									<li><a href="#" class="mega-menu__link">GEHWOL FUSSKRAFT</a></li>
									<li><a href="#" class="mega-menu__link">GEHWOL FUSSKRAFT Soft Feet</a></li>
									<li><a href="#" class="mega-menu__link">GEHWOL PROFESSIONAL</a></li>
									<li><a href="#" class="mega-menu__link">GERLASAN</a></li>
									<li><a href="#" class="mega-menu__link">GERLAVIT</a></li>
								</ul>
							</div>
							<div class="mega-menu__col">
								<a href="#" class="mega-menu__title">Spiedienu uz pēdām mazinoši līdzekļi</a>
								<ul class="mega-menu__list">
									<li><a href="#" class="mega-menu__link">Polimēra gēla izstrādājumi, pārvilkti ar tekstilu</a></li>
									<li><a href="#" class="mega-menu__link">Polimēra gēla izstrādājumi</a></li>
									<li><a href="#" class="mega-menu__link">Plāksteri</a></li>
									<li><a href="#" class="mega-menu__link">Filca izstrādājumi</a></li>
								</ul>
							</div>
							<div class="mega-menu__col">
								<a href="#" class="mega-menu__title">Tehnika</a>
								<ul class="mega-menu__list">
									<li><a href="#" class="mega-menu__link">Pēdu kopšanas aparāti</a></li>
									<li><a href="#" class="mega-menu__link">Pacienta krēsli</a></li>
									<li><a href="#" class="mega-menu__link">Darbinieka krēsli</a></li>
									<li>
										<a href="#" class="mega-menu__subtitle">Rotējošie instrumenti</a>
										<ul class="mega-menu__sublist">
											<li><a href="#" class="mega-menu__link">Keramiskās frēzes</a></li>
											<li><a href="#" class="mega-menu__link">Pulētāji</a></li>
											<li><a href="#" class="mega-menu__link">Vienreizlietojamās smilšpapīra frēzes</a></li>
										</ul>
									</li>
								</ul>
							</div>
						</div>
					</li>
					<li><a href="index.html#news" class="nav__link">Informācija un jaunumi</a></li>
					<li><a href="index.html#contacts" class="nav__link">Kontakti</a></li>
				</ul>
			</nav>

			<button class="mobile-nav-btn" id="mobile-nav-btn">
				<span class="nav-icon"></span>
			</button>
		</div>
	</div>
</header>

@@include('mobile-nav.html')
```

- [ ] **Step 2: Обновить `src/scss/blocks/_header.scss`**

В `.header__row` добавить первой строкой `position: relative;`:

```scss
.header__row {
	position: relative;
	display: flex;
	justify-content: space-between;
	align-items: center;

	@include mobile {
		gap: 12px;
	}
}
```

В конец файла дописать:

```scss
/* Mega menu (Produkti) */

.nav__arrow {
	display: inline-block;
	margin-left: 7px;
	width: 7px;
	height: 7px;
	border-right: 2px solid currentColor;
	border-bottom: 2px solid currentColor;
	transform: rotate(45deg) translate(-1px, -1px);
}

.mega-menu {
	position: absolute;
	top: calc(100% + 18px);
	left: 0;
	right: 0;
	z-index: 50;

	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 32px;
	padding: 28px 32px 32px;

	background-color: var(--page-bg);
	border: 1px solid var(--accent-tint);
	border-top: none;
	border-radius: 0 0 12px 12px;
	box-shadow: 0 18px 40px rgba(0, 0, 0, 0.12);

	opacity: 0;
	visibility: hidden;
	transform: translateY(8px);
	transition: opacity 0.2s ease, transform 0.2s ease, visibility 0s linear 0.25s;

	/* невидимый мост между пунктом меню и панелью, чтобы hover не рвался */
	&::before {
		content: "";
		position: absolute;
		left: 0;
		right: 0;
		bottom: 100%;
		height: 28px;
	}

	@include tablet-small {
		display: none;
	}
}

.header--elevated .mega-menu {
	top: calc(100% + 10px);
}

.nav__item--mega:hover .mega-menu,
.nav__item--mega:focus-within .mega-menu {
	opacity: 1;
	visibility: visible;
	transform: translateY(0);
	transition-delay: 0s;
}

.mega-menu__title {
	display: inline-block;
	margin-bottom: 14px;
	font-family: var(--font-accent);
	font-size: 14px;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.04em;
	color: var(--accent-dark);

	&:hover {
		color: var(--accent);
	}
}

.mega-menu__list {
	display: grid;
	gap: 9px;
}

.mega-menu__link {
	font-size: 14px;
	line-height: 1.35;
	color: var(--text-color);

	&:hover {
		color: var(--accent);
	}
}

.mega-menu__subtitle {
	display: inline-block;
	margin-top: 6px;
	font-family: var(--font-accent);
	font-size: 13px;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.04em;
	color: var(--accent-dark);

	&:hover {
		color: var(--accent);
	}
}

.mega-menu__sublist {
	display: grid;
	gap: 9px;
	margin-top: 9px;
	padding-left: 14px;
	border-left: 2px solid var(--accent-tint);
}
```

- [ ] **Step 3: Собрать HTML и CSS**

Run: `npx gulp html:dev` затем `npx gulp sass:dev`
Expected: обе таски завершаются без ошибок (`Finished 'html:dev'`, `Finished 'sass:dev'`).

- [ ] **Step 4: Проверить вывод**

Run (Git Bash): `grep -c "mega-menu" build/index.html build/css/main.css`
Expected: в обоих файлах счёт > 0. В `build/index.html` нет строки `Risinājumi` внутри `<nav`-блока шапки (секция пока остаётся — её удаляет Task 3).

- [ ] **Step 5: Commit**

```bash
git add src/html/blocks/header.html src/scss/blocks/_header.scss
git commit -m "feat: rebuild desktop nav with Produkti mega menu"
```

---

### Task 2: Мобильное меню — аккордеон 3 уровней

**Files:**
- Modify: `src/html/blocks/mobile-nav.html` (весь файл)
- Modify: `src/scss/blocks/_mobile-nav.scss` (весь файл)
- Modify: `src/js/modules/mobile-nav.js`
- Modify: `src/js/index.js` (подключить placeholder-links)
- Create: `src/js/modules/placeholder-links.js`

**Interfaces:**
- Consumes: ничего из Task 1 (независим).
- Produces: классы `mobile-nav__item`, `mobile-nav__toggle`, `mobile-nav__sub`, `mobile-nav__sublink`, модификатор `mobile-nav__item--open`; модуль `placeholderLinks()` (default export, без аргументов) — гасит переходы по `a[href="#"]` по всему документу (мега-меню и мобильные заглушки).

- [ ] **Step 1: Переписать `src/html/blocks/mobile-nav.html`**

```html
<div class="mobile-nav">
	<ul class="mobile-nav__list">
		<li><a href="#about" class="mobile-nav__link">Ražotājs</a></li>
		<li class="mobile-nav__item">
			<button type="button" class="mobile-nav__toggle">Produkti</button>
			<ul class="mobile-nav__sub">
				<li class="mobile-nav__item">
					<button type="button" class="mobile-nav__toggle">Kosmētika</button>
					<ul class="mobile-nav__sub">
						<li><a href="#" class="mobile-nav__sublink">GEHWOL Classic</a></li>
						<li><a href="#" class="mobile-nav__sublink">GEHWOL MED®</a></li>
						<li><a href="#" class="mobile-nav__sublink">GEHWOL FUSSKRAFT</a></li>
						<li><a href="#" class="mobile-nav__sublink">GEHWOL FUSSKRAFT Soft Feet</a></li>
						<li><a href="#" class="mobile-nav__sublink">GEHWOL PROFESSIONAL</a></li>
						<li><a href="#" class="mobile-nav__sublink">GERLASAN</a></li>
						<li><a href="#" class="mobile-nav__sublink">GERLAVIT</a></li>
					</ul>
				</li>
				<li class="mobile-nav__item">
					<button type="button" class="mobile-nav__toggle">Spiedienu uz pēdām mazinoši līdzekļi</button>
					<ul class="mobile-nav__sub">
						<li><a href="#" class="mobile-nav__sublink">Polimēra gēla izstrādājumi, pārvilkti ar tekstilu</a></li>
						<li><a href="#" class="mobile-nav__sublink">Polimēra gēla izstrādājumi</a></li>
						<li><a href="#" class="mobile-nav__sublink">Plāksteri</a></li>
						<li><a href="#" class="mobile-nav__sublink">Filca izstrādājumi</a></li>
					</ul>
				</li>
				<li class="mobile-nav__item">
					<button type="button" class="mobile-nav__toggle">Tehnika</button>
					<ul class="mobile-nav__sub">
						<li><a href="#" class="mobile-nav__sublink">Pēdu kopšanas aparāti</a></li>
						<li><a href="#" class="mobile-nav__sublink">Pacienta krēsli</a></li>
						<li><a href="#" class="mobile-nav__sublink">Darbinieka krēsli</a></li>
						<li class="mobile-nav__item">
							<button type="button" class="mobile-nav__toggle">Rotējošie instrumenti</button>
							<ul class="mobile-nav__sub">
								<li><a href="#" class="mobile-nav__sublink">Keramiskās frēzes</a></li>
								<li><a href="#" class="mobile-nav__sublink">Pulētāji</a></li>
								<li><a href="#" class="mobile-nav__sublink">Vienreizlietojamās smilšpapīra frēzes</a></li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>
		</li>
		<li><a href="#news" class="mobile-nav__link">Informācija un jaunumi</a></li>
		<li><a href="#contacts" class="mobile-nav__link">Kontakti</a></li>
	</ul>
</div>
```

Важно: тогглы — `<button>` без класса `mobile-nav__link` (иначе JS закроет оверлей при тапе по тогглу).

- [ ] **Step 2: Переписать `src/scss/blocks/_mobile-nav.scss`**

```scss
.mobile-nav {
	position: fixed;
	top: -100%;
	width: 100%;
	height: 100%;
	z-index: 99;

	display: flex;
	flex-direction: column;
	align-items: center;
	padding: 40px var(--container-padding);
	background: var(--accent);
	transition: top 0.2s ease-in;
	overflow-y: auto;
}

.mobile-nav--open {
	top: 0;
}

.mobile-nav__link {
	color: #fff;
	text-transform: uppercase;
	letter-spacing: 0.04em;

	&:hover {
		opacity: 0.8;
	}
}

.mobile-nav__list {
	/* margin: auto вместо justify-content: center — центрирует, но не
	   обрезает верх, когда раскрытый аккордеон выше экрана */
	margin: auto;
	width: 100%;
	display: flex;
	flex-direction: column;
	align-items: center;
	row-gap: 20px;
	font-size: 24px;
}

.mobile-nav__item {
	width: 100%;
	text-align: center;
}

.mobile-nav__toggle {
	position: relative;
	padding-right: 22px;
	color: #fff;
	text-transform: uppercase;
	letter-spacing: 0.04em;

	&::after {
		content: "";
		position: absolute;
		right: 2px;
		top: 50%;
		width: 8px;
		height: 8px;
		border-right: 2px solid currentColor;
		border-bottom: 2px solid currentColor;
		transform: translateY(-75%) rotate(45deg);
		transition: transform 0.2s ease;
	}

	&:hover {
		opacity: 0.8;
	}
}

.mobile-nav__item--open > .mobile-nav__toggle::after {
	transform: translateY(-25%) rotate(225deg);
}

.mobile-nav__sub {
	overflow: hidden;
	max-height: 0;
	transition: max-height 0.3s ease;
	font-size: 18px;

	li {
		margin-top: 14px;
	}

	.mobile-nav__sub {
		font-size: 16px;
	}
}

.mobile-nav__item--open > .mobile-nav__sub {
	max-height: 1200px;
}

.mobile-nav__sublink {
	color: #fff;
	line-height: 1.3;

	&:hover {
		opacity: 0.8;
	}
}
```

- [ ] **Step 3: Обновить `src/js/modules/mobile-nav.js`**

Полное новое содержимое:

```js
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
```

- [ ] **Step 4: Создать `src/js/modules/placeholder-links.js`**

```js
function placeholderLinks() {
  document.querySelectorAll('a[href="#"]').forEach((link) => {
    link.addEventListener("click", (e) => e.preventDefault());
  });
}

export default placeholderLinks;
```

- [ ] **Step 5: Подключить в `src/js/index.js`**

Добавить в конец файла:

```js
import placeholderLinks from "./modules/placeholder-links.js";
placeholderLinks();
```

- [ ] **Step 6: Собрать**

Run: `npx gulp html:dev`, затем `npx gulp sass:dev`, затем `npx gulp js:dev`
Expected: все три завершаются `Finished`, без ошибок webpack/sass.

- [ ] **Step 7: Проверить вывод**

Run (Git Bash): `grep -c "mobile-nav__toggle" build/index.html build/css/main.css build/js/index.bundle.js`
Expected: счёт > 0 во всех трёх файлах.

- [ ] **Step 8: Commit**

```bash
git add src/html/blocks/mobile-nav.html src/scss/blocks/_mobile-nav.scss src/js/modules/mobile-nav.js src/js/modules/placeholder-links.js src/js/index.js
git commit -m "feat: 3-level accordion in mobile nav"
```

---

### Task 3: Удалить секцию Risinājumi

**Files:**
- Modify: `src/html/index.html` (убрать include)
- Delete: `src/html/blocks/solutions.html`
- Delete: `src/scss/blocks/_solutions.scss`
- Modify: `src/scss/base/_base.scss` (убрать `#solutions` из селектора)
- Modify: `src/js/modules/scrollReveal.js` (убрать `.solutions__item`)

**Interfaces:**
- Consumes: ничего. Меню после Task 1/2 уже не ссылается на `#solutions`.
- Produces: сборка без каких-либо упоминаний `solutions` — проверяет Task 4.

- [ ] **Step 1: Убрать include из `src/html/index.html`**

Удалить строку:

```html
		@@include('blocks/solutions.html')
```

- [ ] **Step 2: Удалить файлы блока**

```bash
git rm src/html/blocks/solutions.html src/scss/blocks/_solutions.scss
```

(`main.scss` подключает блоки глобом `./blocks/*.scss` — правки там не нужны.)

- [ ] **Step 3: Обновить `src/scss/base/_base.scss`**

Было:

```scss
#about,
#news,
#products,
#solutions,
#contacts {
```

Стало:

```scss
#about,
#news,
#products,
#contacts {
```

- [ ] **Step 4: Обновить `src/js/modules/scrollReveal.js`**

Было (строка ~60):

```js
  ScrollReveal().reveal(
    `.news__slide, .products__slide, .solutions__item, .btn-link`,
    {
```

Стало:

```js
  ScrollReveal().reveal(`.news__slide, .products__slide, .btn-link`, {
```

(закрывающие скобки вызова привести в соответствие — один аргумент-объект остаётся без изменений).

- [ ] **Step 5: Собрать**

Run: `npx gulp html:dev`, затем `npx gulp sass:dev`, затем `npx gulp js:dev`
Expected: без ошибок; sass-глоб не падает на удалённом файле.

- [ ] **Step 6: Commit**

```bash
git add src/html/index.html src/scss/base/_base.scss src/js/modules/scrollReveal.js
git commit -m "feat: remove Risinajumi (solutions) section"
```

(удаления уже в индексе после `git rm`).

---

### Task 4: Чистая сборка + финальная проверка

**Files:** нет правок — только верификация.

**Interfaces:**
- Consumes: результаты Task 1–3.

- [ ] **Step 1: Чистая сборка dev-вывода**

Run последовательно:

```bash
npx gulp clean:dev
npx gulp html:dev
npx gulp sass:dev
npx gulp js:dev
```

Expected: все таски `Finished` без ошибок.

- [ ] **Step 2: Grep собранного вывода**

Run (Git Bash):

```bash
grep -ri "solutions" build/index.html build/css/main.css build/js/index.bundle.js; echo "exit=$?"
```

Expected: пусто, `exit=1` (совпадений нет).

```bash
grep -c "mega-menu__link" build/index.html
grep -c "mobile-nav__sublink" build/index.html
```

Expected: по 17 в каждом — листовых пунктов дерева ровно 17 (7 Kosmētika + 4 Spiedienu + 3 Tehnika + 3 Rotējošie instrumenti); заголовки колонок используют отдельные классы `mega-menu__title`/`mega-menu__subtitle`, якорные пункты — `nav__link`/`mobile-nav__link`.

- [ ] **Step 3: Ручная проверка в браузере**

Запустить дев-сервер: `npx gulp` (livereload на build/). Проверить:
- Десктоп: hover по Produkti открывает панель, все 3 колонки + подгруппа Rotējošie instrumenti; Tab-фокус тоже открывает; клики по заглушкам не скроллят страницу.
- Остальные пункты скроллят к `#about`, `#news`, `#contacts`.
- ≤990px: бургер → оверлей; Produkti/категории/Rotējošie instrumenti раскрываются; тап по якорному пункту закрывает оверлей; после закрытия аккордеоны свёрнуты.
- Секции Risinājumi на странице нет.

Остановить сервер (Ctrl+C).

- [ ] **Step 4: Финальный коммит (если были правки по итогам проверки)**

```bash
git status
```

Если чисто — готово; иначе поправить, пересобрать, закоммитить `fix: ...`.
