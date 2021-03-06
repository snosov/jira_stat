<?php
error_reporting(E_ERROR);

include __DIR__ . '/vendor/autoload.php';

$jira_host  = '';
$jira_user  = '';
$jira_pwd   = '';

$employees  = array();

$reports    = array();

include __DIR__ . '/data.php';



/**
 * @return chobie\Jira\Api
 */
function getApiClient()
{
    global $jira_host, $jira_user, $jira_pwd;
	$api = new \chobie\Jira\Api(
        $jira_host,
		new \chobie\Jira\Api\Authentication\Basic($jira_user, $jira_pwd)
	);

	return $api;
}

function getMonths($year)
{
    $result = array();
    for ($m = 1; $m <=12; $m++)
    {
        $month = date('F', mktime(0,0,0,$m, 1, $year));
        $result[$month] = array(
            'month'    => $month,
            'firstDay' => date("Y/m/d",strtotime("first day of $month $year")),
            'lastDay'  => date("Y/m/d",strtotime("last day of $month $year"))
        );
    }
    return $result;
}

function getWeeks($year)
{
    $result = array();
    $numberOfWeeks = date('W', strtotime('December 28th, ' . $year));
    $date = new DateTime();
    for ($w = 1; $w <= $numberOfWeeks; $w++)
    {
        $firstDay = date('Y/m/d', $date->setISODate($year, $w, 1)->getTimestamp());
        $lastDay = date('Y/m/d', $date->setISODate($year, $w, 7)->getTimestamp());
        $key = "Week #$w $firstDay - $lastDay";
        $result[$w] = array(
            'key'       => $key,
            'firstDay'  => $firstDay,
            'lastDay'   => $lastDay
        );
    }
    return $result;
}

function std_output($data)
{
    foreach ($data as $report => $row) {
        echo $report . "\t";
        foreach ($row as $value) {
            echo $value . "\t";
        }
        echo "\n";
    }
}
