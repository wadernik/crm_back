<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
      body {
        margin: 0;
        font: normal 9px "DejaVu Sans", sans-serif;
      }

      .table {
        display: table;
      }

      .td {
        font-size: 9px;
      }

      .w-full {
        width: 100%;
      }

      .border-collapse {
        border-collapse: collapse;
      }

      .border-separate {
        border-collapse: separate;
      }

      .border-spacing-2 {
        border-spacing: 0.5rem 0.5rem;
      }

      .border {
        border-width: 1px;
      }

      .px-2 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
      }

      .py-1 {
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
      }

      .font-bold {
        font-weight: 700;
      }

      .text-red-500 {
        --tw-text-opacity: 1;
        color: rgb(239 68 68 / var(--tw-text-opacity));
      }
    </style>
  </head>
  <body>
    @foreach ($orders as $order)
    <table class="w-full border-separate border-spacing-2">
      <tbody>
        <tr>
          <td style="width: 50%">
            <table class="w-full border-collapse">
              <tbody>
                <tr>
                  <td class="td border px-2 py-1 font-bold">Улица</td>
                  <td class="td border px-2 py-1 font-bold text-red-500">{{ $order['seller']['address'] }}</td>
                </tr>
                <tr>
                  <td class="td border px-2 py-1 font-bold">Код</td>
                  <td class="td border px-2 py-1">{{ $order['number'] }}</td>
                </tr>
                <tr>
                  <td class="td border px-2 py-1 font-bold">Наименование продукции</td>
                  <td class="td border px-2 py-1">{{ $order['name'] }}</td>
                </tr>
                <tr>
                  <td class="td border px-2 py-1 font-bold">Количество</td>
                  <td class="td border px-2 py-1">{{ $order['amount'] }}</td>
                </tr>
                <tr>
                  <td class="td border px-2 py-1 font-bold">Дата принятия</td>
                  <td class="td border px-2 py-1">{{ \Carbon\Carbon::parse($order['accepted_date'])->format('d.m.Y') }}</td>
                </tr>
                <tr>
                  <td class="td border px-2 py-1 font-bold">Дата исполнения</td>
                  <td class="td border px-2 py-1">{{ \Carbon\Carbon::parse($order['order_date'])->format('d.m.Y') }}</td>
                </tr>
                <tr>
                  <td class="td border px-2 py-1 font-bold">Время отдачи</td>
                  <td class="td border px-2 py-1">{{ $order['order_time'] }}</td>
                </tr>
                <tr>
                  <td class="td border px-2 py-1 font-bold">Оформление</td>
                  <td class="td border px-2 py-1">{{ $order['decoration'] ?: '-' }}</td>
                </tr>
                <tr>
                  <td class="td border px-2 py-1 font-bold">Надпись</td>
                  <td class="td border px-2 py-1">{{ $order['label'] }}</td>
                </tr>
                <tr>
                  <td class="td border px-2 py-1 font-bold">Комментарий от покупателя</td>
                  <td class="td border px-2 py-1">{{ $order['comment'] }}</td>
                </tr>
                <tr>
                  <td class="td border px-2 py-1 font-bold">Код для получения</td>
                  <td class="td border px-2 py-1">{{ $order['number'] }}</td>
                </tr>
              </tbody>
            </table>
          </td>
          <td style="width: 50%">
            @foreach ($order['files'] as $image)
            <img src="{{ $image['url'] }}" style="width: 50%" />
            @endforeach
          </td>
        </tr>
      </tbody>
    </table>
    @if (!$loop->last)
    <div class="page-break"></div>
    @endif @endforeach
  </body>
</html>
