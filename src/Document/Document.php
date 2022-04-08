<?php

namespace ACAT\Document;

/**
 *
 */
abstract class Document {

    /**
     * @return void
     */
    abstract function save() : void;

    /**
     * @return string
     */
    abstract function getContent() : string;

}