<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
        <form action="{{$returnData['paymentUrl']}}">
            <input type="submit" value="Fizetés Indítása" />
        </form>

        PAYMENT OBJECT
        @dump($payment)

        API REQUEST
        @dump($baseData)

        "API RESULT"
        @dump($returnData)

</body>
</html>
