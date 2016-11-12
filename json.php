<?php

/* =============== DESCRIPTION FILE ======================================== */
/*                                                                           */
/*   Module  : ZEROC0D3 JSON DECODE FORMATTER                                */
/*   Type    : JSON DECODER TOOLS                                            */
/*   Author  : ZEROC0D3 Team                                                 */
/*   Date    : July 2015                                                     */
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

global $zver;
global $snap_date, $start, $end;
global $str_plain;
global $json, $result, $json_unformat, $str_format;
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

function cssFile()
{
    echo "<style>";
    echo ".block { width: 270px; display: inline-block; height: 170px; padding-left: 30px; padding-bottom: 30px; text-align: center; vertical-align: top; position: relative; }  .block2 { margin: 0 auto; text-align: center; }  textarea, input, select { border: #777 1px solid; padding: 5px 0; }  .hiddenInput { display: inline-block; width: 240px; }  .code { display: none; }  .attn { color: #c00; } .button50 { width: 40%; height: 40px; border-width: 1px; border-color: #000000; font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; font-size: 11pt; font-weight: bold; font-color: #FFFFFF; color: #FFFFFF; background-color: #000000; vertical-align: middle; }  .button100 { width: 90%; height: 40px; border-width: 1px; border-color: #000000; font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; font-size: 11pt; font-weight: bold; font-color: #FFFFFF; color: #FFFFFF; background-color: #000000; vertical-align: middle; }  .no-print { display: none; }  .disable { font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; font-size: 11pt; font-weight: bold; background: #DFDFDF; color: #000000; }  .disable1 { font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; font-weight: bold; background: #1B1B1B; color: #000000; }  .title { font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; font-size: 16pt; font-weight: bold; color: #1B1B1B; vertical-align: middle; text-align: center; height: 50px; }  .title1 { font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; font-size: 14pt; font-weight: bold; font-color: #000000; background-color: #AFAFAF; vertical-align: middle; text-align: center; height: 30px; }  .style0 { font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold; color: #1B1B1B; vertical-align: middle; text-align: left; }  .style0:hover { background-color: #FFFF99; }  .style1 { font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; font-size: 7pt; color: #1B1B1B; padding-left: 5px; padding-right: 5px; vertical-align: middle; text-align: left; }  .style1:hover { background-color: #FFFF99; }  .style2 { font-family: \"Monospace\", \"Ubuntu Mono\", \"Courier New\", Tahoma, Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold; color: #1B1B1B; vertical-align: middle; text-align: left; }  .style2:hover { background-color: #FFFF99; }";
    echo "</style>";
}

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

if ($_GET['action'] == "format_json") {
    if (isset($_POST['json_unformat'])) {
        $json_unformat = $_POST['json_unformat'];
        $start = microtime(true);
        $str_format = formatJSON($json = $json_unformat);
        $style1 = $str_format;
        $end = microtime(true);
        $snap_date = date("D, Y-m-d H:i");   
        fclose($unformatted);    
    }
}

?>

<html>
<head>
    <title>ZeroC0D3 JSON DECODE FORMATER</title>
    <?php echo cssFile(); ?>
</head>
<body>
<table width="780" border="1" align="center">
    <tr>
        <th colspan="3" class="title" scope="col" bgcolor="#AFAFAF">&nbsp;<strong>ZeroC0D3 JSON DECODE FORMATER
                (<?php echo $GLOBALS['zver']; ?>)</strong></th>
    </tr>
    <form id="form1" name="form1" method="post"
          action="<?php echo basename($_SERVER['PHP_SELF']); ?>?action=format_json">
        <tr>
            <th scope="row" class="style0" width="250px">&nbsp;JSON (Unformat)&nbsp;</th>
            <td class="style0" style="text-align:center" width="5">
                <center>&nbsp;:&nbsp;</center>
            </td>
            <td>
                <textarea name="json_unformat" cols="80" rows="5"><?php if ($start > 0) {
                        echo $json_unformat . "\n";
                    } ?></textarea>
            </td>
        </tr>
        <tr>
            <th colspan="3" style="text-align:left" scope="row">
                <center>
                    <input type="submit" name="json_text" id="  " value="FORMAT JSON" class="button100"/>
                </center>
            </th>
        </tr>
    </form>
    <tr>
        <th colspan="3" class="title1" scope="col">&nbsp;<strong>JSON FORMATED :: RESULTS</strong></th>
    </tr>
    <tr>
        <th scope="row" class="style0" width="250px">&nbsp;Visual Decoded (Style-1)&nbsp;</th>
        <td class="style0" style="text-align:center" width="5">
            <center>&nbsp;:&nbsp;</center>
        </td>
        <td>
            <textarea name="vstyle1" cols="80" rows="10" readonly><?php if ($start > 0) {
                    echo $style1 . "\n";
                } ?></textarea>
        </td>
    </tr>
    <tr>
        <th scope="row" class="style0" width="250px">&nbsp;Visual Decoded (Style-2)&nbsp;</th>
        <td class="style0" style="text-align:center" width="5">
            <center>&nbsp;:&nbsp;</center>
        </td>
        <td>
            <textarea name="vstyle2" cols="80" rows="10" readonly><?php if ($start > 0) {
                   $style2 = var_dump(json_decode($style1, true));
                   echo $style2 . "\n";
                } ?></textarea>
        </td>
    </tr>
    <tr>
        <th scope="row" class="style0" width="250px">&nbsp;Visual Decoded (Style-3)&nbsp;</th>
        <td class="style0" style="text-align:center" width="5">
            <center>&nbsp;:&nbsp;</center>
        </td>
        <td>
            <textarea name="vstyle3" cols="80" rows="10" readonly><?php if ($start > 0) {
                    $style3 = var_dump(json_decode($style1, false, 512, JSON_BIGINT_AS_STRING)); 
                    echo $style3 . "\n";
                } ?></textarea>
        </td>
    </tr>
    <tr>
        <th colspan="3" class="style1" scope="row">&nbsp;Execution Time (s)
            : <?php echo "<strong>" . ($end - $start) . " | Last Activities : " . $snap_date . " </strong>"; ?></th>
    </tr>
</table>
</body>
</html>
