<?php

$arr = [1, 4, 9, 16, 25, 36, 49, 64, 81, 100];

foreach($arr as $num){
	if($num % 2 == 0){
		echo "$num <br>\n";
	}

}

/*The output was achieved by iterating through each number in the array and determining
which values have a remainder of 0 when divided by 2. The remainder for each number is
found using the modulus operator, %. If the remainder for value is 0, that value is even
and is outputted to the user; otherwise, the pointer of the loop continues to the next
number.
*/

?>
