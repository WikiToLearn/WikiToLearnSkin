# WikiToLearnSkin
A custom Mediawiki skin for WikiToLearn

## Folder structure
* `/build/`, compiled and ready for production files

* `/styles/`, for Sass files
    * `skin.scss`, the heart of the skin, keep it clean!
    * `base/`, contains global styles, such as resets, typography, colors, etc. 
    * `layout/`, contains styling for larger layout components; e.g. nav, header, footer, etc. 
    * `components/`, contains each self-contained component 
    * `pages/`, contains page-specific styling, if necessary
    * `utils/`,  contains global mixins, functions, helper selectors, etc.
    * From [here](https://www.sitepoint.com/architecture-sass-project/)
* `/scripts/`, JS scripts
* `/images/`, static images needed by the skin

* `/i18n/`, localization files

## Developing

* 'npm install --dev'
    * This will install 'gulp' and the required submodules
* 'gulp sass' 
    * This will build the style


