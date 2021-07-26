<?php

namespace WPPS_Vendor\BernskioldMedia\WP\PluginBase\FacetWp;

abstract class Facet
{
    public static function make(array $facets) : array
    {
        return \array_merge($facets, static::get_data());
    }
    protected static abstract function get_data() : array;
}
