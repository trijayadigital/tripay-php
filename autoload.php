<?php

array_map(function ($class) {
    return require_once $class;
}, array_merge(
    glob(__DIR__.'/src/Constants/*.php'),
    glob(__DIR__.'/src/Exceptions/*.php'),
    glob(__DIR__.'/src/Drivers/*.php'),
    glob(__DIR__.'/src/*.php')
));
