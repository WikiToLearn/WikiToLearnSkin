<?php

require_once("HelperFunctions.php");
/**
 * Skin file for skin WikiToLearnSkin
 *
 * @file
 * @ingroup Skins
 */

/**
 * SkinTemplate class for WikiToLearnSkin skin
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
        $out->addMeta( 'viewport', 'width=device-width, initial-scale=1' );
        $out->addModules( 'skin.wikitolearn.js' );
        $out->addModules('ext.courseEditor.publish');
    }

    /**
     * Add CSS via ResourceLoader
     *
     * @param $out OutputPage
     */
    function setupSkinUserCss( OutputPage $out )
    {
        parent::setupSkinUserCss( $out );
        $out->addStyle("//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700");
        $out->addStyle("//fonts.googleapis.com/css?family=Roboto+Slab:100,300,400,700");
        $out->addModuleStyles( 'skin.wikitolearn' );
    }
}

/**
 * BaseTemplate class for WikiToLearnSkin skin
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
      //Declare useful variables for the whole template functions
      global $wgOut, $wgRequest, $wgUser, $wgSupportedLanguages, $wiki_domain, $wiki;
      
      self::prepareOverrideMessages();

      $this->skin = $this->getSkin();
      $this->namespaceId = $wgOut->getTitle()->getNamespace();
      $this->pageTitle = $wgOut->getTitle();
      $this->user = $wgUser;
      $this->contentNavigation = $this->data['content_navigation'];
      $this->toolBox = $this->getToolbox();
      $this->supportedLanguages = $wgSupportedLanguages;
      $this->domain = $wiki_domain;

      $this->html( 'headelement' );
      $this->executeCookies(); ?>
        <?php
          $this->executeHeader();
          if ($wiki != 'meta' && //TODO: allow configurable variable
              $wiki != 'pool' && 
              $this->getSkin()->getTitle()->isMainPage() &&
              !$wgRequest->getText("action")) {  //if an action is set we don't render the home page
            MWDebug::log('Generating Homepage');
            $this->executeHome();
          } else {
            MWDebug::log('Generating Content page');
            $this->executeContentPage();
          }
          $this->executeFooter();

          $this->printTrail(); ?>

          </body>
        </html>
    <?php }

    public function executeCookies() {
      global $wiki_domain; ?>
      <!-- Begin Cookie Consent plugin by Silktide - http://silktide.com/cookieconsent -->
      <script type="text/javascript">
        window.cookieconsent_options = {
          "message":"<?php echo wfMessage('wikitolearnskin-cookies-text'); ?>",
          "dismiss":"<?php echo wfMessage('wikitolearnskin-cookies-dismiss'); ?>",
          "learnMore":"<?php echo wfMessage('wikitolearnskin-cookies-learn-more'); ?>",
          "link":null,
          "theme":"light-bottom",
          "domain": "<?php echo $wiki_domain ?>"
        };
      </script>
      <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.10/cookieconsent.min.js"></script>
    <?php
    }

    public function executeHeader() { ?>
      <header class="header">
        <div class="header__wrapper" >
          <div href="/" class="logo">
            <a href="/">
              <img class="logo__img" src="<?php echo $this->getSkin()->getSkinStylePath("images/wikitolearn-logo.png") ?>">
              <div class="logo__title">
                <span class="text-wtl--red">wiki</span><span class="text-wtl--yellow">to</span><span class="text-wtl--green">learn</span>
              </div>
            </a>
          </div>
          <nav class="nav">
            <a href="<?php echo wfMessage('wikitolearnskin-navbar-about-link')->plain(); ?>" class="nav__link nav__link--hover-red">
              <?php echo wfMessage('wikitolearnskin-navbar-about'); ?>
            </a>
            <a href="<?php echo wfMessage('wikitolearnskin-navbar-contribute-link')->plain(); ?>"  class="nav__link nav__link--hover-yellow">
              <?php echo wfMessage('wikitolearnskin-navbar-contribute'); ?>
            </a>
            <!--  -->
            <div class="dropdown dropdown--more-links">
              <a class="nav__link nav__link--hover-green" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <?php echo wfMessage('wikitolearnskin-navbar-tools'); ?>
              </a>
              <div class="dropdown-menu left">
                <a href="<?php echo wfMessage('wikitolearnskin-navbar-tools-guide-link')->plain(); ?>"  class="dropdown-item">
                  <i class="fa fa-fw fa-question-circle"></i>&nbsp;<?php echo wfMessage('wikitolearnskin-navbar-tools-guide'); ?>
                </a>
                <?php
                  $collectionTools = array_get($this->data['sidebar'], 'coll-print_export');
                  if(!is_null($collectionTools)) { ?>
                    <a href="<?php echo $collectionTools[0]['href'] ?>"  class="dropdown-item">
                    <i class="fa fa-fw fa-book"></i>&nbsp;<?php echo $collectionTools[0]['text'] ?>
                  </a>
                <?php } ?>
                <div class="dropdown-divider"></div>
                <a href="<?php echo wfMessage('wikitolearnskin-navbar-tools-chat-link')->plain(); ?>" class="dropdown-item">
                  <i class="fa fa-fw fa-comments"></i>&nbsp;<?php echo wfMessage('wikitolearnskin-navbar-tools-chat')->plain(); ?>
                </a>
                <a href="<?php echo wfMessage('wikitolearnskin-navbar-tools-community-portal-link'); ?>" class="dropdown-item">
                  <i class="fa fa-fw fa-users"></i>&nbsp;<?php echo wfMessage('wikitolearnskin-navbar-tools-community-portal'); ?>
                </a>
                <a href="<?php echo wfMessage('wikitolearnskin-navbar-tools-reports-link')->plain(); ?>" class="dropdown-item">
                  <i class="fa fa-fw fa-bar-chart"></i>&nbsp;<?php echo wfMessage('wikitolearnskin-navbar-tools-reports'); ?>
                </a>
              </div>
            </div>
            <span class="nav__search nav__<?php echo self::getAnonClass(); ?>">
            <form id="searchForm" action="<?php $this->text( 'wgScript' ); ?>" autocomplete="off">
              <input type="hidden" name="title" value="<?php $this->text( 'searchtitle' ) ?>" />
              <?php echo $this->makeSearchInput( array( 'id' => 'searchInput' ) ); ?>
              <button type="submit" class="nav__search-button">
                <i class="fa fa-search"></i>
              </button>
            </form>
            </span>
            <?php
              $user = $this->skin->getUser();
            ?>
            <?php if($user->isAnon()){ ?>
              <div class="nav__desktop-login">
                <a href="<?php echo $this->skin->makeSpecialUrl('UserLogin'); ?>" class="nav__link nav__link--login nav__link--hover-mwblue dropdown-item">
                  <?php wfMessage( 'login' ) ?>
                </a>
                <a href="<?php echo $this->skin->makeSpecialUrl('CreateAccount'); ?>" class="nav__link nav__link--createaccount nav__link--hover-mwblue dropdown-item">
                  <?php wfMessage( 'createaccount' ) ?>
                </a>
              </div>
              <div class="dropdown dropdown--mobile-login">
                <a data-toggle="dropdown" id="dropdownMobileLogin" aria-haspopup="true" aria-expanded="false">
                  <i class="fa fa-user"></i>
                  <i class="fa fa-angle-down"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMobileLogin">
                  <a href="<?php echo $this->skin->makeSpecialUrl('UserLogin'); ?>" class="nav__link nav__link--login nav__link--hover-mwblue dropdown-item">
                    <?php wfMessage( 'login' ) ?>
                  </a>
                  <a href="<?php echo $this->skin->makeSpecialUrl('CreateAccount'); ?>" class="nav__link nav__link--createaccount nav__link--hover-mwblue dropdown-item">
                    <?php wfMessage( 'createaccount' ) ?>
                  </a>
                </div>
              </div>
            <?php } else { ?>
              <div class="dropdown dropdown--personal-tools">
                <a class="nav__link nav__link--hamburger nav__link--hover-mwblue" href="#" id="dropdownToolbox" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span><?php echo $user->getName() ?></span>
                    <i class="fa fa-bars"></i>
                </a>
                <div class="dropdown-menu dropdown--user-menu left" aria-labelledby="dropdownToolbox">
                  <?php
                    $toolbar = $this->getPersonalTools();
                    unset($toolbar['notifications-alert']);
                    unset($toolbar['notifications-message']);
                    unset($toolbar['newmessages']);
                    foreach ( $toolbar as $key => $tool ) {
                      $tool['class'] = 'dropdown-item';
                      echo $this->makeListItem( $key, $tool, ["tag" => "span"] );
                      //$personalToolsCount++;
                    }
                  ?>

                  <span class="dropdown-item languages__selector hidden-sm-up"><?php echo wfMessage( "wikitolearnskin-navbar-language-selector" ); ?></span>
                  <hr class="languages__divider hidden-sm-up"></hr>
                  <div class="languages--mobile hidden-sm-up">
                    <?php echo self::generateLanguageSelectorItems(); ?>
                  </div>
                </div>
              </div>
              <div class="dropdown dropdown--notifications nav__link--hover-mwblue">
                <a id="notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fa fa-bell"></i> <i class="fa fa-angle-down"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="notifications">
                  <div class="dropdown-header">
                    <span class="dropdown--notifications__notifications-count"><?php echo wfMessage('notifications') ?>&nbsp;<span id="badge-count" class="badge" style="display:none;"></span></span>
                    <span>
                      <a href="#" id="mark-all-read-button" style="display:none;"><?php echo wfMessage('echo-mark-all-as-read') ?></a>
                    </span>
                  </div>
                  <div class="dropdown-divider"></div>
                  <div id="notifications-widget">
                  </div>
                  <div class="dropdown-divider"></div>
                  <div class="dropdown-footer">
                    <a id="notifications-view-all"><?php echo wfMessage('echo-overlay-link') ?></a>
                  </div>
                </div>
              </div>
            <?php } ?>
            <div class="dropdown dropdown--languages dropdown--languages__desktop languages__<?php echo self::getAnonClass(); ?> nav__link--hover-mwblue">
              <a class="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-globe"></i> <i class="fa fa-angle-down"></i>
              </a>
              <div class="dropdown-menu left">
                <?php self::generateLanguageSelectorItems(); ?>
              </div>
            </div>
          </nav>
        </div>
      </header>
    <?php }

    public function executeHome() {
      global $wiki_domain, $wiki;
      ?>
      <main class="page page-home">
        <section class="title">
          <h1> <?php echo wfMessage('wikitolearnskin-home-claim'); ?> </h1>
        </section>
        <section class="departments">
          <ul class="departments__content">
            <?php
            for($i=1;$i<11;$i++){
              self::makeDepartment(
                wfMessage("wikitolearnskin-departments-$i-name"),
                $this->getSkin()->getSkinStylePath( wfMessage("wikitolearnskin-departments-$i-image")->plain() ),
                wfMessage("wikitolearnskin-departments-$i-link")->plain(),
                wfMessage("wikitolearnskin-departments-$i-status")
              );
              if($i==5){
                echo '<div class="clearfix"></div>';
              }
            }
            echo '<div class="clearfix"></div>';
            ?>
          </ul>
        </section>
        <!--<section class="join-us">

        </section>-->
        <section class="media">
          <div class="media__content">
            <div class="media__videocolumn">
              <div class="media__videowrapper">
                <iframe class="media__video" src="<?php echo wfMessage('wikitolearnskin-media-video-url'); ?>" allowfullscreen></iframe>
              </div>
            </div>
            <div class="media__description">
              <div class="join-us__content">
                <div class="join-us__text">
                  <?php echo wfMessage('wikitolearnskin-media-text'); ?>
                </div>
                <div class="join-us__stats">
                <?php
                echo "<i class='fa fa-file-text-o'></i> <span class='stats__count'>" . wfMessage('createacct-benefit-head2')->text() . "</span> " . wfMessage('createacct-benefit-body2')->text();
                echo " <br/><span class='stats__divider'></span>";
                echo "<i class='fa fa-user'></i> <span class='stats__count'>" . wfMessage('createacct-benefit-head3')->text() . "</span> " . wfMessage('createacct-benefit-body3')->text();
                echo " <br/><span class='stats__divider'></span>";
                echo "<i class='fa fa-pencil'></i> <span class='stats__count'>" . wfMessage('createacct-benefit-head1')->text() . "</span> " . wfMessage('createacct-benefit-body1')->text();
                ?>
                </div>
                <a href="//join.<?php echo $wiki_domain ."/" . $wiki ?>" class="join-us__link"><?php echo wfMessage('wikitolearnskin-join-us-button'); ?></a>
            </div>
          </div>
        </section>
        <section class="testimonials">
          <div class="testimonials__content">
            <div class="testimonial">
              <a class="testimonial__link" href="#">
                <img class="testimonial__image" src="<?php echo $this->getSkin()->getSkinStylePath( wfMessage('wikitolearnskin-testimonials-first-image-path') ); ?>" alt="<?php echo wfMessage('wikitolearnskin-testimonials-first-name'); ?>">
              </a>
              <div class="testimonial__body">
                <blockquote class="testimonial__quote">
                  <?php echo wfMessage('wikitolearnskin-testimonials-first-quote'); ?>
                </blockquote>
                <footer class="testimonial__footer">
                  <cite> <?php echo wfMessage('wikitolearnskin-testimonials-first-name'); ?></cite>
                </footer>
              </div>
            </div>
            <div class="testimonial">
              <a class="testimonial__link" href="#">
                <img class="testimonial__image" src="<?php echo $this->getSkin()->getSkinStylePath( wfMessage('wikitolearnskin-testimonials-second-image-path') ); ?>" alt="<?php echo wfMessage('wikitolearnskin-testimonials-second-name'); ?>">
              </a>
              <div class="testimonial__body">
                <blockquote class="testimonial__quote">
                  <?php echo wfMessage('wikitolearnskin-testimonials-second-quote'); ?>
                </blockquote>
                <footer class="testimonial__footer">
                  <cite><?php echo wfMessage('wikitolearnskin-testimonials-second-name'); ?></cite>
                </footer>
              </div>
            </div>
            <div class="testimonial">
              <a class="testimonial__link" href="#">
                <img class="testimonial__image" src="<?php echo $this->getSkin()->getSkinStylePath( wfMessage('wikitolearnskin-testimonials-third-image-path') ); ?>" alt="<?php echo wfMessage('wikitolearnskin-testimonials-third-name'); ?>">
              </a>
              <div class="testimonial__body">
                <blockquote class="testimonial__quote">
                  <?php echo wfMessage('wikitolearnskin-testimonials-third-quote'); ?>
                </blockquote>
                <footer class="testimonial__footer">
                  <cite><?php echo wfMessage('wikitolearnskin-testimonials-third-name'); ?></cite>
                </footer>
              </div>
            </div>
            <!--<a href="<?php echo wfMessage('wikitolearnskin-read-more-stories-button-link')->plain(); ?>" class="testimonials__read-more"><?php echo wfMessage('wikitolearnskin-read-more-stories-button'); ?></a>-->
          </div>
        </section>
        <section class="contributors">
          <div class="contributors__content">
            <h3 class="contributors__title"><?php echo wfMessage('wikitolearnskin-contributions-from'); ?></h3>
            <ul class="contributors__list">
                <a href="http://www.unimib.it/" rel="nofollow" class="contributors__item"><img src="<?php echo $this->getSkin()->getSkinStylePath("images/logos/bicocca.jpg") ?>" alt=""></a>
                <a href="https://home.cern/" rel="nofollow" class="contributors__item"><img src="<?php echo $this->getSkin()->getSkinStylePath("images/logos/cern.jpg") ?>" alt=""></a>
                <a href="https://www.sissa.it/" rel="nofollow" class="contributors__item"><img src="<?php echo $this->getSkin()->getSkinStylePath("images/logos/sissa.png") ?>" alt=""></a>
                <a href="http://www.wikimedia.it/" rel="nofollow" class="contributors__item"><img src="<?php echo $this->getSkin()->getSkinStylePath("images/logos/wikimedia.png") ?>" alt=""></a>
                <a href="http://hepsoftwarefoundation.org/" rel="nofollow" class="contributors__item"><img src="<?php echo $this->getSkin()->getSkinStylePath("images/logos/hep.png") ?>" alt=""></a>
                <a href="https://www.kde.org/" rel="nofollow" class="contributors__item"><img src="<?php echo $this->getSkin()->getSkinStylePath("images/logos/kde.svg") ?>" alt=""></a>
            </ul>
          </div>
        </section>
        <!--
        <section class="sponsors">
          <h3 class="sponsors__title">On the press</h3>
          <ul class="sponsors__list">
            <div class="row">
              <li class="sponsors__item">
                <img src="<?php echo $this->getSkin()->getSkinStylePath( 'images/sponsor1.png'); ?>">
              </li>
              <li class="sponsors__item">
                <img src="<?php echo $this->getSkin()->getSkinStylePath( 'images/sponsor2.png'); ?>">
              </li>
              <li class="sponsors__item">
                <img src="<?php echo $this->getSkin()->getSkinStylePath( 'images/sponsor3.png'); ?>">
              </li>
              <li class="sponsors__item">
                <img src="<?php echo $this->getSkin()->getSkinStylePath( 'images/sponsor4.png'); ?>">
              </li>
            </div>
            <div class="row">
              <li class="sponsors__item">
                <img src="<?php echo $this->getSkin()->getSkinStylePath( 'images/sponsor1.png'); ?>">
              </li>
              <li class="sponsors__item">
                <img src="<?php echo $this->getSkin()->getSkinStylePath( 'images/sponsor2.png'); ?>">
              </li>
              <li class="sponsors__item">
                <img src="<?php echo $this->getSkin()->getSkinStylePath( 'images/sponsor3.png'); ?>">
              </li>
              <li class="sponsors__item">
                <img src="<?php echo $this->getSkin()->getSkinStylePath( 'images/sponsor4.png'); ?>">
              </li>
            </div>
          </ul>
        </section>
        -->
      </main>
    <?php }

    public function executeContentPage() {
      $pageTitle = $this->pageTitle;

      if ($this->namespaceId === NS_SPECIAL) {
        $fullTitle = $this->get('title'); //we do this so special page have their proper pretty name, no more UserLogin or UserRegister but "Login" "Register"
        $components = explode("/", $fullTitle);
        $displayTitle = $components[count($components)-1];
      } else {
        $baseTitle = $pageTitle->getBaseText(); //the part before the subpage name, without namespace
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
      ?>
      <main class="page page--article <?php echo self::getAnonClass(); ?>">
        <?php if ( $this->data['sitenotice'] ) { ?>
          <div class="sitenotice__wrapper">
            <div class="sitenotice__content"> <!-- The CSS class used in Monobook and Vector, if you want to follow a similar design -->
              <?php $this->html( 'sitenotice' ); ?>
            </div>
          </div>
        <?php } ?>
        <div class="article__wrapper">
          <?php self::executeBreadcrumb($pageTitle); //build the breadcrumb?>
          <div class="article__main"> <!-- This is needed to wrap the "sheet" and the tools so we can display them one next to another-->
            <div class="article__sheet"> <!-- This is needed to wrap the content (rectangular sheet and the bottom navigations button (so they don't overlap the tools) -->
              <article class="article__content mw-body">
                <h1 class="article__title">
                  <?php echo $displayTitle; ?>
                </h1>
                <?php if ( $this->data['subtitle'] ) { ?>
                  <div class="article__contentSub" id="contentSub"> <!-- The CSS class used in Monobook and Vector, if you want to follow a similar design -->
                  <?php //$this->html('subtitle'); ?>
                  </div>
                <?php } ?>
                  <?php if ( $this->data['undelete'] ) { ?>
                  <div class="article__contentSub2" id="contentSub2"> <!-- The CSS class used in Monobook and Vector, if you want to follow a similar design -->
                  <?php $this->html( 'undelete' ); ?>
                  </div>
                <?php } ?>
                <div id="content"> <!-- #content tells visauleditor where to put itself: under the title -->
                  <div class="article__text" id="bodyContent">
                    <?php $this->html( 'bodytext' ); ?>
                  </div>
                  <div class="article__categories">
                    <?php $this->html( 'catlinks' ); ?>
                  </div>
                  <div class="article__dataAfterContent">
                    <?php $this->html( 'dataAfterContent' ); ?>
                  </div>
                </div> <!-- Here the real content begins -->
              </article>
              <?php self::executePreviousNext(); //build previos and next ?>
            </div>
            <?php self::executePageTools() //build the tools?>
          </div>
        </div>

      </main>
    <?php }

    public function executeFooter() { ?>
      <footer class="footer">
          <ul class="footer__list">
            <li class="footer__logo">
              <img src="/skins/WikiToLearnSkin/images/wikitolearn-logo.png">
            </li>
            <li class="footer__wikitolearn">
              <h4>WikiToLearn</h4>
              <ul class="footer__wikitolearn-list">
                <li>
                  <a href="<?php echo wfMessage("wikitolearnskin-navbar-about-link")->plain(); ?>"><i class="fa fa-info-circle">&nbsp;</i><?php echo wfMessage("wikitolearnskin-navbar-about"); ?></a>
                </li>
                <li>
                  <a href="<?php echo wfMessage("wikitolearnskin-navbar-contribute-link")->plain(); ?>"><i class="fa fa-check-square-o">&nbsp;</i><?php echo wfMessage("wikitolearnskin-navbar-contribute"); ?></a>
                </li>
                <li>
                  <a href="<?php echo wfMessage("wikitolearnskin-footer-academic-link")->plain(); ?>"><i class="fa fa-university">&nbsp;</i><?php echo wfMessage("wikitolearnskin-footer-academic"); ?></a>
                </li>
              </ul>
              <h4 class="footer__second-heading"><?php echo wfMessage('wikitolearnskin-footer-hosted-by'); ?></h4>
              <ul class="footer__second-list">
                <li>
                  <a href="http://www.garr.it/">GARR</a>
                </li>
                <li>
                  <a href="https://www.neodigit.net/">Neodigit</a>
                </li>
              </ul>
            </li>
            <li class="footer__tools clearfix">
              <h4><?php echo wfMessage("wikitolearnskin-footer-tools"); ?></h4>
              <ul class="footer__tools-list">
                <li>
                  <a href="<?php echo wfMessage("wikitolearnskin-footer-tools-1-link")->plain(); ?>"><i class="fa fa-question-circle">&nbsp;</i><?php echo wfMessage("wikitolearnskin-navbar-tools-guide"); ?></a>
                </li>
                <!--<li>
                  <a href="<?php echo wfMessage("wikitolearnskin-navbar-tools-createbook-link")->plain(); ?>"><i class="fa fa-book">&nbsp;</i><?php echo wfMessage("wikitolearnskin-navbar-tools-createbook"); ?></a>
                </li>-->
                <li><hr /></li>
                <li>
                  <a href="<?php echo wfMessage("wikitolearnskin-navbar-tools-chat-link")->plain(); ?>"><i class="fa fa-comments">&nbsp;</i><?php echo wfMessage("wikitolearnskin-navbar-tools-chat"); ?></a>
                </li>
                <li>
                  <a href="<?php echo wfMessage("wikitolearnskin-navbar-tools-community-portal-link")->plain(); ?>"><i class="fa fa-users">&nbsp;</i><?php echo wfMessage("wikitolearnskin-navbar-tools-community-portal"); ?></a>
                </li>
                <li>
                  <a href="<?php echo wfMessage("wikitolearnskin-navbar-tools-reports-link")->plain(); ?>"><i class="fa fa-bar-chart">&nbsp;</i><?php echo wfMessage("wikitolearnskin-navbar-tools-reports"); ?></a>
                </li>
                <hr>
                <li>
                  <?php
                    $linkObj = Title::newFromText("Special:RecentChanges");
                    echo Linker::linkKnown($linkObj, wfMessage("wikitolearnskin-footer-changes")); ?>
                </li>
                <li>
                  <?php
                    $linkObj = Title::newFromText("Special:SpecialPages");
                    echo Linker::linkKnown($linkObj, wfMessage("wikitolearnskin-footer-special-pages")); ?>
                </li>
              </ul>
            </li>
            <li class="footer__social">
              <h4>
                <?php echo wfMessage("wikitolearnskin-footer-keep-in-touch"); ?>
              </h4>
              <ul class="social-icons">
                <li>
                  <a href="https://www.facebook.com/WikiToLearn" class="fa fa-facebook fa-2x" aria-hidden="true"></a>
                </li>
                <li>
                  <a href="https://twitter.com/wikitolearn" class="fa fa-twitter fa-2x" aria-hidden="true"></a>
                </li>
                <li>
                  <a href="https://www.linkedin.com/company/wikitolearn" class="fa fa-linkedin fa-2x" aria-hidden="true"></a>
                </li>
              </ul>
              <ul>
                <li><a href="mailto:info@wikitolearn.org">info@wikitolearn.org</a></li>
                <li>
                  <a href="<?php echo wfMessage("wikitolearnskin-footer-kit-channels-link")->plain(); ?>"><?php echo wfMessage("wikitolearnskin-footer-kit-channels-text"); ?></a>
                </li>
              </ul>
            </li>
          </ul>
        </footer>
    <?php }

    /*
    * Wrapper for generating and printing all kinds of breadcrumbs
    */
    public function executeBreadcrumb($pageTitle){
      $fullTitle = $pageTitle->getText();
      $titleComponents = explode("/", $fullTitle);
      $partialLink = $pageTitle->getNsText() . ":";
      if(count($titleComponents) > 1) {
        echo '<div class="article__breadcrumb">';
          echo '<div class="breadcrumb">';
          if ($this->namespaceId === NS_COURSE) {
            $this->executeCourseBreadcrumb($titleComponents, $partialLink);
          }elseif ($this->namespaceId === NS_USER) {
            $this->executeUserBreadcrumb($titleComponents, $partialLink);
          }else {
            $this->executeStandardBreadcrumb($titleComponents, $partialLink);
          }
          echo "</div>";
        echo '</div>';
        }
    }

    /**
    * Generete the breadcrumb for all namespaces except NS_USER and NS_COURSE
    * @param string[] $titleComponents the subtokens composed the title
    * @param string $partialLink the partial link from which to build the links
    */
    public function executeStandardBreadcrumb($titleComponents, $partialLink){
      for ($i = 0; $i < sizeof($titleComponents); $i++) {
        $titleComponent = $titleComponents[$i];
        $partialLink .= $titleComponent;
        $linkObj = Title::newFromText($partialLink);
        $link = Linker::linkKnown($linkObj, htmlspecialchars( $titleComponent ), ["class" => "breadcrumb__item"]);
        echo $link;
        if($i !== (sizeof($titleComponents) - 1)) { //we don't add the slash on last link
          echo "<span class='breadcrumb__divider'> <i class='fa fa-angle-right'></i> </span>";
        }
        $partialLink .= "/";
      }
    }

    /**
    * Generete the HTML for the breadcrumb in NS_USER exploting
    * executeCourseBreadcrumb() method for the common part after the
    * userpage link.
    * @param string[] $titleComponents the subtokens composed the title
    * @param string $partialLink the partial link from which to build the links
    */
    public function executeUserBreadcrumb($titleComponents, $partialLink){
      $userPage = array_shift($titleComponents);
      $partialLink .= $userPage;
      $linkObj = Title::newFromText($partialLink);
      $link = Linker::linkKnown($linkObj, htmlspecialchars( $userPage ), ["class" => "breadcrumb__item"]);
      echo ($link);
      echo "<span class='breadcrumb__divider'> <i class='fa fa-angle-right'></i> </span>";
      self::executeCourseBreadcrumb($titleComponents, $partialLink . "/");
    }

    /**
    * Generete the HTML of the breadcrumb for pages that follow the Course
    * structure. We want to provide dropdowns within breadcrumb in these pages.
    * Courses are allowed in NS_COURSE and NS_USER.
    * @param string[] $titleComponents the subtokens composed the title
    * @param string $partialLink the partial link from which to build the links
    */
    // FIXME: This function it's a mess! Should be refactored, but it works!
    public function executeCourseBreadcrumb($titleComponents, $partialLink) {
      $partialLink .= $titleComponents[0];
      $subpages = CourseEditorUtils::getLevelsTwo($partialLink);
      $linkObj = Title::newFromText($partialLink);
      $link = Linker::linkKnown($linkObj, htmlspecialchars( $titleComponents[0] ), ["class" => "breadcrumb__item"]);
      echo $link;
      if (count($subpages) !== 0 && count($titleComponents) > 1){
        self::buildBreadcrumbDropdown($subpages, $partialLink);
      }
      switch (count($titleComponents)) {
        case 2:
          $linkObj = Title::newFromText($partialLink . "/" . $titleComponents[1]);
          $link = Linker::linkKnown($linkObj, htmlspecialchars( $titleComponents[1] ), ["class" => "breadcrumb__item"]);
          echo $link;
          break;
        case 3:
          $partialLink .= "/" . $titleComponents[1];
          $linkObj = Title::newFromText($partialLink);
          $link = Linker::linkKnown($linkObj, htmlspecialchars( $titleComponents[1] ), ["class" => "breadcrumb__item"]);
          echo $link;
          $subpages = CourseEditorUtils::getLevelsThree($partialLink);
          if (sizeof($subpages) !== 0){
            self::buildBreadcrumbDropdown($subpages, $partialLink);
          }
          $linkObj = Title::newFromText($partialLink . "/" . $titleComponents[2]);
          $link = Linker::linkKnown($linkObj, htmlspecialchars( $titleComponents[2] ), ["class" => "breadcrumb__item"]);
          echo $link;
          break;
        default:
          break;
      }
    }

  /**
  * Utility to build the dropdowns in the breadcrumb
  * @param string[] $titleComponents the subtokens composed the title
  * @param string $partialLink the partial link from which to build the links
  */
  private function buildBreadcrumbDropdown($subpages, $partialLink){
    ?>
      <div class="dropdown breadcrumb__dropdown">
        <span href="#" class="dropdown__toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-angle-right"></i>
        </span>
        <div class="dropdown-menu">
          <?php
          foreach ($subpages as $subpage){
            $subpageLink = Skin::makeUrl($partialLink . '/' . $subpage);
            if($subpageLink === $this->pageTitle->getLocalURL()){
              $additionalClass = "active";
            } else {
              $additionalClass = "";
            }
            ?>
            <a class="dropdown-item <?php echo $additionalClass ?>" href="<?php echo $subpageLink ?>"><?php echo $subpage ?></a>
            <?php
          }
          ?>
        </div>
      </div>
    <?php
  }

    /**
    * Generate a WikiToLearn's version of tools related to page.
    * These tools are composed by classic 'views' tools (view, edit, history...)
    * some 'collection' tools (Download as PDF, Download plain text..),
    * and advaced tools.
    */
    public function executePageTools() {

      $namespace = $this->namespaceId;
      switch ($namespace) {
        case NS_MAIN:
          self::executeMainPageTools();
          break;

        case NS_COURSE: case NS_USER: //they have the same actions
          self::executeCoursePageTools();
          break;

        case NS_TEMPLATE: case NS_PROJECT:
          self::executeSecondaryNamespacePageTools();
          break;

        default:
          break; //nothing on purpose
      }
    }

    private function executeMainPageTools(){
      echo '<div class="article__tools">';
        echo '<div id="tools_container">';
          if(self::userHasEnoughRights()){ //only allow admin to see the visual editor edit button on these pages
            self::makeEditTool();
          }
          if(self::pageHasCategory("Department")){ //on departments show discussion
            self::makeDisussionTool();
          }
          if(self::userHasEnoughRights()){
            self::makeAdvancedTools();
          }
        echo '</div>';
      echo '</div>';
    }

    private function executeCoursePageTools(){
      $namespace = $this->namespaceId;
      echo '<div class="article__tools">';
        echo '<div id="tools_container">';
          if(self::pageHasCategory("CourseRoot")) {
            self::makeEditCourseRootTool();
            self::makeDisussionTool();
            self::makeDownloadCourseTool();

            if(self::userHasEnoughRights()){
              if($namespace === NS_USER){
                echo '<div class="tool--divider"></div>';
                if(self::pageHasCategory("ReadyToBePublished")) {
                  self::makeUndoPublishButton();
                } else {
                  self::makePublishButton();
                }
              }
              self::makeAdvancedTools();
            }
          } else if(self::pageHasCategory("CourseLevelTwo")) {
            self::makeEditCourseLevelTwoTool();
            if(self::userHasEnoughRights()){
              self::makeAdvancedTools();
            }
          } else {
            self::makeEditTool();
            self::makeDisussionTool();
            self::makeDownloadPageTool();
            if($namespace === NS_USER && self::pageIsRootLevel()){
              echo '<div class="tool--divider"></div>';
              self::makeUserTools();
              self::makeAdvancedTools();
            } else {
              echo '<div class="tool--divider"></div>';
              self::makeAdvancedTools();
            }
          }
        echo '</div>';
      echo '</div>';
    }

    private function executeSecondaryNamespacePageTools(){
      if(self::userHasEnoughRights()){
        echo '<div class="article__tools">';
          echo '<div id="tools_container">';
            self::makeEditAsWikitextTool();
          echo '</div>';
        echo '</div>';
      }
    }

    public function pageIsRootLevel(){
      global $wgOut;
      $title = $wgOut->getTitle();
      return $title->getRootTitle()->equals($title);
    }

    /**
    * Return true if an user has enough rights (aka admin)
    * Might need improvements
    */
    private function userHasEnoughRights(){
      global $wgOut, $wgUser;
      $title = $wgOut->getTitle();
      $user = $wgUser;
      return $title->userCan('delete', $user, 'secure');
    }

    private function makeEditTool(){
      $editTools = $this->contentNavigation['views'];
      foreach ($editTools as $key => $toolAttributes) {
        if($key === "ve-edit"){
          self::makeTool($toolAttributes['href'], $toolAttributes['text'], $toolAttributes['id'], "tool--red--filled", "fa-pencil" );
        }
      }
    }

    private function makeEditCourseRootTool(){
      global $wgOut;
      $title = $wgOut->getTitle();
      $link = CourseEditorUtils::makeEditCourseUrl($title);
      self::makeTool($link['href'], $link['text'], NULL, "tool--red--filled", "fa-pencil");
    }

    private function makeEditCourseLevelTwoTool(){
      global $wgOut;
      $title = $wgOut->getTitle();
      $link = CourseEditorUtils::makeEditLevelTwoUrl($title);
      self::makeTool($link['href'], $link['text'], NULL, "tool--red--filled", "fa-pencil");
    }

    private function makeEditAsWikitextTool(){
      $editTools = $this->contentNavigation['views'];
      self::makeTool($editTools['edit']['href'], $editTools['edit']['text'], $editTools['edit']['id'], "tool--red--filled", "fa-pencil");
    }

    private function makeDisussionTool(){
      $namespaceAndTalk = $this->contentNavigation['namespaces'];
      foreach ($namespaceAndTalk as $value) {
        if ($value['id'] === "ca-talk") {
          self::makeTool($value['href'], $value['text'], $value['id'], "tool--yellow--filled", "fa-comments-o" );
          break;
        }
      }
    }

    private function makeDownloadCourseTool(){
      global $wgOut;
      $title = $wgOut->getTitle();
      $url = CourseEditorUtils::makeDownloadCourseUrl($title);
      self::makeTool($url, wfMessage('coll-download'), NULL, "tool--green--filled", "fa-download" );
    }

    private function makeDownloadPageTool(){
      $collectionTools = array_get($this->data['sidebar'], 'coll-print_export');
      if(!is_null($collectionTools)){
        self::makeTool($collectionTools[1]['href'], $collectionTools[1]['text'], $collectionTools[1]['id'], "tool--green--filled", "fa-download" );
      }
    }

    private function makePublishButton(){
      $title = wfMessage('wikitolearnskin-publish-course-tool')->text();
      self::makeTool('#', $title, 'publishCourseButton', 'tool--black', 'fa-reply fa-rotate-45' );
    }

     private function makeUndoPublishButton(){
      $title = wfMessage('wikitolearnskin-undo-publish-course-tool')->text();
      self::makeTool('#', $title, 'undoPublishCourseButton', 'tool--black', 'fa-undo' );
    }

    /**
    * Check the namespace in order to confirm or not the
    * generation of certain page tools.
    * @return boolean
    */
    private function isEditableNamespace(){
      $namespaceId = $this->namespaceId;
      $user = $this->user;
      if ($namespaceId === NS_COURSE) {
        $fullTitle = $this->get('title');
        if(substr_count($fullTitle, "/") < 2){ //dont show tools on course root and section pages
          return false;
        } else {
          return true;
        }
      } elseif ($namespaceId === NS_USER) {
        return true;
      } elseif ($namespaceId === NS_MAIN || $namespaceId === NS_TEMPLATE || $namespaceId === NS_PROJECT) {
        if(self::userHasEnoughRights()){
          return true;
        }
      }
      return false;
    }

    /**
    * Generate the HTML of previous and next buttons
    */
    private function executePreviousNext(){
      $previousAndNext = CourseEditorUtils::getPreviousAndNext($this->pageTitle);
      if (array_key_exists('previous', $previousAndNext) ||
          array_key_exists('next', $previousAndNext)) {
        echo '<div class="article__navigation">';
        if (array_key_exists('previous', $previousAndNext)) {
          $previous = $previousAndNext['previous'];
          if($previous != NULL){
            $href = Skin::makeUrl($previous);
            $title = wfMessage('wikitolearnskin-previous-button-title');
            echo "<a href='$href' title='$title' class='navigation__button navigation__button--previous'><i class='fa fa-angle-double-left'></i>&nbsp;$title</a>";
          }
        }
        if (array_key_exists('next', $previousAndNext)) {
          $next = $previousAndNext['next'];
          if($next != NULL){
            $href = Skin::makeUrl($next);
            $title = wfMessage('wikitolearnskin-next-button-title');
            echo "<a href='$href' title='$title' class='navigation__button navigation__button--next'>$title&nbsp;<i class='fa fa-angle-double-right'></i></a>";
          }
        }
        echo '</div>';
      }
    }

    private function makeUserTools(){ ?>
      <div class="multitool horizontal click-to-toggle">
        <span title="<?php echo wfMessage('wikitolearnskin-user-tools-title') ?>" class="tool tool--black multitool__trigger">
          <div class="tool__content">
            <i class="tool__icon fa fa-user fa-fw"></i>
            <span class="tool__title"><? echo wfMessage('wikitolearnskin-user-tools-title') ?></span>
          </div>
        </span>
        <ul>
          <?php
            $toolbox = $this->getToolbox();
            $toolbox = array_reverse($toolbox);
            foreach ($toolbox as $key => $tool){
              switch ($key) {
                case 'contributions':
                  echo "<li>";
                  self::makeTool($tool['href'], $tool['text'], $tool['id'], "tool--smaller tool--black", "fa-certificate" );
                  echo "</li>";
                  break;
                case 'log':
                  echo "<li>";
                  self::makeTool($tool['href'], wfMessage('wikitolearnskin-user-tools-logs-title'), $tool['id'], "tool--smaller tool--black", "fa-tasks" );
                  echo "</li>";
                  break;
                case 'blockip':
                  echo "<li>";
                  self::makeTool($tool['href'], $tool['text'], $tool['id'], "tool--smaller tool--black", "fa-ban" );
                  echo "</li>";
                  break;
                case 'emailuser':
                  echo "<li>";
                  self::makeTool($tool['href'], wfMessage('wikitolearnskin-user-tools-emailuser-title'), $tool['id'], "tool--smaller tool--black", "fa-envelope-o" );
                  echo "</li>";
                  break;
                default:
                  # code...
                  break;
              }
            }
          ?>
        </ul>
      </div>
    <?php }

    /**
    * Generate the HTML of the advanced tools
    */
    private function makeAdvancedTools(){
      $actionsTools = $this->contentNavigation['actions'];
      $editTools = $this->contentNavigation['views'];
      $collectionTools = array_get($this->data['sidebar'], 'coll-print_export');
    ?>
      <div class="multitool horizontal click-to-toggle">
        <span title="<?php echo wfMessage('wikitolearnskin-advanced-button-title') ?>" class="tool tool--black multitool__trigger">
          <div class="tool__content">
            <i class="tool__icon fa fa-ellipsis-v fa-fw"></i>
            <span class="tool__title"><? echo wfMessage('wikitolearnskin-advanced-button-title') ?></span>
          </div>
        </span>
        <ul>
        <?php
        if(!is_null($collectionTools)) {
          if(!is_null(array_get($collectionTools, 2))){
            echo "<li>";
              self::makeTool($collectionTools[2]['href'], $collectionTools[2]['text'], $collectionTools[2]['id'], "tool--smaller tool--black", "fa-file-text-o" );
            echo "</li>";
          }
          if(!is_null(array_get($collectionTools, 3))){
            echo "<li>";
              self::makeTool($collectionTools[3]['href'], $collectionTools[3]['text'], $collectionTools[3]['id'], "tool--smaller tool--black", "fa-print" );
            echo "</li>";
          }
        } ?>
        <?php foreach ($actionsTools as $key => $toolAttributes){ ?>
          <li>
            <?php
            switch ($key) {
              case 'watch':
                self::makeTool($toolAttributes['href'], $toolAttributes['text'], $toolAttributes['id'], "tool--smaller tool--black", "fa-eye" );
                break;
              case 'unwatch':
                self::makeTool($toolAttributes['href'], $toolAttributes['text'], $toolAttributes['id'], "tool--smaller tool--black", "fa-eye-slash" );
                break;
              case 'protect':
                self::makeTool($toolAttributes['href'], $toolAttributes['text'], $toolAttributes['id'], "tool--smaller tool--black", "fa-lock" );
                break;
              case 'delete':
                self::makeTool($toolAttributes['href'], $toolAttributes['text'], $toolAttributes['id'], "tool--smaller tool--black", "fa-trash" );
                break;
              case 'move':
                self::makeTool($toolAttributes['href'], $toolAttributes['text'], $toolAttributes['id'], "tool--smaller tool--black", "fa-reply" );
                break;
            }
            ?>
          </li>
        <?php
      } ?>
          <li>
            <?php
            self::makeTool($editTools['history']['href'], $editTools['history']['text'], $editTools['history']['id'], "tool--smaller tool--black", "fa-history");
             ?>
          </li>
          <li>
            <?php
            self::makeTool($editTools['edit']['href'], $editTools['edit']['text'], $editTools['edit']['id'], "tool--smaller tool--black", "fa-pencil-square-o");
            ?>
          </li>
        </ul>
      </div>
      <?php
    }

    /**
    * Generate the HTML of a page tool.
    * @param string $href the url to assign to the anchor
    * @param string $title the title to assign to the anchor
    * @param string $classes the classes to add to anchor
    * @param string $icon the icon name
    */
    private function makeTool($href, $title, $id, $classes, $icon) {
      ?>
      <a <?php if($id !== NULL){ echo ('id="' . $id . '"'); }?> title="<?php echo $title ?>" class="tool <?php echo $classes?>" href="<?php echo $href ?>">
          <div class="tool__content">
            <i class="tool__icon fa <?php echo $icon ?> fa-fw"></i> <span class="tool__title"><? echo $title ?></span>
          </div>
      </a>
      <?php
    }

    /**
    * Generate the HTML of the languages dropdown using the officially supported
    * language codes to make the subdomain urls and display the language names
    * in the proper language.
    * Cause we deploy WikiToLearn on different domains (staging, testing, ...),
    * I exploited the $wiki_domain variabile to get the domain string in order
    * to make the subdomain urls.
    */
    private function generateLanguageSelectorItems(){
      $supportedLanguages = $this->supportedLanguages;
      $domain = $this->domain;
      asort($supportedLanguages);
      foreach($supportedLanguages as $langCode) {
        $domainLink = '//' . $langCode . '.' . $domain;
        echo '<a class="dropdown-item" href="' . $domainLink . '">' . Language::fetchLanguageName($langCode) .'</a>';
      }
    }

    /**
    * Generate the HTML of the department badges in the static homepage.
    */
    private function makeDepartment($name, $imageUrl, $link, $status){
      ?>
      <li class="departments__wrapper departments__wrapper--first">
        <a class="departments__item <?php echo $status ?>" href="<?php echo $link ?>">
          <img class="departments__image <?php echo $status ?>" src="<?php echo $imageUrl ?>" alt="">
          <span class="departments__name <?php echo $status ?>"><?php echo $name ?></span>
        </a>
      </li>
      <?php
    }

    /**
     * Get the respective class for both the logged
     * and not logged user
     * @return  string the name of the class
     */
    private function getAnonClass() {
      if($this->skin->getUser()->isAnon()){
        return "user--anon";
      }
      return "user--logged";
    }

    /**
    * Checks if a title object has a category
    * @return boolean if the title has a category
    */
    private function pageHasCategory($searchCategoryName) {
      global $wgOut, $wgContLang;
      $title = $wgOut->getTitle();
      $wikiPage = WikiPage::factory($title);
      $text = $wikiPage->getText();

      //HACK: we need to do this because of the async nature of categories
      $toSearch = "[[" . $wgContLang->getNsText( NS_CATEGORY ) . ":" . $searchCategoryName . "]]";
      $toSearchFallback = "[[Category:" . $searchCategoryName . "]]";
      if (strstr($text, $toSearch) || strstr($text, $toSearchFallback)) { //HACK
        return true;
      } else {
        return false;
      }
    }

    //prepare the override messages, works only on the main page, where the content is mostl likely to change
    private function prepareOverrideMessages(){
      $title = $this->getSkin()->getTitle();
      if($title->isMainPage()){ //only allow on main pages
        $wikiPage = WikiPage::factory($title);
        $text = $wikiPage->getText();
        $this->overrideMessages = json_decode($text, true);
      }
    }

    /*
    This is to allow to override localization keys, right from content page
    The default param will override wfMessageKey 
    */
    private function getMessage($key){
      if(isset($this->overrideMessages) && $this->overrideMessages != null){
        $messageText = array_get($this->overrideMessages, $key);
        if($messageText)
          return $messageText;
        else
          return wfMessage($key)->plain();
      } else {
        return wfMessage($key)->plain();
      }
    }
}
