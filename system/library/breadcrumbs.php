<?php
// author wazzzar

class Breadcrumbs {
    private $path = array();
    private $registry;

    public function __construct($registry) {

        //prd($registry);

        $this->registry = $registry;
        $this->registry->language = $this->registry->get('language');
        $this->registry->url = $this->registry->get('url');

        $this->push('text_home', 'common/home');
    }

    public function push($text, $route) {

        $this->path[] = array(
            'text' => $this->registry->language->get((string)$text),
            'href' => $this->registry->url->link((string)$route)
        );
    }

    public function render() {
        // if in path only home link
        if (count($this->path) == 1)
            return null;

        $html = '<ul class="breadcrumb">';
        foreach ($this->path as $part) {
            $html .= '<li><a href="' . $part['href'] . '">' . $part['text'] . '</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function streem() {

        echo $this->render();
    }

    public function getPath() {
        // if in path only home link
        if (count($this->path) == 1)
            return null;

        return $this->path;
    }

}
/* example
// using new object
$bread_crumbs = new Breadcrumbs( $this );
$bread_crumbs->push( 'text_account', 'account/account' );
$data['breadcrumbs_html'] = $bread_crumbs->render();
// we have breadcrumbs html

// using registry ( new, if breadcrumbs is the part of registry and breadcrumbs has moved into header )
$this->breadcrumbs->push( 'text_account', 'account/account', 'args', secure );

// for compatibility $bread_crumbs->getPath() return array

*/
