<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
body {
  font-family: Verdana, sans-serif;
  font-size: 13px;
  font-style: normal;
  font-weight: normal;
  letter-spacing: normal;
  line-height: 1.6em;
}
h2 {
  text-transform: uppercase;
  border-bottom: 1px solid black;
}
h2.no-border {
  border-bottom: 0px
}
table {
  width: 100%;
}

.user {
  font-size: 15px;
}

.right {
  float: right;
  display: inline-block;
}

.left {
  float: left;
  display: inline-block;
}

.w20 {
  width: 20%;
}

.w10 {
  width: 10%;
}

.w40 {
  width: 40%;
}

.w60 {
  width: 60%;
}

.w80 {
  width: 80%;
}

.w90 {
  width: 90%;
}

.clearfix {

  clear: both;

}
.customer-number {

  background-color: #ededed;
  font-weight: bold;

}
table {
}

table, th, td {
   border: 0px solid black;
}
th {
    height: 50px;
}
</style>
</head>

<body>
<img src="public/assets/img/logo-text.png" width="200" />

<br />

<div class="right w60">
&nbsp;
</div>
<div class="right w40">
  <table>
    <tr>
      <td><strong>Facture du</strong></td>
      <td><strong>{{$payment->created_at->format('d.m.Y')}}</strong></td>
    </tr>
    <tr>
      <td>N° de transaction</td>
      <td>{{$payment->id}}</td>
    </tr>
    <tr>
      <td>N° de facture</td>
      <td>{{$payment->bill_id}}</td>
    </tr>
    <tr>
      <td>N° d'abonnement</td>
      <td>{{$profile->id}}-{{$profile->contract_id}}</td>
    </tr>
    <tr class="customer-number">
      <td>N° client</td>
      <td>{{$user->id}}</td>
    </tr>
  </table>
</div>

<div class="clearfix"></div>
<br />


<div class="user">
<strong>
Madame, Monsieur<br />

@if ($billing != NULL)

{{$billing->first_name}} {{$billing->last_name}}<br />
{{$billing->address}}<br/>
{{$billing->zip}} {{$billing->city}}<br />

@else

{{$user->first_name}} {{$user->last_name}}<br />
{{$user->address}}<br/>
{{$user->zip}} {{$user->city}}<br />


@endif

FRANCE
</strong>
</div>

<br />

<h2>Box {{$box->title}}</h2>

@if ($order == NULL)
  
  Cette facture n'est reliée à aucune commande en particulier. La transaction bancaire correspondante est de {{$payment->amount}}€

  @if ($payment->paid == 0)

   (ECHEC)

  @endif

@else

  <table>

    <thead>

      <tr>
        <th>Prestation</th>
        <th>Date approximative de livraison</th>
        <th>Montant à payer (TTC)</th>
      </tr>

    </thead>

    <tbody>

        <tr>

        @if ($order != NULL)

        	<th>Frais d'abonnement de la box surprise</th>
          <th>{{$order->delivery_serie()->first()->delivery}}</th>
          <th>{{$order->unity_and_fees_price}}€

          @if ($payment->paid == 0)

           (ECHEC)

          @endif
  
          </th>
        @endif
          
        </tr>

    </tbody>

  </table>

@endif

<br />

{{HTML::page('bill')}}

<br />

</body></html>
