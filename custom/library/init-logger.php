<?php

const LOGGER = true;

if(defined('LOGGER') && LOGGER) {
    add_action('admin_footer', 'logger_init');
    add_action('wp_footer', 'logger_init');
}
function logger_init()
{
//    if (!is_user_logged_in() || wp_get_current_user()->user_login != LOGGER) {
//        return false;
//    }

    add_action('logger', function () {
        echo "<div class='logger-box'>";
        echo "<div class='logger'>";
    }, 1);

    add_action('logger', function () {
        echo "</div>";
        echo "<div class='logger-nav'>";
        echo "<div class='logger-nav__left'>&lArr;</div><div class='logger-nav__right'>&rArr;</div>";
        echo "<div class='logger-nav__resize'>&#10066;<!--&#10006;--></div>";
        echo "<div class='logger-nav__up'>&uArr;</div><div class='logger-nav__down'>&dArr;</div>";
        echo "</div>";
        echo "</div>";
        echo "<style>";
        echo <<<CSS
.logger-box {
    position: fixed;
    z-index: 999999;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    box-sizing: border-box;
    pointer-events: none;
    display: flex;
    justify-content: flex-end;
    align-items: flex-end;
}
.logger {
    /*font-size: 10px;*/
    /*background: #307;*/
    /*color: white;*/
    /*padding: 5px;*/
    margin: 0;
    display: inline-block;
    max-height: 50%;
    max-width: 50%;
    overflow: auto;
    box-sizing: border-box;
    /*border: 2px solid darkgray;*/
    /*border-radius: 5px;*/
    pointer-events: auto;
    backdrop-filter: blur(5px);
}
.logger__message {
    width: fit-content;
    font-size: 10px;
    background: #307;
    color: white;
    padding: 5px;
    margin: 10px;
    border: 1px solid darkgray;
    border-radius: 5px;
    /*pointer-events: auto;*/
    line-height: 1;
}
.logger-title {
    font-weight: bold;
    font-size: 1.5em;
}
.logger-spoiler {
    max-height: 50px;
    overflow-x: auto;
    overflow-y: hidden;
}
.logger-spoiler:hover {
    max-height: 100%;
}
.logger-nav {
    position: absolute;
    bottom: 10px;
    right: 10px;
    pointer-events: auto;
}
.logger-nav > div {
    width: 24px;
    height: 24px;
    font-size: 24px;
    border: 1px solid;
    border-radius: 5px;
    margin: 5px;
    line-height: .9;
    text-align: center;
    opacity: .2;
    cursor: pointer;
    transition: .3s;
    background-color: #307;
    color: white;
}
.logger-nav > div:hover {
    opacity: 1;
}
CSS;
        echo "</style>";
        echo "<script>";
        echo <<<'JS'
(function () {
    let $box = document.querySelector('.logger-box'); 
    let $logger = document.querySelector('.logger'); 
    let $nav = document.querySelector('.logger-nav'); 
    let settingView = {
        justifyContent: 'flex-end',
        alignItems: 'flex-end',
        maxHeight: '50%',
        maxWidth: '50%',
        visible: 'show'
    };
    
    let settingViewStorage = localStorage.getItem('settingView');
    
    if (settingViewStorage) {
        settingView = JSON.parse(settingViewStorage);
        viewUpdate(settingView);
    }
        
    $nav.addEventListener('click', function (e) {
        
        if (e.target.classList.contains('logger-nav__resize')) {
            // settingView.justifyContent = 'flex-start';
            settingView.visible = settingView.visible === 'show' ? 'hide' : 'show';
            
            if (settingView.visible === 'show') {
                settingView.maxHeight = '100%';
                settingView.maxWidth = '100%';
            } else {
                settingView.maxHeight = '0';
                settingView.maxWidth = '0';
            }
            viewUpdate(settingView);
        }
        
        if (e.target.classList.contains('logger-nav__left')) {
            settingView.justifyContent = 'flex-start';
            settingView.maxHeight = '100%';
            settingView.maxWidth = '50%';
            viewUpdate(settingView);
        }
        
        if (e.target.classList.contains('logger-nav__right')) {
            settingView.justifyContent = 'flex-end';
            settingView.maxHeight = '100%';
            settingView.maxWidth = '50%';
            viewUpdate(settingView);
        }
        
        if (e.target.classList.contains('logger-nav__up')) {
            settingView.alignItems = 'flex-start';
            settingView.maxHeight = '50%';
            settingView.maxWidth = '100%';
            viewUpdate(settingView);
        }
        
        if (e.target.classList.contains('logger-nav__down')) {
            settingView.alignItems = 'flex-end';
            settingView.maxHeight = '50%';
            settingView.maxWidth = '100%';
            viewUpdate(settingView);
        }
    });
    
    function viewUpdate(setting) {
        $box.style.justifyContent = setting.justifyContent;
        $box.style.alignItems = setting.alignItems;
        $logger.style.maxHeight = setting.maxHeight;
        $logger.style.maxWidth = setting.maxWidth;
        localStorage.setItem('settingView', JSON.stringify(setting));
    }
})();
JS;
        echo "</script>";
    }, 99999999);

    do_action('logger');
}

function slog($str, $key = 'default')
{
    if (is_object($str) || is_array($str)) {
        $str_new = '<pre>';
        ob_start();
        var_dump($str);
        $str_new .= ob_get_clean();
        $str_new .= '</pre>';
        $str = $str_new;
    }

    if($key == 'return') {
        return '<div class="logger__message">' . $str . '</div>';
    }

    add_action('logger', function () use ($str, $key) {
        echo '<div class="logger__message">';
        if ($key == 'default') {
            echo $str;
//            echo "<br>------<br>";
        } elseif ($key == 'spoiler') {
            echo "<div class='logger-spoiler'>";
            echo $str;
            echo "</div>";
            echo "<hr>";
        } elseif ($key == 'title') {
            echo "<div class='logger-title'>";
            echo $str;
            echo "</div>";
        } else {
            echo $str;
            echo "<br>======<br>";
        }
        echo '</div>';
    });
}
