<?php
if (! function_exists('array_get')) {
    function array_get($array, $key, $default = null){
        return isset($array[$key]) ? $array[$key] : $default;
    }
}

function getDisplayTitle($title, $fullTitle){
    $pageTitle = $title;
    if ($pageTitle->getNamespace() === NS_SPECIAL) {
        $components = explode("/", $fullTitle);
        $displayTitle = $components[count($components)-1];
    } else {
        $baseTitle = $pageTitle->getBaseText(); //the part before the subpage name, without namespace
        if($baseTitle === $pageTitle->getSubpageText()){ //root pages that does not exist
            $displayTitle = $baseTitle;
        } else {
            $linkObj = Title::newFromText( $baseTitle, $pageTitle->getNamespace());
            if ( is_object( $linkObj ) && $linkObj->isKnown() ) {
                $displayTitle = $pageTitle->getSubpageText(); //the rightmost part after any slashes.
            } else { //there is a slash in the title
                //HACK: this function is badly written, will need a fix in the future, right now it handles only one slash, better than neverland where the full title is printed
                //TODO: handle two or more slashes in the title
                $fullTitle=$pageTitle->getText();
                $components = explode("/", $fullTitle);
                $displayTitle = array_pop($components);
                $displayTitle = array_pop($components) . "/" . $displayTitle;
            }
        }
    }
    return $displayTitle;
}

/** 
 * INject the script for Google and piwik analytics
 * @param boolean $piwik                        wether to use piwik
 * @param string  $wgPiwikURL                   the url to piwik string
 * @param integer  $wgPiwikIDSite                the id of the site
 * @param string  $wgGoogleAnalyticsAccount     the uid of google analytics
 * @param integer  $wgGoogleAnalyticsAnonymizeIP wether to anonymize ips
 */
function setAnalytics($piwik = true, $wgPiwikURL, $wgPiwikIDSite, $wgGoogleAnalyticsAccount, $wgGoogleAnalyticsAnonymizeIP)
{
    if (isset($wgGoogleAnalyticsAccount) && !empty($wgGoogleAnalyticsAccount)) {
        $wgGoogleAnalyticsAnonymizeIP = (isset($wgGoogleAnalytics) && !empty($wgGoogleAnalytics)) ? 
        "ga('set', 'anonymizeIp', 'true') \n" : "ga('set', 'anonymizeIp', 'false') \n" ;
        ?>
        <script>
      // Google analytics
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', '<?php echo $wgGoogleAnalyticsAccount; ?>', 'auto');
      <?php echo $wgGoogleAnalyticsAnonymizeIP; ?>
      ga('send', 'pageview');

      <?php if ($piwik) { ?> 
        // Piwik Analytics
        var _paq = _paq || [];
        _paq.push(["trackPageView"]);
        _paq.push(["enableLinkTracking"]);

        (function() {
            var u=(("https:" == document.location.protocol) ? "https" : "http") + "://<?php echo $wgPiwikURL; ?>/";
            _paq.push(["setTrackerUrl", u+"piwik.php"]);
            _paq.push(["setSiteId", "<?php echo $wgPiwikIDSite; ?>"]);
            var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
            g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
        })();
        <noscript><img src="//<?php echo $wgPiwikURL; ?>/piwik.php?idsite=<?php echo $wgPiwikIDSite ?>&amp;rec=1" style="border:0" alt="" /></noscript>
        <?php } ?>
    </script>
    <?php }
}