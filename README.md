#						Scrolling text video effect

##  What is it?
##  -----------
Php script prepare ffmpeg command for subtitles with text scrolling effect.


##  The Latest Version

	version 1.0 2019.04.01


##  Whats new

	version 1.0 2019.04.01
  + Initial version


##  How to install
```bash
sudo apt-get -y install php php-dg
# install last static binaries of ffmpeg
cd 
wget https://johnvansickle.com/ffmpeg/releases/ffmpeg-release-64bit-static.tar.xz
tar xf ffmpeg-release-64bit-static.tar.xz
sudo mkdir /usr/share/ffmpeg
sudo mv ffmpeg-4.1-64bit-static/ /usr/share/ffmpeg
sudo ln -s /usr/share/ffmpeg/ffmpeg-4.1-64bit-static/ffmpeg /usr/bin/ffmpeg
sudo ln -s /usr/share/ffmpeg/ffmpeg-4.1-64bit-static/ffprobe /usr/bin/ffprobe

# install project files
cd 
git clone https://github.com/ikorolev72/subtitles_to_video.git
cd subtitles_to_video
```
### How to install and use new font ( Linux )
You can check installed fonts in your system with command 
```bash
$ fc-list
/usr/share/fonts/truetype/dejavu/DejaVuSerif-Bold.ttf: DejaVu Serif:style=Bold
/usr/share/fonts/truetype/dejavu/DejaVuSansMono.ttf: DejaVu Sans Mono:style=Book
/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf: DejaVu Sans:style=Book
/usr/share/fonts/truetype/OpenSans-Regular.ttf: Open Sans:style=Regular
...
```

Font files that are placed in the hidden .fonts directory of your home folder will automatically be available.
Eg
```bash
cd ~
mkdir .fonts
cd .fonts
wget https://github.com/google/fonts/raw/master/apache/opensans/OpenSans-Regular.ttf
fc-list | grep -i OpenSans
```



## How to run
```
git clone https://github.com/ikorolev72/subtitles_to_video.git
cd subtitles_to_video
```
Edit next parameters in `convert_subtitles.php` file:
```php
$subtitles = "subtitles.srt"; // input subtitles file, can be in both vtt or srt format
$videoInput = "input.mp4"; // input video
$videoOutput = "output.mp4"; // output video
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
```
Run script
```bash
php convert_subtitles.php
```



## How to use FfmpegEffect library 
You can set path to ffmpeg, output audio and video settings for FfmpegEffect library 
```php
<?php
require_once "./FfmpegEffects.php";
# new instance for FfmpegEffects
$effect = new FfmpegEffects();

$effect->setGeneralSettings(
    array(
        'ffmpeg' => '/usr/bin/ffmpeg',
        'ffmpegLogLevel' => 'info',
        'showCommand' => false,
    )
);
echo "General settings:";
echo var_dump($effect->getGeneralSettings());

# set ffmpeg new audio output settings
$effect->setAudioOutputSettings(
    array(
        'codec' => 'aac',
        'bitrate' => '128k',
    )
);

echo "New settings for output audio ffmpeg:";
echo var_dump($effect->getAudioOutputSettings());

$effect->setVideoOutputSettings(
    array(
        'format' => 'mp4',
        'crf' => 20,
        'framerate' => 30,
        'preset' => 'veryfast',
    )
);
```

##  Bugs
##  ------------


##  Licensing
  ---------
	GNU

  Contacts
  --------

     o korolev-ia [at] yandex.ru
     o http://www.unixpin.com
