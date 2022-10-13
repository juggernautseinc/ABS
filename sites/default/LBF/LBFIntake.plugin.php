<?php

function LBFIntake_javascript_onload()
{
    echo "
       const living = document.getElementById('form_apartment_no_intake');
       living.style.display = 'none';
       const threequater = document.getElementById('form_three_qtr_intake');
       threequater.style.display = 'none';
       const halfway = document.getElementById('form_name_halfway_intake');
       halfway.style.display = 'none';
       const treatment = document.getElementById('form_Name_of_Residential_Treatment_P');
       treatment.style.display = 'none';
       const shelter = document.getElementById('form_Name_of_Shelter');
       shelter.style.display = 'none';
       
       document.getElementById('form_homeless_intake').addEventListener('onchange', displayLiving);
       function displayLiving() {
       alert('fired');
           l = document.getElementById('form_homeless_intake').value;
           if (l == 'apartment') {
              alert(l);
              living.style.display = 'block';
           }
       }
    ";
}
