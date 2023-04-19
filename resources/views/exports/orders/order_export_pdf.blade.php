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

      .float-left {
        float: left;
      }

      .clear-both {
        clear: both;
      }

      .-m-1 {
        margin: -0.25rem;
      }

      .table {
        display: table;
      }

      .w-20 {
        width: 5rem;
      }

      .w-full {
        width: 100%;
      }

      .border-collapse {
        border-collapse: collapse;
      }

      .border {
        border-width: 1px;
      }

      .p-1 {
        padding: 0.25rem;
      }

      .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
      }

      .py-2 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
      }

      .text-left {
        text-align: left;
      }

      .font-bold {
        font-weight: 700;
      }

      .font-normal {
        font-weight: 400;
      }

      .text-red-500 {
        color: #ef4444;
      }

      img {
        max-width: 100%;
      }
    </style>
  </head>
  <body>
    @foreach ($orders as $order)
    <div class="w-full">
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
      <div style="font-size: 0px;" class="-m-1 p-1">
        @foreach ($order['files'] as $image)
        <div class="float-left p-1" style="width: 50%;">
          <img src="{{ $image['url'] }}" />
        </div>
        @endforeach
      </div>
      <div class="clear-both"></div>
    </div>
    @if (!$loop->last)
    <div class="page-break"></div>
    @endif @endforeach
  </body>
</html>
