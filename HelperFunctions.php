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
            $linkObj = Title::newFromText( $baseTitle, $this->namespaceId);
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