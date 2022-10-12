<?php

function LBFIntake_javascript_onload()
{
    echo "
       const livinglabel = document.getElementById('label_id_apartment_no_intake');
       livinglabel.display = 'none';
       const living = document.getElementById('form_apartment_no_intake');
       living.style.display = 'none';
    ";
}
