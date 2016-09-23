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
        $out->addMeta( 'viewport', 'width=device-width, initial-scale=1, shrink-to-fit=no' );
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
            <a href="#" class="nav__link nav__link--hover-red">
              Cos'è
            </a>
            <a href="#"  class="nav__link nav__link--hover-yellow">
              Collabora
            </a>
            <a href="#"  class="nav__link nav__link--hover-green">
              Libri
            </a>
            <span class="nav__search">
            <form action="<?php $this->text( 'wgScript' ); ?>" autocomplete="off">
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
              <a href="<?php echo $this->skin->makeSpecialUrl('UserLogin'); ?>" class="nav__link nav__link--hover-green">
                <?php $this->msg( 'login' ) ?>
              </a>
              <a href="<?php echo $this->skin->makeSpecialUrl('CreateAccount'); ?>" class="nav__link nav__link--hover-green">
                <?php $this->msg( 'createaccount' ) ?>
              </a>
            <?php } else { ?>
              <div class="dropdown dropdown--personal-tools">
                <a class="nav__link nav__link--hamburger nav__link--hover-green" href="#" id="dropdownToolbox" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                      $personalToolsCount++;
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
          </nav>
        </div>
      </header>
    <?php }

    public function executeHome() { ?>
      <main class="page page-home">
        <section class="title">
          <h1> Learn with the best. Create books. Share <em>knowledge</em>. </h1>
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
            <div class="clearfix"></div>
          </ul>
        </section>
        <section class="join-us">
          <div class="join-us__content">
            <a href="#" class="join-us__link">Join Us</a>
            <div class="join-us__this-week">
                This week on WikiToLearn: 32 new edits, 6 new pages and 2 new users.
            </div>
          </div>
        </section>
        <section class="media">
          <div class="media__wrapper">
            <iframe class="media__video" src="//www.youtube.com/embed/NpEaa2P7qZI?rel=0" allowfullscreen></iframe>
          </div>
          <div class="media__description">
            <h3 class="media__title">collaborative textbooks</h3>
            <p class="media__text">
              WikiToLearn vuole creare libri di testo liberi, collaborativi e facilmente accessibili.
              La nostra filosofia è riassunta nel motto “Il sapere si accresce solo se condiviso”. Nella nostra piattaforma l'insegnamento e l'apprendimento convergono nella stesura e nel perfezionamento cooperativo di note, appunti e libri di testo, organizzabili e ri-assemblabili secondo le esigenze specifiche degli utenti.
            </p>
            <button class="media__button">
              Learn More
            </button>
          </div>
        </section>
        <section class="testimonials">
          <div class="testimonials__content">
            <div class="testimonial">
              <a class="testimonial__link" href="#">
                <img class="testimonial__image" src="<?php echo $this->getSkin()->getSkinStylePath( 'images/testimonials.png'); ?>" alt="Generic placeholder image">
              </a>
              <div class="testimonial__body">
                <blockquote class="testimonial__quote">
                  "WikiToLearn allows me to have great and always up-to-date material for my classes"
                </blockquote>
                <footer class="testimonial__footer">
                  <cite> M. Bona</cite>
                </footer>
              </div>
            </div>
            <div class="testimonial">
              <a class="testimonial__link" href="#">
                <img class="testimonial__image" src="<?php echo $this->getSkin()->getSkinStylePath( 'images/testimonials.png'); ?>" alt="Generic placeholder image">
              </a>
              <div class="testimonial__body">
                <blockquote class="testimonial__quote">
                  "WikiToLearn allows me to have great and always up-to-date material for my classes"
                </blockquote>
                <footer class="testimonial__footer">
                  <cite> M. Bona</cite>
                </footer>
              </div>
            </div>
            <div class="testimonial">
              <a class="testimonial__link" href="#">
                <img class="testimonial__image" src="<?php echo $this->getSkin()->getSkinStylePath( 'images/testimonials.png'); ?>" alt="Generic placeholder image">
              </a>
              <div class="testimonial__body">
                <blockquote class="testimonial__quote">
                  "WikiToLearn allows me to have great and always up-to-date material for my classes"
                </blockquote>
                <footer class="testimonial__footer">
                  <cite> M. Bona</cite>
                </footer>
              </div>
            </div>
            <a href="#" class="testimonials__read-more">Read more stories about WikiToLearn</a>
          </div>
        </section>
        <section class="contributors">
          <div class="contributors__content">
            <h3 class="contributors__title">Contributions from</h3>
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
      $namespaceAndTalk = $this->contentNavigation['namespaces'];
      //title-related basics
      $fullTitle = $this->pageTitle->getText();
      $titleComponents = explode("/", $fullTitle);
      if(count($titleComponents)>0) {
        $pageTitle = $titleComponents[count($titleComponents)-1];
      } else {
        //this should never happen but who knows ¯\_(ツ)_/¯
        $pageTitle = $fullTitle;
      }
      ?>
      <main class="page page--article">
        <div class="article__wrapper">
          <div class="article__main">
            <nav class="article__nav">
              <?php
                foreach ($namespaceAndTalk as $value) { ?>
                <div class="nav__item">
                  <a href="<?php echo $value['href'] ?>" class="<?php echo $value['class'] ?>"><?php echo $value['text'] ?></a>
                </div>
                <?php
                }
               ?>
            </nav>
            <article class="article__sheet mw-body">
              <?php $this->executeBreadcrumb($titleComponents) ?>
              <h1 class="article__title">
                <?php echo $pageTitle; ?>
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
              </div>
            </article>
          </div>
          <?php if (self::isEditableNamespace()) { ?>
            <div class="article__tools">
              <div id="tools_container">   
                <?php $this->executePageTools($fullTitle) ?>
              </div>
            </div>
          <?php } ?>
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
              <h4>{{ Contacts }}</h4>
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
              <h4 class="learn-more__first-heading">{{Learn More}}</h4>
              <ul class="learn-more__list">
                <li>
                  <a class="learn-more__philosophy" href="#{{wikitolearn philosophy}}">{{ WikiToLearn philosophy }}</a>
                </li>
              </ul>
              <h4 class="learn-more__second-heading">{{Hosted by}}</h4>
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
              <h4>{{Connect}}</h4>
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

    /**
    * Since this is a very personalized skin we can assume that
    * all the subpages exist and avoid a few of the checks in Neverland
    * @param string[] $titleComponents the subtokens composed the title
    */
    public function executeBreadcrumb($titleComponents) { ?>
      <div class="article__breadcrumb">
        <?php
          array_pop($titleComponents);  //remove current page
          $partialLink = $this->pageTitle->getNsText() . ":";
          for ($i=0; $i<count($titleComponents); $i++) {
            $titleComponent = $titleComponents[$i];
            $partialLink .= $titleComponent;
            $linkObj = Title::newFromText($partialLink);
            $link = Linker::linkKnown($linkObj, htmlspecialchars( $titleComponent ));
            echo $link;
            if($i !== (count($titleComponents)-1)) { //we don't add the slash on last link
              echo "<span class='breadcrumb__divider'>/</span>";
            }
            $partialLink .= "/";
          }
        ?>
      </div>
    <?php }

    /**
    * Generate a WikiToLearn's version of tools related to page.
    * These tools are composed by classic 'views' tools (view, edit, history...)
    * some 'collection' tools (Download as PDF, Download plain text..),
    * and advaced tools.
    */
    public function executePageTools() {
      $editTools = $this->contentNavigation['views'];

      foreach ($editTools as $key => $toolAttributes) {
        if($key === "view"){
          self::makeTool($toolAttributes['href'], $toolAttributes['text'], "tool--green", "fa-book" );
        }else if($key === "ve-edit"){
          self::makeTool($toolAttributes['href'], $toolAttributes['text'], "tool--yellow", "fa-pencil" );
        }
      }
      self::buildCollectionTools();
      self::buildAdvancedTools();
      echo "<i class='tool--divider'></i>";
      self::buildPreviousAndNext();
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
    private function buildPreviousAndNext(){
      $previousAndNext = CourseEditorUtils::getPreviousAndNext($this->pageTitle);
      $previous = $previousAndNext['previous'];
      $next = $previousAndNext['next'];
      if ($previous !== NULL) {
        $href = Skin::makeUrl($previous);
        $title = wfMessage('wikitolearnskin-previous-button-title');
        self::makeTool($href, $title, "tool--blue", "fa-angle-double-left");
      }
      if ($next !== NULL) {
        $href = Skin::makeUrl($next);
        $title = wfMessage('wikitolearnskin-next-button-title');
        self::makeTool($href, $title, "tool--blue", "fa-angle-double-right");
      }
    }

    /**
    * Generate the HTML of the collection tools
    */
    private function buildCollectionTools(){
      $collectionTools = $this->data['sidebar']['coll-print_export'];
      if(!is_null($collectionTools)) { ?>
        <div class="multitool horizontal click-to-toggle">
          <span title="<?php echo wfMessage('wikitolearnskin-download-button-title') ?>" class="tool tool--red multitool__trigger">
            <i class="tool__icon fa fa-download"></i>
          </span>
          <ul>
            <li>
              <?php self::makeTool($collectionTools[2]['href'], $collectionTools[2]['text'], "tool--smaller tool--red", "fa-file-text-o" ); ?>
            </li>
            <li>
              <?php self::makeTool($collectionTools[3]['href'], $collectionTools[3]['text'], "tool--smaller tool--red", "fa-print" ); ?>
            </li>
            <li>
              <?php self::makeTool($collectionTools[1]['href'], $collectionTools[1]['text'], "tool--smaller tool--red", "fa-file-pdf-o" ); ?>
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
        <span title="<?php echo wfMessage('wikitolearnskin-advanced-button-title') ?>" class="tool tool--black multitool__trigger">
          <i class="tool__icon fa fa-wrench"></i>
        </span>
        <ul>
          <li>
            <?php
            self::makeTool($editTools['edit']['href'], $editTools['edit']['text'], "tool--smaller tool--black", "fa-pencil-square-o");
            ?>
          </li>
          <li>
            <?php
            self::makeTool($editTools['history']['href'], $editTools['history']['text'], "tool--smaller tool--black", "fa-history");
             ?>
          </li>
      <?php foreach ($actionsTools as $key => $toolAttributes){ ?>
          <li>
            <?php
            switch ($key) {
              case 'watch':
                self::makeTool($toolAttributes['href'], $toolAttributes['text'], "tool--smaller tool--black", "fa-eye" );
                break;
              case 'unwatch':
                self::makeTool($toolAttributes['href'], $toolAttributes['text'], "tool--smaller tool--black", "fa-eye-slash" );
                break;
              case 'protect':
                self::makeTool($toolAttributes['href'], $toolAttributes['text'], "tool--smaller tool--black", "fa-lock" );
                break;
              case 'delete':
                self::makeTool($toolAttributes['href'], $toolAttributes['text'], "tool--smaller tool--black", "fa-trash" );
                break;
              case 'move':
                self::makeTool($toolAttributes['href'], $toolAttributes['text'], "tool--smaller tool--black", "fa-reply" );
                break;
            }
            ?>
          </li>
        <?php
      } ?>
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
    private function makeTool($href, $title, $classes, $icon) {
      ?>
      <a title="<?php echo $title ?>" class="tool <?php echo $classes?>" href="<?php echo $href ?>">
          <i class="tool__icon fa <?php echo $icon ?>"></i>
      </a>
      <?php
    }
}
