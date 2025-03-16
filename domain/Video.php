<?php

Class Video {
    //private $access_level;
    private $id;
    private $url;
    private $title;
    private $synopsis;
    private $type;


    function __construct(
        $id, $url, $title, $synopsis, $type){
            $this->id = $id;
            $this->url = $url;
            $this->title = $title;
            $this->synopsis = $synopsis;
            $this->type = $type;

        }
    function get_id() {
        return $this->id;
    }
    function get_url(){
        return $this->url;
    }
    function get_title(){
        return $this->title;
    }
    function get_synopsis(){
        return $this->synopsis;
    }
    function get_type(){
        return $this->type;
    }

}
