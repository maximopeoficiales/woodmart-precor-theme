<?php

/**
 * Email Styles
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-styles.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 4.0.0
 */

if (!defined('ABSPATH')) {
	exit;
}

// Load colors.
$bg        = get_option('woocommerce_email_background_color');
$body      = get_option('woocommerce_email_body_background_color');
$base      = get_option('woocommerce_email_base_color');
$base_text = wc_light_or_dark($base, '#202020', '#ffffff');
$text      = get_option('woocommerce_email_text_color');

// Pick a contrasting color for links.
$link_color = wc_hex_is_light($base) ? $base : $base_text;

if (wc_hex_is_light($body)) {
	$link_color = wc_hex_is_light($base) ? $base_text : $base;
}

$bg_darker_10    = wc_hex_darker($bg, 10);
$body_darker_10  = wc_hex_darker($body, 10);
$base_lighter_20 = wc_hex_lighter($base, 20);
$base_lighter_40 = wc_hex_lighter($base, 40);
$text_lighter_20 = wc_hex_lighter($text, 20);
$text_lighter_40 = wc_hex_lighter($text, 40);

// !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
// body{padding: 0;} ensures proper scale/positioning of the email in the iOS native email app.
?>
:root {
--azul: #003b71;
--celeste: #69daf5;
--amarillo: #f0c713;
--verde-claro: #32cc52;
--rojo: #d91023;
--negro: #191919;
--gris-oscuro: #6f6f6f;
--gris-semiclaro: #B6B6B6;
--gris-claro: #e9e9e9;
}
html *{
font-family: "Montserrat", Helvetica, Roboto, Arial, sans-serif;
}
body {
padding: 0;
font-family: "Montserrat", Helvetica, Roboto, Arial, sans-serif;
}

#wrapper {
background-color: <?php echo esc_attr($bg); ?>;
margin: 0;
padding: 70px 0;
-webkit-text-size-adjust: none !important;
width: 100%;
}

#template_container {
box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1) !important;
background-color: <?php echo esc_attr($body); ?>;
border: 1px solid <?php echo esc_attr($bg_darker_10); ?>;
border-radius: 3px !important;
}

#template_header {
background-color: <?php echo esc_attr($base); ?>;
border-radius: 3px 3px 0 0 !important;
color: <?php echo esc_attr($base_text); ?>;
border-bottom: 0;
font-weight: bold;
line-height: 100%;
vertical-align: middle;
font-family: "Montserrat", Helvetica, Roboto, Arial, sans-serif;
}

#template_header h1,
#template_header h1 a {
color: <?php echo esc_attr($base_text); ?>;
background-color: inherit;
}

#template_header_image img {
margin-left: 0;
margin-right: 0;
}

#template_footer td {
padding: 0;
border-radius: 6px;
}

#template_footer #credit {
border: 0;
color: <?php echo esc_attr($text_lighter_40); ?>;
font-family: "Montserrat", Helvetica, Roboto, Arial, sans-serif;
font-size: 12px;
line-height: 150%;
text-align: center;
padding: 24px 0;
}

#template_footer #credit p {
margin: 0 0 16px;
}

#body_content {
background-color: <?php echo esc_attr($body); ?>;
}

#body_content table td {
padding: 48px 48px 32px;
}

#body_content table td td {
padding: 12px;
}

#body_content table td th {
padding: 12px;
}

#body_content td ul.wc-item-meta {
font-size: small;
margin: 1em 0 0;
padding: 0;
list-style: none;
}

#body_content td ul.wc-item-meta li {
margin: 0.5em 0 0;
padding: 0;
}

#body_content td ul.wc-item-meta li p {
margin: 0;
}

#body_content p {
margin: 0 0 16px;
}

#body_content_inner {
color: <?php echo esc_attr($text_lighter_20); ?>;
font-family: "Montserrat", Helvetica, Roboto, Arial, sans-serif;
font-size: 14px;
line-height: 150%;
text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

.td {
color: <?php echo esc_attr($text_lighter_20); ?>;
border: 1px solid <?php echo esc_attr($body_darker_10); ?>;
vertical-align: middle;
}

.address {
padding: 12px;
color: <?php echo esc_attr($text_lighter_20); ?>;
border: 1px solid <?php echo esc_attr($body_darker_10); ?>;
}

.text {
color: <?php echo esc_attr($text); ?>;
font-family: "Montserrat", Helvetica, Roboto, Arial, sans-serif;
}

.link {
color: <?php echo esc_attr($link_color); ?>;
}

#header_wrapper {
padding: 26px 38px;
display: block;
}

h1 {
color: <?php echo esc_attr($base); ?>;
font-family: "Montserrat", Helvetica, Roboto, Arial, sans-serif;
font-size: 30px;
font-weight: 300;
line-height: 150%;
margin: 0;
text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
text-shadow: 0 1px 0 <?php echo esc_attr($base_lighter_20); ?>;
}

h2 {
color: <?php echo esc_attr($base); ?>;
display: block;
font-family: "Montserrat", Helvetica, Roboto, Arial, sans-serif;
font-size: 18px;
font-weight: bold;
line-height: 130%;
margin: 0 0 18px;
text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

h3 {
color: <?php echo esc_attr($base); ?>;
display: block;
font-family: "Montserrat", Helvetica, Roboto, Arial, sans-serif;
font-size: 16px;
font-weight: bold;
line-height: 130%;
margin: 16px 0 8px;
text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

a {
color: <?php echo esc_attr($link_color); ?>;
font-weight: normal;
text-decoration: underline;
}

img {
border: none;
display: inline-block;
font-size: 14px;
font-weight: bold;
height: auto;
outline: none;
text-decoration: none;
text-transform: capitalize;
vertical-align: middle;
margin-<?php echo is_rtl() ? 'left' : 'right'; ?>: 10px;
max-width: 100%;
height: auto;
}


.img-precor-email {
object-fit: cover;
width: 100%;
height: 300px;
}
.img-logo-sobrepuesto {
height: 75px;
width: 180px;
position: absolute;
top: 0;
left: 0;
}

.precor-title-email {
text-align: center;
margin-top: 2rem;
margin-bottom: 1rem;
font-weight: 700;
color: var(--negro);
font-size: 23px;
}
.precor-color-texto {

color: var(--gris-semiclaro);
font-weight: 500;
text-align: justify;
}
.btn-precor-email {
display: block;
text-decoration: none;
padding-top: 18px;
padding-bottom: 18px;
margin-top: 10px;
margin-bottom: 10px;
border-radius: 10px;
text-align: center;
font-weight: 600;
}
.btn-precor-azul {
background-color: var(--azul);
color: white;
}
.footer-parrafo {
margin: 0;
margin-top: 1px;
color: var(--gris-oscuro);
font-weight:500;
}

.img-red-social {
width: 25px;
height: 25px;
margin: 10px 5px;
}
<?php
