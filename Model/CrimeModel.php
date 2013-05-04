<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 3/1/13
 * Time: 12:00 PM
 * To change this template use File | Settings | File Templates.
 */
class CrimeModel
{
    function __construct()
    {
    }

    public $CrimeID="";
    public $CrimeCategoryID="";
    public $CrimeDescription="";
    public $LocationName="";

    public $LocationLatitude="";
    public $LocationLongitude="";
    public $VotesFor="";

    public $VotesAgainst="";
    public $ReportedByUserIP="";
    public $ReportedByUserMac="";

    public $CrimeDate="";
    public $CrimeTime="";
    public $OtherDetail="";

    public $SeverityID="";
    public $CreatedOn="";
    public $CreatedBy="";

}
