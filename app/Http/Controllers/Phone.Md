   // Seller Ad Wallet Phone Pay Payment Gateway Integrate Here 
    // public function addWalletAmount(Request $request)
    // {
    //     $user = Session::get('sid');
    //     $amount = $request->amount;

    //     $merchantId = 'M1SMOAY31YWH';
    //     $saltKey = '06df03a2-65b9-42f8-b7b9-26590674bc29';
    //     $saltIndex = 1;

    //     $transactionId = uniqid('TXN_');
    //     $redirectUrl = route('wallet.payment.callback');
    //     $callbackUrl = $redirectUrl;

    //     // Get latest wallet total for user
    //     $latestWallet = Wallet::where('userid', $user)->orderBy('id', 'desc')->first();
    //     $currentTotal = $latestWallet ? $latestWallet->total : 0;

    //     // Calculate new total
    //     $newTotal = $currentTotal + $amount;

    //     // Save pending transaction
    //     $wallet = new Wallet();
    //     $wallet->userid = $user;
    //     $wallet->c_amount = $amount;
    //     $wallet->d_amount = 0;
    //     $wallet->total = $newTotal;
    //     $wallet->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //     $wallet->status = 'pending';
    //     $wallet->adminid = null;
    //     $wallet->refno = $transactionId;
    //     $wallet->msg = 'credit';
    //     $wallet->save();

    //     // Create payload
    //     $amountInPaise = (int) ($amount * 100);
    //     $payload = [
    //         'merchantId' => $merchantId,
    //         'merchantTransactionId' => $transactionId,
    //         'merchantUserId' => 'user_' . $user,
    //         'amount' => $amountInPaise,
    //         'redirectUrl' => $redirectUrl,
    //         'redirectMode' => 'POST',
    //         'callbackUrl' => $callbackUrl,
    //         'paymentInstrument' => [
    //             'type' => 'PAY_PAGE',
    //         ],
    //     ];


    //     $jsonPayload = json_encode($payload);
    //     $base64Payload = base64_encode($jsonPayload);
    //     $stringToSign = $base64Payload . "/pg/v1/pay" . $saltKey;
    //     $xVerify = hash('sha256', $stringToSign) . "###" . $saltIndex;

    //     // Send request to PhonePe
    //     $response = Http::withHeaders([
    //         'Content-Type' => 'application/json',
    //         'X-VERIFY' => $xVerify,
    //         'X-MERCHANT-ID' => $merchantId,
    //     ])->withBody(json_encode(['request' => $base64Payload]), 'application/json')
    //         ->post('https://api.phonepe.com/apis/hermes/pg/v1/pay');

    //     $res = $response->json();
    //     if (isset($res['success']) && $res['success']) {
    //         $paymentUrl = $res['data']['instrumentResponse']['redirectInfo']['url'];
    //         return redirect()->away($paymentUrl);
    //     } else {
    //         return back()->with('error', 'Payment failed: ' . ($res['code'] ?? 'Unknown Error'));
    //     }
    // }

    // public function addWalletAmount(Request $request)
    // {
    //     $request->validate([
    //         'amount' => 'required|numeric|min:1',
    //     ]);

    //     $user = Session::get('sid');
    //     if (!$user) {
    //         \Log::error('User session not found');
    //         return back()->with('error', 'Session expired. Please log in again.');
    //     }

    //     $amount = $request->amount;

    //     $merchantId = 'M1SMOAY31YWH';
    //     $saltKey = '06df03a2-65b9-42f8-b7b9-26590674bc29';
    //     $saltIndex = 1;

    //     $transactionId = uniqid('TXN_');
    //     $redirectUrl = route('wallet.payment.callback');
    //     $callbackUrl = $redirectUrl;

    //     $latestWallet = Wallet::where('userid', $user)->orderBy('id', 'desc')->first();
    //     $currentTotal = $latestWallet ? $latestWallet->total : 0;
    //     $newTotal = $currentTotal + $amount;

    //     $wallet = new Wallet();
    //     $wallet->userid = $user;
    //     $wallet->c_amount = $amount;
    //     $wallet->d_amount = 0;
    //     $wallet->total = $newTotal;
    //     $wallet->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //     $wallet->status = 'pending';
    //     $wallet->adminid = null;
    //     $wallet->refno = $transactionId;
    //     $wallet->msg = 'credit';
    //     $wallet->save();

    //     $amountInPaise = (int) ($amount * 100);
    //     $payload = [
    //         'merchantId' => $merchantId,
    //         'merchantTransactionId' => $transactionId,
    //         'merchantUserId' => 'user_' . $user,
    //         'amount' => $amountInPaise,
    //         'redirectUrl' => $redirectUrl,
    //         'redirectMode' => 'POST',
    //         'callbackUrl' => $callbackUrl,
    //         'paymentInstrument' => [
    //             'type' => 'PAY_PAGE',
    //         ],
    //     ];

    //     $jsonPayload = json_encode($payload);
    //     $base64Payload = base64_encode($jsonPayload);
    //     $stringToSign = $base64Payload . "/pg/v1/pay" . $saltKey;
    //     $xVerify = hash('sha256', $stringToSign) . "###" . $saltIndex;

    //     try {
    //         $response = Http::withHeaders([
    //             'Content-Type' => 'application/json',
    //             'X-VERIFY' => $xVerify,
    //             'X-MERCHANT-ID' => $merchantId,
    //         ])->withBody(json_encode(['request' => $base64Payload]), 'application/json')
    //             ->post('https://api.phonepe.com/apis/hermes/pg/v1/pay');

    //         $res = $response->json();
    //         \Log::info('PhonePe Payment Initiation Response:', $res);

    //         if (isset($res['success']) && $res['success']) {
    //             $paymentUrl = $res['data']['instrumentResponse']['redirectInfo']['url'];
    //             return redirect()->away($paymentUrl);
    //         } else {
    //             \Log::error('Payment initiation failed:', $res);
    //             return back()->with('error', 'Payment failed: ' . ($res['code'] ?? 'Unknown Error'));
    //         }
    //     } catch (\Exception $e) {
    //         \Log::error('Error initiating payment: ' . $e->getMessage());
    //         return back()->with('error', 'An error occurred while initiating payment.');
    //     }
    // }

    // public function addWalletAmount(Request $request)
    // {
    //     $request->validate([
    //         'amount' => 'required|numeric|min:1',
    //         'razorpay_payment_id' => 'required|string'
    //     ]);

    //     $user = Session::get('bid');

    //     $data = Wallet::where('userid', $user)
    //         ->orderBy('id', 'desc')
    //         ->first();

        


    //     $total = $data ? $data->total + $request->amount : $request->amount;

    //     $wlt = new Wallet();
    //     $wlt->userid = $user;
    //     $wlt->c_amount = $request->amount;
    //     $wlt->datetime = now('Asia/Kolkata')->format('d-m-Y | h:i:s A');
    //     $wlt->total = $total;
    //     $wlt->msg = 'credit';
    //     $wlt->txn_id = $request->razorpay_payment_id; // Save Razorpay payment ID
    //     $wlt->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Amount added successfully!',
    //     ]);
    // }

    // public function walletPaymentCallback(Request $request)
    // {
    //     Log::info('Wallet Payment Callback Request:', $request->all());

    //     $input = $request->all();
    //     $merchantTransactionId = $input['merchantTransactionId'] ?? ($input['transactionId'] ?? null);

    //     if (!$merchantTransactionId) {
    //         Log::error('Invalid callback data: No merchantTransactionId');
    //         return redirect()->to('https://delhiparcel.com/seller-wallet')->with('error', 'Invalid callback data.');
    //     }

    //     // Re-verify with PhonePe
    //     $merchantId = env('PHONEPE_MERCHANT_ID', 'M1SMOAY31YWH');
    //     $saltKey = env('PHONEPE_SALT_KEY', '06df03a2-65b9-42f8-b7b9-26590674bc29');
    //     $saltIndex = env('PHONEPE_SALT_INDEX', 1);

    //     $url = "https://api.phonepe.com/apis/hermes/pg/v1/status/{$merchantId}/{$merchantTransactionId}";
    //     $stringToSign = "/pg/v1/status/{$merchantId}/{$merchantTransactionId}" . $saltKey;
    //     $xVerify = hash('sha256', $stringToSign) . "###" . $saltIndex;

    //     try {
    //         $response = Http::withHeaders([
    //             'X-VERIFY' => $xVerify,
    //             'X-MERCHANT-ID' => $merchantId
    //         ])->get($url);

    //         $res = $response->json();
    //         Log::info('PhonePe Status API Response:', $res);

    //         if (isset($res['success']) && $res['success'] === true && $res['data']['state'] === 'COMPLETED') {
    //             $wallet = Wallet::where('refno', $merchantTransactionId)->first();

    //             if ($wallet && $wallet->status !== 'success') {
    //                 // Update wallet status
    //                 $last = Wallet::where('userid', $wallet->userid)
    //                     ->where('status', 'success')
    //                     ->orderBy('id', 'desc')
    //                     ->first();
    //                 $previousTotal = $last ? $last->total : 0;

    //                 $wallet->total = $previousTotal + $wallet->c_amount;
    //                 $wallet->status = 'success';
    //                 $wallet->save();

    //                 // Restore user session
    //                 Auth::loginUsingId($wallet->userid);
    //                 Session::put('sid', $wallet->userid);

    //                 Log::info('Wallet updated successfully for transaction: ' . $merchantTransactionId);
    //                 return redirect()->to('https://delhiparcel.com/seller-wallet')->with('success', 'Wallet updated successfully!');
    //             } else {
    //                 Log::warning('Wallet not found or already success for transaction: ' . $merchantTransactionId);
    //                 return redirect()->to('https://delhiparcel.com/seller-wallet')->with('error', 'Wallet already updated or not found.');
    //             }
    //         } else {
    //             Log::error('Payment failed for transaction: ' . $merchantTransactionId, ['response' => $res]);
    //             Wallet::where('refno', $merchantTransactionId)->update(['status' => 'failed']);
    //             return redirect()->to('https://delhiparcel.com/seller-wallet')->with('error', 'Payment failed: ' . ($res['code'] ?? 'Unknown Error'));
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('Error in wallet payment callback: ' . $e->getMessage());
    //         return redirect()->to('https://delhiparcel.com/seller-wallet')->with('error', 'An error occurred during payment processing.');
    //     }
    // }


       <!-- <form method="POST" action="{{ route('seller.addWalletAmount') }}">

          <form method="POST" action="{{ route('seller.addWalletAmount') }}">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="amount" placeholder="0.0" required>
                            <div class="input-group-append">
                                <button type="submit"  class="btn btn-success">Pay Now</button>
                            </div>
                        </div>
                    </form>