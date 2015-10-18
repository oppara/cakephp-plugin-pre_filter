# PreFilter

[![Build Status](https://travis-ci.org/oppara/cakephp-plugin-pre_filter.svg?branch=master)](https://travis-ci.org/oppara/cakephp-plugin-pre_filter)

trim request data

## Requirements

* CakePHP >= 2.6


## Installation

    {
        "require": {
            "oppara/pre_filter": "*"
        }
    }


### Enable plugin

`CakePlugin::load('PreFilter');`


### Add components

    class AppController extends Controller
    {
        public $components = array(
            'PreFilter.Trim',
        );
