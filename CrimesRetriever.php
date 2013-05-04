<?php
//require("phpsqlajax_dbinfo.php");

// Start XML file, create parent node

$CrimesDateRange;
$CrimesCategories;

if (isset($_GET['CrimesDateRange']))
{
    date_default_timezone_set('Asia/Karachi');

    if($_GET['CrimesDateRange'] == 0)
    {
        $CrimesDateRange = date('y/m/d');
    }
    else
    if($_GET['CrimesDateRange'] == 1)
    {
        $CrimesDateRange = ' AND Crime_Date between \'' . date('y/m/d',strtotime('monday this week')) . ' \' and \'' .  date('y/m/d') . '\';';

    }
    else
    if($_GET['CrimesDateRange'] == 2)
    {
        $CrimesDateRange = ' AND Crime_Date between \'' . date('y/m/d',strtotime('first day of this month')) . ' \' and \'' .  date('y/m/d') . '\';';
    }
}

if (isset($_GET['CrimesCategories']))
{
    $CrimesCategories = $_GET['CrimesCategories'];
}

$xml = new SimpleXMLElement('<xml/>');
$parnode = $xml->addChild("markers");

// Opens a connection to a MySQL server
$connection=mysql_connect ('localhost', 'root', '');
if (!$connection) {
    die('Not connected : ' . mysql_error());
}

// Set the active MySQL database
$db_selected = mysql_select_db('crimereports', $connection);
if (!$db_selected) {
    die ('Can\'t use db : ' . mysql_error());
}

// Select all the rows in the markers table
$query = "SELECT * FROM vw_crimes WHERE 1 AND Approved = 1 ";

if (!empty($CrimesCategories))
{
    if($CrimesCategories != 'All')
    {
        $query = $query.' AND Crime_Category_ID IN('.$CrimesCategories.')' ;
    }
}

if (!empty($CrimesDateRange))
{
    if($_GET['CrimesDateRange'] == 0)
    {
        $query = $query.' AND Crime_Date between \'' . $CrimesDateRange . ' \' and \'' . $CrimesDateRange . '\';';
    }
    if($_GET['CrimesDateRange'] == 1)
    {
        $query = $query.''.$CrimesDateRange;
    }
    if($_GET['CrimesDateRange'] == 2)
    {
        $query = $query.''.$CrimesDateRange;
    }
}



    $result = mysql_query($query);
if (!$result) {
    die('Invalid query: ' . mysql_error());
}

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
while ($row = @mysql_fetch_assoc($result))
{

    //echo $row['Crime_Description'].' ';
    //echo $row['Location_Name'].' ';
    // ADD TO XML DOCUMENT NODE
    $node = $parnode->addChild("marker");
    $newnode = $node->addAttribute("Description", $row['Crime_Description']);
    $newnode = $node->addAttribute("CategoryName", $row['Category_Name']);
    $newnode = $node->addAttribute("LocationName", $row['Location_Name']);
    $newnode = $node->addAttribute("OtherDetail", $row['Other_Detail']);
    $newnode = $node->addAttribute("CrimeDate", $row['Crime_Date']);
    $newnode = $node->addAttribute("CrimeTime", $row['Crime_Time']);
    $newnode = $node->addAttribute("VotesFor", $row['Votes_For']);
    $newnode = $node->addAttribute("VotesAgainst", $row['Votes_Against']);
    $newnode = $node->addAttribute("lat", $row['Location_Latitude']);
    $newnode = $node->addAttribute("lng", $row['Location_Longitude']);
}

echo $xml->asXML();

?>