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
                        <h2>We got your request to change your password!</h2>
                        <p>This is a temporary password which shall be used only once to login to <strong>AAR My Health
                                app</strong>.</p>
                        <p>Password: <strong>{{$temporaryPassword}}</strong></p>
                        <p>Just so you know, you have only 24 hours before the temporary password is invalid.</p>
                        <p>Please change your password after successfully logging in.</p>
                        <p>Didn't ask for you new password, you can ignore this email</p>
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
