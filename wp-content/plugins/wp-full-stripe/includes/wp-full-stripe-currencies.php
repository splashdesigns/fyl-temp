<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2018.05.31.
 * Time: 11:09
 */
class MM_WPFS_Currencies {

	public static function get_currency_symbol_for( $currency ) {
		$currencyArray = MM_WPFS_Currencies::get_currency_for( $currency );
		if ( is_array( $currencyArray ) && array_key_exists( 'symbol', $currencyArray ) ) {
			return $currencyArray['symbol'];
		}

		return null;
	}

	public static function get_currency_for( $currency ) {
		if ( isset( $currency ) ) {

			$currencyKey         = strtolower( $currency );
			$availableCurrencies = MM_WPFS_Currencies::get_available_currencies();

			if ( isset( $availableCurrencies ) && array_key_exists( $currencyKey, $availableCurrencies ) ) {
				$currencyArray = $availableCurrencies[ $currencyKey ];
			} else {
				$currencyArray = null;
			}

			return $currencyArray;
		}

		return null;
	}

	public static function get_available_currencies() {
		return array(
			'aed' => array(
				'code'               => 'AED',
				'name'               => 'United Arab Emirates Dirham',
				'symbol'             => 'DH',
				'zeroDecimalSupport' => false
			),
			'afn' => array(
				'code'               => 'AFN',
				'name'               => 'Afghan Afghani',
				'symbol'             => '؋',
				'zeroDecimalSupport' => false
			),
			'all' => array(
				'code'               => 'ALL',
				'name'               => 'Albanian Lek',
				'symbol'             => 'L',
				'zeroDecimalSupport' => false
			),
			'amd' => array(
				'code'               => 'AMD',
				'name'               => 'Armenian Dram',
				'symbol'             => '֏',
				'zeroDecimalSupport' => false
			),
			'ang' => array(
				'code'               => 'ANG',
				'name'               => 'Netherlands Antillean Gulden',
				'symbol'             => 'ƒ',
				'zeroDecimalSupport' => false
			),
			'aoa' => array(
				'code'               => 'AOA',
				'name'               => 'Angolan Kwanza',
				'symbol'             => 'Kz',
				'zeroDecimalSupport' => false
			),
			'ars' => array(
				'code'               => 'ARS',
				'name'               => 'Argentine Peso',
				'symbol'             => '$',
				'zeroDecimalSupport' => false
			),
			'aud' => array(
				'code'               => 'AUD',
				'name'               => 'Australian Dollar',
				'symbol'             => '$',
				'zeroDecimalSupport' => false
			),
			'awg' => array(
				'code'               => 'AWG',
				'name'               => 'Aruban Florin',
				'symbol'             => 'ƒ',
				'zeroDecimalSupport' => false
			),
			'azn' => array(
				'code'               => 'AZN',
				'name'               => 'Azerbaijani Manat',
				'symbol'             => 'm.',
				'zeroDecimalSupport' => false
			),
			'bam' => array(
				'code'               => 'BAM',
				'name'               => 'Bosnia & Herzegovina Convertible Mark',
				'symbol'             => 'KM',
				'zeroDecimalSupport' => false
			),
			'bbd' => array(
				'code'               => 'BBD',
				'name'               => 'Barbadian Dollar',
				'symbol'             => 'Bds$',
				'zeroDecimalSupport' => false
			),
			'bdt' => array(
				'code'               => 'BDT',
				'name'               => 'Bangladeshi Taka',
				'symbol'             => '৳',
				'zeroDecimalSupport' => false
			),
			'bgn' => array(
				'code'               => 'BGN',
				'name'               => 'Bulgarian Lev',
				'symbol'             => 'лв',
				'zeroDecimalSupport' => false
			),
			'bif' => array(
				'code'               => 'BIF',
				'name'               => 'Burundian Franc',
				'symbol'             => 'FBu',
				'zeroDecimalSupport' => true
			),
			'bmd' => array(
				'code'               => 'BMD',
				'name'               => 'Bermudian Dollar',
				'symbol'             => 'BD$',
				'zeroDecimalSupport' => false
			),
			'bnd' => array(
				'code'               => 'BND',
				'name'               => 'Brunei Dollar',
				'symbol'             => 'B$',
				'zeroDecimalSupport' => false
			),
			'bob' => array(
				'code'               => 'BOB',
				'name'               => 'Bolivian Boliviano',
				'symbol'             => 'Bs.',
				'zeroDecimalSupport' => false
			),
			'brl' => array(
				'code'               => 'BRL',
				'name'               => 'Brazilian Real',
				'symbol'             => 'R$',
				'zeroDecimalSupport' => false
			),
			'bsd' => array(
				'code'               => 'BSD',
				'name'               => 'Bahamian Dollar',
				'symbol'             => 'B$',
				'zeroDecimalSupport' => false
			),
			'bwp' => array(
				'code'               => 'BWP',
				'name'               => 'Botswana Pula',
				'symbol'             => 'P',
				'zeroDecimalSupport' => false
			),
			'bzd' => array(
				'code'               => 'BZD',
				'name'               => 'Belize Dollar',
				'symbol'             => 'BZ$',
				'zeroDecimalSupport' => false
			),
			'cad' => array(
				'code'               => 'CAD',
				'name'               => 'Canadian Dollar',
				'symbol'             => '$',
				'zeroDecimalSupport' => false
			),
			'cdf' => array(
				'code'               => 'CDF',
				'name'               => 'Congolese Franc',
				'symbol'             => 'CF',
				'zeroDecimalSupport' => false
			),
			'chf' => array(
				'code'               => 'CHF',
				'name'               => 'Swiss Franc',
				'symbol'             => 'Fr',
				'zeroDecimalSupport' => false
			),
			'clp' => array(
				'code'               => 'CLP',
				'name'               => 'Chilean Peso',
				'symbol'             => 'CLP$',
				'zeroDecimalSupport' => true
			),
			'cny' => array(
				'code'               => 'CNY',
				'name'               => 'Chinese Renminbi Yuan',
				'symbol'             => '¥',
				'zeroDecimalSupport' => false
			),
			'cop' => array(
				'code'               => 'COP',
				'name'               => 'Colombian Peso',
				'symbol'             => 'COL$',
				'zeroDecimalSupport' => false
			),
			'crc' => array(
				'code'               => 'CRC',
				'name'               => 'Costa Rican Colón',
				'symbol'             => '₡',
				'zeroDecimalSupport' => false
			),
			'cve' => array(
				'code'               => 'CVE',
				'name'               => 'Cape Verdean Escudo',
				'symbol'             => 'Esc',
				'zeroDecimalSupport' => false
			),
			'czk' => array(
				'code'               => 'CZK',
				'name'               => 'Czech Koruna',
				'symbol'             => 'Kč',
				'zeroDecimalSupport' => false
			),
			'djf' => array(
				'code'               => 'DJF',
				'name'               => 'Djiboutian Franc',
				'symbol'             => 'Fr',
				'zeroDecimalSupport' => true
			),
			'dkk' => array(
				'code'               => 'DKK',
				'name'               => 'Danish Krone',
				'symbol'             => 'kr',
				'zeroDecimalSupport' => false
			),
			'dop' => array(
				'code'               => 'DOP',
				'name'               => 'Dominican Peso',
				'symbol'             => 'RD$',
				'zeroDecimalSupport' => false
			),
			'dzd' => array(
				'code'               => 'DZD',
				'name'               => 'Algerian Dinar',
				'symbol'             => 'DA',
				'zeroDecimalSupport' => false
			),
			'egp' => array(
				'code'               => 'EGP',
				'name'               => 'Egyptian Pound',
				'symbol'             => 'L.E.',
				'zeroDecimalSupport' => false
			),
			'etb' => array(
				'code'               => 'ETB',
				'name'               => 'Ethiopian Birr',
				'symbol'             => 'Br',
				'zeroDecimalSupport' => false
			),
			'eur' => array(
				'code'               => 'EUR',
				'name'               => 'Euro',
				'symbol'             => '€',
				'zeroDecimalSupport' => false
			),
			'fjd' => array(
				'code'               => 'FJD',
				'name'               => 'Fijian Dollar',
				'symbol'             => 'FJ$',
				'zeroDecimalSupport' => false
			),
			'fkp' => array(
				'code'               => 'FKP',
				'name'               => 'Falkland Islands Pound',
				'symbol'             => 'FK£',
				'zeroDecimalSupport' => false
			),
			'gbp' => array(
				'code'               => 'GBP',
				'name'               => 'British Pound',
				'symbol'             => '£',
				'zeroDecimalSupport' => false
			),
			'gel' => array(
				'code'               => 'GEL',
				'name'               => 'Georgian Lari',
				'symbol'             => 'ლ',
				'zeroDecimalSupport' => false
			),
			'gip' => array(
				'code'               => 'GIP',
				'name'               => 'Gibraltar Pound',
				'symbol'             => '£',
				'zeroDecimalSupport' => false
			),
			'gmd' => array(
				'code'               => 'GMD',
				'name'               => 'Gambian Dalasi',
				'symbol'             => 'D',
				'zeroDecimalSupport' => false
			),
			'gnf' => array(
				'code'               => 'GNF',
				'name'               => 'Guinean Franc',
				'symbol'             => 'FG',
				'zeroDecimalSupport' => true
			),
			'gtq' => array(
				'code'               => 'GTQ',
				'name'               => 'Guatemalan Quetzal',
				'symbol'             => 'Q',
				'zeroDecimalSupport' => false
			),
			'gyd' => array(
				'code'               => 'GYD',
				'name'               => 'Guyanese Dollar',
				'symbol'             => 'G$',
				'zeroDecimalSupport' => false
			),
			'hkd' => array(
				'code'               => 'HKD',
				'name'               => 'Hong Kong Dollar',
				'symbol'             => 'HK$',
				'zeroDecimalSupport' => false
			),
			'hnl' => array(
				'code'               => 'HNL',
				'name'               => 'Honduran Lempira',
				'symbol'             => 'L',
				'zeroDecimalSupport' => false
			),
			'hrk' => array(
				'code'               => 'HRK',
				'name'               => 'Croatian Kuna',
				'symbol'             => 'kn',
				'zeroDecimalSupport' => false
			),
			'htg' => array(
				'code'               => 'HTG',
				'name'               => 'Haitian Gourde',
				'symbol'             => 'G',
				'zeroDecimalSupport' => false
			),
			'huf' => array(
				'code'               => 'HUF',
				'name'               => 'Hungarian Forint',
				'symbol'             => 'Ft',
				'zeroDecimalSupport' => false
			),
			'idr' => array(
				'code'               => 'IDR',
				'name'               => 'Indonesian Rupiah',
				'symbol'             => 'Rp',
				'zeroDecimalSupport' => false
			),
			'ils' => array(
				'code'               => 'ILS',
				'name'               => 'Israeli New Sheqel',
				'symbol'             => '₪',
				'zeroDecimalSupport' => false
			),
			'inr' => array(
				'code'               => 'INR',
				'name'               => 'Indian Rupee',
				'symbol'             => '₹',
				'zeroDecimalSupport' => false
			),
			'isk' => array(
				'code'               => 'ISK',
				'name'               => 'Icelandic Króna',
				'symbol'             => 'ikr',
				'zeroDecimalSupport' => false
			),
			'jmd' => array(
				'code'               => 'JMD',
				'name'               => 'Jamaican Dollar',
				'symbol'             => 'J$',
				'zeroDecimalSupport' => false
			),
			'jpy' => array(
				'code'               => 'JPY',
				'name'               => 'Japanese Yen',
				'symbol'             => '¥',
				'zeroDecimalSupport' => true
			),
			'kes' => array(
				'code'               => 'KES',
				'name'               => 'Kenyan Shilling',
				'symbol'             => 'Ksh',
				'zeroDecimalSupport' => false
			),
			'kgs' => array(
				'code'               => 'KGS',
				'name'               => 'Kyrgyzstani Som',
				'symbol'             => 'COM',
				'zeroDecimalSupport' => false
			),
			'khr' => array(
				'code'               => 'KHR',
				'name'               => 'Cambodian Riel',
				'symbol'             => '៛',
				'zeroDecimalSupport' => false
			),
			'kmf' => array(
				'code'               => 'KMF',
				'name'               => 'Comorian Franc',
				'symbol'             => 'CF',
				'zeroDecimalSupport' => true
			),
			'krw' => array(
				'code'               => 'KRW',
				'name'               => 'South Korean Won',
				'symbol'             => '₩',
				'zeroDecimalSupport' => true
			),
			'kyd' => array(
				'code'               => 'KYD',
				'name'               => 'Cayman Islands Dollar',
				'symbol'             => 'CI$',
				'zeroDecimalSupport' => false
			),
			'kzt' => array(
				'code'               => 'KZT',
				'name'               => 'Kazakhstani Tenge',
				'symbol'             => '₸',
				'zeroDecimalSupport' => false
			),
			'lak' => array(
				'code'               => 'LAK',
				'name'               => 'Lao Kip',
				'symbol'             => '₭',
				'zeroDecimalSupport' => false
			),
			'lbp' => array(
				'code'               => 'LBP',
				'name'               => 'Lebanese Pound',
				'symbol'             => 'LL',
				'zeroDecimalSupport' => false
			),
			'lkr' => array(
				'code'               => 'LKR',
				'name'               => 'Sri Lankan Rupee',
				'symbol'             => 'SLRs',
				'zeroDecimalSupport' => false
			),
			'lrd' => array(
				'code'               => 'LRD',
				'name'               => 'Liberian Dollar',
				'symbol'             => 'L$',
				'zeroDecimalSupport' => false
			),
			'lsl' => array(
				'code'               => 'LSL',
				'name'               => 'Lesotho Loti',
				'symbol'             => 'M',
				'zeroDecimalSupport' => false
			),
			'mad' => array(
				'code'               => 'MAD',
				'name'               => 'Moroccan Dirham',
				'symbol'             => 'DH',
				'zeroDecimalSupport' => false
			),
			'mdl' => array(
				'code'               => 'MDL',
				'name'               => 'Moldovan Leu',
				'symbol'             => 'MDL',
				'zeroDecimalSupport' => false
			),
			'mga' => array(
				'code'               => 'MGA',
				'name'               => 'Malagasy Ariary',
				'symbol'             => 'Ar',
				'zeroDecimalSupport' => true
			),
			'mkd' => array(
				'code'               => 'MKD',
				'name'               => 'Macedonian Denar',
				'symbol'             => 'ден',
				'zeroDecimalSupport' => false
			),
			'mnt' => array(
				'code'               => 'MNT',
				'name'               => 'Mongolian Tögrög',
				'symbol'             => '₮',
				'zeroDecimalSupport' => false
			),
			'mop' => array(
				'code'               => 'MOP',
				'name'               => 'Macanese Pataca',
				'symbol'             => 'MOP$',
				'zeroDecimalSupport' => false
			),
			'mro' => array(
				'code'               => 'MRO',
				'name'               => 'Mauritanian Ouguiya',
				'symbol'             => 'UM',
				'zeroDecimalSupport' => false
			),
			'mur' => array(
				'code'               => 'MUR',
				'name'               => 'Mauritian Rupee',
				'symbol'             => 'Rs',
				'zeroDecimalSupport' => false
			),
			'mvr' => array(
				'code'               => 'MVR',
				'name'               => 'Maldivian Rufiyaa',
				'symbol'             => 'Rf.',
				'zeroDecimalSupport' => false
			),
			'mwk' => array(
				'code'               => 'MWK',
				'name'               => 'Malawian Kwacha',
				'symbol'             => 'MK',
				'zeroDecimalSupport' => false
			),
			'mxn' => array(
				'code'               => 'MXN',
				'name'               => 'Mexican Peso',
				'symbol'             => '$',
				'zeroDecimalSupport' => false
			),
			'myr' => array(
				'code'               => 'MYR',
				'name'               => 'Malaysian Ringgit',
				'symbol'             => 'RM',
				'zeroDecimalSupport' => false
			),
			'mzn' => array(
				'code'               => 'MZN',
				'name'               => 'Mozambican Metical',
				'symbol'             => 'MT',
				'zeroDecimalSupport' => false
			),
			'nad' => array(
				'code'               => 'NAD',
				'name'               => 'Namibian Dollar',
				'symbol'             => 'N$',
				'zeroDecimalSupport' => false
			),
			'ngn' => array(
				'code'               => 'NGN',
				'name'               => 'Nigerian Naira',
				'symbol'             => '₦',
				'zeroDecimalSupport' => false
			),
			'nio' => array(
				'code'               => 'NIO',
				'name'               => 'Nicaraguan Córdoba',
				'symbol'             => 'C$',
				'zeroDecimalSupport' => false
			),
			'nok' => array(
				'code'               => 'NOK',
				'name'               => 'Norwegian Krone',
				'symbol'             => 'kr',
				'zeroDecimalSupport' => false
			),
			'npr' => array(
				'code'               => 'NPR',
				'name'               => 'Nepalese Rupee',
				'symbol'             => 'NRs',
				'zeroDecimalSupport' => false
			),
			'nzd' => array(
				'code'               => 'NZD',
				'name'               => 'New Zealand Dollar',
				'symbol'             => 'NZ$',
				'zeroDecimalSupport' => false
			),
			'pab' => array(
				'code'               => 'PAB',
				'name'               => 'Panamanian Balboa',
				'symbol'             => 'B/.',
				'zeroDecimalSupport' => false
			),
			'pen' => array(
				'code'               => 'PEN',
				'name'               => 'Peruvian Nuevo Sol',
				'symbol'             => 'S/.',
				'zeroDecimalSupport' => false
			),
			'pgk' => array(
				'code'               => 'PGK',
				'name'               => 'Papua New Guinean Kina',
				'symbol'             => 'K',
				'zeroDecimalSupport' => false
			),
			'php' => array(
				'code'               => 'PHP',
				'name'               => 'Philippine Peso',
				'symbol'             => '₱',
				'zeroDecimalSupport' => false
			),
			'pkr' => array(
				'code'               => 'PKR',
				'name'               => 'Pakistani Rupee',
				'symbol'             => 'PKR',
				'zeroDecimalSupport' => false
			),
			'pln' => array(
				'code'               => 'PLN',
				'name'               => 'Polish Złoty',
				'symbol'             => 'zł',
				'zeroDecimalSupport' => false
			),
			'pyg' => array(
				'code'               => 'PYG',
				'name'               => 'Paraguayan Guaraní',
				'symbol'             => '₲',
				'zeroDecimalSupport' => true
			),
			'qar' => array(
				'code'               => 'QAR',
				'name'               => 'Qatari Riyal',
				'symbol'             => 'QR',
				'zeroDecimalSupport' => false
			),
			'ron' => array(
				'code'               => 'RON',
				'name'               => 'Romanian Leu',
				'symbol'             => 'RON',
				'zeroDecimalSupport' => false
			),
			'rsd' => array(
				'code'               => 'RSD',
				'name'               => 'Serbian Dinar',
				'symbol'             => 'дин',
				'zeroDecimalSupport' => false
			),
			'rub' => array(
				'code'               => 'RUB',
				'name'               => 'Russian Ruble',
				'symbol'             => 'руб',
				'zeroDecimalSupport' => false
			),
			'rwf' => array(
				'code'               => 'RWF',
				'name'               => 'Rwandan Franc',
				'symbol'             => 'FRw',
				'zeroDecimalSupport' => true
			),
			'sar' => array(
				'code'               => 'SAR',
				'name'               => 'Saudi Riyal',
				'symbol'             => 'SR',
				'zeroDecimalSupport' => false
			),
			'sbd' => array(
				'code'               => 'SBD',
				'name'               => 'Solomon Islands Dollar',
				'symbol'             => 'SI$',
				'zeroDecimalSupport' => false
			),
			'scr' => array(
				'code'               => 'SCR',
				'name'               => 'Seychellois Rupee',
				'symbol'             => 'SRe',
				'zeroDecimalSupport' => false
			),
			'sek' => array(
				'code'               => 'SEK',
				'name'               => 'Swedish Krona',
				'symbol'             => 'kr',
				'zeroDecimalSupport' => false
			),
			'sgd' => array(
				'code'               => 'SGD',
				'name'               => 'Singapore Dollar',
				'symbol'             => 'S$',
				'zeroDecimalSupport' => false
			),
			'shp' => array(
				'code'               => 'SHP',
				'name'               => 'Saint Helenian Pound',
				'symbol'             => '£',
				'zeroDecimalSupport' => false
			),
			'sll' => array(
				'code'               => 'SLL',
				'name'               => 'Sierra Leonean Leone',
				'symbol'             => 'Le',
				'zeroDecimalSupport' => false
			),
			'sos' => array(
				'code'               => 'SOS',
				'name'               => 'Somali Shilling',
				'symbol'             => 'Sh.So.',
				'zeroDecimalSupport' => false
			),
			'std' => array(
				'code'               => 'STD',
				'name'               => 'São Tomé and Príncipe Dobra',
				'symbol'             => 'Db',
				'zeroDecimalSupport' => false
			),
			'srd' => array(
				'code'               => 'SRD',
				'name'               => 'Surinamese Dollar',
				'symbol'             => 'SRD',
				'zeroDecimalSupport' => false
			),
			'svc' => array(
				'code'               => 'SVC',
				'name'               => 'Salvadoran Colón',
				'symbol'             => '₡',
				'zeroDecimalSupport' => false
			),
			'szl' => array(
				'code'               => 'SZL',
				'name'               => 'Swazi Lilangeni',
				'symbol'             => 'E',
				'zeroDecimalSupport' => false
			),
			'thb' => array(
				'code'               => 'THB',
				'name'               => 'Thai Baht',
				'symbol'             => '฿',
				'zeroDecimalSupport' => false
			),
			'tjs' => array(
				'code'               => 'TJS',
				'name'               => 'Tajikistani Somoni',
				'symbol'             => 'TJS',
				'zeroDecimalSupport' => false
			),
			'top' => array(
				'code'               => 'TOP',
				'name'               => 'Tongan Paʻanga',
				'symbol'             => '$',
				'zeroDecimalSupport' => false
			),
			'try' => array(
				'code'               => 'TRY',
				'name'               => 'Turkish Lira',
				'symbol'             => '₺',
				'zeroDecimalSupport' => false
			),
			'ttd' => array(
				'code'               => 'TTD',
				'name'               => 'Trinidad and Tobago Dollar',
				'symbol'             => 'TT$',
				'zeroDecimalSupport' => false
			),
			'twd' => array(
				'code'               => 'TWD',
				'name'               => 'New Taiwan Dollar',
				'symbol'             => 'NT$',
				'zeroDecimalSupport' => false
			),
			'tzs' => array(
				'code'               => 'TZS',
				'name'               => 'Tanzanian Shilling',
				'symbol'             => 'TSh',
				'zeroDecimalSupport' => false
			),
			'uah' => array(
				'code'               => 'UAH',
				'name'               => 'Ukrainian Hryvnia',
				'symbol'             => '₴',
				'zeroDecimalSupport' => false
			),
			'ugx' => array(
				'code'               => 'UGX',
				'name'               => 'Ugandan Shilling',
				'symbol'             => 'USh',
				'zeroDecimalSupport' => false
			),
			'usd' => array(
				'code'               => 'USD',
				'name'               => 'United States Dollar',
				'symbol'             => '$',
				'zeroDecimalSupport' => false
			),
			'uyu' => array(
				'code'               => 'UYU',
				'name'               => 'Uruguayan Peso',
				'symbol'             => '$U',
				'zeroDecimalSupport' => false
			),
			'uzs' => array(
				'code'               => 'UZS',
				'name'               => 'Uzbekistani Som',
				'symbol'             => 'UZS',
				'zeroDecimalSupport' => false
			),
			'vnd' => array(
				'code'               => 'VND',
				'name'               => 'Vietnamese Đồng',
				'symbol'             => '₫',
				'zeroDecimalSupport' => true
			),
			'vuv' => array(
				'code'               => 'VUV',
				'name'               => 'Vanuatu Vatu',
				'symbol'             => 'VT',
				'zeroDecimalSupport' => true
			),
			'wst' => array(
				'code'               => 'WST',
				'name'               => 'Samoan Tala',
				'symbol'             => 'WS$',
				'zeroDecimalSupport' => false
			),
			'xaf' => array(
				'code'               => 'XAF',
				'name'               => 'Central African Cfa Franc',
				'symbol'             => 'FCFA',
				'zeroDecimalSupport' => true
			),
			'xcd' => array(
				'code'               => 'XCD',
				'name'               => 'East Caribbean Dollar',
				'symbol'             => 'EC$',
				'zeroDecimalSupport' => false
			),
			'xof' => array(
				'code'               => 'XOF',
				'name'               => 'West African Cfa Franc',
				'symbol'             => 'CFA',
				'zeroDecimalSupport' => true
			),
			'xpf' => array(
				'code'               => 'XPF',
				'name'               => 'Cfp Franc',
				'symbol'             => 'F',
				'zeroDecimalSupport' => true
			),
			'yer' => array(
				'code'               => 'YER',
				'name'               => 'Yemeni Rial',
				'symbol'             => '﷼',
				'zeroDecimalSupport' => false
			),
			'zar' => array(
				'code'               => 'ZAR',
				'name'               => 'South African Rand',
				'symbol'             => 'R',
				'zeroDecimalSupport' => false
			),
			'zmw' => array(
				'code'               => 'ZMW',
				'name'               => 'Zambian Kwacha',
				'symbol'             => 'ZK',
				'zeroDecimalSupport' => false
			)
		);
	}

	/**
	 * @param $currency
	 * @param $amount
	 * @param bool $decimalsForInteger
	 *
	 * @return null|string
	 */
	public static function format_amount_with_currency_html_escaped( $currency, $amount, $decimalsForInteger = true ) {
		$label = self::format_amount_with_currency( $currency, $amount, $decimalsForInteger );

		return is_null( $label ) ? $label : esc_attr( $label );
	}

	public static function format_amount_with_currency( $currency, $amount, $decimalsForInteger = true ) {
		$currencyArray = MM_WPFS_Currencies::get_currency_for( $currency );
		if ( is_array( $currencyArray ) ) {
			if ( $currencyArray['zeroDecimalSupport'] == true ) {
				$theAmount = is_numeric( $amount ) ? $amount : 0;
				$pattern   = '%s%0d';
			} else {
				$theAmount = is_numeric( $amount ) ? ( $amount / 100.0 ) : 0;
				// tnagy check if theAmount is whole number
				if ( false === $decimalsForInteger && intval( $theAmount ) == ( $theAmount + 0 ) ) {
					$pattern = '%s%0d';
				} else {
					$pattern = '%s%0.2f';
				}
			}

			return sprintf( $pattern, $currencyArray['symbol'], $theAmount );
		}

		return null;
	}
}