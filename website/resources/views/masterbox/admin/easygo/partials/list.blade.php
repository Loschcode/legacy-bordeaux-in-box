<table class="js-datatable-simple">
  <thead>
    <tr>
      <th>Numéro</th>
      <th>Nom</th>
      <th>Anniversaire ?</th>
    </tr>
  </thead>
  <tbody>
    <?php $i = 0 ?>
    @foreach ($orders_filtered as $order)
      <?php $i++ ?>
      <tr>
        <td>@if ($order->already_paid == 0) <i class="fa fa-exclamation-triangle" style="color: red"></i> @endif {{ $i }}</td>
        <td>{{ $order->customer()->first()->getFullName() }}</td>
        <td>
          @if ($order->customer_profile()->first()->isBirthday())
            <span class="easygo__label --green">Oui</span>
          @else
            <span class="easygo__label --red">Non</span>
          @endif

        </td>
      </tr>
    @endforeach


  </tbody>
</table>
