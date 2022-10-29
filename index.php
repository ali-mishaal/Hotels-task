<?php
require_once 'core/Hotels.php';
use core\Hotels;

$advertisers = [
    'https://f704cb9e-bf27-440c-a927-4c8e57e3bad1.mock.pstmn.io/s2/availability',
    'https://f704cb9e-bf27-440c-a927-4c8e57e3bad1.mock.pstmn.io/s1/availability'
];

try{
    if(is_array($advertisers)){
        $hotels =  new Hotels($advertisers);
        echo json_encode(['hotels'=>$hotels->GetData()]);
    }

}catch (Exception $e){

    echo json_encode($e->getMessage());
}

