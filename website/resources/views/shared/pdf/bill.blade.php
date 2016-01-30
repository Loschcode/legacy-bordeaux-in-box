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

.customer {
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

.w45 {
  width: 45%;
}

.w50 {
  width: 50%;
}

.w55 {
  width: 55%;
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

.micro {
  font-size: 10px;
}

table, th, td {
   border: 0px solid black;
}
th {
    height: 50px;
}

.footer {
  position: absolute;
  bottom: 0;
  color: #666666;
  font-size: 12px;
}

.footer__name {
  margin-top: 30px;
}

.footer__signature {
  margin-top: 20px;
  margin-bottom: 20px;
}

.footer__contact {
  text-align: left;
}
</style>
</head>

<body>

  <img src="images/logo.png" width="200" />

  <br />

  <div class="right w50">
  &nbsp;
  </div>
  <div class="right w50">
    <table>
      <tr>
        <td><strong>Facture du</strong></td>
        <td><strong>{{$company_billing->created_at->format('d.m.Y')}}</strong></td>
      </tr>
      <tr>
        <td>N° de facture</td>
        <td>{{$company_billing->bill_id}}</td>
      </tr>
      <tr>
        <td>N° d'abonnement</td>
        <td>{{$company_billing->contract_id}}</td>
      </tr>
      <tr class="customer-number">
        <td>N° client</td>
        <td>{{$company_billing->customer_id}}</td>
      </tr>
    </table>
  </div>

  <div class="clearfix"></div>
  <br />


  <div class="customer">
  <strong>
  Madame, Monsieur<br />

  {{$company_billing->first_name}} {{$company_billing->last_name}}<br />

  @if ($company_billing->address !== NULL)

  {{$company_billing->address}}<br/>
  {{$company_billing->zip}} {{$company_billing->city}}<br />

  @endif

  FRANCE
  </strong>
  </div>

  <br />

  <h2>{{$company_billing->title}}</h2>

    <table>

      <thead>

        <tr>
          <th>Transaction</th>
          <th>Prestation</th>
          <th>Montant à payer</th>
        </tr>

      </thead>

      <tbody>

          @foreach ($company_billing_lines as $company_billing_line)

          <tr>

            <th>
            <strong>#{{$company_billing_line->payment_id}}</strong>
            </th>
            <th>
            {{$company_billing_line->label}}
            </th>
            <th>
            {{euros($company_billing_line->amount)}}
            </th>

          </tr>

          @endforeach

          <tr>
          <th>
          </th>
          <th>
          <h1>Total</h1>
          </th>
          <th>
          <h1>{{euros($total)}}</h1>
          </th>
          </tr>

      </tbody>

    </table>
  <h2></h2>
  <div align="right"><span class="micro">TVA non applicable, article 293 B du Code général des impôts</span>

  <div class="clear"></div>
  
  <div class="footer">
    <div class="footer__name">Laurent Schaffner, Président</div>
    <div class="footer__signature">
      <img src="images/signatures/laurent.png" />
    </div>
    <div class="footer__address">
      La Petite Box SAS<br/>
      SIRET 811 767 532 00013<br/>
      18 avenue Gustave Eiffel<br/>
      33600 Pessac<br/>
    </div>
    <div class="footer__contact w50 left">
      Support technique : bonjour@bordeauxinbox.com<br/>
      Support commercial : hugo@bordeauxinbox.com<br/>
    </div>
  </div>

</body>
</html>

