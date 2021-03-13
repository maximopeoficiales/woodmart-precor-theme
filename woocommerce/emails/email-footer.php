<?php

/**
 * Email Footer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

defined('ABSPATH') || exit;
?>
</div>
</td>
</tr>
</table>
<!-- End Content -->
</td>
</tr>
</table>
<!-- End Body -->
</td>
</tr>
</table>
</td>
</tr>
<tr>
	<td align="center" valign="top">
		<!-- Footer -->
		<!--footer custom  -->
		<style>
			.footer-parrafo {
				margin: 0;
				margin-top: 1px;

				/* font-family: "Open Sans",sans-serif; */
			}

			.img-red-social {
				width: 33px;
				height: 33px;
				margin: 10px 5px;
			}
		</style>
		<div style="width: 100%; background-color: #E9E9E9; padding: 15px 0; margin-top: 2rem;">
			<footer style="text-align: center; color: #6f6f6f; font-size: 10px; font-weight: 500; margin: 15px auto;">
				<div style="margin-bottom: 10px;">
					<span><img src="<?= get_template_directory_uri() . "/helpers/imgs/facebook.png" ?>" alt="" srcset="" class="img-red-social"></span>
					<span><img src="<?= get_template_directory_uri() . "/helpers/imgs/youtube.png" ?>" alt="" srcset="" class="img-red-social"></span>
				</div>
				<p class="footer-parrafo">Oficinas: Av. Manuel Olguin 373 Lima 33 Perú Edificio El Qubo Piso 9</p>
				<p class="footer-parrafo">Planta: Av. Nicolas Dueñas 559,Lima</p>
				<p class="footer-parrafo">Central Administrativa (551) 705-4000</p>
			</footer>
		</div>
		<!-- End Footer -->
	</td>
</tr>
</table>
</div>
</body>

</html>