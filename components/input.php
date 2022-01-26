<?php
function Input(array $options = []):string{

    return '
        <input 
            '.(isset($options["id"]) ? ('id="'.$options["id"].'"') : "").' 
            '.(isset($options["type"]) ? ('type="'.$options["type"].'"') : "").' 
            '.(isset($options["name"]) ? ('name="'.$options["name"].'"') : "").' 
            '.(isset($options["placeholder"]) ? ('placeholder="'.$options["placeholder"].'"') : "").' 
            '.(isset($options["class"]) ? ('class="'.$options["class"].'"') : "").' 
            '.(isset($options["value"]) ? ('value="'.$options["value"].'"') : "").' 
        >
    ';
}