<?php

/**
 * Select address field.
 *
 * This template can be overridden by copying it to your-theme/fr-address-book-for-woocommerce/select-address.php.
 *
 * However, on occasion we will need to update template files and you (the theme 
 * developer) will need to copy the new files to your theme to maintain 
 * compatibility. 
 *
 * @since 1.0.0
 * @version 1.2.2
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */

/* @var $type string */
/* @var $addresses array */
/* @var $saved_address_id int */

$field_options  = array();

foreach ($addresses as $id => $value) {
    $field_options[$id] = isset($value['address_name']) && $value['address_name']
        ? sprintf('<strong class="fabfw-address-name">%s</strong><br>', $value['address_name'])
        : '';
    $field_options[$id] .= wc()->countries->get_formatted_address($value);
    //$field_options[$id] .= sprintf('<br><a href="#" class="fabfw-edit">%s</a>'//, __('Edit', 'fr-address-book-for-woocommerce'));
}
/*
if (count($addresses) < fr_address_book_for_woocommerce()->max_addresses) {
    $field_options['new'] = sprintf('<a class="button">%s</a>', __('Add new address', 'fr-address-book-for-woocommerce'));
}*/
?>

<div class="fabfw-select-address-container">
    <?php if ($addresses) :
    ?>
         <h4 class="text-center  ">Direccion actual : <b class="texto-precor-azul" id="addressPrincipalPrecor"></b></h4>
        <button class="button-precor bg-precor-azul" type="button" id="btnShowModalDirections">Elige una Direccion</button>

        <!-- aqui va el modal -->
        <div id="myModalDirections" class="modalProducts">

            <div class="modalContainerProducts">
                <div class="modalHeaderProducts">
                    <h4 style="color: white; margin-bottom: 0;">Lista de Direcciones</h4>
                    <span class="closeModalProducts" id="hiddeModalDirections">&times;</span>
                </div>
                <!-- contenido del modal-->
                <div class="modalContentProducts">
                    <h4 class="text-center">Direccion Seleccionada: <b class="texto-precor-azul" id="currentAddressPrecor"></b></h4>
                    <div class="" style="display: flex;">
                        <input type="text" class="" placeholder="Busca una direccion" aria-label="Busca una direccion" aria-describedby="Busca una direccion" id="inputSearchAddressPrecor">
                        <div class="">
                            <button class="bg-precor-azul text-white" type="button" id="btnSearchAddressPrecor">Buscar</button>
                        </div>
                    </div>
                    <!-- spinner -->
                    <div class="spinner-container-precor" style="display: none; margin-top: 1rem;">
                        <h4 class="text-center texto-precor-azul">Buscando Direcciones ...</h4>
                        <div class="loader"></div>
                    </div>
                    <div class="not-address-container-precor" style="display: none; margin-top: 1rem;">
                        <h4 class="text-center texto-precor-azul">No se hay direcciones que coincidan con: <b id="textoBuscado">Texto a buscar</b></h4>
                    </div>
                    <!-- direcciones del cliente -->
                    <p class="form-row" id="<?php echo "fabfw_address_{$type}_id_field" ?>">
                        <label><?php esc_html_e('Address book', 'fr-address-book-for-woocommerce') ?></label>
                        <span class="woocommerce-input-wrapper address-container-precor" style="margin-top: 1rem;">
                            <?php foreach ($field_options as $id => $label) :
                                $address = $addresses[$id]["address_1"];
                            ?>
                                <input type="radio" class="input-radio input-radio-address-precor" value="<?php echo $id ?>" name="<?php echo "fabfw_address_{$type}_id" ?>" id="<?php echo "fabfw_address_{$type}_id_{$id}" ?>" <?php checked($id, $saved_address_id) ?> />
                                <label for="<?php echo "fabfw_address_{$type}_id_{$id}" ?>" class="radio precor-my-address-item" data-address="<?= $address ?>">
                                    <span><?php echo $label ?></span>
                                </label>
                            <?php endforeach ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>

    <?php
    // Hide the field if no addresses saved yet.
    else : ?>
        <input type="hidden" name="<?php echo "fabfw_address_{$type}_id" ?>" value="new">
    <?php endif ?>
</div>
<script>
    // <!-- script de funcionamiento del modal -->
    let modal2 = document.querySelectorAll('#myModalDirections')[0];
    let btn2 = document.getElementById('btnShowModalDirections');
    let span2 = document.getElementById('hiddeModalDirections');
    btn2.onclick = function() {
        modal2.style.display = 'block';
    }
    span2.onclick = function() {
        modal2.style.display = 'none';
    }
    window.onclick = function(event) {
        if (event.target == modal2) {
            modal2.style.display = 'none';
        }
    }
    // document.addEventListener("click", (e) => {
    //     console.log(e.target);

    // })
    // buscador de direcciones
    let inputSearchAddressPrecor = document.getElementById('inputSearchAddressPrecor');
    let btnSearchAddressPrecor = document.getElementById('btnSearchAddressPrecor');

    let spinnerContainer = document.querySelector('.spinner-container-precor');
    let addressContainerPrecor = document.querySelector('.address-container-precor');
    let listAddress = document.querySelectorAll('.precor-my-address-item');
    let notAddressContainerPrecor = document.querySelector('.not-address-container-precor');
    const searchAddressPrecor = () => {
        let query = inputSearchAddressPrecor.value;
        // muestro el spinner
        spinnerContainer.style.display = "block"
        addressContainerPrecor.style.display = "none";
        notAddressContainerPrecor.style.display = "none";
        setTimeout(() => {
            let encontroResultado = false;
            if (query == "") {
                // mostrar todas las direcciones
                // console.log(listAddress);
                listAddress.forEach(e => e.style.display = "block");
                // oculto el spinner
            } else {
                // mostrar solo las que coincidam
                listAddress.forEach(e => {
                    if (e.getAttribute("data-address").toLocaleLowerCase().includes(query.toLocaleLowerCase())) {
                        e.style.display = "block";
                        encontroResultado = true;
                    } else {
                        e.style.display = "none";
                    }
                });

            }
            spinnerContainer.style.display = "none";
            addressContainerPrecor.style.display = "block";
            // si no encontro nada y query es diferente de vacio
            if (!encontroResultado && query != "") {
                notAddressContainerPrecor.style.display = "block";
                document.getElementById("textoBuscado").innerText = `"${query}"`;
            }

        }, 1000);
    }
    const getCheckedAddressPrecor = () => {
        // solucion rapida
        setInterval(() => {
            let addressText = "";
            let inputRadioAddressPrecorList = document.querySelectorAll('.input-radio-address-precor');
            inputRadioAddressPrecorList.forEach(e => {
                let addressChecked = document.querySelector(`label[for=${e.id}]`)
                if (e.checked) {
                    addressChecked.classList.add("precor-my-address-item-selected")
                    document.querySelector("#currentAddressPrecor").innerText = addressChecked.getAttribute("data-address");
                    document.querySelector("#addressPrincipalPrecor").innerText = addressChecked.getAttribute("data-address");
                } else {
                    addressChecked.classList.remove("precor-my-address-item-selected")
                }
            })
        }, 1000);
    }
    inputSearchAddressPrecor.addEventListener("keyup", searchAddressPrecor);
    btnSearchAddressPrecor.addEventListener("click", searchAddressPrecor);
    getCheckedAddressPrecor();
</script>
