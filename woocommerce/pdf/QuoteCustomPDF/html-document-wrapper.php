<?php if (!defined("ABSPATH")) {
	exit();
}
require get_template_directory() . "/helpers/wc-orders-custom/OrderCustomWoo.php";
$order = new OrderCustomWoo($this->order_id);
$data = $order->getOrderData();
$currentOrder = $order->getOrder();

// echo $order->getOrder()->get_billing_address_1();
$isQuote = $order->getOrder()->get_created_via() == "ywraq" ? true : false;
$type = $isQuote ? "Cotizacion " : "Pedido";
?>
<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo "$type " . precor_getIDSAPbyOrderID($this->order_id); ?></title>
	<style type="text/css">
		<?php $this->template_styles(); ?>
	</style>
	<style type=" text/css">
		html {
			font-size: 10px;
		}

		p {
			margin-top: 0;
			margin-bottom: 0;

		}

		.text-8 {
			font-size: 8px !important;
		}

		.text-9 {
			font-size: 9px !important;
		}
	</style>
</head>

<body>
	<div>
		<div class="p-2">
			<img src="https://tiendaqa.maxco.pe/wp-content/uploads/2021/logomaxco.png" class="rounded-lg" style="max-width: 150px;">
			<div class=" text-uppercase float-right mb-2">
				<p class=" p-2  my-1"><b><?= $type ?>:</b> <?= precor_getIDSAPbyOrderID($this->order_id) ?></p>
				<p class=" p-2  my-1"><b>Fecha:</b> <?= $data["date_created"] ?></p>
			</div>
			<br>
		</div>

	</div>
	<div class="my-2 border text-uppercase text-sm p-2  border-dark">
		<p class=""><b>CLIENTE:</b> <?= $data["customer_id_cli"] != null
										? $data["customer_id_cli"]
										: "N/A" ?> <?= $currentOrder->get_billing_first_name() ?></p>
		<p class=""><b>DIRECCION:</b> <?= $data["customer_drcfisc"] ?></p>
		<p class=""><b>TELEFONO:</b> <?= $currentOrder->get_billing_phone() ?></p>
		<p class=""><b>ATENCION:</b> <?= $data["customer_nombeje"] ?></p>
	</div>
	<table class="table table-bordered text-uppercase my-1 w-100">
		<thead class="thead-light  text-center">
			<tr>
				<th class="text-8">ITEM</th>
				<th class="text-8">CANT.</th>
				<th class="text-8">CODIGO</th>
				<th class="text-8" style="width: 25%;">DESCRIPCION</th>
				<th class="text-8">UND.</th>
				<th class="text-8">PZAS</th>
				<th class="text-8">
					PESO.PZA KG
				</th>
				<th class="text-8">P.LISTA USD</th>
				<th class="text-8">VAL.VTA USD</th>
				<!-- <th class="text-8">TOTAL USD</th> -->
				<th class="text-8">PESO.TOT KG</th>
			</tr>
		</thead>
		<tbody class="text-8">
			<?php
			// recorro los productos
			$cont = 0;
			$pesoTotalKg = 0;
			foreach ($data["line_items"] as $product) {
				// numero de 10 en 10 en el pdf
				$cont += 10;
				// $product_id = $product["product_id"];
				// peso total kg en el detalle
				// nombre de la unidad
				$und = $product["und"] != null ? $product["und"] : "";
				// valor de la unidad
				$unxpaq = $product["unxpaq"];
				$pzas =
					// piezas
					$und != "" || $unxpaq != ""
					? round($product["quantity"] / $unxpaq, 2)
					: "";
				// es el peso de una pieza
				$pesoPiezaKg = ($unxpaq == "" ? $product["weight"] : $product["und_value"] * $unxpaq);
				// peso pieza kg

				// peso pieza kg total
				$totalPesoPiezaKG = (($unxpaq == "" ? $product["weight"] : $product["und_value"]) *  $product["quantity"]);

				$pesoTotalKg += $totalPesoPiezaKG == "" ? 0 : $totalPesoPiezaKG;

			?>
				<tr>
					<!-- nitem -->
					<td scope="row" class="text-8 text-center"><?php echo str_pad(
																	$cont,
																	4,
																	"0",
																	STR_PAD_LEFT
																); ?></td>
					<!-- cantidad -->
					<td class="text-8 text-center"><?= $product["quantity"] ?></td>
					<!-- id_material -->
					<td class="text-8 text-center"><?= $product["sku"] ?></td>
					<!-- nombre -->
					<td class="text-8 text-left"><?= $product["name"] ?></td>
					<!-- paquete / valor de paquete -->
					<?php  ?>
					<td class="text-8 text-center"><?= ($und == "" || $product["und_value"] == "") ? "" : $und . "/" . number_format($product["und_value"], 2) ?></td>
					<!-- piezas -->
					<td class="text-8 text-right"><?= $pzas == INF ? "" : $pzas ?></td>
					<!-- pezo pieza kg -->
					<td class="text-8 text-right"><?= number_format($pesoPiezaKg, 2) ?></td>
					<!-- precio del producto -->
					<td class="text-8 text-right"><?= number_format($product["price"], 2) ?></td>
					<!-- precio con descuento -->
					<td class="text-8 text-right"><?= number_format($product["total"], 2) ?></td>
					<!-- <td class="text-8 text-right"></td> -->
					<!-- total de kg del producto -->
					<td class="text-8 text-right"><?= number_format($totalPesoPiezaKG, 2) ?></td>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>

	<div class="p-2 my-2">
		<!-- ejemplo de simulacion de flex -->
		<h6 class="text-bold">Observaciones:</h6>
		<div class="border border-dark text-uppercase float-right">
			<p class=" p-2  "><b>PESO TOT KG:</b> <?= number_format(round($pesoTotalKg, 2), 2) ?></p>
			<p class=" p-2 "><b>Subtotal:</b> <?= number_format(round(
													$currentOrder->get_total() / 1.18,
													2
												), 2) ?></p>
			<p class=" p-2  "><b>IGV (18%):</b> <?= number_format(round(
													$currentOrder->get_total() - round($currentOrder->get_total() / 1.18, 2),
													2
												), 2) ?></p>
			<p class=" p-2 "><b>PERC.( %):</b> 0.00</p>
			<p class=" p-2  "><b>TOTAL USD:</b> <?= $currentOrder->get_total() ?></p>
		</div>
		<div class="">
			<div style="width: 70%;">
				<p class="text-10"><?= $currentOrder->get_customer_note() != ""
										? $currentOrder->get_customer_note()
										: "Sin Observaciones" ?>
				<p>
			</div>

		</div>

	</div>
	<br><br>
	<br><br>
	<br><br><br><br>
	<!-- inicio de condiciones -->
	<div class="my-2">
		<h6 class="text-bold text-10">I. CONDICIONES COMERCIALES</h6>
		<div class="p-2">
			<table class="table table-bordered text-9 table-sm">
				<thead class="d-none">
					<tr>
						<th style="width: 30%;">Condicion</th>
						<th style="width: 70%;">Descripcion</th>
					</tr>
				</thead>
				<tbody class="text-left">
					<?php
					$status_ES = wc_get_order_status_name($order->getOrder()->get_status());
					echo !$isQuote ? "
					<tr>
						<td><b>STATUS DE COMPRA</b></td>
						<td>{$status_ES}</td>
					</tr>
					" : "";
					?>
					<tr>
						<td><b>FORMA DE PAGO</b></td>
						<td>Letra 30 días</td>
					</tr>
					<tr>
						<td><b>VIAS DE PAGO</b></td>
						<td><?php echo $isQuote ? "CUENTA RECAUDADORA: BCP, CONTINENTAL y SCOTIABANK,TARJETAS DE CREDITO" : $order->getOrder()->get_payment_method_title() ?></td>
					</tr>


					<tr>
						<td><b>TIEMPO DE ENTREGA</b></td>
						<td></td>
					</tr>
					<tr>
						<td><b>VALIDEZ DE OFERTA</b></td>
						<td><?= get_option('precor_validez_oferta') ?? "08 DIAS" ?></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="my-2 w-100">
			<div class="py-2 pl-5">
				<ol>
					<li>
						El Cliente deberá confirmar las medidas, cantidades, precios y la condición de entrega indicados en la presente cotización.
					</li>
					<li>
						El pedido detallado en la cotización tendrá una vigencia de stock y precios de 24 horas una vez emitida. Posterior a ello, vía
						comunicación con su Ejecutivo de Ventas, deberán confirmar la disponibilidad de stock y precio. <b>Nota:</b> Cualquier producto
						no considerado en esta cotización será considerado como adicional y deberá solicitarse su cotización.
					</li>
					<li>
						Los plazos de entrega serán contabilizados desde el Pago Íntegro del Pedido (para Venta al Contado) o desde el Pago del
						Adelanto (para Venta al Crédito).
					</li>
					<li>
						Todos los créditos otorgados por un plazo mayor a 30 días calendario se encuentran afectos al cobro de intereses.
					</li>
				</ol>
			</div>
		</div>
	</div>
	<div class="my-2">
		<h6 class="text-bold text-10">II. CONDICIONES DE ENTREGA</h6>
		<div class="p-2">
			<p class="p-2 border text-center text-9"><b>DESPACHO/LOCAL DEL CLIENTE/DESESTIBA A CARGO DE PRECOR/COLOCAR EN RACKS</b></p>
		</div>
		<p class="my-1">Precor entrega el material en el lugar de destino acordado por el Cliente:</p>
		<p class="text-center border p-2 font-weight-bold" style="font-size: 12px ;border-style: dashed;"><?= $currentOrder->get_billing_address_1() ?></p>
		<div class="my-2 w-100 text-9">
			<div class="py-2 pl-5">
				<ol>
					<li>
						PRECOR S.A. suministrará Los Materiales en el lugar previamente designado y especificado por el Cliente dentro de Lima
						Metropolitana.
					</li>
					<li>
						Se considerará que el servicio de entrega de Los Materiales ha concluido cuando se firma la Guía de Remisión con la
						conformidad de la entrega. Luego de dicha entrega, PRECOR S.A. no es responsable de lo que suceda durante el
						manipuleo interno de los Materiales por parte del Cliente.
					</li>
					<li>
						De presentarse observaciones del producto con posterioridad a la entrega, el Cliente tendrá 15 días calendario para
						presentar su reclamo, computados a partir del día de entrega, de no realizarse el reclamo dentro del plazo establecido se
						entenderá que no existe ninguna observación y cualquier reclamo posterior no será aceptado. Realizado el reclamo
						(según los términos antes descritos), el departamento de Post Venta se presentará en las instalaciones en las cuales se
						encuentren Los Materiales dentro de las 24 horas para absolver consultas y realizar el informe respectivo.
					</li>
					<li>
						Si por alguna razón el Cliente o agencia asignada no acepta que se descargue el material o excede el tiempo máximo de
						espera de la unidad (01hora), por causas imputables al Cliente, el transporte regresará a PRECOR S.A. y no se realizará
						un nuevo servicio de flete salvo que el Cliente acepte el presupuesto de transporte; de ser así, PRECOR S.A. emitirá una
						factura por dicho servicio, de acuerdo al costo en el que incurra.
					</li>
				</ol>
			</div>
		</div>
	</div>
	<div class="my-2">
		<h6 class="text-bold text-10">III. CONDICIONES GENERALES</h6>
		<div class="my-2 w-100 text-9">
			<div class="py-2 pl-5">
				<ol>
					<li>
						Venta al Contado: PRECOR S.A. se reserva la propiedad del bien materia de esta cotización, hasta que se haya realizado
						el pago íntegro del precio, dentro del plazo establecido y según las condiciones acordadas. La falta de pago del íntegro del
						precio pactado, motivará la ejecución del pacto de reserva de propiedad, pudiendo PRECOR S.A. disponer del bien; lo
						cual será puesto en conocimiento del cliente.
					</li>
					<li>
						De no concretarse la venta, del monto entregado en calidad de adelanto, PRECOR S.A retendrá el monto correspondiente
						a los costos y gastos incurridos tanto por el almacenamiento de Los Materiales, como por los gastos de fabricación
						incurridos
					</li>
					<li>
						Ante el incumplimiento de los pagos por parte del Cliente, en las fechas acordadas, PRECOR S.A. se encontrará facultado
						a cobrar gastos financieros por concepto de intereses moratorios y compensatorios.
					</li>
					<li>
						PMP HOLDING S.A. es una empresa respetuosa de la normativa anticorrupción vigente: Ley N° 30424, D.L. N° 1352,
						D.L. N° 1385, siendo esta lista enunciativa y no taxativa. En mérito a ello, los Clientes y/o Proveedores declaran y
						garantizan que han leído todas las políticas, procedimientos y Línea Ética (Sistema Confidencial de Denuncias) de
						PMP HOLDING S.A., los cuales se encuentran disponibles en la web <a href="https://www.lineaeticapmp.com/" target="_blanks"> https://www.lineaeticapmp.com/</a> y que además
						se alinean al cumplimiento de todos los dispositivos legales previamente enunciados. En tal sentido, PMP HOLDING
						S.A. no asume ninguna responsabilidad por incumplimientos ni por ilícitos de sus Clientes y/o Proveedores, en materia de
						corrupción y/o LAFT (Lavado de Activos y Financiamiento del Terrorismo).
					</li>
				</ol>
			</div>
		</div>
	</div>
	<!-- fin de condiciones -->
	<div class="my-2 float-left">
		<p><?= $data["customer_nombeje"] ?></p>
		<p><?= $data["customer_telfeje"] ?></p>
		<p><?= $data["customer_emaileje"] ?></p>

		<p>Av. Manuel Olguin 373, Edificio Qubo</p>
		<p>Piso 9, Surco, Lima</p>
		<p>Telf.: (511) 705-4000</p>
		<p>www.PRECOR.com.pe</p>
	</div>
	<div class="my-2 float-right">
		<br>
		<br>
		<br>
		<br>
		<div class="border-top " style="border-color: black; width: 200px;">
			<p class=""><b>Sello y firma del Aceptante</b></p>
			<p>Nombre:</p>
		</div>

	</div>
</body>

</html>