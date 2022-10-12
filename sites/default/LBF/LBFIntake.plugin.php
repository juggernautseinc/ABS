<?php

function LBFIntake_javascript_onload()
{
    echo "
       const living = document.getElementById('form_apartment_no_intake');
       living.style.display = 'none';
    ";
}
