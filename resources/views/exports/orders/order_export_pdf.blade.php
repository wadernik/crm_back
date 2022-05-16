<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>
    /* ! tailwindcss v3.0.24 | MIT License | https://tailwindcss.com */

    /*
    1. Prevent padding and border from affecting element width. (https://github.com/mozdevs/cssremedy/issues/4)
    2. Allow adding a border to an element by just adding a border-width. (https://github.com/tailwindcss/tailwindcss/pull/116)
    */

    *,
    ::before,
    ::after {
    box-sizing: border-box;
    /* 1 */
    border-width: 0;
    /* 2 */
    border-style: solid;
    /* 2 */
    border-color: #e5e7eb;
    /* 2 */
    }

    ::before,
    ::after {
    --tw-content: '';
    }

    /*
    1. Use a consistent sensible line-height in all browsers.
    2. Prevent adjustments of font size after orientation changes in iOS.
    3. Use a more readable tab size.
    4. Use the user's configured `sans` font-family by default.
    */

    html {
    line-height: 1.5;
    /* 1 */
    -webkit-text-size-adjust: 100%;
    /* 2 */
    -moz-tab-size: 4;
    /* 3 */
    tab-size: 4;
    /* 3 */
    font-family: DejaVu Sans, sans-serif;
    /* 4 */
    }

    /*
    1. Remove the margin in all browsers.
    2. Inherit line-height from `html` so users can set them as a class directly on the `html` element.
    */

    body {
    margin: 0;
    /* 1 */
    line-height: inherit;
    /* 2 */
    }

    /*
    1. Add the correct height in Firefox.
    2. Correct the inheritance of border color in Firefox. (https://bugzilla.mozilla.org/show_bug.cgi?id=190655)
    3. Ensure horizontal rules are visible by default.
    */

    hr {
    height: 0;
    /* 1 */
    color: inherit;
    /* 2 */
    border-top-width: 1px;
    /* 3 */
    }

    /*
    Add the correct text decoration in Chrome, Edge, and Safari.
    */

    abbr:where([title]) {
    -webkit-text-decoration: underline dotted;
            text-decoration: underline dotted;
    }

    /*
    Remove the default font size and weight for headings.
    */

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
    font-size: inherit;
    font-weight: inherit;
    }

    /*
    Reset links to optimize for opt-in styling instead of opt-out.
    */

    a {
    color: inherit;
    text-decoration: inherit;
    }

    /*
    Add the correct font weight in Edge and Safari.
    */

    b,
    strong {
    font-weight: bolder;
    }

    /*
    1. Use the user's configured `mono` font family by default.
    2. Correct the odd `em` font sizing in all browsers.
    */

    code,
    kbd,
    samp,
    pre {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    /* 1 */
    font-size: 1em;
    /* 2 */
    }

    /*
    Add the correct font size in all browsers.
    */

    small {
    font-size: 80%;
    }

    /*
    Prevent `sub` and `sup` elements from affecting the line height in all browsers.
    */

    sub,
    sup {
    font-size: 75%;
    line-height: 0;
    position: relative;
    vertical-align: baseline;
    }

    sub {
    bottom: -0.25em;
    }

    sup {
    top: -0.5em;
    }

    /*
    1. Remove text indentation from table contents in Chrome and Safari. (https://bugs.chromium.org/p/chromium/issues/detail?id=999088, https://bugs.webkit.org/show_bug.cgi?id=201297)
    2. Correct table border color inheritance in all Chrome and Safari. (https://bugs.chromium.org/p/chromium/issues/detail?id=935729, https://bugs.webkit.org/show_bug.cgi?id=195016)
    3. Remove gaps between table borders by default.
    */

    table {
    text-indent: 0;
    /* 1 */
    border-color: inherit;
    /* 2 */
    border-collapse: collapse;
    /* 3 */
    }

    /*
    1. Change the font styles in all browsers.
    2. Remove the margin in Firefox and Safari.
    3. Remove default padding in all browsers.
    */

    button,
    input,
    optgroup,
    select,
    textarea {
    font-family: inherit;
    /* 1 */
    font-size: 100%;
    /* 1 */
    line-height: inherit;
    /* 1 */
    color: inherit;
    /* 1 */
    margin: 0;
    /* 2 */
    padding: 0;
    /* 3 */
    }

    /*
    Remove the inheritance of text transform in Edge and Firefox.
    */

    button,
    select {
    text-transform: none;
    }

    /*
    1. Correct the inability to style clickable types in iOS and Safari.
    2. Remove default button styles.
    */

    button,
    [type='button'],
    [type='reset'],
    [type='submit'] {
    -webkit-appearance: button;
    /* 1 */
    background-color: transparent;
    /* 2 */
    background-image: none;
    /* 2 */
    }

    /*
    Use the modern Firefox focus style for all focusable elements.
    */

    :-moz-focusring {
    outline: auto;
    }

    /*
    Remove the additional `:invalid` styles in Firefox. (https://github.com/mozilla/gecko-dev/blob/2f9eacd9d3d995c937b4251a5557d95d494c9be1/layout/style/res/forms.css#L728-L737)
    */

    :-moz-ui-invalid {
    box-shadow: none;
    }

    /*
    Add the correct vertical alignment in Chrome and Firefox.
    */

    progress {
    vertical-align: baseline;
    }

    /*
    Correct the cursor style of increment and decrement buttons in Safari.
    */

    ::-webkit-inner-spin-button,
    ::-webkit-outer-spin-button {
    height: auto;
    }

    /*
    1. Correct the odd appearance in Chrome and Safari.
    2. Correct the outline style in Safari.
    */

    [type='search'] {
    -webkit-appearance: textfield;
    /* 1 */
    outline-offset: -2px;
    /* 2 */
    }

    /*
    Remove the inner padding in Chrome and Safari on macOS.
    */

    ::-webkit-search-decoration {
    -webkit-appearance: none;
    }

    /*
    1. Correct the inability to style clickable types in iOS and Safari.
    2. Change font properties to `inherit` in Safari.
    */

    ::-webkit-file-upload-button {
    -webkit-appearance: button;
    /* 1 */
    font: inherit;
    /* 2 */
    }

    /*
    Add the correct display in Chrome and Safari.
    */

    summary {
    display: list-item;
    }

    /*
    Removes the default spacing and border for appropriate elements.
    */

    blockquote,
    dl,
    dd,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    hr,
    figure,
    p,
    pre {
    margin: 0;
    }

    fieldset {
    margin: 0;
    padding: 0;
    }

    legend {
    padding: 0;
    }

    ol,
    ul,
    menu {
    list-style: none;
    margin: 0;
    padding: 0;
    }

    /*
    Prevent resizing textareas horizontally by default.
    */

    textarea {
    resize: vertical;
    }

    /*
    1. Reset the default placeholder opacity in Firefox. (https://github.com/tailwindlabs/tailwindcss/issues/3300)
    2. Set the default placeholder color to the user's configured gray 400 color.
    */

    input::placeholder,
    textarea::placeholder {
    opacity: 1;
    /* 1 */
    color: #9ca3af;
    /* 2 */
    }

    /*
    Set the default cursor for buttons.
    */

    button,
    [role="button"] {
    cursor: pointer;
    }

    /*
    Make sure disabled buttons don't get the pointer cursor.
    */

    :disabled {
    cursor: default;
    }

    /*
    1. Make replaced elements `display: block` by default. (https://github.com/mozdevs/cssremedy/issues/14)
    2. Add `vertical-align: middle` to align replaced elements more sensibly by default. (https://github.com/jensimmons/cssremedy/issues/14#issuecomment-634934210)
    This can trigger a poorly considered lint error in some tools but is included by design.
    */

    img,
    svg,
    video,
    canvas,
    audio,
    iframe,
    embed,
    object {
    display: block;
    /* 1 */
    vertical-align: middle;
    /* 2 */
    }

    /*
    Constrain images and videos to the parent width and preserve their intrinsic aspect ratio. (https://github.com/mozdevs/cssremedy/issues/14)
    */

    img,
    video {
    max-width: 100%;
    height: auto;
    }

    /*
    Ensure the default browser behavior of the `hidden` attribute.
    */

    [hidden] {
    display: none;
    }

    *, ::before, ::after {
    --tw-translate-x: 0;
    --tw-translate-y: 0;
    --tw-rotate: 0;
    --tw-skew-x: 0;
    --tw-skew-y: 0;
    --tw-scale-x: 1;
    --tw-scale-y: 1;
    --tw-pan-x:  ;
    --tw-pan-y:  ;
    --tw-pinch-zoom:  ;
    --tw-scroll-snap-strictness: proximity;
    --tw-ordinal:  ;
    --tw-slashed-zero:  ;
    --tw-numeric-figure:  ;
    --tw-numeric-spacing:  ;
    --tw-numeric-fraction:  ;
    --tw-ring-inset:  ;
    --tw-ring-offset-width: 0px;
    --tw-ring-offset-color: #fff;
    --tw-ring-color: rgb(59 130 246 / 0.5);
    --tw-ring-offset-shadow: 0 0 #0000;
    --tw-ring-shadow: 0 0 #0000;
    --tw-shadow: 0 0 #0000;
    --tw-shadow-colored: 0 0 #0000;
    --tw-blur:  ;
    --tw-brightness:  ;
    --tw-contrast:  ;
    --tw-grayscale:  ;
    --tw-hue-rotate:  ;
    --tw-invert:  ;
    --tw-saturate:  ;
    --tw-sepia:  ;
    --tw-drop-shadow:  ;
    --tw-backdrop-blur:  ;
    --tw-backdrop-brightness:  ;
    --tw-backdrop-contrast:  ;
    --tw-backdrop-grayscale:  ;
    --tw-backdrop-hue-rotate:  ;
    --tw-backdrop-invert:  ;
    --tw-backdrop-opacity:  ;
    --tw-backdrop-saturate:  ;
    --tw-backdrop-sepia:  ;
    }

    .container {
    width: 100%;
    }

    @media (min-width: 640px) {
    .container {
        max-width: 640px;
    }
    }

    @media (min-width: 768px) {
    .container {
        max-width: 768px;
    }
    }

    @media (min-width: 1024px) {
    .container {
        max-width: 1024px;
    }
    }

    @media (min-width: 1280px) {
    .container {
        max-width: 1280px;
    }
    }

    @media (min-width: 1536px) {
    .container {
        max-width: 1536px;
    }
    }

    .mx-auto {
    margin-left: auto;
    margin-right: auto;
    }

    .mt-2 {
    margin-top: 0.5rem;
    }

    .mt-4 {
    margin-top: 1rem;
    }

    .table {
    display: table;
    }

    .w-full {
    width: 100%;
    }

    .w-56 {
    width: 14rem;
    }

    .border-collapse {
    border-collapse: collapse;
    }

    .border {
    border-width: 1px;
    }

    .border-black {
    --tw-border-opacity: 1;
    border-color: rgb(0 0 0 / var(--tw-border-opacity));
    }

    .bg-gray-50 {
    --tw-bg-opacity: 1;
    background-color: rgb(249 250 251 / var(--tw-bg-opacity));
    }

    .py-2 {
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    }

    .px-4 {
    padding-left: 1rem;
    padding-right: 1rem;
    }

    .text-left {
    text-align: left;
    }

    .text-\[9px\] {
      font-size: 9px;
    }

    .font-normal {
    font-weight: 400;
    }

    .font-bold {
    font-weight: 700;
    }

    .uppercase {
    text-transform: uppercase;
    }

    .leading-5 {
    line-height: 1.25rem;
    }

    .tracking-wider {
    letter-spacing: 0.05em;
    }

    .text-red-500 {
    --tw-text-opacity: 1;
    color: rgb(239 68 68 / var(--tw-text-opacity));
    }
  </style>
</head>
<body>
  @foreach ($orders as $order)
  <div class="mx-auto container text-[9px] font-normal leading-5">
    <table class="w-full">
      <tbody>
        <tr>
          <td class="font-bold uppercase text-red-500 w-56">
            {{ $order['seller']['address'] }}
          </td>
          <td>
            Код: {{ $order['number'] }}
          </td>
        </tr>
      </tbody>
    </table>
    <table class="w-full mt-2 border-collapse border border-black">
      <thead class="bg-gray-50">
        <tr>
          <th class="border border-black py-2 px-4 text-left font-bold uppercase tracking-wider">
            Наименование продукции
          </th>
          <th class="border border-black py-2 px-4 text-left font-bold uppercase tracking-wider">
            Количество
          </th>
          <th class="border border-black py-2 px-4 text-left font-bold uppercase tracking-wider">
            Дата принятия
          </th>
          <th class="border border-black py-2 px-4 text-left font-bold uppercase tracking-wider">
            Дата исполнения
          </th>
          <th class="border border-black py-2 px-4 text-left font-bold uppercase tracking-wider">
            Время отдачи
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="border border-black py-2 px-4">
            ...
          </td>
          <td class="border border-black py-2 px-4">
            {{ $order['amount'] }}
          </td>
          <td class="border border-black py-2 px-4">
            {{ \Carbon\Carbon::parse($order['accepted_date'])->format('d.m.Y') }}
          </td>
          <td class="border border-black py-2 px-4">
            {{ \Carbon\Carbon::parse($order['order_date'])->format('d.m.Y') }}
          </td>
          <td class="border border-black py-2 px-4">
            {{ $order['order_time'] }}
          </td>
        </tr>
      </tbody>
    </table>
    <table class="mt-2 w-full">
      <tbody>
        <tr>
          <td class="font-bold w-56">
            Оформление:
          </td>
          <td>
            {{ $order['name'] }}
          </td>
        </tr>
      </tbody>
    </table>
    <table class="mt-2 w-full">
      <tbody>
        <tr>
          <td class="font-bold w-56">
            Надпись:
          </td>
          <td>
            {{ $order['label'] }}
          </td>
        </tr>
      </tbody>
    </table>
    <table class="mt-2 w-full">
      <tbody>
        <tr>
          <td class="font-bold w-56">
            Комментарий от покупателя:
          </td>
          <td>
            {{ $order['comment'] }}
          </td>
        </tr>
      </tbody>
    </table>
    <table class="mt-2 w-full">
      <tbody>
        <tr>
          <td class="font-bold w-56">
            Код для получения:
          </td>
          <td>
            {{ $order['number'] }}
          </td>
        </tr>
      </tbody>
    </table>
    <table class="mt-4 w-full">
      <tbody>
        @foreach ($order['files'] as $image)
        <tr>
          <td>
            <img src="{{ $image['url'] }}">
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="page-break"></div>
  @endforeach
</body>
</html>