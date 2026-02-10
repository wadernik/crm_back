@php
  use App\DTOs\Report\AcceptedOrderDto;
@endphp
<table>
  <thead>
  <tr>
    <th style="font-weight: bold; font-size: 16px;" colspan="14">Отчет за {{ $dateFrom }} - {{ $dateTo }}</th>
  </tr>
  <tr>
    <th style="font-weight: bold; font-size: 14px;">#</th>
    <th style="font-weight: bold; font-size: 14px;">Номер</th>
    <th style="font-weight: bold; font-size: 14px;">Внешний номер</th>
    <th style="font-weight: bold; font-size: 14px;">Статус</th>
    <th style="font-weight: bold; font-size: 14px;">Точка продажи</th>
    <th style="font-weight: bold; font-size: 14px;">Точка выдачи</th>
    <th style="font-weight: bold; font-size: 14px;">Покупатель</th>
    <th style="font-weight: bold; font-size: 14px;">Телефон</th>
    <th style="font-weight: bold; font-size: 14px;">Инспектор</th>
    <th style="font-weight: bold; font-size: 14px;">Дата принятия</th>
    <th style="font-weight: bold; font-size: 14px;">Дата исполнения</th>
    <th style="font-weight: bold; font-size: 14px;">Время выдачи</th>
    <th style="font-weight: bold; font-size: 14px;">Стоимость</th>
    <th style="font-weight: bold; font-size: 14px;">Товары</th>
  </tr>
  </thead>
  <tbody>
  @php
    /** @var AcceptedOrderDto[] $orders */
  @endphp
  @foreach($orders as $order)
    <tr>
      <td>{{ $order->id }}</td>
      <td>{{ $order->number }}</td>
      <td>{{ $order->numberExternal }}</td>
      <td>{{ $order->status }}</td>
      <td>{{ $order->source }}</td>
      <td>{{ $order->seller }}</td>
      <td>{{ $order->buyer }}</td>
      <td>{{ $order->phone }}</td>
      <td>{{ $order->inspector }}</td>
      <td>{{ $order->acceptedDate }}</td>
      <td>{{ $order->orderDate }}</td>
      <td>{{ $order->orderTime }}</td>
      <td>{{ $order->price }}</td>
      <td>
        @foreach($order->items as $item)
          <p>{{ $item->title }} | {{ $item->amount }} {{ $item->unit }}</p>
        @endforeach
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
