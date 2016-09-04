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
        $out->addModuleStyles( 'font.wikitolearn' );
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
                <div href="/" class="logo col-md-6">
                    <a href="/">
                        <img id="logo-img" src="/skins/WikiToLearnSkin/images/wikitolearn-logo.png">
                        
                        <div id="logo-title">
                            <span class="text-wtl-red">wiki</span><span class="text-wtl-yellow">to</span><span class="text-wtl-green">learn</span>
                        </div>
                    </a>
                </div>
                <nav class="nav-right col-md-6">    
                  <a href="#" class="menu-left hover-red">
                    Cos'è    
                  </a>
                  <a href="#"  class="menu-left hover-yellow">
                    Collabora
                  </a>
                  <a href="#"  class="menu-left hover-green">
                    Libri
                  </a>
                  <span class="separator"></span>
                  <a class="fa fa-search" href=""></a>
                  <span class="separator"></span>
                  <a href="#" class="menu-right">
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
                          <div class="media">
                            <a class="media-left" href="google.com">
                              <i class="fa fa-quote-left "></i>
                            </a>
                            <div class="media-body">
                              <h5 class="media-heading">{{notifications.title}}</h5>
                              <span class="notifications-message"> {{ notifications.message }} </span>
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
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="dropdown-item">
                          <div class="media">
                            <a class="media-left" href="google.com">
                              <i class="fa fa-quote-left "></i>
                            </a>
                            <div class="media-body">
                              <h5 class="media-heading">{{notifications.title}}</h5>
                              <span class="notifications-message"> {{ notifications.message }} </span>
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
                        </div>
                        <div class="dropdown-footer"> {{ View All }} </div>
                    </div>
                  </div> 
                </nav>
            </div>
        </header>
        <main id="page">
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
        </main>

        <script type="text/javascript">
        (function(w, d, s, u) {
              w.RocketChat = function(c) { w.RocketChat._.push(c) }; w.RocketChat._ = []; w.RocketChat.url = u;
                var h = d.getElementsByTagName(s)[0], j = d.createElement(s);
                j.async = true; j.src = 'https://chat.wikitolearn.org/packages/rocketchat_livechat/assets/rocket-livechat.js';
                  h.parentNode.insertBefore(j, h);
        })(window, document, 'script', 'https://chat.wikitolearn.org/livechat');
        </script>
        <?php $this->printTrail(); ?>
        </body>
        </html><?php
    }
}
