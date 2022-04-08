<?php

namespace ACAT\Document;

/**
 *
 */
abstract class Document {

    /**
     * @return array
     */
    abstract function getContentParts() : array;

}