<html>
<head>
    <meta charset="UTF-8">
</head>
<body>

        <pre>
			<a href="{{route('simplepay_sandbox')}}">INDEX</a>
            @dump($payment_back)
		</pre>

@if(count($payment_back)>0)
    <a href="{{route('simplepay_sandbox.query',['orderRef'=>$payment_back['o'],'transactionId'=>$payment_back['t'],'merchant'=>$payment_back['m']])}}"> QUERY: {{$payment_back['t']}}</a><br>
    <a href="{{route('simplepay_sandbox.refund',['orderRef'=>$payment_back['o'],'transactionId'=>$payment_back['t'],'merchant'=>$payment_back['m']])}}"> REFUND 5 HUF</a><br>
    Kétlépcsős tranzakció lezárása esetén<br>
    <a href="{{route('simplepay_sandbox.query',['orderRef'=>$payment_back['o'],
                                      'transactionId'=>$payment_back['t'],
                                      'merchant'=>$payment_back['m'],
                                      'originalTotal'=>200,
                                      'approveTotal'=>200])}}"> FINISH 200 HUF (terhelés teljes összeggel)</a><br>
    <a href="{{route('simplepay_sandbox.query',['orderRef'=>$payment_back['o'],
                                      'transactionId'=>$payment_back['t'],
                                      'merchant'=>$payment_back['m'],
                                      'originalTotal'=>200,
                                      'approveTotal'=>100])}}"> FINISH 100 HUF (terhelés a foglaltnál kisebb összeggel)</a><br>
    <a href="{{route('simplepay_sandbox.query',['orderRef'=>$payment_back['o'],
                                      'transactionId'=>$payment_back['t'],
                                      'merchant'=>$payment_back['m'],
                                      'originalTotal'=>200,
                                      'approveTotal'=>0])}}"> FINISH 0 HUF (foglalás feloldása)</a><br>
@endif


</body>
</html>
