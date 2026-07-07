# Gehwol LV — секция Produkcija: дерево категорий вместо слайдера

Дата: 2026-07-07
Статус: утверждено пользователем (устно в сессии)

## Цель

Заменить swiper-слайдер в секции `#products` статичной сеткой из трёх карточек-категорий,
показывающей всё дерево продукции (то же, что в мега-меню). Слайдер продуктов удалить
полностью, включая JS-инициализацию.

## Секция (HTML, `src/html/blocks/products.html`)

- `<section class="products" id="products">` и заголовок `Produkcija` (`title-2`) остаются.
- Класс `text-center` у секции остаётся (центрирует заголовок и CTA); контент карточек
  выравнивается влево собственными стилями.
- Слайдер (`.products__slider`, `.swiper-products`, кнопки `.products__btn--prev/next`,
  `.products__wrapper`, все `.products__slide`) удаляется.
- Вместо него `.products__grid` с тремя `.products__card`:

Карточка 1 — **Kosmētika**:
GEHWOL Classic, GEHWOL MED®, GEHWOL FUSSKRAFT, GEHWOL FUSSKRAFT Soft Feet,
GEHWOL PROFESSIONAL, GERLASAN, GERLAVIT.

Карточка 2 — **Spiedienu uz pēdām mazinoši līdzekļi**:
Polimēra gēla izstrādājumi, pārvilkti ar tekstilu; Polimēra gēla izstrādājumi;
Plāksteri; Filca izstrādājumi.

Карточка 3 — **Tehnika**:
Pēdu kopšanas aparāti; Pacienta krēsli; Darbinieka krēsli; далее подгруппа
**Rotējošie instrumenti**: Keramiskās frēzes, Pulētāji, Vienreizlietojamās
smilšpapīra frēzes.

- Разметка карточки: `.products__card-title` (заголовок категории, ссылка `#`),
  `.products__list` > `li` > `.products__link` (ссылки `#`); в Tehnika —
  `.products__subtitle` (ссылка `#`) + `.products__sublist` > `li` > `.products__link`.
- Все ссылки — заглушки `href="#"`; глобальный `placeholder-links.js` уже гасит переходы.
- CTA `<a href="#contacts" class="btn-link products__cta">Sazinieties, lai pasūtītu →</a>`
  остаётся под сеткой.
- Тексты — латышские, копировать точно (®, диакритика).

## Стили (`src/scss/blocks/_products.scss`)

- Все стили слайдера (`.products__slider`, `.products__image`, `.products__slide-title`,
  `.products__btn*`) удаляются. `.products__container` (mobile-паддинг) и
  `.products__cta` остаются.
- `.products__grid`: CSS grid, `repeat(3, 1fr)`, `gap: 20px`; `@include tablet-small`
  (990px) — одна колонка.
- `.products__card`: фон `var(--accent-tint)`, `border-left: 4px solid var(--accent)`,
  внутренний паддинг, `text-align: left`; hover — лёгкая тень (в духе бывших слайдов).
- `.products__card-title`: font-accent, uppercase, `--accent-dark`, hover `--accent`.
- `.products__link`: обычный текст, hover `--accent`.
- `.products__subtitle` + `.products__sublist`: как в мега-меню — подзаголовок uppercase,
  под-список с левым отступом и линией `var(--accent-tint)`... линия на фоне accent-tint
  не видна — использовать `var(--accent)` для бордера под-списка.
- Только существующие CSS-переменные.

## JS

- `src/js/modules/swipers.js`: инициализация `.swiper-products` и все products-ветки
  удаляются. Модуль упрощается до единственного news-свайпера с ТЕМ ЖЕ эффективным
  поведением, что сейчас: modules `[Autoplay]`, `loop: false`, `slidesPerView` по
  брейкпоинтам (0→1, 760→2, 1024→3), `spaceBetween: 20`, autoplay `{delay: 2500,
  disableOnInteraction: false, reverseDirection: false, pauseOnMouseEnter: true}`,
  `speed: 700`. Импорты `Navigation` и `swiper/css/navigation` удаляются (навигация
  использовалась только продуктовым слайдером).
- `src/js/modules/scrollReveal.js`: в reveal-селекторе `.products__slide` заменяется
  на `.products__card` (заодно исправить оставшуюся после прошлой правки
  рассогласованную индентацию этого вызова).

## Критерии готовности

- Секция #products показывает 3 карточки со всем деревом (17 листовых ссылок),
  Rotējošie instrumenti — подгруппа внутри Tehnika.
- Слайдера нет ни в HTML, ни в CSS, ни в JS; news-слайдер работает как раньше.
- `grep -c "products__link" build/index.html` = 17; `swiper-products` в сборке
  отсутствует.
- Сборка gulp без ошибок.
