<?php

//Model
abstract class Data {
    public $Data = [];

    public function __construct( $Data ) {
        $this->Data =  $Data;
    }

    public static function init( $Data ) {
        return new static( $Data );
    }
    //abstract function init( $Data );
}

class Card extends Data {
    /*

    public function init( $Data ) {
        return new self( $Data );
    }
    */
}

class Request extends Data {
    /*

    public function init( $Data ) {
        return new self( $Data );
    }
    */
}

//Facade

class Validator {
    public static function validate( Request $Request, Card  $Card ) {
        if ( self::validatePin( $Request,   $Card ) && self::validateCardNumber( $Card ) ) {
            return true;
        }
    }

    public static function validatePin( Request $Request, Card  $Card ) {
        return $Card->Data['Vor']['Pin'] == $Request->Data['Vor']['Pin']? true: false;
    }

    public static function validateCardNumber( Card  $Card ) {
        return preg_match( '/^[#]+/', $Card->Data['Vor']['Number'] )? true: false;
    }

}

//Controller

class BalanceController {

    public function showBalance( Card $Card ) {
        return $Card->Data['Vor']['Total'];
    }

    public static function updateBalance( Request $Request, Card  $Card ) {
        if ( Validator::validate( $Request, $Card ) ) {
            $Card->Data['Vor']['Total'] = $Card->Data['Vor']['Total'] + $Request->Data['Vor']['AltSum'];
            return $Card;
        }
    }
}

//Process
echo "<pre>";
$CardData = [
    'Vor'=>[
        'Pin'=>'1234567890',
        'Number'=>'#98765923756325',
        'CV'=>'CV#1634567896',
        'Total'=>'1000000',
        'ExpDate'=>'23.12.35',
    ],
    'User'=>[
        'Name'=>'Modest',
        'SurName'=>'Codest',
        'FurName'=>'Yodest',
    ],
    'Bank'=>
    ['BankName'=>'ReachYardBeachesBank',
    'BankLicence'=>'ADV934.0000.3A',
    'IdBank'=>'#00004574258', ],
];

$RequestData = ['Vor'=>['Pin'=>'1234567890', 'AltSum'=>'-1']];

$Card = Card::init( $CardData );
$Request = Request::init( $RequestData );

echo "Card Pin: ";
print_r( $Card->Data['Vor']['Pin'] );

echo ", Input Pin: ";
print_r( $Request->Data['Vor']['Pin'] );

echo "<br>";
print_r( BalanceController::showBalance( $Card ) );
print_r( $Request->Data['Vor']['AltSum'] );
echo "=";
BalanceController::updateBalance( $Request, $Card );
print_r( BalanceController::showBalance( $Card ) );
