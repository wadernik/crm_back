<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
      body {
        margin: 0;
        font: normal 9px DejaVu Sans, sans-serif;
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

      .h1 {
        font-size: 11px;
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

      .text-center {
        text-align: center
      }

      .font-normal {
        font-weight: 400
      }
    </style>
  </head>
  <body>
    <div class="container mx-auto">
      <table class="w-full border-collapse">
        <tbody>
          <tr>
            <td class="h1 py-2 px-4 text-center">Отчет по продажам сотрудников за период с {{ $dateStart }} @if (isset($dateEnd)) по {{ $dateEnd }} @endif</td>
          </tr>
        </tbody>
      </table>
      <table class="table w-full border-collapse">
        <thead class="thead">
          <tr>
            <th class="th py-2 px-4 text-left font-normal">Имя Фамилия</th>
            <th class="th py-2 px-4 text-left font-normal">Количество заказов</th>
            <th class="th py-2 px-4 text-left font-normal">Продано</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($total as $row)
          <tr>
            <td class="td py-2 px-4">{{ $row['name'] }}</td>
            <td class="td py-2 px-4">{{ $row['total_sold'] }}</td>
            <td class="td py-2 px-4">{{ $row['total_price'] }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </body>
</html>
