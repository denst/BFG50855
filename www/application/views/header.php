<? foreach ($styles as $file => $type) { ?>
	<link type="text/css" href="http://realtynova.loc/themes/<?= $file ?>" media="<?= $type ?>" rel="stylesheet" />
	<!--<link type="text/css" href="http://realtynova.ru/themes/<? //$file ?>" media="<? //$type ?>" rel="stylesheet" />-->
    
<? } ?>
<? foreach ($scripts as $file) { ?>
	<!--<script type="text/javascript" src="http://realtynova.ru/themes/js/<? //$file ?>"></script>-->
	<script type="text/javascript" src="http://realtynova.loc/themes/js/<?= $file ?>"></script>
<? } ?>

<? foreach ($custom as $file) {
    echo $file;
} ?>

<!--[if IE 6]>
    <link rel="stylesheet" type="text/css" href="/themes/ie6.css" />
<![endif]-->
<!--[if IE 7]>
    <link rel="stylesheet" type="text/css" href="/themes/ie7.css">
<![endif]-->
<!--[if IE 8]>
    <link rel="stylesheet" type="text/css" href="/themes/ie8.css" />
<![endif]-->

<!--[if lt IE 7]>
        <script type="text/javascript" src="/themes/js/lib/iepngfix_tilebg.js"></script>
<![endif]-->
<link rel="shortcut icon" href="http://realtynova.ru/themes/favicon.ico" />
<? if (Kohana::$environment == Kohana::PRODUCTION) { ?>

<script type="text/javascript">

/*
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20259704-4']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

*/

</script>

<? } ?>