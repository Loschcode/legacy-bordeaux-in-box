<?php

/**
 * Format a line for the layout email
 * @param string $content Content to "line up"
 * @return string The line formated 
 */
Html::macro('emailLine', function($content) {
  return '<p style="Margin-top: 0;Margin-bottom: 0;text-align: left;">' . $content . '</p>';

});

/**
 * Format a button action for the layout email
 * @param string $text The text of the link
 * @param string $link The url of the link
 * @return string Button html formated
 */
Html::macro('emailAction', function($text, $url) {
  return '
  <div class="btn btn--flat" style="Margin-bottom: 20px;text-align: center;">
    <![if !mso]><a style="border-radius: 4px;display: inline-block;font-weight: bold;text-align: center;text-decoration: none !important;transition: opacity 0.1s ease-in;color: #60686b;background-color: #becdd1;font-family: \'Open Sans\', sans-serif;font-size: 14px;line-height: 24px;padding: 12px 35px;" href="' . $url . '" data-width="162">' . $text . '</a><![endif]>
    <!--[if mso]><p style="line-height:0;margin:0;">&nbsp;</p><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" href="' . $url . '" style="width:232px" arcsize="9%" fillcolor="#BECDD1" stroke="f"><v:textbox style="mso-fit-shape-to-text:t" inset="0px,11px,0px,11px"><center style="font-size:14px;line-height:24px;color:#60686B;font-family:sans-serif;font-weight:bold;mso-line-height-rule:exactly;mso-text-raise:4px">' . $text . '</center></v:textbox></v:roundrect><![endif]--></div>
  </div>
  ';
});
