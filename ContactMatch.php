<?php
error_reporting(0);
$contactsO = file('WLMContacts.csv');
$output='"First Name","Last name","Phone number","Email"';

foreach ($contactsO as $contact) {
if(empty($contact))
continue;
$contactN=str_getcsv($contact);
if (!empty($contactN[3]))
	$contactN[2] = ' ';
$contacts[$contactN[1] . $contactN[2] . $contactN[3]] = array("First name" => $contactN[1], "Last name" => $contactN[3], "Contact number" => $contactN[28], "Email" => $contactN[46]);
}

$checklist = file('checklist.txt');
foreach ($checklist as $check) {
$check = trim($check);
if (!empty($check) && isset($contacts[$check])) {
	$output .= chr(10) .'"' . $contacts[$check]['First name'].'","'.$contacts[$check]['Last name'].'","'.$contacts[$check]['Contact number'].'","'.$contacts[$check]['Email'].'"';
	$i++;
}
}
file_put_contents('To go.csv',$output);
echo "Found {$i} contacts";
?>