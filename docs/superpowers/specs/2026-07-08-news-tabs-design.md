# Секция «Jaunumi un informācija» — тоггл двух слайдеров

**Дата:** 2026-07-08
**Статус:** утверждено

## Цель

Секция новостей получает переключатель из двух подписанных плашек над слайдером:
«Jaunumi» (новости) и «Noderīgi raksti» (полезные статьи). Каждая плашка
показывает свой слайдер; виден всегда только один.

## HTML — `src/html/blocks/news.html`

- Заголовок секции: `h2` → «Jaunumi un informācija» (id и классы без изменений).
- Под заголовком блок плашек:

```html
<div class="news__tabs" role="tablist" aria-label="Jaunumi un informācija">
	<button class="news__tab news__tab--active" type="button" role="tab"
		aria-selected="true" aria-controls="news-panel-news" id="news-tab-news">Jaunumi</button>
	<button class="news__tab" type="button" role="tab"
		aria-selected="false" aria-controls="news-panel-articles" id="news-tab-articles">Noderīgi raksti</button>
</div>
```

- Два слайдера-панели:
  - `#news-panel-news` — `.news__slider.swiper.swiper-news`, `role="tabpanel"`,
    `aria-labelledby="news-tab-news"`. Виден по умолчанию.
  - `#news-panel-articles` — `.news__slider.swiper.swiper-articles`, `role="tabpanel"`,
    `aria-labelledby="news-tab-articles"`, атрибут `hidden`.
- Разметка слайдов одинаковая: `news__slide` → `news__image` (цветной блок-плейсхолдер)
  + `news__slide-title`.

### Контент

Текущие 3 заголовка — советы, не новости. Переходят в «Noderīgi raksti»:

1. Kā izvairīties no sausas ādas uz papēžiem ziemā
2. 5 ieradumi veselīgām pēdām ikdienā
3. Kāpēc regulāra pēdu kopšana ir svarīga pēc 40 gadu vecuma

Для «Jaunumi» — 3 новых плейсхолдера новостного типа (латышский), например:

1. GEHWOL FUSSKRAFT sērija tagad pieejama Latvijā
2. Atklāts jauns GEHWOL partneru salons Rīgā
3. Pavasara akcija: dāvana pie pirkuma virs 30 €

## JS

### `src/js/modules/swipers.js`

- Конфиг `initNewsSwiper` выносится в фабрику `createNewsSwiper(selector)`:
  тот же объект настроек (Autoplay, slidesPerView 3/2/1, spaceBetween 20,
  autoplay 2500, speed 700), селектор — параметр.
- `initAllSwipers()` инициализирует только видимый слайдер `.swiper-news`
  и экспортирует фабрику для ленивой инициализации второго.

### Новый `src/js/modules/newsTabs.js`

- Находит `.news__tabs`; нет — молча выходит (паттерн проекта).
- Клик по неактивной плашке:
  1. Переключить `news__tab--active` и `aria-selected`.
  2. Поставить `hidden` на текущую панель, снять с целевой.
  3. Целевой слайдер: если ещё не инициализирован — `createNewsSwiper()`
    (ленивая инициализация: Swiper в `display:none` считает ширину 0);
    если инициализирован — `autoplay.start()`.
  4. Скрытому слайдеру — `autoplay.stop()`.
- Подключается в `src/js/index.js`.

## SCSS — `src/scss/blocks/_news.scss`

- `.news__tabs`: `display: flex; gap: 12px;` отступ сверху/снизу между
  заголовком и слайдером (~20–30px).
- `.news__tab`: в стиле кнопок сайта — без скруглений, uppercase,
  `font-weight: 700`, `font-size: 16px`, `padding: 12px 24px`, без рамки,
  `cursor: pointer`, `transition: background-color 0.2s ease-in`.
  - Неактивная: фон `var(--accent-tint)`, цвет `var(--text-color)`.
  - Hover/focus неактивной: затемнение tint.
  - Активная: фон `var(--accent)`, цвет `#fff`.
- Mobile (`@include mobile`): `font-size: 14px`, `padding: 10px 14px`,
  `flex-wrap: wrap` на контейнере.

## Ошибки и крайние случаи

- Нет `.news__tabs` или панелей в DOM — модуль выходит без ошибок.
- Повторный клик по активной плашке — no-op.
- Ленивая инициализация исключает нулевую ширину слайдов скрытого Swiper.

## Проверка

Автотестов в проекте нет. Проверка вручную через dev-сборку (gulp):
переключение плашек, корректная ширина слайдов второго слайдера после
первого показа, autoplay только у видимого, адаптив на мобильной ширине.
