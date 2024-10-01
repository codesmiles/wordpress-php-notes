<?=
// ----------- update
        // if ($transaction_response["is_successful"] && $transaction_response["has_result"]) {
        //     /*
        //     |--------------------------------------------------------------------------
        //     | set payload
        //     |--------------------------------------------------------------------------
        //     */
        //     $current_dateTime = now();
        //     $transaction_response = (array) $transaction_response["response"];
        //     $customer_transactions = (array) $transaction_response["transactions"];

        //     $is_recent = $current_dateTime <= Carbon::parse($transaction_response["created_at"])->addMinutes(20);
        //     $is_current_page = $request->query("page") !== null
        //         ? $customer_transactions["current_page"] === (int) $request->query("page")
        //         : $customer_transactions["current_page"] === 1;

        //     /*
        //     |--------------------------------------------------------------------------
        //     | check if the customer transaction in cache is recently created and is on the same page
        //     |--------------------------------------------------------------------------
        //     */
        //     if($is_recent && $is_current_page){
        //         /*
        //         |--------------------------------------------------------------------------
        //         | response data
        //         |--------------------------------------------------------------------------
        //         */
        //         return $this->sendResponse($transaction_response["response"], C_1100_TransactionResponseCode::TRANSACTION_REQUEST_SUCCESSFUL);

        //     };

        // }
//------------ update