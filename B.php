<?php
echo "<pre>";

//Model

class Data {
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

//------------------------------------------------------------------------------
interface Reader {
    public function read( $Data ):Data;
}
/*

abstract class Reader {
    abstract public function read( $Data ):Data;
}
*/

class CardReader implements Reader {
    public function read( $Data ):Data {
        return $Card = Card::init( $Data );
    }
}

/*

class RequestReader extends Reader {
    public function read( Data $Data ) {
    }
}
*/

//------------------------------------------------------------------------------
$DB = [
    'Vor'=>[
        'Pin'=>'1234567890',
        'Number'=>'#98765923756325',
        'CV'=>'CV#1634567896',
        'Total'=>'1000000',
        'ExpDate'=>'23.12.35',
    ],

    'Bank'=>
    ['BankName'=>'ReachYardBeachesBank',
    'BankLicence'=>'ADV934.0000.3A',
    'IdBank'=>'#00004574258', ],
    'User'=>[
        'Name'=>'Modest',
        'SurName'=>'Codest',

    ]
];

$Data = new Data( $DB );

$Card = CardReader::read( $Data );
print_r( $Card);

print_r( $Card->Data->Data['Vor']['Pin'] );

$Input = ['Vor'=>['Pin'=>'1234567890', 'AltSum'=>'-1']];
$Data = new Data( $Input );
$Request = Request::init( $Data );
print_r( $Request->Data->Data['Vor']['Pin'] );
print_r( BalanceController::updateBalance( $Request, $Card ) );

//Facade//

class Validator {
    public static function validate( Request $Request, Card  $Card ) {
        if ( self::validatePin( $Request,   $Card ) && self::validateCardNumber( $Card ) ) {
            return true;
        }
    }

    public static function validatePin( Request $Request, Card  $Card ) {
        return $Card->Data->Data['Vor']['Pin'] == $Request->Data->Data['Vor']['Pin']? true: false;
    }

    public static function validateCardNumber( Card  $Card ) {
        return preg_match( '/^[#]+/', $Card->Data->Data['Vor']['Number'] )? true: false;
    }

}
//------------------------------------------------------------------------------

class Display {
    public static function view( $Message ):Data {
    }
}
//Controllers

abstract class Controller {
    public function view( $Data ) {
        print_r( $Data );
    }
}

//------------------------------------------------------------------------------

class BalanceController extends Controller {

    public function showBalance( Card $Card ) {
        return view( $Card );
    }

    public static function updateBalance( Request $Request, Card  $Card ) {
        if ( Validator::validate( $Request, $Card ) ) {
            $Card->Data->Data['Vor']['Total'] = $Card->Data->Data['Vor']['Total'] + $Request->Data->Data['Vor']['AltSum'];
            return $Card;
        }
    }
}

