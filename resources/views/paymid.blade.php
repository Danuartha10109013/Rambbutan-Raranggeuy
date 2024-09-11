<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    <title>Payment || Midtrans</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .payment-container {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        #pay-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.2);
            transition: background-color 0.3s ease;
        }

        #pay-button:hover {
            background-color: #0056b3;
        }

        #snap-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <img width="20%" src="{{asset('logo.png')}}" alt="">
        <h1>Bayar, Dan Dapatkan Produkmu Secepatnya</h1>
        <p>Snap Token: <strong>{{$snapToken}}</strong></p>
        <button class="btn" id="pay-button">Pay Now</button>
        <div id="snap-container"></div>
    </div>

    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            // Use the generated snapToken from the controller
            window.snap.embed('{{$snapToken}}', {
                embedId: 'snap-container',
                onSuccess: function (result) {
                    alert("Payment success!");
                    console.log(result);
                },
                onPending: function (result) {
                    alert("Waiting for your payment!");
                    console.log(result);
                },
                onError: function (result) {
                    alert("Payment failed!");
                    console.log(result);
                },
                onClose: function () {
                    alert('You closed the popup without finishing the payment');
                }
            });
        });
    </script>

</body>
</html>
