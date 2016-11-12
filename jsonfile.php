<?php

/* =============== DESCRIPTION FILE ======================================== */
/*                                                                           */
/*   Module  : ZEROC0D3 JSON DECODE FORMATTER LOAD FROM FILE                 */
/*   Type    : JSON DECODER TOOLS                                            */
/*   Author  : ZEROC0D3 Team                                                 */
/*   Date    : November 2016                                                 */
/*                                                                           */
/*  __________                  _________ _______       .___________         */
/*  \____    /___________  ____ \_   ___ \\   _  \    __| _/\_____  \ Team   */
/*    /     // __ \_  __ \/  _ \/    \  \//  /_\  \  / __ |   _(__  <        */   
/*   /     /\  ___/|  | \(  <_> )     \___\  \_/   \/ /_/ |  /       \       */
/*  /_______ \___  >__|   \____/ \______  /\_____  /\____ | /______  /       */
/*          \/   \/                     \/       \/      \/        \/        */
/*                                                                           */
/*  ZeroC0d3 Team                                                            */  
/*  [ N0th1ng Imp0ss1bl3, Grey Hat Coder ]                                   */
/*  --------------------------------------------------------                 */
/*  http://pastebin.com/u/zeroc0d3                                           */
/*                                                                           */
/* ========================================================================= */

define("FILE_INPUT", "result_unformatted.txt");
define("FILE_OUTPUT", "result_formatted.txt");

global $zver;
global $snap_date, $start, $end;
global $str_plain;
global $json, $result, $str_format;
global $style1, $style2, $style3;

$GLOBALS['zver']  = "ver 1.5";

date_default_timezone_set('Asia/Jakarta');
error_reporting(0);
@ini_set('display_errors', 'Off');
@ini_set('log_errors', 'Off');
@ini_set('html_errors', 'Off');
@ini_set('safe_mode', 'Off');
@ini_set('register_globals', 'Off');
@ini_set('post_max_size', '128M');
@ini_set('memory_limit', '128M');
@set_time_limit(0);

function formatJSON($json)
{
    $result = '';
    $level = 0;
    $in_quotes = false;
    $in_escape = false;
    $ends_line_level = NULL;
    $json_length = strlen($json);

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if ( $in_escape ) {
            $in_escape = false;
        } else if( $char === '"' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        } else if ( $char === '\\' ) {
            $in_escape = true;
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
    }

    return $result;
}

echo "\n";
echo "ZeroC0D3 JSON DECODE FORMATER (FROM FILE) - " .$GLOBALS['zver']." :               \n";
echo "----------------------------------------------------------------------------------\n";
echo ">> Load from file: " . FILE_INPUT . "\n";
echo "\n";

/*** Begin JSON Formatted ***/
echo ">> Processing... " . "\n";
$start = microtime(true);
$unformatted = file_get_contents(FILE_INPUT, true);
//$result_formatted = json_encode($unformatted, JSON_PRETTY_PRINT);
$result_formatted = formatJSON($unformatted);
$end = microtime(true);
$snap_date = date("D, Y-m-d H:i");
/*** End JSON Formatted ***/
echo ">> DONE! " . "\n";
echo "\n";
if(empty($result_formatted)) { die("Empty result formatted file!"); }
fclose($unformatted);

$formatted  = fopen(FILE_OUTPUT, "w") or die("Unable to open result_formatted file!");
$result_all = fwrite($formatted, $result_formatted);
if(empty($result_all)) { die("Empty string! Failed to write the result..."); }
fclose($formatted);

echo ">> Result in file: " . FILE_OUTPUT . "\n";
echo "----------------------------------------------------------------------------------\n";
echo "Execution Time (s): " . ($end - $start) . " | Last Activities : " . $snap_date;
echo "\n";

?>
