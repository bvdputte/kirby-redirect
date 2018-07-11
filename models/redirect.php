<?php

class RedirectPage extends Page {

  public function url($lang = false) {
    if ($this->isExternal()) {
      return $this->external()->value();
    }

    if($lang) {
      $args = func_get_args();
      $lang = array_shift($args);

      // for multi language sites every url needs
      // to be treated specially to make sure each uid is translated properly
      // and language codes are prepended if needed
      if(is_null($lang)) {
        // get the current language
        $lang = $this->site->language->code;
      }

      // Kirby is trying to remove the home folder name from the url
      if($this->isHomePage()) {
        $url = $this->site->url($lang);

        // append a query param if the new language is on another domain
        if($this->site->language->host() !== $this->site->language($lang)->host()) {
          $url = url::build(['query' => ['language' => 'switch']], $url);
        }

        return $url;
      } else if($this->parent->isHomePage()) {
        return $this->site->url($lang) . '/' . $this->parent->slug($lang) . '/' . $this->slug($lang);
      } else {
        return $this->parent->url($lang) . '/' . $this->slug($lang);
      }

    } else {
      return parent::url();
    }
  }

  public function isExternal() {
    if ($this->redirect()->isEmpty() || (!page($this->redirect()->value()))) {
      return $this->external()->isNotEmpty();
    }

    return false;
  }

}
