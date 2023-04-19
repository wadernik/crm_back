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

      .my-1 {
        margin-top: 0.25rem;
        margin-bottom: 0.25rem;
      }

      .ml-2 {
        margin-left: 0.5rem;
      }

      .table {
        display: table;
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

      .py-1 {
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
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

      .uppercase {
        text-transform: uppercase;
      }

      .text-red-500 {
        color: #ef4444;
      }

      img {
        max-width: 100%;
      }

      .grid {
        display: grid;
      }

      .grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .bg-gray-500 {
        --tw-bg-opacity: 1;
        background-color: rgb(107 114 128 / var(--tw-bg-opacity));
      }
    </style>
  </head>
  <body>
    <div class="grid grid-cols-2">
      @foreach ($orders as $order)
      <div class="bg-gray-500">
        test...
      </div>
      @endforeach
    </div>
    <!-- <div class="">
      @foreach ($orders as $order)
      <div style="width: 877px; height: 620px;" class="float-left">
        <table class="w-full border-collapse">
          <tbody>
            <tr>
              <td>
                <table class="w-full border-collapse">
                  <tbody>
                    <tr>
                      <td class="td px-4 py-2">
                        <span class="font-bold uppercase text-red-500">{{ $order['seller']['address'] }}</span>
                        <span class="ml-2">Код: {{ $order['number'] }}</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table class="w-full border-collapse border">
                  <thead class="thead">
                    <tr>
                      <th class="th border px-4 py-2 text-left font-normal">Наименование продукции</th>
                      <th class="th border px-4 py-2 text-left font-normal" style="width: 15%">Количество</th>
                      <th class="th border px-4 py-2 text-left font-normal" style="width: 15%">Дата принятия</th>
                      <th class="th border px-4 py-2 text-left font-normal" style="width: 20%">Дата исполнения</th>
                      <th class="th border px-4 py-2 text-left font-normal" style="width: 15%">Время отдачи</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="td border px-4 py-2">{{ $order['name'] }}</td>
                      <td class="td border px-4 py-2">{{ $order['amount'] }}</td>
                      <td class="td border px-4 py-2">{{ \Carbon\Carbon::parse($order['accepted_date'])->format('d.m.Y') }}</td>
                      <td class="td border px-4 py-2">{{ \Carbon\Carbon::parse($order['order_date'])->format('d.m.Y') }}</td>
                      <td class="td border px-4 py-2">{{ $order['order_time'] }}</td>
                    </tr>
                  </tbody>
                </table>
                <table class="border-collapse" style="width: 70%">
                  <tbody>
                    <tr>
                      <td class="td py-1">
                        <span class="font-bold">Оформление:</span>
                        <span>{{ $order['decoration'] ?: '-' }}</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="td py-1">
                        <span class="font-bold">Надпись:</span>
                        <span>{{ $order['label'] }}</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="td py-1">
                        <span class="font-bold">Комментарий от покупателя:</span>
                        <span>{{ $order['comment'] }}</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="td py-1">
                        <span class="font-bold">Код для получения:</span>
                        <span>{{ $order['number'] }}</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div style="font-size: 0px;" class="-m-1">
                  @foreach ($order['files'] as $image)
                  <div class="float-left p-1" style="width: 50%;">
                    <img src="{{ $image['url'] }}" />
                  </div>
                  @endforeach
                  <div class="clear-both"></div>
                </div>
              </td>
              <td style="width: 30%"></td>
            </tr>
          </tbody>
        </table>
      </div>
      @if (!$loop->last)
      <div class="page-break clear-both"></div>
      @endif
      @endforeach
    </div> -->
  </body>
</html>
