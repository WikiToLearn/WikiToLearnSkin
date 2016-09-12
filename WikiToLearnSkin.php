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
        $out->addStyle("https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700");
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
        <header>
            <div id="header-wrapper" >
                <div href="/" class="logo">
                  <a href="/">
                    <img id="logo-img" src="/skins/WikiToLearnSkin/images/wikitolearn-logo.png">

                    <div id="logo-title">
                      <span class="text-wtl-red">wiki</span><span class="text-wtl-yellow">to</span><span class="text-wtl-green">learn</span>
                    </div>
                  </a>
                </div>
                <nav class="nav-right">
                  <a href="#" class="menu hover-red">
                    Cos'è
                  </a>
                  <a href="#"  class="menu hover-yellow">
                    Collabora
                  </a>
                  <a href="#"  class="menu hover-green">
                    Libri
                  </a>
                  <span class="menu search-box">
                  <form action="<?php $this->text( 'wgScript' ); ?>">
                    <input type="hidden" name="title" value="<?php $this->text( 'searchtitle' ) ?>" />
                    <input type="search" id="search" placeholder="{{ search }}">
                    <button type="submit" class="search-button">
                      <i class="fa fa-search"></i>
                    </button>
                  </form>
                  </span>
                  <a href="#" class="menu hover-dark-green">
                      crisbal
                  </a>
                  <div class="dropdown">
                    <a id="notifications" class="menu-right dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fa fa-bell"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="notifications">
                      <div class="dropdown-header">
                        <span class="notifications-count">{{ Notifications (1) }}</span>
                        <span class="mark-read-notifications">{{ Mark all as read }}</span>
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
                      </div>
                      <div class="dropdown-divider"></div>

                      <div class="dropdown-footer"> {{ View All }} </div>
                    </div>
                  </div>
                </nav>
            </div>
        </header>
        <main id="page">
        <?php if ($this->getSkin()->getTitle()->isMainPage()) { ?>
          <section class="container-departments">
              <div class="departments">
                <div class="wrapper-department">
                  <div class="department">
                    <img class="department-image" src="http://i.imgur.com/XtYKLW2.png" alt="">
                    <span class="department-name">Fisica</span>
                  </div>
                </div>
                <div class="wrapper-department">
                  <div class="department">
                    <img class="department-image" src="http://i.imgur.com/PEPTSKE.png" alt="">
                    <span class="department-name">Bioscienze</span>
                  </div>
                </div>
                <div class="wrapper-department">
                  <div class="department">
                    <img class="department-image" src="http://i.imgur.com/XtYKLW2.png" alt="">
                    <span class="department-name">Informatica</span>
                  </div>
                </div>
                <div class="wrapper-department">
                  <div class="department">
                    <img class="department-image" src="http://i.imgur.com/PEPTSKE.png" alt="">
                    <span class="department-name">Matematica</span>
                  </div>
                </div>
                <div class="wrapper-department">
                  <div class="department">
                    <img class="department-image" src="http://i.imgur.com/XtYKLW2.png" alt="">
                    <span class="department-name">Fisica</span>
                  </div>
                </div>
                <div class="wrapper-department">
                  <div class="department">
                    <img class="department-image" src="http://i.imgur.com/PEPTSKE.png" alt="">
                    <span class="department-name">Bioscienze</span>
                  </div>
                </div>
                <div class="wrapper-department">
                  <div class="department">
                    <img class="department-image" src="http://i.imgur.com/XtYKLW2.png" alt="">
                    <span class="department-name">Informatica</span>
                  </div>
                </div>
                <div class="wrapper-department">
                  <div class="department">
                    <img class="department-image" src="http://i.imgur.com/PEPTSKE.png" alt="">
                    <span class="department-name">Matematica</span>
                  </div>
                </div>
              </div>
          </section>
          <section id="container-join-us">
            <a href="#" id="link-join-us">Join Us</a>
          </section>
          <hr class="wikitolearn-divider-red">
          <section class="videos">
            <div class="video">
              <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/yVclxeOLBd0"></iframe>
            </div>
            <div class="video">
              <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/yVclxeOLBd0" allowfullscreen></iframe>
            </div>
            <div class="video">
              <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/yVclxeOLBd0" allowfullscreen></iframe>
            </div>
          </section>
          <hr class="wikitolearn-divider-yellow">
          <section class="testimonials">
            <div class="testimonial">
              <a class="media-left" href="#">
                <img class="media-object" src="<?php echo $this->getSkin()->getSkinStylePath( 'images/testimonials.png'); ?>" alt="Generic placeholder image">
              </a>
              <div class="media-body">
                <blockquote>
                  "WikiToLearn allows me to have great and always up-to-date material for my classes"
                </blockquote>
                <footer>
                  <cite> M. Bona</cite>
                </footer>
              </div>
            </div>
            <div class="testimonial">
              <a class="media-left" href="#">
                <img class="media-object" src="<?php echo $this->getSkin()->getSkinStylePath( 'images/testimonials.png'); ?>" alt="Generic placeholder image">
              </a>
              <div class="media-body">
                <blockquote>
                  "WikiToLearn allows me to have great and always up-to-date material for my classes"
                </blockquote>
                <footer>
                  <cite> M. Bona</cite>
                </footer>
              </div>
            </div>
            <div class="testimonial">
              <a class="media-left" href="#">
                <img class="media-object" src="<?php echo $this->getSkin()->getSkinStylePath( 'images/testimonials.png'); ?>" alt="Generic placeholder image">
              </a>
              <div class="media-body">
                <blockquote class="blockquote">
                  "WikiToLearn allows me to have great and always up-to-date material for my classes"
                </blockquote>
                <footer>
                  <cite> M. Bona</cite>
                </footer>
              </div>
            </div>
            <a href="#" class="btn read-more btn-outline-success">Read more stories about WikiToLearn</a>
          </section>
          <hr class="wikitolearn-divider-green">
          <section class="sponsors">
            <div class="image">
              <img src="<?php echo $this->getSkin()->getSkinStylePath( 'images/sponsor1.png'); ?>">
            </div>
            <div class="image">
              <img src="<?php echo $this->getSkin()->getSkinStylePath( 'images/sponsor2.png'); ?>">
            </div>
            <div class="image">
              <img src="<?php echo $this->getSkin()->getSkinStylePath( 'images/sponsor3.png'); ?>">
            </div>
            <div class="image">
              <img src="<?php echo $this->getSkin()->getSkinStylePath( 'images/sponsor4.png'); ?>">
            </div>
            <div class="clearfix"></div>
            <div class="image">
              <img src="<?php echo $this->getSkin()->getSkinStylePath( 'images/sponsor1.png'); ?>">
            </div>
            <div class="image">
              <img src="<?php echo $this->getSkin()->getSkinStylePath( 'images/sponsor2.png'); ?>">
            </div>
            <div class="image">
              <img src="<?php echo $this->getSkin()->getSkinStylePath( 'images/sponsor3.png'); ?>">
            </div>
            <div class="image">
              <img src="<?php echo $this->getSkin()->getSkinStylePath( 'images/sponsor4.png'); ?>">
            </div>
          </section>
        <?php } else { ?>
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

                <?php $this->html( 'catlinks' ); ?>

                <?php $this->html( 'dataAfterContent' ); ?>
            </section>
        <?php } ?>
        </main>
        <footer class="footer">
          <div class="content">
            <div class="logo">
              <img src="/skins/WikiToLearnSkin/images/wikitolearn-logo.png">
            </div>
            <div class="contacts">
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
            </div>
            <div class="learn-more">
              <h4 class="first-heading">{{Learn More}}</h4>
              <ul class="learn-more-list">
                <li>
                  <a class="wikitolearn-philosophy" href="#{{wikitolearn philosophy}}">{{ WikiToLearn philosophy }}</a>
                </li>
              </ul>
              <h4 class="second-heading">{{Hosted by}}</h4>
              <ul class="sponsors">
                <li>
                  <a href="#GARR">Garr</a>
                </li>
                <li>
                  <a href="#neodigit">Neo Digit</a>
                </li>
              </ul>
            </div>
            <div class="social">
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
            </div>
          </div>
          <div class="wikitolearn-divider"></div>
          <div class="last-modified">
            <?php
            foreach ( $this->getFooterLinks() as $category => $links ) { ?>
            <ul class="<?php echo $category; ?>">
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

        <?php $this->printTrail(); ?>
        </body>
        </html><?php
    }
}
