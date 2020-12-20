<?php

use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;

require "./vendor/autoload.php";

$phpWord = new PhpWord();
$section = $phpWord->addSection();

// Begin code
$section = $phpWord->addSection();
$section->addText('Local image without any styles:');
$section->addImage('resources/mars.jpg');

printSeparator($section);
$section->addText('Local image with styles:');
$section->addImage('resources/left.png', array('width' => 210, 'height' => 210, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

// Remote image
printSeparator($section);
$source = 'http://php.net/images/logos/php-med-trans-light.gif';
$section->addText("Remote image from: {$source}");
$section->addImage($source);

// Image from string
printSeparator($section);
$source = 'resources/mars.jpg';
$fileContent = file_get_contents($source);
$section->addText('Image from string');
$section->addImage($fileContent);

//Wrapping style
printSeparator($section);
$text = str_repeat('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ', 2);
$wrappingStyles = array('inline', 'behind', 'infront', 'square', 'tight');
foreach ($wrappingStyles as $wrappingStyle) {
    $section->addText("Wrapping style {$wrappingStyle}");
    $section->addImage(
        'resources/left.png',
        array(
            'positioning'        => 'relative',
            'marginTop'          => -1,
            'marginLeft'         => 1,
            'width'              => 80,
            'height'             => 80,
            'wrappingStyle'      => $wrappingStyle,
            'wrapDistanceRight'  => Converter::cmToPoint(1),
            'wrapDistanceBottom' => Converter::cmToPoint(1),
        )
    );
    $section->addText($text);
    printSeparator($section);
}

//Absolute positioning
$section->addText('Absolute positioning: see top right corner of page');
$section->addImage(
    'resources/left.png',
    array(
        'width'            => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(3),
        'height'           => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(3),
        'positioning'      => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
        'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
        'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
        'posVerticalRel'   => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
        'marginLeft'       => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(15.5),
        'marginTop'        => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.55),
    )
);

//Relative positioning
printSeparator($section);
$section->addText('Relative positioning: Horizontal position center relative to column,');
$section->addText('Vertical position top relative to line');
$section->addImage(
    'resources/mars.jpg',
    array(
        'width'            => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(3),
        'height'           => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(3),
        'positioning'      => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
        'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
        'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_COLUMN,
        'posVertical'      => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_TOP,
        'posVerticalRel'   => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_LINE,
    )
);

function printSeparator(Section $section)
{
    $section->addTextBreak();
    $lineStyle = array('weight' => 0.2, 'width' => 150, 'height' => 0, 'align' => 'center');
    $section->addLine($lineStyle);
    $section->addTextBreak(2);
}

$filename = time() . uniqid() . ".docx";
$phpWord->save($filename);