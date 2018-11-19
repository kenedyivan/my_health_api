<?php 
    $article = '';
    if($service_type == 'Ambulance'){
        $article = 'an';
    }else{
        $article = 'a';
    }

?> 

<h2>Dear Liberty Health Insurance</h2>
<p>This is a request from {{$customer_name}} who would like <?php echo $article;?> {{$service_type}} on <?php $date = new DateTime($date.' '.$time);
echo $date->format('Y-m-d \a\t H:i a');?>.</p>
<p>Location: {{$location}}</p>
<p>Contact: {{$phone_number}}</p>
<br/>

<h3><span style="color:red"><strong>CANCELLED</strong></span></h3>

<br/>
<br/>
<p>Sent from <strong>Liberty Health App at</strong> <?php echo date("Y/m/d h:i:s a");?></p>