<?php

function convert_to_graph_colors($colors_array) {

  $final_array = [];

  foreach ($colors_array as $color) {

    if ($color == 'blue') array_push($final_array, '#0b62a4');
    elseif ($color == 'red') array_push($final_array, '#D64541');
    elseif ($color == 'green') array_push($final_array, '#1E824C');
    elseif ($color == 'purple') array_push($final_array, '#913D88');
    elseif ($color == 'black') array_push($final_array, '#2C3E50');
    elseif ($color == 'brown') array_push($final_array, '#96281B');
    elseif ($color == 'orange') array_push($final_array, '#E67E22');
    else array_push($final_array, $color);

  }

  return $final_array;

}
