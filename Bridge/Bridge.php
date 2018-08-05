<?php

namespace DesignPatterns\Structural;

/**
 * Here we have the WebPages hierarchy.
 * WebPage delegates colors and styles to its theme object
 */
abstract class WebPage
{
    protected $theme;

    public function __construct(Theme $theme)
    {
        $this->theme = $theme;
    }

    abstract public function getContent(): string;
}

class HomePage extends WebPage
{
    public function getContent(): string
    {
        return "Home page in " . $this->theme->getColor();
    }
}

class AboutPage extends WebPage
{
    public function getContent(): string
    {
        return "About page in " . $this->theme->getColor();
    }
}


/**
 * Separate themes hierarchy.
 * You can extend themes hierarchy independently from pages classes
 */
interface Theme
{
    public function getColor();
}

/**
 * Themes follow the common interface. It makes them
 * compatible with all types of pages.
 */
class DarkTheme implements Theme
{
    public function getColor()
    {
        return 'Dark colors' . PHP_EOL;
    }
}

class LightTheme implements Theme
{
    public function getColor()
    {
        return 'White colors'. PHP_EOL;
    }
}

# Client code example
$darkTheme = new DarkTheme();
$homePage = new HomePage($darkTheme);
echo $homePage->getContent();       // Output: Home page in Dark colors

$lightTheme = new LightTheme();
$aboutPage = new AboutPage($lightTheme);
echo $aboutPage->getContent();      // Output: About page in White colors