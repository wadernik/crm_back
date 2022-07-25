<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
      body {
        margin: 0;
        font: normal 9px "DejaVu Sans", sans-serif;
      }

      .border {
        border: 1px solid black;
      }

      .thead {
        background-color: #F9FAFB;
      }

      .th {
        font-size: 9px;
      }

      .td {
        font-size: 9px;
      }

      .container {
        width: 100%
      }

      @media (min-width: 640px) {
        .container {
          max-width: 640px
        }
      }

      @media (min-width: 768px) {
        .container {
          max-width: 768px
        }
      }

      @media (min-width: 1024px) {
        .container {
          max-width: 1024px
        }
      }

      @media (min-width: 1280px) {
        .container {
          max-width: 1280px
        }
      }

      @media (min-width: 1536px) {
        .container {
          max-width: 1536px
        }
      }

      .mx-auto {
        margin-left: auto;
        margin-right: auto
      }

      .table {
        display: table
      }

      .w-full {
        width: 100%
      }

      .w-20 {
        width: 5rem
      }

      .border-collapse {
        border-collapse: collapse
      }

      .border {
        border-width: 1px
      }

      .py-2 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem
      }

      .px-4 {
        padding-left: 1rem;
        padding-right: 1rem
      }

      .text-left {
        text-align: left
      }

      .font-bold {
        font-weight: 700
      }

      .font-normal {
        font-weight: 400
      }

      .text-red-500 {
        --tw-text-opacity: 1;
        color: rgb(239 68 68 / var(--tw-text-opacity))
      }

      img {
          max-width: 100%;
      }
    </style>
  </head>
  <body>
    @foreach ($orders as $order)
    <div class="container mx-auto">
      <table class="w-full border-collapse">
        <tbody>
          <tr>
            <td class="td w-20 py-2 px-4">Улица</td>
            <td class="td py-2 px-4 font-bold text-red-500">{{ $order['seller']['address'] }}</td>
          </tr>
          <tr>
            <td class="td w-20 py-2 px-4">Код</td>
            <td class="td py-2 px-4">{{ $order['number'] }}</td>
          </tr>
        </tbody>
      </table>
      <table class="w-full border-collapse border">
        <thead class="thead">
          <tr>
            <th class="th border py-2 px-4 text-left font-normal">Наименование продукции</th>
            <th class="th border py-2 px-4 text-left font-normal">Количество</th>
            <th class="th border py-2 px-4 text-left font-normal">Дата принятия</th>
            <th class="th border py-2 px-4 text-left font-normal">Дата исполнения</th>
            <th class="th border py-2 px-4 text-left font-normal">Время отдачи</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="td border py-2 px-4">{{ $order['name'] }}</td>
            <td class="td border py-2 px-4">{{ $order['amount'] }}</td>
            <td class="td border py-2 px-4">{{ \Carbon\Carbon::parse($order['accepted_date'])->format('d.m.Y') }}</td>
            <td class="td border py-2 px-4">{{ \Carbon\Carbon::parse($order['order_date'])->format('d.m.Y') }}</td>
            <td class="td border py-2 px-4">{{ $order['order_time'] }}</td>
          </tr>
        </tbody>
      </table>
      <table class="w-full border-collapse">
        <tbody>
          <tr>
            <td class="td w-20 py-2 px-4">Оформление</td>
            <td class="td py-2 px-4">{{ $order['decoration'] ?: '-' }}</td>
          </tr>
          <tr>
            <td class="td w-20 py-2 px-4">Надпись</td>
            <td class="td py-2 px-4">{{ $order['label'] }}</td>
          </tr>
          <tr>
            <td class="td w-20 py-2 px-4">Комментарий от покупателя</td>
            <td class="td py-2 px-4">{{ $order['comment'] }}</td>
          </tr>
          <tr>
            <td class="td w-20 py-2 px-4">Код для получения</td>
            <td class="td py-2 px-4">{{ $order['number'] }}</td>
          </tr>
        </tbody>
      </table>
      <table class="w-full border-collapse">
        <tbody>
          @foreach ($order['files'] as $image)
          <tr>
            <td>
              <img src="{{ $image['url'] }}" />
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if (!$loop->last)
    <div class="page-break"></div>
    @endif @endforeach
  </body>
</html>
