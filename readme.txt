=== Plugin Name ===
Contributors: oddjar
Donate link: http://www.amazon.com/The-Road-Happiness-Johnathon-Williams/dp/1938308018/
Tags: poetry, shortcodes, editor
Requires at least: 3.3
Tested up to: 3.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Lineate provides simple shortcodes for formatting poetry in the WordPress editor. 

== Description ==

Lineate provides two simple shortcodes for easily formatting poetry in the WordPress editor.

The first is the stanza shortcode. To indicate a stanza, wrap the appropriate text the [stanza]...[/stanza] shortcode as follows:

[stanza]
In Xanadu did Kubla Khan
A stately pleasure-dome decree :
Where Alph, the sacred river, ran
Through caverns measureless to man
Down to a sunless sea.
[/stanza]

The amount of vertical distance between stanzas can be set on the Lineate settings page.

The second shortcode is for indicating individual lines within a stanza. To ensure that each line in the above example was rendered correctly, you would use the [lineate] shortcode as follows:

[stanza]
[lineate]In Xanadu did Kubla Khan[/lineate]
[lineate]A stately pleasure-dome decree :[/lineate]
[lineate]Where Alph, the sacred river, ran[/lineate]
[lineate]Through caverns measureless to man[/lineate]
[lineate]Down to a sunless sea.[/lineate]
[/stanza]

A new button is included in the visual editor for automatically wrapping individual lines in the lineate shortcode. To use the button, hightlight a single line, and click the button with the line break symbol (a red slash).

The lineate shortcode also supports indented or "dropped" lines, with three levels of indentation.

The horizontal width of each indent is set through the base indent value on the Lineate settings page. The default value is 30 pixels.

To indicate dropped lines, include the indent attribute as follows:

[stanza]
[lineate]In Xanadu did Kubla Khan[/lineate]
[lineate indent=1]A stately pleasure-dome decree :[/lineate]
[lineate indent=2]Where Alph, the sacred river, ran[/lineate]
[lineate indent=3]Through caverns measureless to man[/lineate]
[/stanza]

In this example, assuming the base indent was set at 20 pixels, the second line in the stanza would be 20 pixels from the left margin, the third line would be 40 pixels from the left margin, and the fourth line would be 60 pixels from the left margin.

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

None.

== Screenshots ==

1. The Lineate settings page.

== Changelog ==

= 1.0 =
* Original release

== Upgrade Notice ==

None
