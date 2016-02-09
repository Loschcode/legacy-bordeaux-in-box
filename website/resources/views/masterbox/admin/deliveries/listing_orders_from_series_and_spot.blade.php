<html>
<head>
  <style>
    body {
      text-align: center;
      font: 14px Verdana;
    }
    img {
      width: 200px;
    }
    .footer {
      font-size: 17px;
      font-weight: bold;
      padding-top: 1em;
    }
    table {
      font: 14px Verdana, Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
      }

    th {
      background-color: black;
      color: white;
      padding: 0.8em;
      text-align: center;
      }

    tr.yellow td {
      border-top: 1px solid #FB7A31;
      border-bottom: 1px solid #FB7A31;
      background: #FFC;
      }

    td {
      border-bottom: 1px solid #CCC;
      padding: 1.2em;
      text-align: center;
      }

    td:first-child {
      width: 190px;
      }

    td+td {
      border-left: 1px solid #CCC;
      text-align: center;
      }
  </style>
</head>
<body>
  <img src="{{ public_path('images/logo.png') }}" />
  <h1>{{ $spot->name }} - {{ $spot->address }}</h1>  
  <h2>Série {{ Html::dateFrench($series->delivery, true) }}</h2>
  <h3>{{ $orders->count() }} Commandes</h3>

  <table>
    <thead>
      <tr class="yellow"> 
        <th>Client(e)</th>
        <th>Téléphone</th>
        <th>Signature</th>
      </tr>
    </thead>
    <tbody>
      
      @foreach ($orders as $order)
        
        <tr>
          <td>{{ $order->customer()->first()->full_name }}</td>
          <td>{{ readable_customer_phone($order->customer()->first()->phone) }}</td>
          <td></td>
        </tr>

      @endforeach

    </tbody>
  </table>

  <br/>

  <div class="footer">
  Ne pas oublier de faire signer la liste<br/>
  La conserver jusqu'au mois suivant et la remettre lors de la livraison suivante<br/>
  Ne pas hésiter à appeler si besoin au 06 09 49 12 16 ou partenaires@bordeauxinbox.com
  </div>
</body>
</html>