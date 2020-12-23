<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<h3>Teszt tranzakció indítása - Teszt Page</h3>

        <pre>
			<a href="{{route('simplepay_sandbox.start_test')}}">Egylépcsős fizetés indítása</a>
		</pre>

        <pre>
			<a href="{{route('simplepay_sandbox.start_test',['twoStep' => 1])}}">Kétlépcsős fizetés indítása</a>
		</pre>


</body>
</html>
