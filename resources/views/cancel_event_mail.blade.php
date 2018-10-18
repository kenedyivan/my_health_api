<?php 
    $article = '';
    if($event_type == 'Appointment'){
        $article = 'an';
    }else{
        $article = 'a';
    }

?> 

<h2>Dear AAR</h2>
<p>This is a request from {{$customer_name}} who would like to schedule <?php echo $article;?> {{$event_type}} for {{$actual_date_time}}.</p>
<p>Subject: {{$title}}</p>
<p>Location: {{$location}}</p>
<p>Contact: {{$phone_number}}</p>
<br/>

<h3><span style="color:red"><strong>CANCELLED</strong></span></h3>

<br/>
<br/>
<p>Sent from <strong>My Health App at</strong> <?php echo date("Y/m/d h:i:sa");?></p>