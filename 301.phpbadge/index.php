<?php


use PHP\Badge\Badge;
use PHP\Badge\Font\Font;
use PHP\Badge\Font\GdDimensionCalculator;
use PHP\Badge\Part;
use PHP\Badge\Renderer\SvgRenderer;

require_once __DIR__ . '/vendor/autoload.php';

$badge = new Badge();
$badge->setBorderRadius(3);
$badge->addPart(new Part('build', '#555', '#fff', new Font(11, 'verdana', 'fonts/verdana.ttf')));
$badge->addPart(new Part('passing', '#4c1', '#fff', new Font(11, 'verdana', 'fonts/verdana.ttf')));

$renderer = new SvgRenderer(new GdDimensionCalculator());

echo $renderer->render($badge);