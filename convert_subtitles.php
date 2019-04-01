<?php
# This script demo for parsing srt and vtt subtitles and convert to ASS format
# Add new subtitles to video
# korolev-ia@yandex.ru
#

require_once "./FfmpegEffects.php";

// DEFINE NEXT VARIABLES
//$subtitles = "subtitles.vtt";
$subtitles = "subtitles.srt";
$videoInput = "input.mp4";
$videoOutput = "output.mp4";
$width = 1080;
$height = 1080;
$x = 170; // subtitles top left
$y = 100; // subtitles top left
$showLines = 3; // how many line will be shown in the scrolling window
$fontFile = "/usr/share/fonts/truetype/OpenSans-Regular.ttf";
$fontSize = 40;
$fontHtmlColor = "FFFFFF";
$outLine = 0;
$textBoxWidth = 785;
//$temporaryAssFile = "$tempDir/" . time() . rand(10000, 99999) . ".ass";
$temporaryAssFile = time() . ".ass";
//////////////////////////

// CALCULATED VALUES
$textBoxHeight = $fontSize * $showLines; // usual it will be equivalent of lines * fontSize, for example lines=3, fontSize=35, textBoxHeight=3*35=105

// new instance for FfmpegEffects
$effect = new FfmpegEffects();

// set path to ffmpeg
$effect->setGeneralSettings(
  array(
      'ffmpeg' => '/usr/bin/ffmpeg',
      'ffprobe' => '/usr/bin/ffprobe',
      'ffmpegLogLevel' => 'info',
      'showCommand' => false,
  )
);



$fontColor = $effect->getKlmColor($fontHtmlColor); // convert HTML color to KLM
$assFontProperties = $effect->getFontProperties($fontFile); // get font properties
$fontName = $assFontProperties['family']; // font name

// transcode srt or vtt subtitles to ASS format
$cmd = $effect->transcodeSubtitlesToAss($subtitles, $temporaryAssFile);
if (!$cmd) {
    echo $effect->getLastError();
    @unlink($temporaryAssFile);
    exit(1);
}
if (!$effect->doExec($cmd)) {
    $effect->writeToLog("Someting wrong: $cmd");
    exit(1);
}

$dialogues = $effect->parseAssFile($temporaryAssFile);

$newDialogues = [];
foreach ($dialogues as $line) {
    $startTime = $line[1];
    $endTime = $line[2];
    $text = $line[3];
    $text = $effect->reWrapText($text, $textBoxWidth, $fontSize, $fontFile);
    $newDialogues[] = $effect->prepareSubtitlesDialog(
        $x,
        $y,
        $textBoxWidth,
        $textBoxHeight,
        $text,
        $startTime,
        $endTime,
        $showLines,
        "Default"
    );
}
$content = $effect->prepareSubtitlesHeader(
    $width,
    $height,
    $fontName,
    $fontSize,
    $fontColor,
    $assFontProperties['bold'],
    $assFontProperties['italic'],
    $outLine
);
$content .= join('', $newDialogues);

// save new content for ASS file
if (!@file_put_contents($temporaryAssFile, $content)) {
    $effect->writeToLog("Cannot save temporary subtitles file '$temporaryAssFile'");
    exit(1);
}

$cmd = $effect->addSubtitlesToVideo($videoInput, $temporaryAssFile, $videoOutput);
if (!$cmd) {
    echo $effect->getLastError();
    @unlink($temporaryAssFile);
    exit(1);
}
if (!$effect->doExec($cmd)) {
    $effect->writeToLog("Someting wrong: $cmd");
    exit(1);
}

exit(0);
