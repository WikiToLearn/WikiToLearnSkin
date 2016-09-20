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
      $this->skin = $this->getSkin();
      
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
              $this->execute_header();
              if ($this->getSkin()->getTitle()->isMainPage()) {
                MWDebug::log('Generating Homepage');
                $this->execute_home();
              } else {
                MWDebug::log('Generating Content page');
                $this->execute_content_page();
              }
              $this->execute_footer();

              $this->printTrail(); ?>

          </body>
        </html>
    <?php }

    public function execute_header() { ?>
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
            <a href="#" class="nav__hamburger-menu">
              <i class="fa fa-bars"></i>
            </a>
            <span class="nav__search">
            <form action="<?php $this->text( 'wgScript' ); ?>">
              <input type="hidden" name="title" value="<?php $this->text( 'searchtitle' ) ?>" />
              <input type="search" id="search" placeholder="{{ search }}">
              <button type="submit" class="nav__search-button">
                <i class="fa fa-search"></i>
              </button>
            </form>
            </span>

            <?php 
              $user = $this->skin->getUser();
            ?>
            <?php if($user->isAnon()){ ?>
                <a href="<?php echo $this->skin->makeSpecialUrl('UserLogin'); ?>" class="nav__link nav__link--hover-green">Login</a>
                <a href="<?php echo $this->skin->makeSpecialUrl('CreateAccount'); ?>" class="nav__link nav__link--hover-green">Register</a>
            <?php } else { ?>
            <div class="dropdown dropdown--personal-tools">
              <a class="nav__link nav__link--hover-green" href="#" id="dropdownToolbox" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <?php echo $user->getName() ?>
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
                  <span class="notifications-count"><?php echo wfMessage('notifications') ?>&nbsp;<span id="badge-count" class="badge" style="display:none;"></span></span>
                  <span class="mark-read-notifications"><button type="button" class="btn btn-link" id="mark-all-read-button" style="display:none;"><?php echo wfMessage('echo-mark-all-as-read') ?></button></span>
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

    public function execute_home() { ?>
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

    public function execute_content_page() { ?>
      <main class="page page-content">
        <article class="page__body mw-body">
          <h1 class="page__title" id="firstHeading"> 
            <?php $this->html( 'title' ); ?>
          </h1>

          <?php if ( $this->data['subtitle'] ) { ?>
            <div class="page__contentSub" id="contentSub"> <!-- The CSS class used in Monobook and Vector, if you want to follow a similar design -->
            <?php $this->html( 'subtitle' ); ?>
            </div>
          <?php } ?>
            <?php if ( $this->data['undelete'] ) { ?>
            <div class="page__contentSub2" id="contentSub2"> <!-- The CSS class used in Monobook and Vector, if you want to follow a similar design -->
            <?php $this->html( 'undelete' ); ?>
            </div>
          <?php } ?>
          <div class="page__text" id="bodyContent">
            <?php $this->html( 'bodytext' ); ?>  
          </div>

          <div class="page__categories">  
            <?php $this->html( 'catlinks' ); ?>
          </div>
          <div class="page__dataAfterContent">
            <?php $this->html( 'dataAfterContent' ); ?>
          </div>
        </article>
      </main>
    <?php }

    public function execute_footer() { ?>
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
}
