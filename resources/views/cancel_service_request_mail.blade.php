<?php 
    $article = '';
    if($service_type == 'Ambulance'){
        $article = 'an';
    }else{
        $article = 'a';
    }

?> 

<h2>Dear AAR</h2>
<p>This is a request from {{$customer_name}} who would like <?php echo $article;?> {{$service_type}} on {{$date}} at {{$time}}</p>
<p>Location: {{$location}}</p>
<p>Contact: {{$phone_number}}</p>
<br/>

<h3><span style="color:red"><strong>CANCELLED</strong></span></h3>

<br/>
<br/>
<p>Sent from <strong>My Health App at</strong> <?php echo date("Y/m/d h:i:sa");?></p>