<?php
/**
 * Skin file for skin Foo Bar.
 *
 * @file
 * @ingroup Skins
 */

/**
 * SkinTemplate class for Foo Bar skin
 * @ingroup Skins
 */
class SkinWikiToLearnSkin extends SkinTemplate 
{
    var $skinname = 'wikitolearnskin', $stylename = 'WikiToLearnSkin',
        $template = 'WikiToLearnSkinTemplate', $useHeadElement = true;

    /**
     * This function adds JavaScript via ResourceLoader
     *
     * Use this function if your skin has a JS file or files.
     * Otherwise you won't need this function and you can safely delete it.
     *
     * @param OutputPage $out
     */
    
    public function initPage( OutputPage $out ) 
    {
        parent::initPage( $out );
        $out->addModules( 'skin.wikitolearn.js' );
    }

    /**
     * Add CSS via ResourceLoader
     *
     * @param $out OutputPage
     */
    function setupSkinUserCss( OutputPage $out ) 
    {
        parent::setupSkinUserCss( $out );
        $out->addModuleStyles( 'skin.wikitolearn' );
    }
}

/**
 * BaseTemplate class for Foo Bar skin
 *
 * @ingroup Skins
 */
class WikiToLearnSkinTemplate extends BaseTemplate {

    /**
     * Print the attributes given the string
     * @param  string $string 
     * @return string the attributes as a string
     */
    public function getAttributesFromString($string)
    {
        return Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( $string ) );
    }

    /**
     * Outputs the entire contents of the page
     */
    public function execute() 
    {
        $this->html( 'headelement' ); ?>

        <?php $this->html( 'newtalk' ); ?>

        <?php if ( $this->data['newtalk'] ) { ?>
          <div class="usermessage"> <!-- The CSS class used in Monobook and Vector, if you want to follow a similar design -->
            <?php $this->html( 'newtalk' );?>
          </div>
        <?php } ?>

        <?php $this->html( 'sitenotice' ); ?>

        <?php if ( $this->data['sitenotice'] ) { ?>
          <div id="siteNotice"> <!-- The CSS class used in Monobook and Vector, if you want to follow a similar design -->
            <?php $this->html( 'sitenotice' ); ?>
          </div>
        <?php } ?>

        <?php $this->text( 'sitename' ); ?>

        <a
            href="<?php 
                echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] );
                // This outputs your wiki's main page URL to the browser.
                ?>"
            <?php echo $this->getAttributesFromString('p-logo'); ?>
        >
            <img src="<?php 
                    $this->text( 'logopath' );  
                    // This outputs the path to your logo's image
                    // You can also use $this->data['logopath'] to output the raw URL of the image. Remember to HTML-escape
                    // if you're using this method, because the text() method does it automatically.
                ?>"
                alt="<?php $this->text( 'sitename' ) ?>"
            >
        </a>

        <nav>
            <ul>
            <?php
                foreach ( $this->getPersonalTools() as $key => $item ) {
                    echo $this->makeListItem( $key, $item );
                }
            ?>
            </ul>
        </nav>

        <h1> <?php $this->html( 'title' ); ?> </h1>

        <?php echo $this->getIndicators(); ?>


        <section id="content" class="mw-body">
            <?php $this->msg( 'tagline' ); ?>
            <?php if ( $this->data['subtitle'] ) { ?>
                  <div id="contentSub"> <!-- The CSS class used in Monobook and Vector, if you want to follow a similar design -->
                  <?php $this->html( 'subtitle' ); ?>
                  </div>
            <?php } ?>
                  <?php if ( $this->data['undelete'] ) { ?>
                  <div id="contentSub2"> <!-- The CSS class used in Monobook and Vector, if you want to follow a similar design -->
                  <?php $this->html( 'undelete' ); ?>
                  </div>
            <?php } ?>

            <?php $this->html( 'bodytext' ) ?>

            <?php $this->html( 'catlinks' ); ?>

            <?php $this->html( 'dataAfterContent' ); ?>


        </section>

        <?php $this->printTrail(); ?>
        </body>
        </html><?php
    }
}