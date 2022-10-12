<?php

function LBFIntake_javascript_onload()
{
    echo "
       const living_label = document.getElementById('label_id_apartment_no_intake');
       living_label.display = 'none';
       const living = document.getElementById('form_apartment_no_intake');
       living.style.display = 'none';
    ";
}
