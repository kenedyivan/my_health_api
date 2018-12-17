@extends('layouts.email')
@section('content')
    @include('includes.header')
    <?php
    $article = '';
    if ($service_type == 'Ambulance') {
        $article = 'an';
    } else {
        $article = 'a';
    }

    ?>
    <tr bgcolor="f7f7f7">
        <td>
            <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                   class="container-middle">
                <tbody>
                <!-- padding-top -->
                <tr>
                    <td class="ser_pad" height="70"></td>
                </tr>
                <!-- //padding-top -->
                <tr>
                    <td class="wel_text" align="center"
                        style="font-size:1.5em;color:#d70b03;font-family:Candara;text-align:center;font-weight:600;">
                        SERVICE REQUEST
                    </td>
                </tr>
                <tr>
                    <td height="15"></td>
                </tr>
                <tr>
                    <td class="ser_text" align="center"
                        style="color:#464646; font-size: 1.2em; font-family: Candara; line-height:1.8em;">
                        <p>This is a request from <strong>{{$customer_name}}</strong> who would
                            like <?php echo $article;?> <strong>{{ strtoupper($service_type ) }}.</strong></p>
                        <p>On <?php $date = new DateTime($date . ' ' . $time);
                            echo $date->format('Y-m-d \a\t H:i a');?>.</p>
                        <p>Location: {{$location}}</p>
                        <p>Contact: {{$phone_number}}</p>
                    </td>
                </tr>
                <tr>
                    <td height="30"></td>
                </tr>
                <!-- //padding-top -->
                </tbody>
            </table>
        </td>
    </tr>
    @include('includes.footer')
@endsection
