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
        $out->addMeta( 'viewport', 'width=device-width, initial-scale=1' );
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
        $out->addStyle("https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700");
        $out->addStyle("https://fonts.googleapis.com/css?family=Roboto+Slab:100,300,400,700");
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
      //Declare useful variables for the whole template functions
      global $wgOut, $wgRequest, $wgUser;
      $this->skin = $this->getSkin();
      $this->namespaceId = $wgOut->getTitle()->getNamespace();
      $this->pageTitle = $wgOut->getTitle();
      $this->user = $wgUser;
      $this->contentNavigation = $this->data['content_navigation'];

      $this->html( 'headelement' ); ?>
            <?php $this->html( 'newtalk' ); ?>

            <?php if ( $this->data['newtalk'] ) { ?>
              <div class="usermessage"> <!-- The CSS class used in Monobook and Vector, if you want to follow a similar design -->
                <?php $this->html( 'newtalk' );?>
              </div>
            <?php } ?>

            <?php if ( $this->data['sitenotice'] ) { ?>
              <div id="siteNotice"> <!-- The CSS class used in Monobook and Vector, if you want to follow a similar design -->
                <?php $this->html( 'sitenotice' ); ?>
              </div>
            <?php } ?>

            <?php
              $this->executeHeader();
              if ($this->getSkin()->getTitle()->isMainPage()) {
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

    public function executeHeader() { ?>
      <header class="header">
        <div class="header__wrapper" >
          <div href="/" class="logo">
            <a href="/">
              <img class="logo__img" src="/skins/WikiToLearnSkin/images/wikitolearn-logo.png">

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
            <a href="<?php echo wfMessage('wikitolearnskin-navbar-third-option-link')->plain(); ?>"  class="nav__link nav__link--hover-green">
              <?php echo wfMessage('wikitolearnskin-navbar-third-option'); ?>
            </a>
            <span class="nav__search">
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
              <a href="<?php echo $this->skin->makeSpecialUrl('UserLogin'); ?>" class="nav__link nav__link--hover-mwblue">
                <?php $this->msg( 'login' ) ?>
              </a>
              <a href="<?php echo $this->skin->makeSpecialUrl('CreateAccount'); ?>" class="nav__link nav__link--hover-mwblue">
                <?php $this->msg( 'createaccount' ) ?>
              </a>
            <?php } else { ?>
              <div class="dropdown dropdown--personal-tools">
                <a class="nav__link nav__link--hamburger nav__link--hover-mwblue" href="#" id="dropdownToolbox" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span><?php echo $user->getName() ?></span>
                    <i class="fa fa-bars"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownToolbox">
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
                </div>
              </div>
              <div class="dropdown dropdown--notifications">
                <a id="notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fa fa-bell"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="notifications">
                  <div class="dropdown-header">
                    <span class="dropdown--notifications__notifications-count"><?php echo wfMessage('notifications') ?>&nbsp;<span id="badge-count" class="badge" style="display:none;"></span></span>
                    <span class="dropdown--notifications__mark-read-notifications">
                      <a href="#" id="mark-all-read-button" style="display:none;"><?php echo wfMessage('echo-mark-all-as-read') ?></a>
                    </span>
                  </div>
                  <div class="dropdown-divider"></div>
                  <div id="notifications-widget">
                  </div>
                  <!-- <div class="dropdown-item">
                    <div class="notifications-icon">
                      <i class="fa fa-quote-left"></i>
                    </div>
                    <div class="notifications-content">
                      <h5 class="notifications-title">{{ notifications.title }}</h5>
                      <p class="notifications-message"> {{ notifications.message }} </p>
                      <div class="notifications-details">
                        <span> <i class="fa fa-user"></i> {{ Author }} </span>
                        <span> <i class="fa fa-comment"></i> {{ File }} </span>
                        <span>
                          <a href="#something" class="notifications-check" title="{{ Segna come già letto }}">
                            <i class="fa fa-check"></i>
                          </a>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="dropdown-divider"></div>
                  <div class="dropdown-item">
                    <div class="notifications-icon">
                      <i class="fa fa-quote-left"></i>
                    </div>
                    <div class="notifications-content">
                      <h5 class="notifications-title">{{ notifications.title }}</h5>
                      <p class="notifications-message"> {{ notifications.message }} </p>
                      <div class="notifications-details">
                        <span> <i class="fa fa-user"></i> {{ Author }} </span>
                        <span> <i class="fa fa-comment"></i> {{ File }} </span>
                        <span>
                          <a href="#something" class="notifications-check" title="{{ Segna come già letto }}">
                            <i class="fa fa-check"></i>
                          </a>
                        </span>
                      </div>
                    </div>
                  </div> -->
                  <div class="dropdown-divider"></div>
                  <div class="dropdown-footer">
                    <a id="notifications-view-all"><?php echo wfMessage('echo-overlay-link') ?></a>
                  </div>
                </div>
              </div>
            <?php } ?>
            &nbsp;
            <div class="dropdown dropdown--languages">
              <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-language"></i>
              </a>
              <div class="dropdown-menu">
                <?php self::generateLanguageSelectorItems(); ?>
              </div>
            </div>
          </nav>
        </div>
      </header>
    <?php }

    public function executeHome() { ?>
      <main class="page page-home">
        <section class="title">
          <h1> <?php echo wfMessage('wikitolearnskin-home-claim'); ?> </h1>
        </section>
        <section class="departments">
          <ul class="departments__content">
            <li class="departments__wrapper departments__wrapper--first">
              <div class="departments__item">
                <img class="departments__image" src="http://i.imgur.com/XtYKLW2.png" alt="">
                <span class="departments__name">Fisica</span>
              </div>
            </li>
            <li class="departments__wrapper">
              <div class="departments__item">
                <img class="departments__image" src="http://i.imgur.com/PEPTSKE.png" alt="">
                <span class="departments__name">Bioscienze</span>
              </div>
            </li>
            <li class="departments__wrapper">
              <div class="departments__item">
                <img class="departments__image" src="http://i.imgur.com/XtYKLW2.png" alt="">
                <span class="departments__name">Informatica</span>
              </div>
            </li>
            <li class="departments__wrapper departments__wrapper--last">
              <div class="departments__item">
                <img class="departments__image" src="http://i.imgur.com/PEPTSKE.png" alt="">
                <span class="departments__name">Matematica</span>
              </div>
            </li>
            <li class="departments__wrapper departments__wrapper--last">
              <div class="departments__item">
                <img class="departments__image" src="http://i.imgur.com/PEPTSKE.png" alt="">
                <span class="departments__name">Matematica</span>
              </div>
            </li>
            <div class="clearfix"></div>
            <li class="departments__wrapper">
              <div class="departments__item">
                <img class="departments__image" src="http://i.imgur.com/XtYKLW2.png" alt="">
                <span class="departments__name">Fisica</span>
              </div>
            </li>
            <li class="departments__wrapper">
              <div class="departments__item">
                <img class="departments__image" src="http://i.imgur.com/PEPTSKE.png" alt="">
                <span class="departments__name">Bioscienze</span>
              </div>
            </li>
            <li class="departments__wrapper">
              <div class="departments__item">
                <img class="departments__image" src="http://i.imgur.com/XtYKLW2.png" alt="">
                <span class="departments__name">Informatica</span>
              </div>
            </li>
            <li class="departments__wrapper">
              <div class="departments__item">
                <img class="departments__image" src="http://i.imgur.com/PEPTSKE.png" alt="">
                <span class="departments__name">Matematica</span>
              </div>
            </li>
            <li class="departments__wrapper">
              <div class="departments__item">
                <img class="departments__image" src="http://i.imgur.com/XtYKLW2.png" alt="">
                <span class="departments__name">Fisica</span>
              </div>
            </li>
            <div class="clearfix"></div>
          </ul>
        </section>
        <section class="join-us">
          <div class="join-us__content">
            <a href="<?php echo wfMessage('wikitolearnskin-join-us-button-link')->plain(); ?>" class="join-us__link"><?php echo wfMessage('wikitolearnskin-join-us-button'); ?></a>
            <div class="join-us__this-week">
                {{This week on WikiToLearn: 32 new edits, 6 new pages and 2 new users.}}
            </div>
          </div>
        </section>
        <section class="media">
          <div class="media__wrapper">
            <iframe class="media__video" src="<?php echo wfMessage('wikitolearnskin-media-video-url'); ?>" allowfullscreen></iframe>
          </div>
          <div class="media__description">
            <h3 class="media__title"><?php echo wfMessage('wikitolearnskin-media-title'); ?></h3>
            <p class="media__text">
              <?php echo wfMessage('wikitolearnskin-media-text'); ?>
            </p>
            <a href="<?php echo wfMessage('wikitolearnskin-join-us-button-link')->plain(); ?>" class="media__button">
              <?php echo wfMessage('wikitolearnskin-media-learn-more'); ?>
            </a>
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
            <a href="<?php echo wfMessage('wikitolearnskin-read-more-stories-button-link')->plain(); ?>" class="testimonials__read-more"><?php echo wfMessage('wikitolearnskin-read-more-stories-button'); ?></a>
          </div>
        </section>
        <section class="contributors">
          <div class="contributors__content">
            <h3 class="contributors__title"><?php echo wfMessage('wikitolearnskin-contributions-from'); ?></h3>
            <ul class="contributors__list">
              <div class="row">
                <li class="contributors__item"><img src="https://upload.wikimedia.org/wikipedia/it/a/a2/Logo_Universit%C3%A0_Milano-Bicocca.jpg" alt=""></li>
                <li class="contributors__item"><img src="http://design-guidelines.web.cern.ch/sites/design-guidelines.web.cern.ch/files/u6/CERN-logo.jpg" alt=""></li>
                <li class="contributors__item"><img src="https://upload.wikimedia.org/wikipedia/it/thumb/5/54/Logo_Scuola_Internazionale_Superiore_di_Studi_Avanzati.svg/1280px-Logo_Scuola_Internazionale_Superiore_di_Studi_Avanzati.svg.png" alt=""></li>
              </div>
              <div class="row">
                <li class="contributors__item"><img src="http://wikitolearn.org/images/it-wikimedia.png" alt=""></li>
                <li class="contributors__item"><img src="http://wikitolearn.org/images/hep-logo.png" alt=""></li>
                <li class="contributors__item"><img src="http://www.kde.org/stuff/clipart/klogo-official-lineart_simple.svg" alt=""></li>
              </div>
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
      $displayTitle = $this->pageTitle->getSubpageText();
      ?>
      <main class="page page--article">
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
            <?php self::executePageTools($fullTitle) //build the tools?>
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
            <li class="footer__contacts">
              <h4><?php echo wfMessage('wikitolearnskin-footer-contacs'); ?></h4>
              <ul class="contacts-list">
                <li>
                  <a href="mailto:info@wikitolearn.org"> info@wikitolearn.org </a>
                </li>
                <li>
                  <a href="#{{mailing-list}}"> mailing list </a>
                </li>
                <li>
                  <a href="#{{communications-channels}}"> communications channels </a>
                </li>
                <li>
                  <a href="#{{F.A.Q}}"> F.A.Q </a>
                </li>
              </ul>
            </li>
            <li class="footer__learn-more clearfix">
              <h4 class="learn-more__first-heading"><?php echo wfMessage('wikitolearnskin-footer-learn-more'); ?></h4>
              <ul class="learn-more__list">
                <li>
                  <a class="learn-more__philosophy" href="#{{wikitolearn philosophy}}">{{ WikiToLearn philosophy }}</a>
                </li>
              </ul>
              <h4 class="learn-more__second-heading"><?php echo wfMessage('wikitolearnskin-footer-hosted-by'); ?></h4>
              <ul class="learn-more__sponsors">
                <li>
                  <a href="#GARR">Garr</a>
                </li>
                <li>
                  <a href="#neodigit">Neo Digit</a>
                </li>
              </ul>
            </li>
            <li class="footer__social">
              <h4><?php echo wfMessage('wikitolearnskin-footer-follow-us'); ?></h4>
              <ul class="social-icons">
                <li>
                  <i class="fa fa-facebook fa-2x" aria-hidden="true"></i>
                </li>
                <li>
                  <i class="fa fa-twitter fa-2x" aria-hidden="true"></i>
                </li>
                <li>
                  <i class="fa fa-linkedin fa-2x" aria-hidden="true"></i>
                </li>
              </ul>
            </li>
          </ul>
          <div class="wikitolearn-divider"></div>
          <div class="last-modified">
            <?php
            foreach ( $this->getFooterLinks() as $category => $links ) { ?>
            <ul class="last-modified__<?php echo $category; ?>">
            <?php
              foreach ( $links as $key ) { ?>
              <li><?php $this->html( $key ) ?></li>

            <?php
              } ?>
            </ul>
            <?php
            } ?>
          </div>
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
        $link = Linker::linkKnown($linkObj, htmlspecialchars( $titleComponent, ["class" => "breadcrumb__item"] ));
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
      $subpages = CourseEditorUtils::getSections($partialLink);
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
        $subpages = CourseEditorUtils::getChapters($partialLink);
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
            ?>
            <a class="dropdown-item" href="<?php echo $subpageLink ?>"><?php echo $subpage ?></a>
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
      if (self::isEditableNamespace()) {
        echo '<div class="article__tools">';
          echo '<div id="tools_container">';
          $editTools = $this->contentNavigation['views'];
          $namespaceAndTalk = $this->contentNavigation['namespaces'];
          foreach ($editTools as $key => $toolAttributes) {
            /*if($key === "view"){
              self::makeTool($toolAttributes['href'], $toolAttributes['text'], $toolAttributes['id'], "tool--green", "fa-book" );
            }else*/ if($key === "ve-edit"){
              self::makeTool($toolAttributes['href'], $toolAttributes['text'], $toolAttributes['id'], "tool--red", "fa-pencil" );
            }
          }
          self::buildAdvancedTools();
          self::buildCollectionTools();
          foreach ($namespaceAndTalk as $value) {
            if ($value['id'] === "ca-talk") {
              self::makeTool($value['href'], $value['text'], $value['id'], "tool--black", "fa-comments-o" );
              break;
            }
          }
          echo '</div>';
        echo '</div>';
      }
    }

    /**
    * Check the namespace in order to confirm or not the
    * generation of certain page tools.
    * @return boolean
    */
    private function isEditableNamespace(){
      $id = $this->namespaceId;
      $user = $this->user;
      if($id === NS_COURSE || $id === NS_USER){
        return true;
      }elseif ($id === NS_MAIN || $id === NS_TEMPLATE || $id === NS_PROJECT) {
        if($this->pageTitle->userCan('delete', $user, 'secure')){
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
      $previous = $previousAndNext['previous'];
      $next = $previousAndNext['next'];
      if ($previous !== NULL || $next != NULL){
        echo '<div class="article__navigation">';
        if ($previous !== NULL) {
          $href = Skin::makeUrl($previous);
          $title = wfMessage('wikitolearnskin-previous-button-title');
          echo "<a href='$href' title='$title' class='navigation__button navigation__button--previous'><i class='fa fa-angle-double-left'></i>&nbsp;$title</a>";

        }
        if ($next !== NULL) {
          $href = Skin::makeUrl($next);
          $title = wfMessage('wikitolearnskin-next-button-title');
          echo "<a href='$href' title='$title' class='navigation__button navigation__button--next'>$title&nbsp;<i class='fa fa-angle-double-right'></i></a>";
        }
        echo '</div>';
      }
    }

    /**
    * Generate the HTML of the collection tools
    */
    private function buildCollectionTools(){
      $collectionTools = $this->data['sidebar']['coll-print_export'];
      if(!is_null($collectionTools)) { ?>
        <div class="multitool horizontal click-to-toggle">
          <span title="<?php echo wfMessage('wikitolearnskin-download-button-title') ?>" class="tool tool--green multitool__trigger">
            <i class="tool__icon fa fa-download"></i>
          </span>
          <ul>
            <li>
              <?php self::makeTool($collectionTools[2]['href'], $collectionTools[2]['text'], $collectionTools[2]['id'], "tool--smaller tool--green", "fa-file-text-o" ); ?>
            </li>
            <li>
              <?php self::makeTool($collectionTools[3]['href'], $collectionTools[3]['text'], $collectionTools[3]['id'], "tool--smaller tool--green", "fa-print" ); ?>
            </li>
            <li>
              <?php self::makeTool($collectionTools[1]['href'], $collectionTools[1]['text'], $collectionTools[1]['id'], "tool--smaller tool--green", "fa-file-pdf-o" ); ?>
            </li>
          </ul>
        </div>
        <?php
      }
    }

    /**
    * Generate the HTML of the advanced tools
    */
    private function buildAdvancedTools(){
      $actionsTools = $this->contentNavigation['actions'];
      $editTools = $this->contentNavigation['views'];
    ?>
      <div class="multitool horizontal click-to-toggle">
        <span title="<?php echo wfMessage('wikitolearnskin-advanced-button-title') ?>" class="tool tool--yellow multitool__trigger">
          <i class="tool__icon fa fa-wrench"></i>
        </span>
        <ul>
        <?php foreach ($actionsTools as $key => $toolAttributes){ ?>
          <li>
            <?php
            switch ($key) {
              case 'watch':
                self::makeTool($toolAttributes['href'], $toolAttributes['text'], $toolAttributes['id'], "tool--smaller tool--yellow", "fa-eye" );
                break;
              case 'unwatch':
                self::makeTool($toolAttributes['href'], $toolAttributes['text'], $toolAttributes['id'], "tool--smaller tool--yellow", "fa-eye-slash" );
                break;
              case 'protect':
                self::makeTool($toolAttributes['href'], $toolAttributes['text'], $toolAttributes['id'], "tool--smaller tool--yellow", "fa-lock" );
                break;
              case 'delete':
                self::makeTool($toolAttributes['href'], $toolAttributes['text'], $toolAttributes['id'], "tool--smaller tool--yellow", "fa-trash" );
                break;
              case 'move':
                self::makeTool($toolAttributes['href'], $toolAttributes['text'], $toolAttributes['id'], "tool--smaller tool--yellow", "fa-reply" );
                break;
            }
            ?>
          </li>
        <?php
      } ?>
          <li>
            <?php
            self::makeTool($editTools['history']['href'], $editTools['history']['text'], $editTools['history']['id'], "tool--smaller tool--yellow", "fa-history");
             ?>
          </li>
          <li>
            <?php
            self::makeTool($editTools['edit']['href'], $editTools['edit']['text'], $editTools['edit']['id'], "tool--smaller tool--yellow", "fa-pencil-square-o");
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
          <i class="tool__icon fa <?php echo $icon ?>"></i>
      </a>
      <?php
    }

    /**
    * Generate the HTML of the languages dropdown
    */
    private function generateLanguageSelectorItems(){
      $supportedLanguages = array('it' => 'it', 'en' => 'gb', 'de' => 'de', 'fr' => 'fr', 'ca' => 'es', 'es' => 'es');
      asort($supportedLanguages);
      foreach($supportedLanguages as $lang => $flag) {
        echo '<a class="dropdown-item" href="' . $lang . '"><span class="flag-icon flag-icon-' . $flag . '"></span>&nbsp;' . $lang . '</a>';
      }
    }
}
