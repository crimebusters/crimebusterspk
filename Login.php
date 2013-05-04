<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 5/3/13
 * Time: 6:45 PM
 * To change this template use File | Settings | File Templates.
 */?>


<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>

<form id="frmLogin" name="frmLogin" method="POST" action="Controller/LoginController.php">

    <table>
        <tr>
            <td style="text-align: right;">Email</td>
            <td style="text-align: left;">
                <input type="text" id="email" name="email" >
            </td>
        </tr>

        <tr>
            <td style="text-align: right;">Password</td>
            <td style="text-align: left;">
                <input type="password" id="password" name="password" >
            </td>
        </tr>

        <tr>
            <td style="text-align: right;">
                <input type="submit" id="btnSubmit" name="btnSubmit" value="Login"/>
            </td>
        </tr>
    </table>

</form>

</body>
</html>