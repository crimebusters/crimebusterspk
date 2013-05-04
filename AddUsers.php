<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 5/3/13
 * Time: 2:29 PM
 * To change this template use File | Settings | File Templates.
 *
 */

include ("Repository/SQL_Connection.php");
require_once ("Repository/RoleRepository.php");

session_start();

$flag = $_SESSION["IS_LOGGEDIN"];
//echo  $flag;
//echo $_SESSION["USER_EMAIL"];

if($flag != "true")
{
    header("location:Login.php");
    return;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
</head>
<body>
<form id="frmAddUsers" name="frmAddUsers" method="post" action="Controller/UserController.php" onsubmit="return ValidateValues();">

    <input type="hidden" id="userRole" name="userRole" value="1">

<table>
    <tr>
        <td style="text-align: right;" colspan="2">
            <label id="lblMessage" style="color: red;"></label>
        </td>
    </tr>

    <tr>
        <td style="text-align: right;">First Name</td>
        <td style="text-align: left;">    <input type="text" id="firstName" name="firstName" required="required" >
        </td>
    </tr>

    <tr>
        <td style="text-align: right;">Last Name</td>
        <td style="text-align: left;">    <input type="text" id="lastName" name="lastName" required="required">
        </td>
    </tr>

    <tr>
        <td style="text-align: right;">Email</td>
        <td style="text-align: left;">    <input type="text" id="email" name="email" required="required">
        </td>
    </tr>

    <tr>
        <td style="text-align: right;">Password</td>
        <td style="text-align: left;">    <input type="password" id="password" name="password" required="required">
        </td>
    </tr>

    <tr>
        <td style="text-align: right;">Re Enter Password</td>
        <td style="text-align: left;">    <input type="password" id="password2" name="password2">
        </td>
    </tr>

    <tr>
        <td style="text-align: right;">User Role</td>
        <td style="text-align: left;">
            <select id="userRole" name="userRole">
                <?php
                    $userRoleRepository = new RoleRepository();
                    $roles = $userRoleRepository->GetAll();
                foreach($roles as $role)
                {
                    echo "<option value='".$role["Role_ID"]."'>";
                    echo $role["Role_Name"];
                    echo "</option>";
                }
                ?>
            </select>
        </td>
    </tr>

    <tr>
        <td style="text-align: right;">
            <input type="submit" id="btnSubmit" name="btnSubmit" value="Add"/>
            </td>
        <td style="text-align: left;">    <input type="button" id="btnCancel" name="btnCancel" value="Cancel" onclick="window.location='Users.php';">
        </td>
    </tr>
</table>

</form>
</body>
</html>

<script type="text/javascript">

   function ValidateValues()
   {
       var succeed = true;

       var firstName = document.getElementById('firstName').value;
       var lastName = document.getElementById('lastName').value;
       var email = document.getElementById('email').value;

       var message = document.getElementById('lblMessage');
       var pass1 = document.getElementById('password').value;
       var pass2 = document.getElementById('password2').value;

       if(firstName == "" || firstName == null)
       {
           message.innerText="You must provide first name";
           succeed=false;
       }
       else if(lastName == "" || lastName == null)
       {
           message.innerText="You must provide last name";
           succeed=false;
       }
       else if(email == "" || email == null)
       {
           message.innerText="You must provide your email";
           succeed=false;
       }
       else if(pass1 == "" || pass1 == null)
       {
           message.innerText="You must provide password";
           succeed=false;
       }
       else if(pass1!=pass2)
       {
           message.innerText= ("Both passwords must be same")
           succeed=false;
       }

       return succeed;
   }

</script>