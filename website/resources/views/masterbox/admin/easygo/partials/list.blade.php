<table class="listing">
  <thead>
    <tr class="listing__heading">
      <th>Numéro</th>
      <th>Nom</th>
      <th>Marraine ?</th>
      <th>Anniversaire ?</th>
    </tr>
  </thead>
  <tbody class="listing__content">
    <?php $i = 0 ?>
    @foreach ($orders_filtered as $order)
      <?php $i++ ?>
      <tr>
        <td>@if ($order->already_paid == 0) <i class="fa fa-exclamation-triangle" style="color: red"></i> @endif {{ $i }}</td>
        <td>{{ $order->customer()->first()->getFullName() }}</td>
        <td>
          @if ($order->customer_profile()->first()->isSponsor())
            <i class="fa fa-check" style="color: green"></i>
          @else
            <i class="fa fa-times" style="color: grey"></i>
          @endif
        </td>
        <td>
          @if ($order->customer_profile()->first()->isBirthday())
            <i class="fa fa-check" style="color: green"></i>
          @else
            <i class="fa fa-times" style="color: grey"></i>
          @endif

        </td>
      </tr>
    @endforeach


  </tbody>
</table>