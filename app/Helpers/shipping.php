<?php 

function getCountryCitiesFromAramex($country_code)
{
	$soapClient = new SoapClient(url('/public/storage/shipping/Location-API-WSDL.wsdl'));
	$params = array(
		'ClientInfo' => array(
                        'AccountCountryCode'		=> 'JO',
                        'AccountEntity'		 	=> 'AMM',
                        'AccountNumber'		 	=> '20016',
                        'AccountPin'		 	=> '331421',
                        'UserName'			=> 'testingapi@aramex.com',
                        'Password'		 	=> 'R123456789$r',
                        'Version'		 	=> 'v1.0',
                        'Source' 			=> NULL			
                    ),

		'Transaction' => array(
                            'Reference1'			=> '001',
                            'Reference2'			=> '002',
                            'Reference3'			=> '003',
                            'Reference4'			=> '004',
                            'Reference5'			=> '005'
                        
                        ),
		'CountryCode' => $country_code,
		'State'	=> NULL,
		// 'NameStartsWith' => 'N'

		);
	
	// calling the method and printing results
	try {
		$auth_call = $soapClient->FetchCities($params);
        return (array)$auth_call;
		echo '<pre>';
		print_r($auth_call);
		die();

	} catch (SoapFault $fault) {
		die('Error : ' . $fault->faultstring);
	}
}
function aramexCities($country_code = null)
{
    $list = [];
    if ($country_code != null) {
        $apiCities = getCountryCitiesFromAramex($country_code);
        foreach($apiCities as $key => $value){
            $cities[$key] = $value;
        }
        if ($cities['HasErrors'] == false) {
            foreach ($cities['Cities']->string as $value) {
                $list[$value] = $value;
            }
        }
    }
    return $list;
}
function shippingCalculator($user_id,$shipping_id,$shippingMethod,$currency = null)
{
    $totalShipping = 0;
    $user = App\User::find($user_id);
    if ($user->cart() != '') {
        $order = App\Orders::find($user->cart()['id'])->update([
            'shipping_address_id' => $shipping_id,
            'shipping_method' => $shippingMethod
        ]);
        $distination = App\UserAddress::find($shipping_id);
        $hardCopy = [];
        $theCurrency = App\Currencies::find($currency);
        if ($theCurrency == '') {
            return [
                'status' => 'faild',
                'message' => 'check currency'
            ];
        }
        foreach ($user->cart()->subOrders as $subOrder) {
            if ($subOrder->hasHardCopy() == '1') {
                if ($subOrder->publisher != '') {
                    
                    if ($subOrder->publisher->country != '' && $subOrder->publisher->governorate) {
                        if ($shippingMethod == 'aramex') {
                            $shipping = aramexShippingCal($subOrder,$distination,$theCurrency);
                        } else {
                            $shipping = fedexShippingCal($subOrder,$distination,$theCurrency);
                        }
                        if ($shipping['status'] == 'faild') {
                            return $shipping;
                        }
                        $totalShipping += $shipping['value'];
                    } else {
                        return [
                            'status' => 'faild',
                            'message' => 'pickup point has no details'
                        ];
                    }
                } else {
                    return [
                        'status' => 'faild',
                        'message' => 'vendor has no shipping details'
                    ];
                }
            }
        }
        $order = App\Orders::find($user->cart()['id'])->update([
            'shipping_total' => $totalShipping
        ]);
        
    }
    return [
        'status' => 'success',
        'message' => round($totalShipping, 0, PHP_ROUND_HALF_UP)
    ];
}
function aramexShippingCal($order,$distination,$theCurrency)
{
                        
    $params = array(
        'ClientInfo' => array(
                        'AccountCountryCode'	=> 'AE',
                        'AccountEntity'		 	=> 'DXB',
                        'AccountNumber'		 	=> '7986893',
                        'AccountPin'		 	=> '296806',
                        'UserName'			 	=> 'dxbit@aramex.com',
                        'Password'			 	=> 'Ar@m3x$h1pp1ng',
                        'Version'			 	=> 'v1'
                    ),
        'Transaction'=> array(
                        'Reference1'			=> '001' 
                    ),
        'OriginAddress' => array(
                            'City'					=> $order->publisher->governorate,
                            'CountryCode'			=> $order->publisher->country
                        ),
        'DestinationAddress' => array(
                                'City'					=> $distination->city,
                                'CountryCode'			=> $distination->country
                            ),
        'ShipmentDetails' => array(
                            'PaymentType'			 => 'P',
                            'ProductGroup'			 => 'EXP',
                            'ProductType'			 => 'PPX',
                            'ActualWeight' 			 => array('Value' => $order->itemsCalculator()['weight'], 'Unit' => 'KG'),
                            'ChargeableWeight' 	     => array('Value' => $order->itemsCalculator()['weight'], 'Unit' => 'KG'),
                            'NumberOfPieces'		 => $order->itemsCalculator()['count']
                        )
    );
    
    $soapClient = new SoapClient(url('/public/storage/shipping/aramex-rates-calculator-wsdl.wsdl'), array('trace' => 1));
    $results = $soapClient->CalculateRate($params);	
    $arr = json_decode(json_encode($results, true), true);
    if ($arr['HasErrors'] === true) {
        return [
            'status' => 'faild',
            'message' => 'aramex api error: '.$arr['Notifications']['Notification']['Message']
        ];
    }
    if ($arr['TotalAmount']['Value'] > 0) {
        $total = round(
                        convertCurrency(
                            $arr['TotalAmount']['Value'],
                            countriesCurrencies($order->publisher->country),
                            $theCurrency->sympl_en
                        )
                    );
        return [
            'status' => 'success',
            'value' => $total
        ];         
    }
    return [
        'status' => 'success',
        'value' => 0
    ];         
}
function fedexShippingCal($order,$distination,$theCurrency)
{
    $request = new HttpRequest();
    $request->setUrl('https://apis-sandbox.fedex.com/rate/v1/rates/quotes');
    $request->setMethod(HTTP_METH_POST);

    $request->setHeaders(array(
    'Authorization' => 'Bearer ',
    'X-locale' => 'en_US',
    'Content-Type' => 'application/json'
    ));

    $request->setBody(input); // 'input' refers to JSON Payload
    $response = $request->send();

    return [
        'status' => 'faild',
        'message' => $response->getBody()
    ];

    try {
        $response = $request->send();

        echo $response->getBody();
    } catch (HttpException $ex) {
        echo $ex;
    }
    $params = array(
        'ClientInfo' => array(
                        'AccountCountryCode'	=> 'AE',
                        'AccountEntity'		 	=> 'DXB',
                        'AccountNumber'		 	=> '7986893',
                        'AccountPin'		 	=> '296806',
                        'UserName'			 	=> 'dxbit@aramex.com',
                        'Password'			 	=> 'Ar@m3x$h1pp1ng',
                        'Version'			 	=> 'v1'
                    ),
        'Transaction'=> array(
                        'Reference1'			=> '001' 
                    ),
        'OriginAddress' => array(
                            'City'					=> $order->publisher->governorate,
                            'CountryCode'			=> $order->publisher->country
                        ),
        'DestinationAddress' => array(
                                'City'					=> $distination->city,
                                'CountryCode'			=> $distination->country
                            ),
        'ShipmentDetails' => array(
                            'PaymentType'			 => 'P',
                            'ProductGroup'			 => 'EXP',
                            'ProductType'			 => 'PPX',
                            'ActualWeight' 			 => array('Value' => $order->itemsCalculator()['weight'], 'Unit' => 'KG'),
                            'ChargeableWeight' 	     => array('Value' => $order->itemsCalculator()['weight'], 'Unit' => 'KG'),
                            'NumberOfPieces'		 => $order->itemsCalculator()['count']
                        )
    );
    
    $soapClient = new SoapClient(url('/public/storage/shipping/aramex-rates-calculator-wsdl.wsdl'), array('trace' => 1));
    $results = $soapClient->CalculateRate($params);	
    $arr = json_decode(json_encode($results, true), true);
    if ($arr['HasErrors'] === true) {
        return [
            'status' => 'faild',
            'message' => 'aramex api error: '.$arr['Notifications']['Notification']['Message']
        ];
    }
    if ($arr['TotalAmount']['Value'] > 0) {
        $total = round(
                        convertCurrency(
                            $arr['TotalAmount']['Value'],
                            countriesCurrencies($order->publisher->country),
                            $theCurrency->sympl_en
                        )
                    );
        return [
            'status' => 'success',
            'value' => $total
        ];         
    }
    return [
        'status' => 'success',
        'value' => 0
    ];         
}
function createShippingOrder($from,$to)
{
    error_reporting(E_ALL);
	ini_set('display_errors', '1');
	
	$soapClient = new SoapClient(url('/public/storage/shipping/shipping-services-api-wsdl.wsdl'));
	echo '<pre>';
	print_r($soapClient->__getFunctions());

	$params = array(
			'Shipments' => array(
				'Shipment' => array(
						'Shipper'	=> array(
										'Reference1' 	=> 'Ref 111111',
										'Reference2' 	=> 'Ref 222222',
										'AccountNumber' => '20016',
										'PartyAddress'	=> array(
											'Line1'					=> 'Mecca St',
											'Line2' 				=> '',
											'Line3' 				=> '',
											'City'					=> 'Amman',
											'StateOrProvinceCode'	=> '',
											'PostCode'				=> '',
											'CountryCode'			=> 'Jo'
										),
										'Contact'		=> array(
											'Department'			=> '',
											'PersonName'			=> 'Michael',
											'Title'					=> '',
											'CompanyName'			=> 'Aramex',
											'PhoneNumber1'			=> '5555555',
											'PhoneNumber1Ext'		=> '125',
											'PhoneNumber2'			=> '',
											'PhoneNumber2Ext'		=> '',
											'FaxNumber'				=> '',
											'CellPhone'				=> '07777777',
											'EmailAddress'			=> 'michael@aramex.com',
											'Type'					=> ''
										),
						),
												
						'Consignee'	=> array(
										'Reference1'	=> 'Ref 333333',
										'Reference2'	=> 'Ref 444444',
										'AccountNumber' => '',
										'PartyAddress'	=> array(
											'Line1'					=> '15 ABC St',
											'Line2'					=> '',
											'Line3'					=> '',
											'City'					=> 'Dubai',
											'StateOrProvinceCode'	=> '',
											'PostCode'				=> '',
											'CountryCode'			=> 'AE'
										),
										
										'Contact'		=> array(
											'Department'			=> '',
											'PersonName'			=> 'Mazen',
											'Title'					=> '',
											'CompanyName'			=> 'Aramex',
											'PhoneNumber1'			=> '6666666',
											'PhoneNumber1Ext'		=> '155',
											'PhoneNumber2'			=> '',
											'PhoneNumber2Ext'		=> '',
											'FaxNumber'				=> '',
											'CellPhone'				=> '',
											'EmailAddress'			=> 'mazen@aramex.com',
											'Type'					=> ''
										),
						),
						
						'ThirdParty' => array(
										'Reference1' 	=> '',
										'Reference2' 	=> '',
										'AccountNumber' => '',
										'PartyAddress'	=> array(
											'Line1'					=> '',
											'Line2'					=> '',
											'Line3'					=> '',
											'City'					=> '',
											'StateOrProvinceCode'	=> '',
											'PostCode'				=> '',
											'CountryCode'			=> ''
										),
										'Contact'		=> array(
											'Department'			=> '',
											'PersonName'			=> '',
											'Title'					=> '',
											'CompanyName'			=> '',
											'PhoneNumber1'			=> '',
											'PhoneNumber1Ext'		=> '',
											'PhoneNumber2'			=> '',
											'PhoneNumber2Ext'		=> '',
											'FaxNumber'				=> '',
											'CellPhone'				=> '',
											'EmailAddress'			=> '',
											'Type'					=> ''							
										),
						),
						
						'Reference1' 				=> 'Shpt 0001',
						'Reference2' 				=> '',
						'Reference3' 				=> '',
						'ForeignHAWB'				=> 'ABC 000111',
						'TransportType'				=> 0,
						'ShippingDateTime' 			=> time(),
						'DueDate'					=> time(),
						'PickupLocation'			=> 'Reception',
						'PickupGUID'				=> '',
						'Comments'					=> 'Shpt 0001',
						'AccountingInstrcutions' 	=> '',
						'OperationsInstructions'	=> '',
						
						'Details' => array(
										'Dimensions' => array(
											'Length'				=> 10,
											'Width'					=> 10,
											'Height'				=> 10,
											'Unit'					=> 'cm',
											
										),
										
										'ActualWeight' => array(
											'Value'					=> 0.5,
											'Unit'					=> 'Kg'
										),
										
										'ProductGroup' 			=> 'EXP',
										'ProductType'			=> 'PDX',
										'PaymentType'			=> 'P',
										'PaymentOptions' 		=> '',
										'Services'				=> '',
										'NumberOfPieces'		=> 1,
										'DescriptionOfGoods' 	=> 'Docs',
										'GoodsOriginCountry' 	=> 'Jo',
										
										'CashOnDeliveryAmount' 	=> array(
											'Value'					=> 0,
											'CurrencyCode'			=> ''
										),
										
										'InsuranceAmount'		=> array(
											'Value'					=> 0,
											'CurrencyCode'			=> ''
										),
										
										'CollectAmount'			=> array(
											'Value'					=> 0,
											'CurrencyCode'			=> ''
										),
										
										'CashAdditionalAmount'	=> array(
											'Value'					=> 0,
											'CurrencyCode'			=> ''							
										),
										
										'CashAdditionalAmountDescription' => '',
										
										'CustomsValueAmount' => array(
											'Value'					=> 0,
											'CurrencyCode'			=> ''								
										),
										
										'Items' 				=> array(
											
										)
						),
				),
		),
		
			'ClientInfo'  			=> array(
										'AccountCountryCode'	=> 'JO',
										'AccountEntity'		 	=> 'AMM',
										'AccountNumber'		 	=> '20016',
										'AccountPin'		 	=> '221321',
										'UserName'			 	=> 'reem@reem.com',
										'Password'			 	=> '123456789',
										'Version'			 	=> '1.0'
									),

			'Transaction' 			=> array(
										'Reference1'			=> '001',
										'Reference2'			=> '', 
										'Reference3'			=> '', 
										'Reference4'			=> '', 
										'Reference5'			=> '',									
									),
			'LabelInfo'				=> array(
										'ReportID' 				=> 9201,
										'ReportType'			=> 'URL',
			),
	);
	
	$params['Shipments']['Shipment']['Details']['Items'][] = array(
		'PackageType' 	=> 'Box',
		'Quantity'		=> 1,
		'Weight'		=> array(
				'Value'		=> 0.5,
				'Unit'		=> 'Kg',		
		),
		'Comments'		=> 'Docs',
		'Reference'		=> ''
	);
	
	print_r($params);
	
	try {
		$auth_call = $soapClient->CreateShipments($params);
		echo '<pre>';
		print_r($auth_call);
		die();
	} catch (SoapFault $fault) {
		die('Error : ' . $fault->faultstring);
	}
}
function convertCurrency($amount, $from, $to)  
{  
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://currency-exchange.p.rapidapi.com/exchange?to=".$to."&from=".$from."&q=".$amount,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: currency-exchange.p.rapidapi.com",
            "X-RapidAPI-Key: 0155dcbd53mshdb4bf21872b7c19p17b11djsn14819770674f"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    // if ($err) {
    //     echo "cURL Error #:" . $err;
    // } else {
    //     echo $response;
    // }
    return $amount*$response;  
} 
