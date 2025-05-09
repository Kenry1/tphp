<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M-Pesa Payments</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@500&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #eaedf4;
            font-family: "Rubik", sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .mpesa-logo {
            width: 100px;
            margin: 20px auto;
        }
        .btn-block {
            width: 100%;
        }
        .alert-position {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 250px;
        }
    </style>
</head>
<body>
    <div class="card px-4 py-4">
        <div>
            <h4 class="mt-2 mb-3">LIPA NA MPESA</h4>
        </div>
        <div class="text-center">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/36/M-PESA_LOGO-01.png/800px-M-PESA_LOGO-01.png" alt="M-Pesa Logo" class="mpesa-logo">
        </div>
        <!-- Alert Messages -->
        <div id="alert-container" class="alert-position"></div>
        <!-- Payment Form -->
        <form class="row g-3" action="" method="POST" id="mpesa-form">
            <div class="col-12">
                <label for="inputAmount" class="form-label">Amount</label>
                <input type="number" class="form-control" name="amount" id="inputAmount" placeholder="Enter Amount" min="1" required>
            </div>
            <div class="col-12">
                <label for="inputPhone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" name="phone" id="inputPhone" placeholder="Enter Phone Number" pattern="^\+?254\d{9}$" title="Enter a valid Kenyan phone number (e.g., +254700000000)" required>
                <small class="form-text text-muted">Format: +254700000000</small>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success btn-block" name="submit" value="submit">
                    <i class="fas fa-check-circle"></i> Proceed to Pay
                </button>
            </div>
            <div class="col-12 mt-3">
                <a href="./cart.php" class="btn btn-primary btn-block">
                    <i class="fas fa-angle-right"></i> finish 
                </a>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5.1.3 JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" ></script>
    <!-- jQuery (Optional: Bootstrap 5 doesn't require jQuery, but kept if needed) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Custom JavaScript for Alerts -->
    <script>
        function showAlert(message, type='success') {
            const alertContainer = $('#alert-container');
            const alert = $(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
            alertContainer.append(alert);
            // Automatically remove alert after 5 seconds
            setTimeout(() => {
                alert.alert('close');
            }, 5000);
        }

        // Optional: Handle form submission via AJAX for better UX
        $('#mpesa-form').on('submit', function(e){
            // Uncomment below lines to enable AJAX submission
            /*
            e.preventDefault();
            const form = $(this);
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function(response){
                    // Handle success response
                    showAlert('Payment request sent. Please complete the payment on your phone.', 'success');
                },
                error: function(){
                    // Handle error response
                    showAlert('An error occurred while processing your payment.', 'danger');
                }
            });
            */
        });
    </script>
</body>
</html>

<?php
if(isset($_POST['submit'])){
    // Start output buffering to capture any output
    ob_start();
    
    date_default_timezone_set('Africa/Nairobi');
    // M-Pesa API credentials
    $consumerKey = 'nk16Y74eSbTaGQgc9WF8j6FigApqOMWr'; // Fill with your app Consumer Key
    $consumerSecret = '40fD1vRXCq90XFaU'; // Fill with your app Secret
    $BusinessShortCode = '174379';
    $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
    $PartyA = $_POST['phone'];
    $AccountReference = 'shopping';
    $TransactionDesc = 'Test Payment';
    $Amount = $_POST['amount'];
    $Timestamp = date('YmdHis');
    $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);
    $headers = ['Content-Type:application/json; charset=utf8'];
    $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    $CallBackURL = 'https://yourcallbackurl.com/callback_url.php'; // Replace with your actual callback URL
    
    // Validate inputs
    if(empty($Amount) || empty($PartyA)){
        echo '<script>
                $(document).ready(function(){
                    showAlert("Amount and Phone Number are required.", "danger");
                });
              </script>';
        exit;
    }

    // Generate access token
    $curl = curl_init($access_token_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
    $result = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if($status !== 200){
        echo '<script>
                $(document).ready(function(){
                    showAlert("Failed to obtain access token.", "danger");
                });
              </script>';
        exit;
    }
    $result = json_decode($result);
    $access_token = $result->access_token;
    curl_close($curl);

    // Initiate the STK push
    $stkheader = ['Content-Type:application/json','Authorization:Bearer '.$access_token];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $initiate_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader);
    $curl_post_data = array(
        'BusinessShortCode' => $BusinessShortCode,
        'Password' => $Password,
        'Timestamp' => $Timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $Amount,
        'PartyA' => $PartyA,
        'PartyB' => $BusinessShortCode,
        'PhoneNumber' => $PartyA,
        'CallBackURL' => $CallBackURL,
        'AccountReference' => $AccountReference,
        'TransactionDesc' => $TransactionDesc
    );
    $data_string = json_encode($curl_post_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    $curl_response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // Process the response
    if($http_code == 200){
        // Decode response to check status
        $response = json_decode($curl_response, true);
        if(isset($response['ResponseCode']) && $response['ResponseCode'] == '0'){
            // Success - Redirect or show success message
            echo '<script>
                    $(document).ready(function(){
                        showAlert("Payment request sent successfully. Please complete the payment on your phone.", "success");
                    });
                  </script>';
        } else {
            // Failed - Show error message
            $errorMessage = isset($response['errorMessage']) ? $response['errorMessage'] : 'Payment request failed.';
            echo '<script>
                    $(document).ready(function(){
                        showAlert("Error: '.$errorMessage.'", "danger");
                    });
                  </script>';
        }
    } else {
        // HTTP error
        echo '<script>
                $(document).ready(function(){
                    showAlert("HTTP Error: Unable to process the payment.", "danger");
                });
              </script>';
    }

    // Flush the output buffer
    ob_end_flush();
}
?>
