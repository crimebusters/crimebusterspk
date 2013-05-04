

<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 3/8/13
 * Time: 12:14 PM
 * To change this template use File | Settings | File Templates.
 */

include_once("../Model/CrimeModel.php");
//include_once("../Model/LocationModel.php");


include_once("../Repository/SQL_Connection.php");//Place it before all the repositories
include_once("../Repository/CrimeRepository.php");

include_once("../Repository/LocationRepository.php");
include_once("../Repository/UserRepository.php");

//echo "done";

//echo $message;

//sendEmail("Spur Designs", "info@spurdesigns.com", "azhar.spursolutions@gmail.com", "New Website Request", "testing", "");

echo "<br/> Message Sent";


date_default_timezone_set('Asia/Karachi');

//echo date("m/d/Y h:i:s a", time());

$crime = new CrimeModel();

//echo $_POST['clientIP'];
$crime->ReportedByUserIP=$_POST['clientIP'];
$crime->LocationLatitude=$_POST["latitude"];
$crime->LocationLongitude=$_POST["longitude"];

$crime->LocationName=$_POST["locationName"];
$crime->CrimeCategoryID=$_POST["txtCategory"];
//echo $_POST["txtCategory"];

echo $_POST["txtCrimeDate"];
echo "<br/>";
$date = strtotime($_POST["txtCrimeDate"]);
echo date('Y/m/d H:i:s', $date);
//return;
$crime->CrimeDate= date('Y/d/m H:i:s', $date);
$crime->CrimeTime=$_POST["txtCrimeTime"];

$crime->CrimeDescription=$_POST["txtDescription"];
$crime->OtherDetail=$_POST["txtOtherDetail"];
$crime->CreatedBy="sysdeveloper";

$crime->CreatedOn=date('y/m/d');
$crime->VotesAgainst="0";
$crime->VotesFor="10";

//echo "done";
$crimeRepository = new CrimeRepository();

$newID = $crimeRepository->Add($crime);

echo "The New ID is ".$newID;


$crimeRow = $crimeRepository->Get($newID);

$crimeDescription = "";
$userIP="";

foreach($crimeRow as $newCrime)
{
    //echo $newCrime["Crime_Description"];
    $crimeDescription = $newCrime["Crime_Description"];
    $userIP=$newCrime["Reported_By_User_IP"];
    break;
}

//echo "The new id is ". $newID;

$userRepository = new UserRepository();
$userRows = $userRepository->GetAll();

$counter =0;
$userEmails = Array();
$userMails ="";
foreach($userRows as $userRow)
{
//$userEmails[$counter] = $userRow["User_Name"];
    $userMails .= $userRow["User_Name"]."; ";
    $counter=$counter+1;
}

$message = "A new crime is report is waiting for your approval.";

$message .= "<br/><br/>";

$message .= "<br/>Date:- ".date("m/d/Y");
$message .= "<br/>Time:- ".date("h:i:s a", time());

$message .= "<br/>User IP:- ".$userIP;
$message .= "<br/>Description:- ".$crimeDescription;

$message .= "<br/><br/><br/><br/><br/><a href='http://localhost:8080/CrimeReports/ApproveDecline.php?flag=1&CrimeID=".$newID."'>Approve</a>";

$message .="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

$message .= "<a href='http://localhost:8080/CrimeReports/ApproveDecline.php?flag=0&CrimeID=".$newID."'".">Decline</a>";

//echo "User Emails ".$userMails;

//sendEmail("Spur Designs", "info@spurdesigns.com", $userMails, "New Website Request", $message, "");

echo "<br/> Message Sent";



$str = 'This is a top secret...';
$enc = base64_encode($str);
$dec = base64_decode($enc);



//header("location:../ReportCrime.php");
header("location:../index.php");
//header("location:../MailContentTest.php");





function sendEmail($name, $email, $to_mail, $subject, $msg, $attachment = "") {
    $sending = false;

    if (!empty($attachment['tmp_name']) && !empty($attachment['error'])) $attachment['tmp_name'] = "";

    if (!empty($name) && !empty($email) && !empty($to_mail) && !empty($subject) && !empty($msg)) {
        $from_name = $name;
        $from_mail = $email;
        $sending = true;
    }

    if ($sending) {
        $eol = "\n";

        $tosend['email'] = $to_mail;
        $tosend['subject'] = $subject;

        $tosend['message'] = $msg;

        $tosend['headers'] = "From: \"".$from_name."\" <".$from_mail.">".$eol;
        $tosend['headers'] .= "Return-path: <".$from_mail.">".$eol;
        $tosend['headers'] .= "MIME-Version: 1.0".$eol;
        if (!empty($attachment['tmp_name'])) {
            $file = $attachment['tmp_name'];
            $content = file_get_contents($file);
            $content = chunk_split(base64_encode($content));
            $uid = md5(uniqid(time()));
            $f_name = $attachment['name'];
            $tosend['headers'] .= "Content-Type: multipart/mixed; boundary=\"PHP-mixed-".$uid."\"".$eol.$eol;
            $tosend['headers'] .= "This is a multi-part message in MIME format.".$eol;
            $tosend['headers'] .= "--PHP-mixed-".$uid."".$eol;
            $tosend['headers'] .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-".$uid."\"".$eol.$eol;
            $tosend['headers'] .= "--PHP-alt-".$uid."".$eol;
            $tosend['headers'] .= "Content-type: text/html; charset=utf-8".$eol;
            $tosend['headers'] .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
            $tosend['headers'] .= $tosend['message']."".$eol.$eol;
            $tosend['headers'] .= "--PHP-alt-".$uid."--".$eol;
            $tosend['headers'] .= "--PHP-mixed-".$uid."".$eol;
            $tosend['headers'] .= "Content-Type: application/octet-stream; name=\"".$f_name."\"".$eol; // use diff. types here
            $tosend['headers'] .= "Content-Transfer-Encoding: base64".$eol;
            $tosend['headers'] .= "Content-Disposition: attachment; filename=\"".$f_name."\"".$eol.$eol;
            $tosend['headers'] .= $content."".$eol.$eol;
            $tosend['headers'] .= "--PHP-mixed-".$uid."--";
            $tosend['message'] = "";//-- The message is already in the headers.
        }

        if (mail($tosend['email'], $tosend['subject'], $tosend['message'] , $tosend['headers'])){
            echo "Message sent";
            return true;
        }
        else{
            echo "Message sending failed";
            return false;
        }
    }//-- if ($sending)
    return false;
}




?>