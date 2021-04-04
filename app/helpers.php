<?php

if(! function_exists('remove_space')){
    function remove_space($string){
        $result = str_replace(' ', '', $string);
        return $result;
    }
}


