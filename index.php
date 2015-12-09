<?php $time = microtime();$time = explode(' ', $time);$time = $time[1] + $time[0];$start = $time;  // Get time for page load length for no reason at all

// This function gets HSL values as decimals
function rgbToHsl($r, $g, $b) { $r /= 255; $g /= 255; $b /= 255; $max = max($r, $g, $b); $min = min($r, $g, $b); $h = 0; $s = 0; $l = ($max + $min) / 2; if($max == $min){ $h = $s = 0; }else{ $d = $max - $min; $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min); switch($max){ case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break; case $g: $h = ($b - $r) / $d + 2; break; case $b: $h = ($r - $g) / $d + 4; break; } $h /= 6; } return array('h'=>$h, 's'=>$s, 'l'=>$l); }

// This function is a multi-dimensional array sort
function sortmulti ($array, $index, $order, $natsort=FALSE, $case_sensitive=FALSE) {if( is_array($array) && count($array)>0 ) {foreach( array_keys($array) as $key ) $temp[$key]=$array[$key][$index];if(!$natsort) {if ($order=='asc') asort($temp,SORT_NUMERIC);else arsort($temp,SORT_NUMERIC);}foreach(array_keys($temp) as $key) $sorted[$key]=$array[$key];return $sorted;} return $sorted; }

//  Establish default form variables, get user values if they are legal
$n = "5000";    if ( is_numeric($_POST["n"]) && $_POST["n"] >= 100 && $_POST["n"] <= 10000 ) { $n = $_POST["n"]; }
$sort1 = "l";   if ( $_POST["sort1"] == "x" || $_POST["sort1"] == "h" || $_POST["sort1"] == "s" || $_POST["sort1"] == "l" || $_POST["sort1"] == "sl") {$sort1 = $_POST["sort1"];}
$sort2 = "asc";  if ( $_POST["sort2"] == "asc" || $_POST["sort2"] == "desc" ) { $sort2 = $_POST["sort2"];}
$sections = "12"; if ( is_numeric($_POST["sections"]) && $_POST["sections"] >= 1 && $_POST["sections"] <= 100 ) {$sections = $_POST["sections"];}
$size = "20";      if ( is_numeric($_POST["size"]) && $_POST["size"] >= 1 && $_POST["size"] <= 100 ) {$size = $_POST["size"];}
$serious = "";     if ( $_POST["serious"] == "totally" ) {$serious = $_POST["serious"];}

//  Array used to get random hex codes
$digits = array('15'=>'0','14'=>'1','13'=>'2','12'=>'3','11'=>'4','10'=>'5','9'=>'6','8'=>'7','7'=>'8','6'=>'9','5'=>'A','4'=>'B','3'=>'C','2'=>'D','1'=>'E','0'=>'F');

//  Add a 30% to the given color amount to account for pulling only unique values below, but keep the primary variable for slicing the array below
$nn = $n + (.3 * $n);

// Generate the random color hex codes
for ($i = 0; $i < $nn; $i++) {
 $hex_arr[$i] = $digits[ mt_rand(0, 15) ] . $digits[ mt_rand(0, 15) ] . $digits[ mt_rand(0, 15) ] . $digits[ mt_rand(0, 15) ] . $digits[ mt_rand(0, 15) ] . $digits[ mt_rand(0, 15) ];
}

// Save random color hex codes into array
$hex_arr = array_unique( $hex_arr );
$hex_arr = array_slice($hex_arr, 0, $n); 
unset($digits,$nn); 
$total = count( $hex_arr );

// Use rgbToHsl to get HSL values for each color, save it all in a big fucking array
foreach ( $hex_arr as $val ) {
  $r = hexdec(substr($val, 0, 2)); $g = hexdec(substr($val, 2, 2)); $b = hexdec(substr($val, 4, 2)); $hsl = rgbToHsl($r, $g, $b);
  if ( strlen($val) == 6 ) {
    $sl = round( ((1000 * $hsl['s']) * ($hsl['l'])), 20 );
    $colors[$val] = array(
      'x' => $val,
      'h' => str_pad( $hsl['h'], 20, "0" ),
      's' => str_pad( $hsl['s'], 20, "0" ),
      'l' => str_pad( $hsl['l'], 20, "0" ),
      'sl' => $sl,);
  }
  unset($key, $val, $g, $r, $b, $hsl, $sl, $hex_arr );  //  Be cool, pack it in, pack it out.  Shakka.
}

//  Get random BG colors for display fun times
$colors = sortmulti($colors,'l','desc');
$bg1 = array_slice($colors, 0, 1);
$bg1 = array_slice($colors, 1, 2);
$colors = sortmulti($colors,'l','asc');
$range = round( count( $colors ) * .09 );
$text = array_slice($colors, $range, $range+1);
$text = array_slice($colors, $range+1, $range+2);
$text = array_slice($colors, $range+2, $range+3);
?>
<html>
 <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Unique Color Generator - KyleJester.com</title>
  <meta name="description" content="unique color generator, rgb to hsl, php convert rgb, color generator, hex code generator">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800,300' rel='stylesheet' type='text/css' />
     <link href="css/stuff.css" rel="stylesheet" type="text/css" />
  <style>
   body{padding:20px;margin:0;font-size:1em;font-family: 'Open Sans', sans-serif;color:#<?php echo key($text); ?>;}
   a,a:link,a:visited,a:active{color:#c00;text-decoration:none;font-weight:bolder;}
   a:hover{text-decoration:underline;color:#fd000b;}
   .cursor-pointer:hover {cursor: pointer}
   #wrapper{width:99%;margin:0 auto 0 auto;padding:0;}
   #form{box-shadow: 6px 6px 5px #888888;width:96%;padding:1%;margin:0 auto;border:1px solid #555;background:#<?php echo key($bg1); ?>;border-radius:6px;}
   form,p,h3{margin:0;padding:0;}
   #form-section{width:22%;float:left;margin:0 5% 20px 5%;padding:0;}
   input{margin:4px 0 4px 0;}
   #about{box-shadow: 6px 6px 5px #888888;font-size:.9em;border:1px solid #000;background:#<?php next($bg1); echo key($bg1); ?>;border-radius:8px;width:90%;clear:both;padding:12px;margin:0 auto 12px auto !important;<?php if($_GET['display'] != "about"){echo "display:none;";} ?>}
   .arrow-up,.arrow-down {padding:0;margin:4px 8px 0 0;width:0;height:0;border-left:8px solid transparent;border-right:8px solid transparent;float:left;}
   .arrow-up{border-bottom: 8px solid #c00;}
   .arrow-down {border-top: 8px solid #c00;}
   h2{padding-left:5%;}
   h3{olor:#<?php next($text); echo key($text); ?>;}
   p{color:#<?php next($text); echo key($text); ?>;}
   li{list-style: none;margin-bottom:8px;}
   #color-wrappage{width:<?php if( $serious == "totally" ) { echo ($n / $sections) * ($size * 1.1); } else { echo "96%";} ?>;margin:20px auto 0 auto;padding:0;clear:both;}
   #color{box-shadow: 2px 2px 3px #888888;float:left;margin:0 1px 1px 0;padding:0;border-radius:50%;}
   #spacer{clear:both;height:10px;}
   #ad{width:100%;height:100px;margin:0;padding:12px 0 12px 0;clear:both;}
   #clear-both{clear:both;padding:0;margin:0;}
  </style>
 </head>
 <body>
  <div id="wrapper">
   <div id="form">
    <h2><a href="/colors/">Unique Color Generator</a></h2>
    <form name="colors" method="post" action="index.php<?php if($_SERVER["QUERY_STRING"]=="display=about"){echo "?" . $_SERVER["QUERY_STRING"];} ?>">
     <div id="form-section">
      <b>Number of Colors</b><br />(100 - 10,000, default is 5,000)<br />
      <input type="text" value="<?php echo $n; ?>" name="n"><br />
      <b>Number of Sections of Hue</b><br />(1 - 100, default is 12)<br />
      <input type="text" value="<?php echo $sections; ?>" name="sections"><br />
      <b>Width/Height of DIVs in Pixels</b><br />(1 - 100, default is 20)<br />
      <input type="text" value="<?php echo $size; ?>" name="size">
     </div>
     <div id="form-section">
      <b>Order Sections By</b><br />
      <input type="radio" name="sort1" <?php if($sort1 == "h"){echo "checked"; } ?> value="h" /> Hue (HSL)<br />
      <input type="radio" name="sort1" <?php if($sort1 == "s"){echo "checked"; } ?> value="s" /> Saturation (HSL)<br />
      <input type="radio" name="sort1" <?php if($sort1 == "l"){echo "checked"; } ?> value="l" /> Lightness (HSL, default)<br />
      <input type="radio" name="sort1" <?php if($sort1 == "x"){echo "checked"; } ?> value="x" /> Hex Code<br />
      <input type="radio" name="sort1" <?php if($sort1 == "sl"){echo "checked"; } ?> value="sl" /> Saturation * Lightness (HSL)
     </div>
     <div id="form-section">
      <b>Direction of Sorting</b><br />
      <input type="radio" name="sort2" <?php if($sort2 == "desc"){echo "checked"; } ?> value="desc" /> Descending<br />
      <input type="radio" name="sort2" <?php if($sort2 == "asc"){echo "checked"; } ?> value="asc" /> Ascending (default)<br /><br />
      <input type="checkbox" name="serious" <?php if ( $_POST["serious"] == "totally" ) {echo "checked";} ?> value="totally"> Wide Mode<br />
      <input type="submit" value="Submit"><br /><br /><br />
      <?php if($_GET['display'] != "about"){echo "<a href=\"?display=about\"><div class=\"arrow-down\"></div> About</a>";} else {echo "<a href=\"index.php\"><div class=\"arrow-up\"></div> About</a>";} ?>
     </div>
     <div id="clear-both"></div>
     <div id="about">
      <h3>About This Little Fucker</h3>
      <p>
       <ul>
        <li>i found myself believing that the world needs a random color generator and that i should be the one to provide it.
        then i made it.  i think it's neat and mostly self-explanatory.  now i can bask in the splendor of it's colors.  and i will.  bask.
        <li>the bg colors of this box and the form box and the text colors are taken from the randomly generated 
        colors within specific lightness parameters. neato.
        <li>i made this with PHP because that's all i know.  it uses mt_rand for color generation.  and i stole a snippet to convert hex color 
        codes into HSL for sorting. search for RGB to HSL functions, they're out there.  
        <li>the color info is in the title attribute of each color div.  hover to see it. click to copy the hex code to the clipboard.
       </ul>
      </p>
     </div>
    </form>
    <div id="clear-both"></div>
   </div>
   <div id="color-wrappage">
<?php
// Sort the array by hue initially, calculate the section size, and chunk into sections
$colors = sortmulti($colors,'h','desc');
$num = ceil( count($colors)/$sections );
$colors = array_chunk($colors, $num, true);

//  Display the number of generated colors after pulling unique so we can see if it's pulling enough
echo "<h4>$total Colors Generated -> HOVER for color info, CLICK to copy hex code to clipboard</h4>";

// DISPLAY CODE & HSL Calculations from decimals, sort by given data, cycle through the new array and display the colors
for ($i = 0; $i < $sections; $i++) {
  $arr = sortmulti($colors[$i],$sort1,$sort2);
  foreach ( $arr as $key => $val ) {
    $hue = round( 360 * $colors[$i][$key]['h'] ) . "&deg;";
    $saturation = round( 100 * $colors[$i][$key]['s'] ) . "%";
    if ( $saturation > 100 ) { $saturation = "100%"; }  //  This fixed an error where this value got fucked up like this: 1.0E+21%  <-- what the fuck is that anyways?
    $lightness = round( 100 * $colors[$i][$key]['l'] ) . "%";

    // Display divs and put color values in the title attribute, and display that shit so people can read/copy it for fuck's sake
    echo "
   <div class=\"copy-target cursor-pointer\" data-clipboard-text=\"#" . $colors[$i][$key]['x'] . "\" id=\"color\" title=\"HEX: #" . $colors[$i][$key]['x'] . ", HSL: ($hue, $saturation, $lightness)\" style=\"background:#" . $colors[$i][$key]['x'] . ";width:" . $size . ";height:" . $size . "px;\"></div>";
  }
  echo "

   <div id=\"spacer\" title=\"This is just to space out the HUE sections. Nothing to see here. Stop reading.\">&nbsp;</div>
";
}
unset( $digits,$n, $hue, $saturation, $lightness, $total );  //  \m/
?>
   </div>
   <div id="ad">  <!--  lord satan, please let some honkies or ethnics click on this ad. the few pennies will go in my piggy bank shaped like a black hole.  -->
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><!-- Colors.php --><ins class="adsbygoogle"     style="display:block"     data-ad-client="ca-pub-8272820157791982"     data-ad-slot="8113094271"     data-ad-format="auto"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
   </div>
<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');ga('create','UA-20479126-4','auto');ga('send','pageview');</script>
<?php $time = microtime();$time = explode(' ', $time);$time = $time[1] + $time[0];$finish = $time;$total_time = round(($finish - $start), 8);echo 'Load Time: '.$total_time.' seconds.'; //  get and display page load time.  why, nobody's gonna ever look at it?>
  </div>
 <script src="js/donkey-balls.js"></script>
 <script src="js/highlight.pack.min.js"></script>
 <script src="js/clipboard.min.js"></script>
 <script src="js/tooltips.js"></script>
 </body>
</html>

<!--
        I'd like to give thanks to my wife, who believes that I was cleaning the house while I coded this.
        without her ignorance, none of this bullshit would be possible.

        and also to lord satan for your warmth.
        good luck in the overlord champion soccer semi-finals next week.
-->
