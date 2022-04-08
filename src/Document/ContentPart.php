<?php

namespace ACAT\Document;


/**
 *
 */
abstract class ContentPart {

    /**
     * @return string
     */
    abstract function getContent() : string;

}