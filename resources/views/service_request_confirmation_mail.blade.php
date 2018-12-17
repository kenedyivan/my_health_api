@extends('layouts.email')
@section('content')
    <tr>
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
                    <td class="wel_text">
                        <img src="http://82.163.78.92:8001/images/aar_logo.jpeg" width="50px" height="50px">
                    </td>
                </tr>
                <tr>
                    <td height="15"></td>
                </tr>
                <tr>
                    <td class="ser_text" align="justified"
                        style="color:#464646; font-size: 1.2em; font-family: Candara; line-height:1.8em;">
                        <h2>Dear {{$customer_name}},</h2>
                        <p>Your request has been successfully sent to AAR for handling</p>
                        <p>You shall receive a confirmation email shortly.</p>
                        <p>Thank you.</p>
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
@endsection
