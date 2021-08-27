<?php
function String2Hex($string){
    $hex='';
    for ($i=0; $i < strlen($string); $i++){
       if(($i+1) <= 31){
            $hexpre = dechex(ord($string[$i])-($i+1));
        }
        elseif(($i+1) <= 61){
            $hexpre = dechex(ord($string[$i])-(($i+1)-30));
        }
        elseif(($i+1) <= 91){
            $hexpre = dechex(ord($string[$i])-(($i+1)-60));
        }
        elseif(($i+1) <= 121){
            $hexpre = dechex(ord($string[$i])-(($i+1)-90));
        }
        if(strlen($hexpre)==1){
            $hexpre = "0".$hexpre;
        }
        $hex .= $hexpre;
    }
    return $hex;
}

function String2Hex2($string){
    $hex22 = '';
    for ($i=0; $i < strlen($string); $i++){
        $hexpre = dechex(ord($string[$i]));
        
        if(strlen($hexpre)==1){
            $hexpre = "0".$hexpre;
        }
        $hex22 .= $hexpre;
    }
    return $hex22;
}
 
function Hex2String($hex){
    $string='';
    $i2 = 1;
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        if(($i2+1) <= 31){
            //$hex .= dechex(ord($string[$i])-($i+1));
            $hexpre = (hexdec($hex[$i].$hex[$i+1])+($i2));
        }
        elseif(($i2+1) <= 61){
            //$hex .= dechex(ord($string[$i])-(($i+1)-30));
           $hexpre = (hexdec($hex[$i].$hex[$i+1])+($i2-30));
        }
        elseif(($i2+1) < 91){
            $hexpre = (hexdec($hex[$i].$hex[$i+1])+($i2-60));
        }
        elseif(($i2+1) < 121){
            $hexpre = (hexdec($hex[$i].$hex[$i+1])+($i2-90));
        }
        if(strlen($hexpre)==1){
            //$hexpre = "0".$hexpre;
            echo "Broke!!";
        }
        $string .= chr($hexpre);
        $i2++;
    }
    return $string;
}
 
 
$hex = "ee".String2Hex('{"SID":"Dansons-Corp","PWD":"W!f!on5thFloor"}')."ef";

$hex22 = strtoupper(String2Hex2('{"SID":"jhouse1","PWD":"4807218408"}'));

// $hex contains 746573742073656e74656e63652e2e2e
print $hex;
print "<br>"."ee7a2050453f1c331a3b5763676260641d325d5f5c0d160b383e2a071e053920641e6b692f6d603d62646365146eef";
print "<br>".$hex22;



$hex2 ='7a204a4f421c331a29282a242a272127221e24211d1c1e191816151513122f2e1f281d3d463c1930172d2423131c113b2e2f0d240b1b17272a25171b374442313e1c251a3a4449162d142724116b';
        

print "<br>new: ".Hex2String(str_replace(" ", "", $hex2));

$hex3 = Hex2String(strtoupper(str_replace(" ", "", $hex22)));
$hex4 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $hex3);
print "<br>";
print $hex4;

$json = json_decode($hex4, true);
print "<br>";
            $pc_set = substr($json['MSG'], 0, 3);
            $p1_temp = substr($json['MSG'], 3, 3);
            $p2_temp = substr($json['MSG'], 6, 3);
            $p3_temp = substr($json['MSG'], 9, 3);
            $grill_settemp = substr($json['MSG'], 12, 3);
            $grill_acttemp = substr($json['MSG'], 15, 3);
            $grill_degrees = substr($json['MSG'], 18, 3);
            print "<br>";
            echo $pc_set;
            print "<br>";
            echo $p1_temp;
            print "<br>";
            echo $p2_temp;
            print "<br>";
            echo $p3_temp;
            print "<br>";
            echo $grill_settemp;
            print "<br>";
            echo $grill_acttemp;
            print "<br>";
            echo $grill_degrees;
            print "<br>";



// outputs: test sentence...