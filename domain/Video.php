<?php

class Video {
    //private $access_level;
    private $id;
    private $url;
    private $title;
    private $synopsis;
    private $type;


    public function __construct($id, $url, $title, $synopsis, $type) {
        $this->id = $id;
        $this->url = $url;
        $this->title = $title;
        $this->synopsis = $synopsis;
        $this->type = $type;
    }

    public function get_id() {
        return $this->id;
    }
    public function get_url(){
        return $this->url;
    }
    public function get_title(){
        return $this->title;
    }
    public function get_synopsis(){
        return $this->synopsis;
    }
    public function get_type(){
        return $this->type;
    }

}
?>