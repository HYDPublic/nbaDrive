<?php

$maxpage = 9;
$pageCourante = 9;
$nbaffiche = $nbaffiche2 = 5;
 
if( $pageCourante < $nbaffiche ) {
    $nbaffiche2 = $nbaffiche + $nbaffiche - $pageCourante;
} 
elseif( $pageCourante > $maxpage -$nbaffiche2 ) {
    $nbaffiche = $nbaffiche + $maxpage - $pageCourante;
}

for( $i=$nbaffiche ; $i>0 ; $i-- ) {
    if($pageCourante-$i > 0){
        echo '<br/><a href="#">'.($pageCourante-$i).'</a>';
    }
}

echo '<br/>-------------------------<br/>';
echo '<button class="actif">'.$pageCourante.'</button>';
echo '<br/>-------------------------<br/>';

for( $i=1 ; $i<=$nbaffiche2 ; $i++ )
    if($pageCourante+$i <= $maxpage)
        echo '<br/><a href="#">'.($pageCourante+$i).'</a>';

?>