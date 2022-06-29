<?php
require "bootstrap.php";
class EventManager {

    public function getArangementer($x)
    {

        if($x->children()) {
        $text="";
        foreach($x->children() as $y)
        {
        $text .= "<h2>". $y->Title ."</h2>";
        $text .=  "<h3>". $y->Dato ."</h3>";
        }
        return $text;
        }else{ 
            echo "<h2>Arrangementsliste forberedes... </h2>";
        }
    }
}